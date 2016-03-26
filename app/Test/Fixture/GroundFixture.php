<?php
/**
 * GroundFixture
 *
 */
class GroundFixture extends CakeTestFixture {
	
	/**
	 * Fields
	 *
	 * @var array
	 */
	public $fields = array (
			'id' => array (
					'type' => 'integer',
					'null' => false,
					'default' => null,
					'key' => 'primary' 
			),
			'name' => array (
					'type' => 'string',
					'null' => false,
					'default' => null,
					'length' => 200,
					'key' => 'unique',
					'collate' => 'latin1_swedish_ci',
					'charset' => 'latin1' 
			),
			'address_line_1' => array (
					'type' => 'string',
					'null' => false,
					'default' => null,
					'length' => 300,
					'collate' => 'latin1_swedish_ci',
					'charset' => 'latin1' 
			),
			'address_line_2' => array (
					'type' => 'string',
					'null' => false,
					'default' => null,
					'length' => 300,
					'collate' => 'latin1_swedish_ci',
					'charset' => 'latin1' 
			),
			'count' => array (
					'type' => 'integer',
					'null' => false,
					'default' => '1',
					'length' => 5 
			),
			'locality' => array (
					'type' => 'string',
					'null' => false,
					'default' => null,
					'length' => 150,
					'collate' => 'latin1_swedish_ci',
					'charset' => 'latin1' 
			),
			'city' => array (
					'type' => 'string',
					'null' => false,
					'default' => null,
					'length' => 150,
					'collate' => 'latin1_swedish_ci',
					'charset' => 'latin1' 
			),
			'state' => array (
					'type' => 'string',
					'null' => false,
					'default' => null,
					'length' => 150,
					'collate' => 'latin1_swedish_ci',
					'charset' => 'latin1' 
			),
			'pin' => array (
					'type' => 'string',
					'null' => false,
					'default' => null,
					'length' => 10,
					'collate' => 'latin1_swedish_ci',
					'charset' => 'latin1' 
			),
			'phone' => array (
					'type' => 'string',
					'null' => false,
					'default' => null,
					'length' => 50,
					'collate' => 'latin1_swedish_ci',
					'charset' => 'latin1' 
			),
			'created_date' => array (
					'type' => 'datetime',
					'null' => false,
					'default' => null 
			),
			'modified_date' => array (
					'type' => 'datetime',
					'null' => false,
					'default' => null 
			),
			'active' => array (
					'type' => 'integer',
					'null' => false,
					'default' => '1',
					'length' => 1 
			),
			'featured' => array (
					'type' => 'integer',
					'null' => false,
					'default' => '0',
					'length' => 1 
			),
			'staff_picked' => array (
					'type' => 'integer',
					'null' => false,
					'default' => '0',
					'length' => 1 
			),
			'tags' => array (
					'type' => 'string',
					'null' => true,
					'default' => null,
					'length' => 200,
					'collate' => 'latin1_swedish_ci',
					'charset' => 'latin1' 
			),
			'user_id' => array (
					'type' => 'integer',
					'null' => true,
					'default' => null,
					'key' => 'index' 
			),
			'rating' => array (
					'type' => 'integer',
					'null' => false,
					'default' => '1',
					'length' => 5 
			),
			'indexes' => array (
					'PRIMARY' => array (
							'column' => 'id',
							'unique' => 1 
					),
					'id_UNIQUE' => array (
							'column' => 'id',
							'unique' => 1 
					),
					'name_UNIQUE' => array (
							'column' => 'name',
							'unique' => 1 
					),
					'fk_grounds_users1_idx' => array (
							'column' => 'user_id',
							'unique' => 0 
					) 
			),
			'tableParameters' => array (
					'charset' => 'latin1',
					'collate' => 'latin1_swedish_ci',
					'engine' => 'InnoDB' 
			) 
	);
	
	/**
	 * Records
	 *
	 * @var array
	 */
	public $records = array (
			array (
					'id' => 1,
					'name' => 'Lorem ipsum dolor sit amet',
					'address_line_1' => 'Lorem ipsum dolor sit amet',
					'address_line_2' => 'Lorem ipsum dolor sit amet',
					'count' => 1,
					'locality' => 'Lorem ipsum dolor sit amet',
					'city' => 'Lorem ipsum dolor sit amet',
					'state' => 'Lorem ipsum dolor sit amet',
					'pin' => 'Lorem ip',
					'phone' => 'Lorem ipsum dolor sit amet',
					'created_date' => '2015-01-25 11:39:16',
					'modified_date' => '2015-01-25 11:39:16',
					'active' => 1,
					'featured' => 1,
					'staff_picked' => 1,
					'tags' => 'Lorem ipsum dolor sit amet',
					'user_id' => 1,
					'rating' => 1 
			) 
	);
}
