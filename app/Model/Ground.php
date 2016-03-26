<?php
App::uses ( 'AppModel', 'Model' );
/**
 * Ground Model
 *
 * @property User $User
 * @property BookedSlot $BookedSlot
 * @property Booking $Booking
 * @property Schedule $Schedule
 */
class Ground extends AppModel {
	
	/**
	 * Validation rules
	 *
	 * @var array
	 */
	public $validate = array (
			'name' => array (
					'notempty' => array (
							'rule' => array (
									'notempty' 
							) 
					) 
			),
			
			// 'message' => 'Your custom message here',
			// 'allowEmpty' => false,
			// 'required' => false,
			// 'last' => false, // Stop validation after this rule
			// 'on' => 'create', // Limit validation to 'create' or 'update' operations
			
			'address_line_1' => array (
					'notempty' => array (
							'rule' => array (
									'notempty' 
							) 
					) 
			),
			
			// 'message' => 'Your custom message here',
			// 'allowEmpty' => false,
			// 'required' => false,
			// 'last' => false, // Stop validation after this rule
			// 'on' => 'create', // Limit validation to 'create' or 'update' operations
			
			'address_line_2' => array (
					'notempty' => array (
							'rule' => array (
									'notempty' 
							) 
					) 
			),
			
			// 'message' => 'Your custom message here',
			// 'allowEmpty' => false,
			// 'required' => false,
			// 'last' => false, // Stop validation after this rule
			// 'on' => 'create', // Limit validation to 'create' or 'update' operations
			
			'count' => array (
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
			
			'locality' => array (
					'notempty' => array (
							'rule' => array (
									'notempty' 
							) 
					) 
			),
			
			// 'message' => 'Your custom message here',
			// 'allowEmpty' => false,
			// 'required' => false,
			// 'last' => false, // Stop validation after this rule
			// 'on' => 'create', // Limit validation to 'create' or 'update' operations
			
			'city' => array (
					'notempty' => array (
							'rule' => array (
									'notempty' 
							) 
					) 
			),
			
			// 'message' => 'Your custom message here',
			// 'allowEmpty' => false,
			// 'required' => false,
			// 'last' => false, // Stop validation after this rule
			// 'on' => 'create', // Limit validation to 'create' or 'update' operations
			
			'state' => array (
					'notempty' => array (
							'rule' => array (
									'notempty' 
							) 
					) 
			),
			
			// 'message' => 'Your custom message here',
			// 'allowEmpty' => false,
			// 'required' => false,
			// 'last' => false, // Stop validation after this rule
			// 'on' => 'create', // Limit validation to 'create' or 'update' operations
			
			'pin' => array (
					'notempty' => array (
							'rule' => array (
									'notempty' 
							) 
					) 
			),
			
			// 'message' => 'Your custom message here',
			// 'allowEmpty' => false,
			// 'required' => false,
			// 'last' => false, // Stop validation after this rule
			// 'on' => 'create', // Limit validation to 'create' or 'update' operations
			
			'phone' => array (
					'notempty' => array (
							'rule' => array (
									'notempty' 
							) 
					) 
			),
			
			// 'message' => 'Your custom message here',
			// 'allowEmpty' => false,
			// 'required' => false,
			// 'last' => false, // Stop validation after this rule
			// 'on' => 'create', // Limit validation to 'create' or 'update' operations
			
			'created' => array (
					'datetime' => array (
							'rule' => array (
									'datetime' 
							) 
					) 
			),
			
			// 'message' => 'Your custom message here',
			// 'allowEmpty' => false,
			// 'required' => false,
			// 'last' => false, // Stop validation after this rule
			// 'on' => 'create', // Limit validation to 'create' or 'update' operations
			
			'modified' => array (
					'datetime' => array (
							'rule' => array (
									'datetime' 
							) 
					) 
			),
			
			// 'message' => 'Your custom message here',
			// 'allowEmpty' => false,
			// 'required' => false,
			// 'last' => false, // Stop validation after this rule
			// 'on' => 'create', // Limit validation to 'create' or 'update' operations
			
			'active' => array (
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
			
			'featured' => array (
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
			
			'staff_picked' => array (
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
			
			'rating' => array (
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
			'User' => array (
					'className' => 'User',
					'foreignKey' => 'user_id',
					'conditions' => '',
					'fields' => '',
					'order' => '' 
			),
			'Type' => array (
					'className' => 'Type',
					'foreignKey' => 'type_id',
					'conditions' => '',
					'fields' => '',
					'order' => '' 
			) 
	);
	
	/**
	 * hasMany associations
	 *
	 * @var array
	 */
	public $hasMany = array (
			'BookedSlot' => array (
					'className' => 'BookedSlot',
					'foreignKey' => 'ground_id',
					'dependent' => false,
					'conditions' => '',
					'fields' => '',
					'order' => '',
					'limit' => '',
					'offset' => '',
					'exclusive' => '',
					'finderQuery' => '',
					'counterQuery' => '' 
			),
			'Booking' => array (
					'className' => 'Booking',
					'foreignKey' => 'ground_id',
					'dependent' => false,
					'conditions' => '',
					'fields' => '',
					'order' => '',
					'limit' => '',
					'offset' => '',
					'exclusive' => '',
					'finderQuery' => '',
					'counterQuery' => '' 
			),
			'Schedule' => array (
					'className' => 'Schedule',
					'foreignKey' => 'ground_id',
					'dependent' => false,
					'conditions' => '',
					'fields' => '',
					'order' => '',
					'limit' => '',
					'offset' => '',
					'exclusive' => '',
					'finderQuery' => '',
					'counterQuery' => '' 
			) 
	);
	
	// Available Slots
	// Returns as 24 slots csv format(1 hour). Eg : 1,0,0,1,1,0,0,1,1,0,0,1,1,0,0,1,1,0,0,1,1,0,0,1
	function available_slots($id = null, $days = null, $start = 0, $no_selected = 1) {
		if ($days == null)
			$days = Configure::read ( 'display_days' );

		$this->id = $id;
		if (! $this->exists ()) {
			$output_data = array ();
			for($i = 0; $i < $days; $i ++) {
				$output_data [($i+$start)] ['Schedule'] ['slots'] = $this->block_slots ();
				$output_data [($i+$start)] ['Schedule'] ['id'] = $id;
				$output_data [($i+$start)] ['Schedule'] ['date'] = date ( 'Y-m-d', strtotime ( ($i+$start) . ' days' ) );
			}
			return $output_data;
		} else {
			$court = $this->field ( 'count' );
			$data = $this->Schedule->find ( 'all', array (
					'fields' => array (
							'DISTINCT Schedule.date',
							'Schedule.id',
							'Schedule.slots' 
					),
					'conditions' => array (
							'Schedule.ground_id' => $id,
							"Schedule.date <" => date ( 'Y-m-d H:i:s', strtotime ( $start+$days . " days" ) ),
							"Schedule.date >" => date ( 'Y-m-d H:i:s', strtotime ( ($start-1) ." days" ) ) 
					),
					'recursive' => - 1,
					'limit'=>$days,
					'order' => 'Schedule.date ASC' 
			) );
			
			if($no_selected < 1 || $no_selected > $court)
				$no_selected = 1;
			
			//Available Days
			$output_data = array();
			if (! empty ( $data )) {
				foreach ( $data as $k => $datum ) {
					// Check the booking
					$csv_data = $this->BookedSlot->booked_slots_csv ( $id, $datum ['Schedule'] ['date'] );
					$csv_data = $this->invert_binary($this->binary_slot ( $csv_data, $court, $no_selected ));
					
					// And fill slots availability accordingly by comparing Booking data, court count and Schedule
					if (! $slots = $this->combine_schedule_with_booking ( $datum ['Schedule'] ['slots'], $csv_data ))
						$slots = $this->block_slots ( $datum ['Schedule'] ['slots'] );
					
					$output_data [date('Y-m-d',strtotime($datum ['Schedule'] ['date']))] = $slots;
				}
			}
			
			//All Days
			$all_data = array ();
			for($i = 0; $i < $days; $i ++) {
				if(isset($output_data[date('Y-m-d',strtotime ( ($i+$start) . ' days' ))]))
					$all_data [date('Y-m-d',strtotime ( ($i+$start) . ' days' ))] = $output_data[date('Y-m-d',strtotime ( ($i+$start) . ' days' ))];
				else
					$all_data [date('Y-m-d',strtotime ( ($i+$start) . ' days' ))] = $this->block_slots ();
			}
		}
		return $all_data;
	}
	
	// Fill with date count
	public function fill_slots($id, $data, $days) {
		if (count ( $data ) == $days)
			return $data;
		
		$output_data = array ();
		for($i = 0; $i < $days; $i ++) {
			$output_data [$i] ['Schedule'] ['slots'] = block_slots ();
			$output_data [$i] ['Schedule'] ['id'] = $id;
			$output_data [$i] ['Schedule'] ['date'] = date ( 'Y-m-d', strtotime ( $i . ' days' ) );
		}
		return $output_data;
	}
	
	// Block all slots
	public function block_slots($data = null) {
		if ($data == null) {
			$output = array ();
			for($i = 0; $i < Configure::read ( 'slots_per_day' ); $i ++) {
				$output [] = 0;
			}
			return implode ( ',', $output );
		}
		$data_array = explode ( ',', $data );
		foreach ( $data_array as $k => $datum ) {
			$output_array [$k] = 0;
		}
		return implode ( ',', $output_array );
	}
	
	// Change to binary slot Eg: 2,3,0,0,2,1,1,0 -> 1,1,0,0,1,1,1,0 by comparing count
	public function binary_slot($data, $count = 1, $selected_court = 1) {
		$data_array = explode ( ',', $data );
		$output_array = array ();
		foreach ( $data_array as $k => $datum ) {
			$datum = $datum + ($selected_court - 1);
			$output_array [$k] = ($datum < 1 || $datum < $count) ? 0 : 1;
		}
		return implode ( ',', $output_array );
	}
	
	public function invert_binary($data) {
		$data_array = explode ( ',', $data );
		$output_array = array ();
		foreach ( $data_array as $k => $datum ) {
			$output_array [$k] = ($datum == 0) ? 1 : 0;
		}
		return implode ( ',', $output_array );
	}
	
	// Combine schedule with booked data
	public function combine_schedule_with_booking($sch_data, $booked_data) {
		$sch_data_arr = explode ( ',', $sch_data );
		$booked_data_arr = explode ( ',', $booked_data );
		if (count ( $sch_data_arr ) != count ( $booked_data_arr ))
			return false;
		foreach ( $sch_data_arr as $k => $datum ) {
			$sch_data_arr [$k] = ($sch_data_arr[$k] > 0 && $booked_data_arr [$k] > 0) ? 1 : 0;
		}
		return implode ( ',', $sch_data_arr );
	}
	
	public function isOwner($gid = null,$uid = null){
		if($gid == null || $uid == null)
			return false;
		
		$data = $this->find('first',array('conditions'=>array('Ground.id'=>$gid,'Ground.user_id'=>$uid),'fields'=>array('Ground.user_id'),'recursive'=>-1));
		if(empty($data))
			return false;
		
		return true;
	}
	
	public function getPriceBasedOnGround($id, $cond_date = null) {
		$data = $this->Schedule->find ( 'first', array (
					'fields' => array (
							'DISTINCT Schedule.date',
							'Schedule.id',
							'Schedule.slots',
							'Schedule.prices' 
					),
					'conditions' => array (
							'Schedule.ground_id' => $id,
							"Schedule.date " => date ( 'Y-m-d H:i:s', strtotime ($cond_date)) 
					)
			) );
		return $data;
	}
}
