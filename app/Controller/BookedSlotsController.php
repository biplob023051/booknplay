<?php
App::uses ( 'AppController', 'Controller' );
/**
 * BookedSlots Controller
 *
 * @property BookedSlot $BookedSlot
 */

class BookedSlotsController extends AppController {
	var $priv = array (
			'admin' => '*',
			'gowner' =>array('add_slots','book'),
			'guest' => array('book','cron_clear')
	);
	public $components = array('Paginator');
	public $adminLayouts = "*";
	/**
	 * index method
	 *
	 * @return void
	 */
	public function beforeFilter() {
		parent::beforeFilter ();
		$this->Security->unlockedActions [] = "book";
		$this->Security->unlockedActions [] = "add_slots";
	}
	public function index() {
		$this->BookedSlot->recursive = 0;
		$this->Paginator->settings['order'] = array('created'=>'DESC');
		$this->Paginator->settings['conditions'] = array('BookedSlot.created <='=>date('Y-m-d H:i:s', strtotime('-20 mins')));
		$this->set ( 'bookedSlots', $this->Paginator->paginate() );
	}
	
	/**
	 * view method
	 *
	 * @throws NotFoundException
	 * @param string $id        	
	 * @return void
	 */
	public function view($id = null) {
		if (! $this->BookedSlot->exists ( $id )) {
			throw new NotFoundException ( __ ( 'Invalid booked slot' ) );
		}
		$options = array (
				'conditions' => array (
						'BookedSlot.' . $this->BookedSlot->primaryKey => $id 
				) 
		);
		$this->set ( 'bookedSlot', $this->BookedSlot->find ( 'first', $options ) );
	}
	
