<?php defined('BASEPATH') || exit('No direct script access allowed');

/**
 * Content controller
 */
class Content extends Admin_Controller
{
    protected $permissionCreate = 'Company_users.Content.Create';
    protected $permissionDelete = 'Company_users.Content.Delete';
    protected $permissionEdit   = 'Company_users.Content.Edit';
    protected $permissionView   = 'Company_users.Content.View';

    /**
     * Constructor
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        
        $this->auth->restrict($this->permissionView);
        $this->load->model('company_users/company_users_model');
        $this->load->model(array('profile/profile_model', 'users_model'));
        $this->lang->load('company_users');
        
        $this->form_validation->set_error_delimiters("<div class='alert alert-danger'>", "</div>");
        
        Template::set_block('sub_nav', 'content/_sub_nav');

        Assets::add_module_js('company_users', 'company_users.js');

        $profile_select = $this->profile_model->get_profile_select();
        Template::set('profile_select', $profile_select);

        $users_select = $this->users_model->get_users_select();
        Template::set('users_select', $users_select);

    }

    /**
     * Display a list of Company Users data.
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
                    $deleted = $this->company_users_model->delete($pid);
                    if ($deleted == false) {
                        $result = false;
                    }
                }
                if ($result) {
                    Template::set_message(count($checked) . ' ' . lang('company_users_delete_success'), 'success');
                } else {
                    Template::set_message(lang('company_users_delete_failure') . $this->company_users_model->error, 'error');
                }
            }
        }
        $pagerUriSegment = 5;
        $pagerBaseUrl = site_url(SITE_AREA . '/content/company_users/index') . '/';
        
        $limit  = $this->settings_lib->item('site.list_limit') ?: 15;

        $this->load->library('pagination');
        $pager['base_url']    = $pagerBaseUrl;
        $pager['total_rows']  = $this->company_users_model->count_all();
        $pager['per_page']    = $limit;
        $pager['uri_segment'] = $pagerUriSegment;

        $this->pagination->initialize($pager);
        $this->company_users_model->limit($limit, $offset);
        
        $records = $this->company_users_model->find_all();

        Template::set('records', $records);
        
    Template::set('toolbar_title', lang('company_users_manage'));

        Template::render();
    }
    
    /**
     * Create a Company Users object.
     *
     * @return void
     */
    public function create()
    {
        $this->auth->restrict($this->permissionCreate);
        
        if (isset($_POST['save'])) {
            if ($insert_id = $this->save_company_users()) {
                log_activity($this->auth->user_id(), lang('company_users_act_create_record') . ': ' . $insert_id . ' : ' . $this->input->ip_address(), 'company_users');
                Template::set_message(lang('company_users_create_success'), 'success');

                redirect(SITE_AREA . '/content/company_users');
            }

            // Not validation error
            if ( ! empty($this->company_users_model->error)) {
                Template::set_message(lang('company_users_create_failure') . $this->company_users_model->error, 'error');
            }
        }

        Template::set('toolbar_title', lang('company_users_action_create'));
        Template::set_view('content/edit');            
        Template::render();
    }
    /**
     * Allows editing of Company Users data.
     *
     * @return void
     */
    public function edit()
    {
        $id = $this->uri->segment(5);
        if (empty($id)) {
            Template::set_message(lang('company_users_invalid_id'), 'error');

            redirect(SITE_AREA . '/content/company_users');
        }
        
        if (isset($_POST['save'])) {
            $this->auth->restrict($this->permissionEdit);

            if ($this->save_company_users('update', $id)) {
                log_activity($this->auth->user_id(), lang('company_users_act_edit_record') . ': ' . $id . ' : ' . $this->input->ip_address(), 'company_users');
                Template::set_message(lang('company_users_edit_success'), 'success');
                redirect(SITE_AREA . '/content/company_users');
            }

            // Not validation error
            if ( ! empty($this->company_users_model->error)) {
                Template::set_message(lang('company_users_edit_failure') . $this->company_users_model->error, 'error');
            }
        }
        
        elseif (isset($_POST['delete'])) {
            $this->auth->restrict($this->permissionDelete);

            if ($this->company_users_model->delete($id)) {
                log_activity($this->auth->user_id(), lang('company_users_act_delete_record') . ': ' . $id . ' : ' . $this->input->ip_address(), 'company_users');
                Template::set_message(lang('company_users_delete_success'), 'success');

                redirect(SITE_AREA . '/content/company_users');
            }

            Template::set_message(lang('company_users_delete_failure') . $this->company_users_model->error, 'error');
        }
        
        Template::set('company_users', $this->company_users_model->find($id));

        Template::set('toolbar_title', lang('company_users_edit_heading'));
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
    private function save_company_users($type = 'insert', $id = 0)
    {
        if ($type == 'update') {
            $_POST['id'] = $id;
        }

        // Validate the data
        $this->form_validation->set_rules($this->company_users_model->get_validation_rules());
        if ($this->form_validation->run() === false) {
            return false;
        }

        // Make sure we only pass in the fields we want
        
        $data = $this->company_users_model->prep_data($this->input->post());

        // Additional handling for default values should be added below,
        // or in the model's prep_data() method
        

        $return = false;
        if ($type == 'insert') {
            $id = $this->company_users_model->insert($data);

            if (is_numeric($id)) {
                $return = $id;
            }
        } elseif ($type == 'update') {
            $return = $this->company_users_model->update($id, $data);
        }

        return $return;
    }
}