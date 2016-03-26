<?php
/**
 * GroupFixture
 *
 */
class GroupFixture extends CakeTestFixture {
	
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
			'type_group' => array (
					'type' => 'string',
					'null' => false,
					'default' => null,
					'length' => 100,
					'key' => 'unique',
					'collate' => 'latin1_swedish_ci',
					'charset' => 'latin1' 
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
					'group_UNIQUE' => array (
							'column' => 'type_group',
							'unique' => 1 
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
					'type_group' => 'Lorem ipsum dolor sit amet' 
			) 
	);
}