	/**
	 * add method
	 *
	 * @return void
	 */
	public function add() {
		if ($this->request->is ( 'post' )) {
			$this->BookedSlot->create ();
			if ($this->BookedSlot->save ( $this->request->data )) {
				$this->Session->setFlash ( __ ( 'The booked slot has been saved' ) );
				$this->redirect ( array (
						'action' => 'index' 
				) );
			} else {
				$this->Session->setFlash ( __ ( 'The booked slot could not be saved. Please, try again.' ) );
			}
		}
		$bookings = $this->BookedSlot->Booking->find ( 'list' );
		$grounds = $this->BookedSlot->Ground->find ( 'list' );
		$this->set ( compact ( 'bookings', 'grounds' ) );
	}
	public function add_slots($gid = null,$start = null, $count = 1) {
		$this->layout = 'admin';
		$conditions = array();
		$this->loadModel ( 'Ground' );
		if ($gid != null && $this->Ground->exists ( $gid ) && $start != null) {
			//Checking for Gowner
			$this->loadModel('Ground');
			if($this->Auth->user ( "role" ) != 'admin'){
				if(!$this->Ground->isOwner($gid,$this->Auth->user ( "id" ))){
					$this->Session->setFlash ( __ ( 'Invalid access!' ) );
					$this->redirect ( $this->referer() );
				}
			}
			
			$start_date = date('Y-m-d',strtotime($start));
			$users = $this->BookedSlot->Booking->User->find ( 'list',array('conditions'=>array('User.role'=>'user')) );
			$this->set ( compact ( 'users', 'gid', 'slots','start_date', 'count' ) );
		} else {
			if($this->Auth->user ( "role" ) != 'admin')
				$conditions['Ground.user_id'] = $this->Auth->user(id);
			
			if ($gid != null)
				$conditions['Ground.id'] = $gid;
			
			$grounds = $this->BookedSlot->Ground->find ( 'list', array('conditions' => $conditions) );
			$show_ground = true;
			$this->set ( compact ( 'grounds', 'show_ground', 'count' ) );
		}
	}
	public function book() {
		if ($this->request->is ( 'post' ) || $this->Session->check('BookData')) {
			//Setting Session data as post data
			if($this->Session->check('BookData')){
				$this->request->data = $this->Session->read('BookData');
				$this->Session->delete('BookData');
			}
			
			$saveData = array ();
			$saveData ['Booking'] ['status'] = (isset($this->request->data['payment_method']) && ($this->request->data['payment_method'] == 'DIRECT'))?'PENDING':'INITIATED';
			$saveData ['Booking'] ['payment_method'] = (isset($this->request->data['payment_method']) && ($this->request->data['payment_method'] == 'PAYU'))?'PAYU':'DIRECT';
			$saveData ['Booking'] ['amount'] = 0;
			$saveData ['Booking'] ['initiator'] = (isset($this->request->data ['BookedSlot'] ['initiator']))?$this->request->data ['BookedSlot'] ['initiator']:'ADMIN';
			$saveData ['Booking'] ['ground_id'] = $this->request->data ['BookedSlot'] ['ground_id'];
			$saveData ['Booking'] ['user_id'] = isset($this->request->data ['BookedSlot'] ['user_id'])?$this->request->data ['BookedSlot'] ['user_id']:NULL;
			$saveData ['Booking'] ['name'] = isset($this->request->data ['BookedSlot'] ['email'])?$this->request->data ['BookedSlot'] ['email']:NULL;
			$saveData ['Booking'] ['phone'] = isset($this->request->data ['BookedSlot'] ['phone'])?$this->request->data ['BookedSlot'] ['phone']:NULL;
			$saveData ['Booking'] ['sex'] = isset($this->request->data ['BookedSlot'] ['sex'])?$this->request->data ['BookedSlot'] ['sex']:NULL;
			$saveData ['Booking'] ['age'] = isset($this->request->data ['BookedSlot'] ['age'])?$this->request->data ['BookedSlot'] ['age']:NULL;
			// Processing slots with ground and user
			$slot_summary = "";
			if (! empty ( $this->request->data ['slots'] )) {
				$temp = array ();
				$i = 0;
				foreach ( $this->request->data ['slots'] as $key => $datum ) {
					$datetime = $this->BookedSlot->process_private_key_for_date ( $key );
					if (! $datetime)
						continue;
		$slot_summary .= date ( 'j M , g:i a', strtotime ( $datetime ) ).'('.$this->request->data['selected_court'].'), ';
					$j=0;
					while($j<$this->request->data['selected_court']){
						$saveData ['BookedSlot'] [$i] ['datetime'] = date ( 'Y-m-d H:i:s', strtotime ( $datetime ) );
						$saveData ['BookedSlot'] [$i] ['locked'] = 1;
						$saveData ['BookedSlot'] [$i] ['ground_id'] = $this->request->data ['BookedSlot'] ['ground_id'];
						$saveData ['Booking'] ['amount'] += Configure::read( 'advance_rate');
						$saveData ['Booking'] ['amount'] += Configure::read( 'service_charge');
						$j++;
						$i++;
					}
					
				}
			}
			$slot_summary = substr($slot_summary, 0, -2);
			// End data processing
			
			// price calculation 
			$total = 0;
			$dynamic_price = array();
			$final_calc = array();
			if(!empty($saveData['Booking']['ground_id'])) { 
				$this->loadModel ( 'Ground' );
				$slot_prices = $this->Ground->getPriceBasedOnGround ($saveData['Booking']['ground_id'], $this->request->data['date']);
				
				if(!empty($slot_prices)) {
					$slot_prices = explode(',', $slot_prices['Schedule']['prices']);
					if(!empty($this->request->data['slots']) && !empty($slot_prices[0])) {
						foreach($this->request->data['slots'] as $avl_key => $avl_value) {
							$dynamic_price[(substr($avl_key, -2))] = $slot_prices[(substr($avl_key, -2)-1)];
						}
					}
				}
			}
			if(!empty($dynamic_price)) {
				$base = 0;
				foreach($dynamic_price as $g_price) {
					$base = $base + ($g_price * $this->request->data['selected_court']);
				}
			} else {
				$base = 0;
				if(!empty($this->request->data['slots'])) {
					foreach($this->request->data['slots'] as $slot_value) {
						$base = $base + (100 * $this->request->data['selected_court']);
					}
				}
			}
			$total = $base;
			$saveData ['Booking'] ['amount'] = $total;
			
			$this->loadModel ( 'Booking' );
			if ($this->Booking->saveAll ( $saveData )) {
				
				if($saveData ['Booking'] ['payment_method'] == 'PAYU')
					$this->redirect(array("plugin"=>"payment_gateway", "controller"=>"payment_gateway", "action"=>"forward", "payu", $this->Booking->id));
				
				if($saveData ['Booking'] ['payment_method'] == 'DIRECT'){
					$cdata = $this->BookedSlot->Ground->find('first',array('conditions'=>array('Ground.id'=>$this->request->data ['BookedSlot'] ['ground_id'])));
					//Send Sms notification for buyer
					if(isset($this->request->data['User'])){
						$msg = "Booking Id:".$this->Booking->id.
								", Name:".$this->request->data['User']['display'].
								", M:".$this->request->data['User']['phone'].
								", Slots:".$slot_summary.
								", Court:".$cdata['Ground']['name'].
								", Address:".$cdata['Ground']['address_line_1'].', '.$cdata['Ground']['address_line_2'].', '.$cdata['Ground']['locality'].', '.$cdata['Ground']['city'].
								", Map:".$cdata['Ground']['google_maps'];
						$this->Sms->sendSms($this->request->data['User']['phone'],nl2br($msg));
						
						try{
							//Mail Notification for buyer
							$Email = new CakeEmail();
							$Email->to(isset($this->request->data ['BookedSlot'] ['email'])?$this->request->data ['BookedSlot'] ['email']:'booknplay@gmail.com');
							$Email->from(array('booknplay@gmail.com' => 'Book N Play!'));
							$Email->subject('Booked successfully ! Booking Id:'.$this->Booking->id);
							//$Email->send($msg);
						} catch(Exception $ex) {
							echo $ex->getMessage();
							die();
						}
					}
					
					//Send Sms notification for gowner
					if(isset($cdata['User'])){
						$msg = "Booking Id:".$this->Booking->id.
						", Name:".$this->request->data['User']['display'].
						", M:".$this->request->data['User']['phone'].
						", Court:".$cdata['Ground']['name'].
						", Slots:".$slot_summary;          
                                                $numberArray = explode(',', $cdata['Ground']['phone']);
                                                for ($i = 0; $i < count($numberArray); $i++) {
                                                $this->Sms->sendSms($numberArray[$i],$msg);
                                                }

						/*$this->Sms->sendSms($cdata['User']['phone'],$msg);
                                                $numberArray = explode(',', $cdata['User']['phone']);
                                                foreach($mobileNum as $numberArray {
                                                  $this->Sms->sendSms($mobileNum,$msg);
                                                }*/
		
					
						try{
							//Mail Notification for gowner
							$Email = new CakeEmail();
							$Email->to(isset($cdata['User']['email'])?$cdata['User']['email']:'booknplay@gmail.com');
							$Email->from(array('booknplay@gmail.com' => 'Book N Play!'));
							$Email->subject('Booked successfully ! Booking Id:'.$this->Booking->id);
							$Email->send($msg);
						} catch(Exception $ex) {
							echo $ex->getMessage();
							die();
						}
					}
					
					$this->Session->setFlash ( __ ( 'Booking completed !' ) );
					$this->redirect (array('controller'=>'bookings','action'=>'payment_status',1,$this->Booking->id));
				}
			} else {
				$this->Session->setFlash ( __ ( 'The booked slot could not be saved. Please, try again.' ) );
			}
		}
		$this->Session->setFlash ( __ ( 'Invalid Access!' ) );
		$this->redirect ($this->referer());
	}
	
