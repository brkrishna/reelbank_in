<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Users_model extends BF_Model {
	//--------------------------------------------------------------------

	public function get_users_select ( )
	{
		$query = $this->db->select('id, username')->get_where('users', array('deleted'=>0, 'role_id'=>'4'));

		if ( $query->num_rows() <= 0 )
			return '';

		$option = array('-1'=>'Select one');
		foreach ($query->result() as $row)
		{
			$option[$row->id] = $row->username;
		}

		$query->free_result();

		return $option;
	}

	//--------------------------------------------------------------------

}