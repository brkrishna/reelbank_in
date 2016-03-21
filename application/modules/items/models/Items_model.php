<?php defined('BASEPATH') || exit('No direct script access allowed');

class Items_model extends BF_Model
{
    protected $table_name	= 'items';
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
			'field' => 'profile',
			'label' => 'lang:items_field_profile',
			'rules' => 'required|trim|alpha_dash|max_length[255]',
		),
		array(
			'field' => 'strength',
			'label' => 'lang:items_field_strength',
			'rules' => 'required|trim|alpha_dash|max_length[255]',
		),
		array(
			'field' => 'gsm',
			'label' => 'lang:items_field_gsm',
			'rules' => 'required|trim|alpha_numeric|max_length[255]',
		),
		array(
			'field' => 'decal',
			'label' => 'lang:items_field_decal',
			'rules' => 'required|trim|is_natural_no_zero|max_length[10]',
		),
		array(
			'field' => 'weight',
			'label' => 'lang:items_field_weight',
			'rules' => 'required|trim|is_natural_no_zero|max_length[255]',
		),
		array(
			'field' => 'type',
			'label' => 'lang:items_field_type',
			'rules' => 'required|trim|alpha_numeric|max_length[255]',
		),
		array(
			'field' => 'mill_name',
			'label' => 'lang:items_field_mill_name',
			'rules' => 'required|trim|max_length[255]',
		),
		array(
			'field' => 'condition',
			'label' => 'lang:items_field_condition',
			'rules' => 'required|trim|alpha_numeric|max_length[255]',
		),
		array(
			'field' => 'qty',
			'label' => 'lang:items_field_qty',
			'rules' => 'required|numeric|max_length[16]',
		),
		array(
			'field' => 'orig_qty',
			'label' => 'lang:items_field_orig_qty',
			'rules' => 'numeric|max_length[16]',
		),
		array(
			'field' => 'remarks',
			'label' => 'lang:items_field_remarks',
			'rules' => 'trim|max_length[4000]',
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

    public function find_by_qty($qty = 0)
    {
        $query = $this->db->get_where($this->table_name, array('qty > '=> $qty));
        
		if (!$query->num_rows())
		{
			return FALSE;
		}else{
            return $query->result();
        }
    }

    public function find_all($profile_id = NULL, $qty = 0)
    {
        if ($profile_id != NULL)
        {
            $query = $this->db->get_where($this->table_name, array('profile != '=>$profile_id, 'qty > '=>$qty));
        }
        else{
            $query = $this->db->get($this->table_name);
        }
        
		if (!$query->num_rows())
		{
			return FALSE;
		}else{
            return $query->result();
        }
    }
    
    public function find_my_items($profile_id = NULL)
    {
        if ($profile_id != NULL)
        {
            $query = $this->db->get_where($this->table_name, array('profile'=>$profile_id));
        }
        else{
            $query = $this->db->get($this->table_name);
        }
        
		if (!$query->num_rows())
		{
			return FALSE;
		}else{
            return $query->result();
        }
    }
}