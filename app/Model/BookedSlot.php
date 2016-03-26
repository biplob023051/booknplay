<?php
App::uses ( 'AppModel', 'Model' );
/**
 * BookedSlot Model
 *
 * @property Booking $Booking
 * @property Ground $Ground
 */
class BookedSlot extends AppModel {
	
	/**
	 * Validation rules
	 *
	 * @var array
	 */
	public $validate = array (
			'booking_id' => array (
					'numeric' => array (
							'rule' => array (
									'numeric' 
							) 
					) 
			),
			
			// 'message' => 'Your custom message here',
			// 'allowEmpty' => false,
			// 'required' => false,
			// 'last' => false, // Stop validation after this rule
			// 'on' => 'create', // Limit validation to 'create' or 'update' operations
			
			'ground_id' => array (
					'numeric' => array (
							'rule' => array (
									'numeric' 
							) 
					) 
			) 
	)
	// 'message' => 'Your custom message here',
	// 'allowEmpty' => false,
	// 'required' => false,
	// 'last' => false, // Stop validation after this rule
	// 'on' => 'create', // Limit validation to 'create' or 'update' operations
	
	;
	
	// The Associations below have been created with all possible keys, those that are not needed can be removed
	
	/**
	 * belongsTo associations
	 *
	 * @var array
	 */
	public $belongsTo = array (
			'Booking' => array (
					'className' => 'Booking',
					'foreignKey' => 'booking_id',
					'conditions' => '',
					'fields' => '',
					'order' => '' 
			),
			'Ground' => array (
					'className' => 'Ground',
					'foreignKey' => 'ground_id',
					'conditions' => '',
					'fields' => '',
					'order' => '' 
			) 
	);
	
	// Return booked slots(Date specific) of a particular date | Date format : Y-m-d
	public function booked_slots($gid = null, $date = null) {
		if ($date == null) {
			return false;
		}
		if (! $this->Ground->exists ( $gid )) {
			return false;
		}
		
		$start_date = date ( 'Y-m-d H:i:s', strtotime ( $date ) );
		$next_date = date ( 'Y-m-d H:i:s', strtotime ( $date . ' +1 days' ) );
		return $this->find ( 'all', array (
				'conditions' => array (
						'BookedSlot.ground_id' => $gid,
						'BookedSlot.locked' => 1,
						'BookedSlot.datetime >=' => $start_date,
						'BookedSlot.datetime <' => $next_date 
				),
				'recursive' => - 1 
		) );
	}
	
	// Booked Slots (date specific) in CSV - 1 hour format
	public function booked_slots_csv($gid = null, $date = null) {
		$data = $this->booked_slots ( $gid, $date );
		$csv_array = array_fill(0,Configure::read('slots_per_day'),0);
		if ($data != false && ! empty ( $data )) {
			foreach ( $data as $datum ) {
				$hour = date ( 'H', strtotime ( $datum ['BookedSlot'] ['datetime'] ) );
				$csv_array [(int)$hour] += 1;
			}
		}
		return implode ( ',', $csv_array );
	}
	
	// Date time correction to one hour - Since a slot is considered as 1 hour
	public function datetime_correction($date = null) {
		if ($date == null)
			return false;
		
		$corrected_date = date ( 'Y-m-d', strtotime ( $date ) ) . " " . date ( 'H', strtotime ( $date ) ) . ":00:00";
		return date ( 'Y-m-d H:i:s', strtotime ( $corrected_date ) );
	}
	public function beforeSave($options = array()) {
		if (in_array ( "datetime", $options ['fieldList'] ) || ($this->id == null && $this->data ['BookedSlot'] ['datetime'])) {
			$this->data ['BookedSlot'] ['datetime'] = $this->datetime_correction ( $this->data ['BookedSlot'] ['datetime'] );
		}
		return true;
	}
	public function process_private_key_for_date($value = null) {
		if (empty ( $value )) {
			return false;
		}
		$no = ltrim(substr ( $value, 0, -2 ),'s');
		$hr = substr ( $value, -2 );
		if ($no < 1)
			return false;
	
		return date ( 'Y-m-d H:i:s', strtotime ( date ( 'Y-m-d', strtotime ( ($no - 1) . ' days' ) ) . " " . ($hr - 1) . ":00:00" ) );
	}
}
