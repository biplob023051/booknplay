<?php
/**
 * ScheduleFixture
 *
 */
class ScheduleFixture extends CakeTestFixture {
	
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
			'date' => array (
					'type' => 'datetime',
					'null' => false,
					'default' => null 
			),
			'slots' => array (
					'type' => 'string',
					'null' => false,
					'default' => null,
					'length' => 100,
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
			'ground_id' => array (
					'type' => 'integer',
					'null' => false,
					'default' => null,
					'key' => 'primary' 
			),
			'indexes' => array (
					'PRIMARY' => array (
							'column' => array (
									'id',
									'ground_id' 
							),
							'unique' => 1 
					),
					'id_UNIQUE' => array (
							'column' => 'id',
							'unique' => 1 
					),
					'fk_schedules_grounds1_idx' => array (
							'column' => 'ground_id',
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
					'date' => '2015-01-25 11:39:25',
					'slots' => 'Lorem ipsum dolor sit amet',
					'created_date' => '2015-01-25 11:39:25',
					'modified_date' => '2015-01-25 11:39:25',
					'ground_id' => 1 
			) 
	);
}
