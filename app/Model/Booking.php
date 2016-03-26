<?php
App::uses ( 'AppModel', 'Model' );
/**
 * Booking Model
 *
 * @property Ground $Ground
 * @property BookedSlot $BookedSlot
 */
class Booking extends AppModel {
	
	/**
	 * Validation rules
	 *
	 * @var array
	 */
	public $validate = array (
			'status' => array (
					'notempty' => array (
							'rule' => array (
									'notempty' 
							) 
					),
					
					// 'message' => 'Your custom message here',
					// 'allowEmpty' => false,
					// 'required' => false,
					// 'last' => false, // Stop validation after this rule
					// 'on' => 'create', // Limit validation to 'create' or 'update' operations
					'enum' => array (
							'rule' => array (
									'inlist',
									array (
											'INITIATED',
											'SUCCESS',
											'FAILED',
											'REFUNDED',
											'PENDING',
											'CANCELLED' 
									) 
							),
							'message' => array (
									'It must one among these initiated, success, failed, refuned' 
							) 
					) 
			),
			'payment_method' => array (
					'notempty' => array (
							'rule' => array (
									'notempty' 
							) 
					),
					
					// 'message' => 'Your custom message here',
					// 'allowEmpty' => false,
					// 'required' => false,
					// 'last' => false, // Stop validation after this rule
					// 'on' => 'create', // Limit validation to 'create' or 'update' operations
					'enum' => array (
							'rule' => array (
									'inlist',
									array (
											'PAYU',
											'DIRECT' 
									) 
							),
							'message' => array (
									'It must one among these PAYU or DIRECT' 
							) 
					) 
			),
			'initiator' => array (
					'notempty' => array (
							'rule' => array (
									'notempty' 
							) 
					),
					
					// 'message' => 'Your custom message here',
					// 'allowEmpty' => false,
					// 'required' => false,
					// 'last' => false, // Stop validation after this rule
					// 'on' => 'create', // Limit validation to 'create' or 'update' operations
					'enum' => array (
							'rule' => array (
									'inlist',
									array (
											'USER',
											'ADMIN',
											'GUEST',
											'GOWNER' 
									) 
							),
							'message' => array (
									'It must one among these PAYU or DIRECT' 
							) 
					) 
			),
			'created' => array (
					'datetime' => array (
							'rule' => array (
									'datetime' 
							) 
					) 
			),
			'datetime' => array (
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
			'Ground' => array (
					'className' => 'Ground',
					'foreignKey' => 'ground_id',
					'conditions' => '',
					'fields' => '',
					'order' => '' 
			),
			'User' => array (
					'className' => 'User',
					'foreignKey' => 'user_id',
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
					'foreignKey' => 'booking_id',
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
	
	public function onSuccessfulPayment($transactionAttemptId, $id, $feedback) {
			$this->id = $id;
			$this->saveField("status", "SUCCESS");
			$this->recursive = 1;
			$paymentData = $this->read();
			
			//Mail and SmS to users on successful payment
			if(!empty($paymentData['User']['email']) && !empty($paymentData['User']['phone'])) {
					$slot_summary = "";
					foreach ( $paymentData['BookedSlot'] as $datum ) {
						$datetime = $datum['datetime'];
						if (! $datetime)
							continue;

//$slot_summary .= date ( 'j M , g:i a', strtotime ( $datetime ) ).'- '.date ( 'j M , g:i a', strtotime ( $datetime ) + 3600).', ';					
  $slot_summary .= date ( 'j M , g:i a', strtotime ( $datetime ) ).'- '.date ( 'h:i a', strtotime ( $datetime ) + 3600).', ';
}
					$slot_summary = substr($slot_summary, 0, -2);
					 
					//Send Sms notification
					$msg = "Booking Id:".$this->id.
							", Name:".$paymentData['User']['display_name'].
							", M:".$paymentData['User']['phone'].
							", Slots:".$slot_summary.
							", Court:".$paymentData['Ground']['name'].
							", Address:".$paymentData['Ground']['address_line_1'].', '.$paymentData['Ground']['address_line_2'].', '.$paymentData['Ground']['locality'].', '.$paymentData['Ground']['city'].
							", Map:".$paymentData['Ground']['google_maps'];
					
					$gmsg = "Booking Id:".$this->id.
					", Name:".$paymentData['User']['display_name'].
					", M:".$paymentData['User']['phone'].
					", Court:".$paymentData['Ground']['name'].
					", Slots:".$slot_summary;
					
					App::import('Component','Sms');
					$sms = new SmsComponent(new ComponentCollection);
					$sms->sendSms($paymentData['User']['phone'],$msg);
					
					try{
						//Mail Notification
						$Email = new CakeEmail();
						$Email->to($paymentData['User']['email']);
						$Email->from(array('booknplay@gmail.com' => 'Book N Play!'));
						$Email->subject('Booked successfully ! Booking Id:'.$id);
						//$Email->send($msg);
					} catch(Exception $ex) {
						echo $ex->getMessage();
						die();
					}
					
					//Mail to Gower on successful payment
                                        $numberArray = explode(',', $paymentData['Ground']['phone']);
                                        for ($i = 0; $i < count($numberArray); $i++) {
                                        $sms->sendSms($numberArray[$i],$gmsg);
                                        }
					$gower = $this->User->read(null,$paymentData['Ground']['user_id']);
					//$sms->sendSms($gower['User']['phone'],$gmsg);
					try{
						//Mail Notification
						$Email = new CakeEmail();
						$Email->to($gower['User']['email']);
						$Email->from(array('booknplay.in@gmail.com' => 'Book N Play!'));
						$Email->subject('Booked successfully ! Booking Id:'.$id);
						$Email->send($gmsg);
					} catch(Exception $ex) {
						echo $ex->getMessage();
						die();
					}
			}
	
			//Email to the admin
			/*$Email = new CakeEmail();
			$Email->to(Configure::read("Admin.emails"));
			$Email->from(array('booknplay.in@gmail.com' => 'Book N Play!'));
			$Email->subject("Hooo! We just had a sale");*/
			
						
			try {
			$Email = new CakeEmail();
			$Email->to(Configure::read("Admin.emails"));
			$Email->from(array('bookings@booknplay.in' => 'Book N Play!'));
			$Email->subject('Court:'.$paymentData['Ground']['name'].', Slots:'.$slot_summary);
			$Email->send($gmsg);
			} catch (Exception $e) {}
		}
		
		public function onFailedPayment($transactionAttemptId, $id, $feedback) {
			$this->id = $id;
			$this->saveField("status", "FAILED");
		
			//Email to buyer
			$paymentData = $this->read();
			if(!empty($paymentData['User']['email'])) {
			try{
					//Mail Notification
					$Email = new CakeEmail();
					$Email->to(isset($paymentData['User']['email'])?$paymentData['User']['email']:'booknplay@gmail.com');
					$Email->from(array('booknplay@gmail.com' => 'Book N Play!'));
					$Email->subject('Booking Fail!');
					$Email->send('Booking Failed! Booking Id:'.$id);
					} catch(Exception $ex) {
						//die('Mail Issue!');
					}
			}
		}
}