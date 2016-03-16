<?php defined('BASEPATH') || exit('No direct script access allowed');

/**
 * Content controller
 */
class Content extends Admin_Controller
{
    protected $permissionCreate = 'Inquiry.Content.Create';
    protected $permissionDelete = 'Inquiry.Content.Delete';
    protected $permissionEdit   = 'Inquiry.Content.Edit';
    protected $permissionView   = 'Inquiry.Content.View';

    /**
     * Constructor
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        
        $this->auth->restrict($this->permissionView);
        $this->load->model('inquiry/inquiry_model');
        $this->load->model('company_users/company_users_model');
        $this->lang->load('inquiry');
        
        $this->form_validation->set_error_delimiters("<div class='alert alert-danger'>", "</div>");
        
        Template::set_block('sub_nav', 'content/_sub_nav');

        Assets::add_module_js('inquiry', 'inquiry.js');
    }

    /**
     * Display a list of Inquiry data.
     *
     * @return void
     */
    public function index($offset = 0)
    {
        // Deleting anything?
        if (isset($_POST['delete'])) {
            $this->auth->restrict($this->permissionDelete);
            $checked = $this->input->post('checked');
            if (is_array($checked) && count($checked)) {

                // If any of the deletions fail, set the result to false, so
                // failure message is set if any of the attempts fail, not just
                // the last attempt

                $result = true;
                foreach ($checked as $pid) {
                    $deleted = $this->inquiry_model->delete($pid);
                    if ($deleted == false) {
                        $result = false;
                    }
                }
                if ($result) {
                    Template::set_message(count($checked) . ' ' . lang('inquiry_delete_success'), 'success');
                } else {
                    Template::set_message(lang('inquiry_delete_failure') . $this->inquiry_model->error, 'error');
                }
            }
        }
        $pagerUriSegment = 5;
        $pagerBaseUrl = site_url(SITE_AREA . '/content/inquiry/index') . '/';
        
        $limit  = $this->settings_lib->item('site.list_limit') ?: 15;

        $this->load->library('pagination');
        $pager['base_url']    = $pagerBaseUrl;
        $pager['total_rows']  = $this->inquiry_model->count_all();
        $pager['per_page']    = $limit;
        $pager['uri_segment'] = $pagerUriSegment;

        $this->pagination->initialize($pager);
        $this->inquiry_model->limit($limit, $offset);
        
        $records = $this->inquiry_model->find_all();

        Template::set('records', $records);
        
        Template::set('toolbar_title', lang('inquiry_manage'));

        Template::render();
    }
    
    /**
     * Create a Inquiry object.
     *
     * @return void
     */
    public function create()
    {
        $profile_id = NULL;
        if (!empty($this->current_user)){ 
            if ($this->current_user->role_id == 4)
            {
                $profile_id = $this->company_users_model->get_profile_id($this->current_user->id);    
                $profile_id = ($profile_id > 0 ? $profile_id : -1);
                $this->session->set_userdata('profile_id',$profile_id);
            }
        }

        $id = $this->uri->segment(5);
        if (empty($id)) {
            Template::set_message(lang('inquiry_invalid_id'), 'error');

            redirect(SITE_AREA . '/content/inquiry');
        }
        $this->auth->restrict($this->permissionCreate);
        
        if (isset($_POST['save'])) {
            if ($insert_id = $this->save_inquiry()) {
                log_activity($this->auth->user_id(), lang('inquiry_act_create_record') . ': ' . $insert_id . ' : ' . $this->input->ip_address(), 'inquiry');
                Template::set_message(lang('inquiry_create_success'), 'success');

                redirect(SITE_AREA . '/content/inquiry');
            }

            // Not validation error
            if ( ! empty($this->inquiry_model->error)) {
                Template::set_message(lang('inquiry_create_failure') . $this->inquiry_model->error, 'error');
            }
        }

        Template::set('toolbar_title', lang('inquiry_action_create'));
        Template::set('item_id', $id);
        Template::set('profile_id', $this->session->userdata('profile_id'));
        Template::set_view('content/edit');
        Template::render();
    }
    /**
     * Allows editing of Inquiry data.
     *
     * @return void
     */
    public function edit()
    {
        $id = $this->uri->segment(5);
        if (empty($id)) {
            Template::set_message(lang('inquiry_invalid_id'), 'error');

            redirect(SITE_AREA . '/content/inquiry');
        }
        
        if (isset($_POST['save'])) {
            $this->auth->restrict($this->permissionEdit);

            if ($this->save_inquiry('update', $id)) {
                log_activity($this->auth->user_id(), lang('inquiry_act_edit_record') . ': ' . $id . ' : ' . $this->input->ip_address(), 'inquiry');
                Template::set_message(lang('inquiry_edit_success'), 'success');
                redirect(SITE_AREA . '/content/inquiry');
            }

            // Not validation error
            if ( ! empty($this->inquiry_model->error)) {
                Template::set_message(lang('inquiry_edit_failure') . $this->inquiry_model->error, 'error');
            }
        }
        
        elseif (isset($_POST['delete'])) {
            $this->auth->restrict($this->permissionDelete);

            if ($this->inquiry_model->delete($id)) {
                log_activity($this->auth->user_id(), lang('inquiry_act_delete_record') . ': ' . $id . ' : ' . $this->input->ip_address(), 'inquiry');
                Template::set_message(lang('inquiry_delete_success'), 'success');

                redirect(SITE_AREA . '/content/inquiry');
            }

            Template::set_message(lang('inquiry_delete_failure') . $this->inquiry_model->error, 'error');
        }
        
        Template::set('inquiry', $this->inquiry_model->find($id));

        Template::set('toolbar_title', lang('inquiry_edit_heading'));
        Template::render();
    }

    //--------------------------------------------------------------------------
    // !PRIVATE METHODS
    //--------------------------------------------------------------------------

    /**
     * Save the data.
     *
     * @param string $type Either 'insert' or 'update'.
     * @param int    $id   The ID of the record to update, ignored on inserts.
     *
     * @return boolean|integer An ID for successful inserts, true for successful
     * updates, else false.
     */
    private function save_inquiry($type = 'insert', $id = 0)
    {
        if ($type == 'update') {
            $_POST['id'] = $id;
        }

        // Validate the data
        $this->form_validation->set_rules($this->inquiry_model->get_validation_rules());
        if ($this->form_validation->run() === false) {
            return false;
        }

        // Make sure we only pass in the fields we want
        
        $data = $this->inquiry_model->prep_data($this->input->post());

        // Additional handling for default values should be added below,
        // or in the model's prep_data() method
        

        $return = false;
        if ($type == 'insert') {
            $id = $this->inquiry_model->insert($data);

            if (is_numeric($id)) {
                $return = $id;
            }
        } elseif ($type == 'update') {
            $return = $this->inquiry_model->update($id, $data);
        }

        return $return;
    }
}