	/**
	 * edit method
	 *
	 * @throws NotFoundException
	 * @param string $id        	
	 * @return void
	 */
	public function edit($id = null) {
		if (! $this->BookedSlot->exists ( $id )) {
			throw new NotFoundException ( __ ( 'Invalid booked slot' ) );
		}
		if ($this->request->is ( 'post' ) || $this->request->is ( 'put' )) {
			if ($this->BookedSlot->save ( $this->request->data )) {
				$this->Session->setFlash ( __ ( 'The booked slot has been saved' ) );
				$this->redirect ( array (
						'action' => 'index' 
				) );
			} else {
				$this->Session->setFlash ( __ ( 'The booked slot could not be saved. Please, try again.' ) );
			}
		} else {
			$options = array (
					'conditions' => array (
							'BookedSlot.' . $this->BookedSlot->primaryKey => $id 
					) 
			);
			$this->request->data = $this->BookedSlot->find ( 'first', $options );
		}
		$bookings = $this->BookedSlot->Booking->find ( 'list' );
		$grounds = $this->BookedSlot->Ground->find ( 'list' );
		$this->set ( compact ( 'bookings', 'grounds' ) );
	}
	
	/**
	 * delete method
	 *
	 * @throws NotFoundException
	 * @param string $id        	
	 * @return void
	 */
	public function delete($id = null) {
		$this->BookedSlot->id = $id;
		if (! $this->BookedSlot->exists ()) {
			throw new NotFoundException ( __ ( 'Invalid booked slot' ) );
		}
		$this->request->onlyAllow ( 'post', 'delete' );
		if ($this->BookedSlot->delete ()) {
			$this->Session->setFlash ( __ ( 'Booked slot deleted' ) );
			$this->redirect ( array (
					'action' => 'index' 
			) );
		}
		$this->Session->setFlash ( __ ( 'Booked slot was not deleted' ) );
		$this->redirect ( array (
				'action' => 'index' 
		) );
	}
	public function  cron_clear(){
		$conditions = array('BookedSlot.created <='=>date('Y-m-d H:i:s', strtotime('-20 mins')),'locked'=>1);
		$data = $this->BookedSlot->find('all',array('conditions'=>$conditions,'recursive'=>0));
		if(!empty($data)){
			foreach($data as $datum){
				if($datum['Booking']['status'] != 'SUCCESS' && $datum['Booking']['status'] != 'PENDING'){
					$this->BookedSlot->id = $datum['BookedSlot']['id'];
					$this->BookedSlot->saveField('locked', 0);
				}
			}
		}
		die();
	}
}