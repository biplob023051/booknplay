<?php
/**
 * BookingFixture
 *
 */
class BookingFixture extends CakeTestFixture {
	
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
			'status' => array (
					'type' => 'string',
					'null' => false,
					'default' => null,
					'length' => 45,
					'collate' => 'latin1_swedish_ci',
					'charset' => 'latin1' 
			),
			'payment_method' => array (
					'type' => 'string',
					'null' => false,
					'default' => null,
					'length' => 45,
					'collate' => 'latin1_swedish_ci',
					'charset' => 'latin1' 
			),
			'slots' => array (
					'type' => 'integer',
					'null' => false,
					'default' => null,
					'length' => 5,
					'comment' => 'Add number of slots booked for this booking � Count need to be taken from booked slots.' 
			),
			'initiator' => array (
					'type' => 'string',
					'null' => false,
					'default' => null,
					'length' => 45,
					'collate' => 'latin1_swedish_ci',
					'charset' => 'latin1' 
			),
			'amount' => array (
					'type' => 'float',
					'null' => false,
					'default' => '0' 
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
					'fk_bookings_grounds1_idx' => array (
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
					'status' => 'Lorem ipsum dolor sit amet',
					'payment_method' => 'Lorem ipsum dolor sit amet',
					'slots' => 1,
					'initiator' => 'Lorem ipsum dolor sit amet',
					'amount' => 1,
					'created_date' => '2015-01-25 11:39:08',
					'modified_date' => '2015-01-25 11:39:08',
					'ground_id' => 1 
			) 
	);
}
