<?php defined('BASEPATH') || exit('No direct script access allowed');

/**
 * Content controller
 */
class Content extends Admin_Controller
{
    protected $permissionCreate = 'Bursting_strength.Content.Create';
    protected $permissionDelete = 'Bursting_strength.Content.Delete';
    protected $permissionEdit   = 'Bursting_strength.Content.Edit';
    protected $permissionView   = 'Bursting_strength.Content.View';

    /**
     * Constructor
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        
        $this->auth->restrict($this->permissionView);
        $this->load->model('bursting_strength/bursting_strength_model');
        $this->lang->load('bursting_strength');
        
            $this->form_validation->set_error_delimiters("<div class='alert alert-danger'>", "</div>");
        
        Template::set_block('sub_nav', 'content/_sub_nav');

        Assets::add_module_js('bursting_strength', 'bursting_strength.js');
    }

    /**
     * Display a list of Bursting Strength data.
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
                    $deleted = $this->bursting_strength_model->delete($pid);
                    if ($deleted == false) {
                        $result = false;
                    }
                }
                if ($result) {
                    Template::set_message(count($checked) . ' ' . lang('bursting_strength_delete_success'), 'success');
                } else {
                    Template::set_message(lang('bursting_strength_delete_failure') . $this->bursting_strength_model->error, 'error');
                }
            }
        }
        $pagerUriSegment = 5;
        $pagerBaseUrl = site_url(SITE_AREA . '/content/bursting_strength/index') . '/';
        
        $limit  = $this->settings_lib->item('site.list_limit') ?: 15;

        $this->load->library('pagination');
        $pager['base_url']    = $pagerBaseUrl;
        $pager['total_rows']  = $this->bursting_strength_model->count_all();
        $pager['per_page']    = $limit;
        $pager['uri_segment'] = $pagerUriSegment;

        $this->pagination->initialize($pager);
        $this->bursting_strength_model->limit($limit, $offset);
        
        $records = $this->bursting_strength_model->find_all();

        Template::set('records', $records);
        
    Template::set('toolbar_title', lang('bursting_strength_manage'));

        Template::render();
    }
    
    /**
     * Create a Bursting Strength object.
     *
     * @return void
     */
    public function create()
    {
        $this->auth->restrict($this->permissionCreate);
        
        if (isset($_POST['save'])) {
            if ($insert_id = $this->save_bursting_strength()) {
                log_activity($this->auth->user_id(), lang('bursting_strength_act_create_record') . ': ' . $insert_id . ' : ' . $this->input->ip_address(), 'bursting_strength');
                Template::set_message(lang('bursting_strength_create_success'), 'success');

                redirect(SITE_AREA . '/content/bursting_strength');
            }

            // Not validation error
            if ( ! empty($this->bursting_strength_model->error)) {
                Template::set_message(lang('bursting_strength_create_failure') . $this->bursting_strength_model->error, 'error');
            }
        }

        Template::set('toolbar_title', lang('bursting_strength_action_create'));

        Template::render();
    }
    /**
     * Allows editing of Bursting Strength data.
     *
     * @return void
     */
    public function edit()
    {
        $id = $this->uri->segment(5);
        if (empty($id)) {
            Template::set_message(lang('bursting_strength_invalid_id'), 'error');

            redirect(SITE_AREA . '/content/bursting_strength');
        }
        
        if (isset($_POST['save'])) {
            $this->auth->restrict($this->permissionEdit);

            if ($this->save_bursting_strength('update', $id)) {
                log_activity($this->auth->user_id(), lang('bursting_strength_act_edit_record') . ': ' . $id . ' : ' . $this->input->ip_address(), 'bursting_strength');
                Template::set_message(lang('bursting_strength_edit_success'), 'success');
                redirect(SITE_AREA . '/content/bursting_strength');
            }

            // Not validation error
            if ( ! empty($this->bursting_strength_model->error)) {
                Template::set_message(lang('bursting_strength_edit_failure') . $this->bursting_strength_model->error, 'error');
            }
        }
        
        elseif (isset($_POST['delete'])) {
            $this->auth->restrict($this->permissionDelete);

            if ($this->bursting_strength_model->delete($id)) {
                log_activity($this->auth->user_id(), lang('bursting_strength_act_delete_record') . ': ' . $id . ' : ' . $this->input->ip_address(), 'bursting_strength');
                Template::set_message(lang('bursting_strength_delete_success'), 'success');

                redirect(SITE_AREA . '/content/bursting_strength');
            }

            Template::set_message(lang('bursting_strength_delete_failure') . $this->bursting_strength_model->error, 'error');
        }
        
        Template::set('bursting_strength', $this->bursting_strength_model->find($id));

        Template::set('toolbar_title', lang('bursting_strength_edit_heading'));
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
    private function save_bursting_strength($type = 'insert', $id = 0)
    {
        if ($type == 'update') {
            $_POST['id'] = $id;
        }

        // Validate the data
        $this->form_validation->set_rules($this->bursting_strength_model->get_validation_rules());
        if ($this->form_validation->run() === false) {
            return false;
        }

        // Make sure we only pass in the fields we want
        
        $data = $this->bursting_strength_model->prep_data($this->input->post());

        // Additional handling for default values should be added below,
        // or in the model's prep_data() method
        

        $return = false;
        if ($type == 'insert') {
            $id = $this->bursting_strength_model->insert($data);

            if (is_numeric($id)) {
                $return = $id;
            }
        } elseif ($type == 'update') {
            $return = $this->bursting_strength_model->update($id, $data);
        }

        return $return;
    }
}