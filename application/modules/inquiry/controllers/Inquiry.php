<?php defined('BASEPATH') || exit('No direct script access allowed');

/**
 * Content controller
 */
class Content extends Authenticated_Controller
{
    protected $permissionCreate = 'Inquiry.Content.Create';
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
        $this->load->model(array('company_users/company_users_model', 'items/items_model', 'users_model', 'profile/profile_model'));
        $this->lang->load('inquiry');
        
        $this->form_validation->set_error_delimiters("<div class='alert alert-danger'>", "</div>");
        
        Template::set_block('sub_nav', 'content/_sub_nav');

        Assets::add_module_js('inquiry', 'inquiry.js');

        $profile_id = NULL;
        if (!empty($this->current_user)){ 
            if ($this->current_user->role_id == 4)
            {
                $profile_id = $this->company_users_model->get_profile_id($this->current_user->id);    
                $profile_id = ($profile_id > 0 ? $profile_id : -1);
                $this->session->set_userdata('profile_id',$profile_id);
            }
        }

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


        $id = $this->uri->segment(5);
        if (empty($id)) {
            Template::set_message(lang('inquiry_invalid_id'), 'error');

            redirect(SITE_AREA . '/');
        }
        $this->auth->restrict($this->permissionCreate);
        
        $this->load->model(array('bursting_strength/bursting_strength_model', 'gsm/gsm_model', 'specific_type/specific_type_model', 'condition/condition_model', 'profile/profile_model'));

        $bursting_strength_select = $this->bursting_strength_model->get_bursting_strength_select();
        var_dump($bursting_strength_select);
        exit;
        Template::set('bursting_strength_select', $bursting_strength_select);

        $gsm_select = $this->gsm_model->get_gsm_select();
        Template::set('gsm_select', $gsm_select);

        $specific_type_select = $this->specific_type_model->get_specific_type_select();
        Template::set('specific_type_select', $specific_type_select);
        
        $condition_select = $this->condition_model->get_condition_select();
        Template::set('condition_select', $condition_select);

        $profile_select = $this->profile_model->get_profile_select();
        Template::set('profile_select', $profile_select);

        $item = $this->items_model->find($id);
        $user = $this->items_model->find($this->current_user->id);
        $buyer = $this->profile_model->find($profile_id);
        //var_dump($item);
        $seller_profile_id = $item->profile;
        $seller = $this->profile_model->find($seller_profile_id);

        /*
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
        */
        if ($insert_id = $this->save_inquiry('insert', $id)) {
            log_activity($this->auth->user_id(), lang('inquiry_act_create_record') . ': ' . $insert_id . ' : ' . $this->input->ip_address(), 'inquiry');
            Template::set_message(lang('inquiry_create_success'), 'success');
        }

        // Now send the email
        $this->load->library('emailer/emailer');
        $email = array(
            'to'      => $this->input->post('email'),
            'subject' => lang('us_reset_pass_subject'),
            'message' => $this->load->view(
                '_emails/forgot_password', $item),
                true
            );

        if ($this->emailer->send($email)) {
            Template::set_message(lang('inquiry_sent_email_success'), 'success');
        } else {
            Template::set_message(lang('inquiry_sent_email_failure') . $this->emailer->error, 'error');
        }

        Template::set('toolbar_title', lang('inquiry_action_create'));
        Template::set('item_id', $id);
        Template::set('profile_id', $this->session->userdata('profile_id'));
        Template::set('item', $item);
        Template::set_view('thankyou');
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
        }else{
            $_POST['item_id'] = $id;
            $_POST['profile_id'] = $this->session->userdata('profile_id');
            $_POST['comments'] = 'System genereated';
        }

        // Validate the data
        $this->form_validation->set_rules($this->inquiry_model->get_validation_rules());
        if ($this->form_validation->run() === false) {
            return false;
        }

        // Make sure we only pass in the fields we want
        
        $data = $this->inquiry_model->prep_data($this->input->post());
        //$data['item_id'] = $id;
        //$data['profile_id'] = $this->session->userdata('profile_id');

        // Additional handling for default values should be added below,
        // or in the model's prep_data() method
        
        var_dump($data);
        exit;    
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