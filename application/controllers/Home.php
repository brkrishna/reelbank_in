<?php defined('BASEPATH') || exit('No direct script access allowed');

/**
 * Bonfire
 *
 * An open source project to allow developers to jumpstart their development of
 * CodeIgniter applications.
 *
 * @package   Bonfire
 * @author    Bonfire Dev Team
 * @copyright Copyright (c) 2011 - 2014, Bonfire Dev Team
 * @license   http://opensource.org/licenses/MIT The MIT License
 * @link      http://cibonfire.com
 * @since     Version 1.0
 * @filesource
 */

/**
 * Home controller
 *
 * The base controller which displays the homepage of the Bonfire site.
 *
 * @package    Bonfire
 * @subpackage Controllers
 * @category   Controllers
 * @author     Bonfire Dev Team
 * @link       http://guides.cibonfire.com/helpers/file_helpers.html
 *
 */
class Home extends MX_Controller
{
	public function __construct()
	{
		parent::__construct();

		$this->load->helper('application');
		$this->load->library('Template');
		$this->load->library('Assets');
		$this->lang->load('application');
		$this->load->library('events');

        $this->load->library('installer_lib');
        if (! $this->installer_lib->is_installed()) {
            $ci =& get_instance();
            $ci->hooks->enabled = false;
            redirect('install');
        }


        // Make the requested page var available, since
        // we're not extending from a Bonfire controller
        // and it's not done for us.
        $this->requested_page = isset($_SESSION['requested_page']) ? $_SESSION['requested_page'] : null;

        $this->load->model('items/items_model');
        $this->load->model(array('bursting_strength/bursting_strength_model', 'gsm/gsm_model', 'specific_type/specific_type_model', 'condition/condition_model', 'profile/profile_model', 'company_users/company_users_model'));
        $this->lang->load('items/items');
        
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

	//--------------------------------------------------------------------

	/**
	 * Displays the homepage of the Bonfire app
	 *
	 * @return void
	 */
	public function index($offset = 0)
	{
		$this->load->library('users/auth');
		$this->set_current_user();

        $profile_id = NULL;
        if (!empty($this->current_user)){ 
            if ($this->current_user->role_id == 4)
            {
                $profile_id = $this->company_users_model->get_profile_id($this->current_user->id);    
                $profile_id = ($profile_id > 0 ? $profile_id : -1);
                $this->session->set_userdata('profile_id',$profile_id);
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

        if($this->session->userdata('profile_id')) {
            $records = $this->items_model->find_all($this->session->userdata('profile_id'), 1);        
        }else{
            $records = $this->items_model->find_by_qty(1);
        } 

        Template::set('records', $records);

		Template::set_view('items/index');	
		Template::render();
	}//end index()

	//--------------------------------------------------------------------

	/**
	 * If the Auth lib is loaded, it will set the current user, since users
	 * will never be needed if the Auth library is not loaded. By not requiring
	 * this to be executed and loaded for every command, we can speed up calls
	 * that don't need users at all, or rely on a different type of auth, like
	 * an API or cronjob.
	 *
	 * Copied from Base_Controller
	 */
	protected function set_current_user()
	{
        if (class_exists('Auth')) {
			// Load our current logged in user for convenience
            if ($this->auth->is_logged_in()) {
				$this->current_user = clone $this->auth->user();

				$this->current_user->user_img = gravatar_link($this->current_user->email, 22, $this->current_user->email, "{$this->current_user->email} Profile");

				// if the user has a language setting then use it
                if (isset($this->current_user->language)) {
					$this->config->set_item('language', $this->current_user->language);
				}
            } else {
				$this->current_user = null;
			}

			// Make the current user available in the views
            if (! class_exists('Template')) {
				$this->load->library('Template');
			}
			Template::set('current_user', $this->current_user);
		}
	}
}
/* end ./application/controllers/home.php */
