<?php defined('BASEPATH') || exit('No direct script access allowed');

/**
 * Content controller
 */
class Content extends Admin_Controller
{
    protected $permissionCreate = 'Items.Content.Create';
    protected $permissionDelete = 'Items.Content.Delete';
    protected $permissionEdit   = 'Items.Content.Edit';
    protected $permissionView   = 'Items.Content.View';

    /**
     * Constructor
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        
        $this->auth->restrict($this->permissionView);
        $this->load->model('items/items_model');
        $this->load->model(array('bursting_strength/bursting_strength_model', 'gsm/gsm_model', 'specific_type/specific_type_model', 'condition/condition_model', 'profile/profile_model'));
        $this->lang->load('items');
        
        $this->form_validation->set_error_delimiters("<div class='alert alert-danger'>", "</div>");
        
        Template::set_block('sub_nav', 'content/_sub_nav');

        Assets::add_module_js('items', 'items.js');

        $profile_id = NULL;
        if (!empty($this->current_user)){ 
            if ($this->current_user->role_id == 4)
            {
                $profile_id = $this->company_users_model->get_profile_id($this->current_user->id);    
                $profile_id = ($profile_id > 0 ? $profile_id : -1);
                $this->session->set_userdata('profile_id',$profile_id);
            }
        }
        
        $bursting_strength_select = $this->bursting_strength_model->get_bursting_strength_select();
        Template::set('bursting_strength_select', $bursting_strength_select);

        $gsm_select = $this->gsm_model->get_gsm_select();
        Template::set('gsm_select', $gsm_select);

        $specific_type_select = $this->specific_type_model->get_specific_type_select();
        Template::set('specific_type_select', $specific_type_select);

        $condition_select = $this->condition_model->get_condition_select();
        Template::set('condition_select', $condition_select);

        $profile_select = $this->profile_model->get_profile_select();
        Template::set('profile_select', $profile_select);

    }

    /**
     * Display a list of Items data.
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
                    $deleted = $this->items_model->delete($pid);
                    if ($deleted == false) {
                        $result = false;
                    }
                }
                if ($result) {
                    Template::set_message(count($checked) . ' ' . lang('items_delete_success'), 'success');
                } else {
                    Template::set_message(lang('items_delete_failure') . $this->items_model->error, 'error');
                }
            }
        }
        $pagerUriSegment = 5;
        $pagerBaseUrl = site_url(SITE_AREA . '/content/items/index') . '/';
        
        $limit  = $this->settings_lib->item('site.list_limit') ?: 15;

        $this->load->library('pagination');
        $pager['base_url']    = $pagerBaseUrl;
        $pager['total_rows']  = $this->items_model->count_all();
        $pager['per_page']    = $limit;
        $pager['uri_segment'] = $pagerUriSegment;

        $this->pagination->initialize($pager);
        $this->items_model->limit($limit, $offset);

        if(isset($current_user)) {
            if ($current_user->role_id == 4){
                $records = $this->items_model->find_all($this->session->userdata('profile_id'));        
            }
            else{
               $records = $this->items_model->find_all();             
            }   
        } 
        

        Template::set('records', $records);
        
        Template::set('toolbar_title', lang('items_manage'));

        Template::render();
    }
    
    /**
     * Create a Items object.
     *
     * @return void
     */
    public function create()
    {
        $this->auth->restrict($this->permissionCreate);
        
        if (isset($_POST['save']) || isset($_POST['savenew'])) {
            if ($insert_id = $this->save_items()) {
                log_activity($this->auth->user_id(), lang('items_act_create_record') . ': ' . $insert_id . ' : ' . $this->input->ip_address(), 'items');
                Template::set_message(lang('items_create_success'), 'success');

                if (isset($_POST['savenew'])){
                    redirect(SITE_AREA . '/content/items/create');    
                }else{
                    redirect(SITE_AREA . '/content/items');    
                }
                
            }

            // Not validation error
            if ( ! empty($this->items_model->error)) {
                Template::set_message(lang('items_create_failure') . $this->items_model->error, 'error');
            }
        }

        Template::set('toolbar_title', lang('items_action_create'));
        Template::set_view('content/edit');    
        Template::render();
    }
    /**
     * Allows editing of Items data.
     *
     * @return void
     */
    public function edit()
    {
        $id = $this->uri->segment(5);
        if (empty($id)) {
            Template::set_message(lang('items_invalid_id'), 'error');

            redirect(SITE_AREA . '/content/items');
        }
        
        if (isset($_POST['save'])) {
            $this->auth->restrict($this->permissionEdit);

            if ($this->save_items('update', $id)) {
                log_activity($this->auth->user_id(), lang('items_act_edit_record') . ': ' . $id . ' : ' . $this->input->ip_address(), 'items');
                Template::set_message(lang('items_edit_success'), 'success');
                redirect(SITE_AREA . '/content/items');
            }

            // Not validation error
            if ( ! empty($this->items_model->error)) {
                Template::set_message(lang('items_edit_failure') . $this->items_model->error, 'error');
            }
        }
        
        elseif (isset($_POST['delete'])) {
            $this->auth->restrict($this->permissionDelete);

            if ($this->items_model->delete($id)) {
                log_activity($this->auth->user_id(), lang('items_act_delete_record') . ': ' . $id . ' : ' . $this->input->ip_address(), 'items');
                Template::set_message(lang('items_delete_success'), 'success');

                redirect(SITE_AREA . '/content/items');
            }

            Template::set_message(lang('items_delete_failure') . $this->items_model->error, 'error');
        }
        
        Template::set('items', $this->items_model->find($id));

        Template::set('toolbar_title', lang('items_edit_heading'));
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
    private function save_items($type = 'insert', $id = 0)
    {
        if ($type == 'update') {
            $_POST['id'] = $id;
        }

        // Validate the data
        $this->form_validation->set_rules($this->items_model->get_validation_rules());
        if ($this->form_validation->run() === false) {
            return false;
        }

        // Make sure we only pass in the fields we want
        
        $data = $this->items_model->prep_data($this->input->post());

        // Additional handling for default values should be added below,
        // or in the model's prep_data() method
        

        $return = false;
        if ($type == 'insert') {
            $id = $this->items_model->insert($data);

            if (is_numeric($id)) {
                $return = $id;
            }
        } elseif ($type == 'update') {
            $return = $this->items_model->update($id, $data);
        }

        return $return;
    }
}