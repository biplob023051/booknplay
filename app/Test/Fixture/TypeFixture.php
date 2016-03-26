<?php
/**
 * TypeFixture
 *
 */
class TypeFixture extends CakeTestFixture {
	
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
			'type' => array (
					'type' => 'string',
					'null' => false,
					'default' => null,
					'length' => 100,
					'key' => 'unique',
					'collate' => 'latin1_swedish_ci',
					'charset' => 'latin1' 
			),
			'group_id' => array (
					'type' => 'integer',
					'null' => false,
					'default' => null,
					'key' => 'primary' 
			),
			'indexes' => array (
					'PRIMARY' => array (
							'column' => array (
									'id',
									'group_id' 
							),
							'unique' => 1 
					),
					'id_UNIQUE' => array (
							'column' => 'id',
							'unique' => 1 
					),
					'type_UNIQUE' => array (
							'column' => 'type',
							'unique' => 1 
					),
					'fk_type_group1_idx' => array (
							'column' => 'group_id',
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
					'type' => 'Lorem ipsum dolor sit amet',
					'group_id' => 1 
			) 
	);
}
