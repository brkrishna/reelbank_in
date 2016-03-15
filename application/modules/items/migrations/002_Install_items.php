<?php defined('BASEPATH') || exit('No direct script access allowed');

class Migration_Install_items extends Migration
{
	/**
	 * @var string The name of the database table
	 */
	private $table_name = 'items';

	/**
	 * @var array The table's fields
	 */
	private $fields = array(
        'profile' => array(
            'type'       => 'INT',
            'constraint' => 11,
        ),
		'id' => array(
			'type'       => 'INT',
			'constraint' => 11,
			'auto_increment' => true,
		),
        'strength' => array(
            'type'       => 'VARCHAR',
            'constraint' => 255,
            'null'       => false,
        ),
        'gsm' => array(
            'type'       => 'VARCHAR',
            'constraint' => 255,
            'null'       => false,
        ),
        'decal' => array(
            'type'       => 'VARCHAR',
            'constraint' => 10,
            'null'       => true,
        ),
        'weight' => array(
            'type'       => 'VARCHAR',
            'constraint' => 255,
            'null'       => true,
        ),
        'type' => array(
            'type'       => 'VARCHAR',
            'constraint' => 255,
            'null'       => true,
        ),
        'mill_name' => array(
            'type'       => 'VARCHAR',
            'constraint' => 255,
            'null'       => true,
        ),
        'condition' => array(
            'type'       => 'VARCHAR',
            'constraint' => 255,
            'null'       => true,
        ),
        'qty' => array(
            'type'       => 'DECIMAL',
            'constraint' => '15,2',
            'null'       => true,
        ),
        'orig_qty' => array(
            'type'       => 'DECIMAL',
            'constraint' => '15,2',
            'null'       => true,
        ),
        'remarks' => array(
            'type'       => 'TEXT',
            'null'       => true,
        ),
        'deleted' => array(
            'type'       => 'TINYINT',
            'constraint' => 1,
            'default'    => '0',
        ),
        'deleted_by' => array(
            'type'       => 'BIGINT',
            'constraint' => 20,
            'null'       => true,
        ),
        'created_on' => array(
            'type'       => 'datetime',
            'default'    => '0000-00-00 00:00:00',
        ),
        'created_by' => array(
            'type'       => 'BIGINT',
            'constraint' => 20,
            'null'       => false,
        ),
        'modified_on' => array(
            'type'       => 'datetime',
            'default'    => '0000-00-00 00:00:00',
        ),
        'modified_by' => array(
            'type'       => 'BIGINT',
            'constraint' => 20,
            'null'       => true,
        ),
	);

	/**
	 * Install this version
	 *
	 * @return void
	 */
	public function up()
	{
		$this->dbforge->add_field($this->fields);
		$this->dbforge->add_key('id', true);
		$this->dbforge->create_table($this->table_name);
	}

	/**
	 * Uninstall this version
	 *
	 * @return void
	 */
	public function down()
	{
		$this->dbforge->drop_table($this->table_name);
	}
}