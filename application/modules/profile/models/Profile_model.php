<?php defined('BASEPATH') || exit('No direct script access allowed');

class Profile_model extends BF_Model
{
    protected $table_name	= 'profile';
	protected $key			= 'id';
	protected $date_format	= 'datetime';

	protected $log_user 	= true;
	protected $set_created	= true;
	protected $set_modified = true;
	protected $soft_deletes	= true;

	protected $created_field     = 'created_on';
    protected $created_by_field  = 'created_by';
	protected $modified_field    = 'modified_on';
    protected $modified_by_field = 'modified_by';
    protected $deleted_field     = 'deleted';
    protected $deleted_by_field  = 'deleted_by';

	// Customize the operations of the model without recreating the insert,
    // update, etc. methods by adding the method names to act as callbacks here.
	protected $before_insert 	= array();
	protected $after_insert 	= array();
	protected $before_update 	= array();
	protected $after_update 	= array();
	protected $before_find 	    = array();
	protected $after_find 		= array();
	protected $before_delete 	= array();
	protected $after_delete 	= array();

	// For performance reasons, you may require your model to NOT return the id
	// of the last inserted row as it is a bit of a slow method. This is
    // primarily helpful when running big loops over data.
	protected $return_insert_id = true;

	// The default type for returned row data.
	protected $return_type = 'object';

	// Items that are always removed from data prior to inserts or updates.
	protected $protected_attributes = array();

	// You may need to move certain rules (like required) into the
	// $insert_validation_rules array and out of the standard validation array.
	// That way it is only required during inserts, not updates which may only
	// be updating a portion of the data.
	protected $validation_rules 		= array(
		array(
			'field' => 'name',
			'label' => 'lang:profile_field_name',
			'rules' => 'required|unique[bf_profile.name,bf_profile.id]|trim|max_length[255]',
		),
		array(
			'field' => 'contact',
			'label' => 'lang:profile_field_contact',
			'rules' => 'required|trim|max_length[255]',
		),
		array(
			'field' => 'phone',
			'label' => 'lang:profile_field_phone',
			'rules' => 'required|trim|max_length[255]',
		),
		array(
			'field' => 'website',
			'label' => 'lang:profile_field_website',
			'rules' => 'trim|max_length[255]',
		),
		array(
			'field' => 'email',
			'label' => 'lang:profile_field_email',
			'rules' => 'trim|valid_email|max_length[255]',
		),
		array(
			'field' => 'pan',
			'label' => 'lang:profile_field_pan',
			'rules' => 'trim|alpha_numeric|max_length[50]',
		),
		array(
			'field' => 'tin',
			'label' => 'lang:profile_field_tin',
			'rules' => 'trim|alpha_numeric|max_length[255]',
		),
		array(
			'field' => 'excise_nbr',
			'label' => 'lang:profile_field_excise_nbr',
			'rules' => 'trim|alpha_numeric|max_length[255]',
		),
	);
	protected $insert_validation_rules  = array();
	protected $skip_validation 			= false;

    /**
     * Constructor
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

	public function get_profile_select ()
	{
	    $query = $this->db->select('id, name')->get_where($this->table_name, array('deleted'=>0));

		if ( $query->num_rows() <= 0 )
			return '';

		$option = array('-1'=>'Select one');
		foreach ($query->result() as $row)
		{
			$option[$row->id] = $row->name;
		}

		$query->free_result();

		return $option;
	}

}