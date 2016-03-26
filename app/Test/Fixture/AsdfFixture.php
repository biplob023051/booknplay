<?php
/**
 * AsdfFixture
 *
 */
class AsdfFixture extends CakeTestFixture {
	
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
					'length' => 100,
					'collate' => 'latin1_swedish_ci',
					'charset' => 'latin1' 
			),
			'desc' => array (
					'type' => 'string',
					'null' => false,
					'default' => null,
					'length' => 100,
					'collate' => 'latin1_swedish_ci',
					'charset' => 'latin1' 
			),
			'oops' => array (
					'type' => 'integer',
					'null' => false,
					'default' => null 
			),
			'indexes' => array (
					'PRIMARY' => array (
							'column' => 'id',
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
					'name' => 'Lorem ipsum dolor sit amet',
					'desc' => 'Lorem ipsum dolor sit amet',
					'oops' => 1 
			) 
	);
}
