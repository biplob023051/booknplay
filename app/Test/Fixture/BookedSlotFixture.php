<?php
/**
 * BookedSlotFixture
 *
 */
class BookedSlotFixture extends CakeTestFixture {
	
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
			'datetime' => array (
					'type' => 'datetime',
					'null' => true,
					'default' => null 
			),
			'locked' => array (
					'type' => 'integer',
					'null' => true,
					'default' => null,
					'length' => 1 
			),
			'created_date' => array (
					'type' => 'datetime',
					'null' => true,
					'default' => null 
			),
			'modified_date' => array (
					'type' => 'datetime',
					'null' => true,
					'default' => null 
			),
			'booking_id' => array (
					'type' => 'integer',
					'null' => false,
					'default' => null,
					'key' => 'primary' 
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
									'booking_id',
									'ground_id' 
							),
							'unique' => 1 
					),
					'fk_booked_slots_bookings1_idx' => array (
							'column' => 'booking_id',
							'unique' => 0 
					),
					'fk_booked_slots_grounds1_idx' => array (
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
					'datetime' => '2015-01-25 11:38:57',
					'locked' => 1,
					'created_date' => '2015-01-25 11:38:57',
					'modified_date' => '2015-01-25 11:38:57',
					'booking_id' => 1,
					'ground_id' => 1 
			) 
	);
}
