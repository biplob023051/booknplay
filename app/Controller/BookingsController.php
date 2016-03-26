<?php
App::uses ( 'AppController', 'Controller' );
/**
 * Bookings Controller
 *
 * @property Booking $Booking
 * @property BookedSlot $BookedSlot
 */
class BookingsController extends AppController {
	var $priv = array (
			'admin' => '*' ,
			'gowner' =>array('index','cancel','payment','process_book','payment_status','mark_paid'),
			'guest' => array('payment','process_book','payment_status'),
			'user' => array('my_books','cancel')
	);
	public $components = array('Paginator',"PaymentGateway.Payu");
	public $adminLayouts = "*";
	public function beforeFilter() {
		parent::beforeFilter ();
		$this->Security->unlockedActions [] = "payment";
		$this->Security->unlockedActions [] = "process_book";
	}
	/**
	 * index method
	 *
	 * @return void
	 */
	public function index($id=null) {
		
		$this->layout = 'admin';
		//Validation
		//Id exist
		if(empty($id)){
			$this->Session->setFlash ( __ ( 'Select Ground from the list' ) );
			$this->redirect ( array (
					'controller'=>'grounds',
					'action' => 'select_list'
			) );
		}

		// search form submitted
		if ($this->request->is('post')) {
			if (!empty($this->request->data['Search']['created']) ||
			    !empty($this->request->data['Search']['id']) || 
				(!empty($this->request->data['Search']['from_date']) && 
				!empty($this->request->data['Search']['to_date']))) {
				$this->redirect('/bookings/index/'. $id .'/booking_created:' . $this->request->data['Search']['created'] . '/booking_id:' . $this->request->data['Search']['id'] . '/from_date:' . $this->request->data['Search']['from_date'] . '/to_date:' . $this->request->data['Search']['to_date']);
			} else {
				$this->redirect(array('controller' => 'bookings', 'action' => 'index', $id));
			}
		}

		//Ground Exists
		$this->loadModel('Ground');
		if(!$this->Ground->exists($id)){
			$this->Session->setFlash ( __ ( 'Select valid Ground from the list' ) );
			$this->redirect ( array (
					'controller'=>'grounds',
					'action' => 'select_list'
			) );
		}
		//Checking for Gowner
		if($this->Auth->user ( "role" ) != 'admin'){
			if(!$this->Ground->isOwner($id,$this->Auth->user ( "id" ))){
				$this->Session->setFlash ( __ ( 'Invalid access!' ) );
				$this->redirect ( array (
						'controller'=>'grounds',
						'action' => 'select_list'
				) );
			}
		}
		//End of validation
		
		if($this->Ground->exists($id)){
			$conditions = array('Booking.ground_id'=>$id);
			if (!empty($this->request->named['booking_created'])) {
				$conditions['Booking.created LIKE'] = '%'.$this->request->named['booking_created'].'%';
				$this->request->data['Search']['created'] = $this->request->named['booking_created'];
			}
			if (!empty($this->request->named['booking_id'])) {
				$conditions['Booking.id'] = $this->request->named['booking_id'];
				$this->request->data['Search']['id'] = $this->request->named['booking_id'];
			}
			$this->Booking->virtualFields = ['Min_datetime' => 'SELECT MIN(datetime) FROM booked_slots as BookedSlot WHERE BookedSlot.booking_id = Booking.id'];

			$this->Booking->recursive = -1;
			$this->Booking->Behaviors->load('Containable');
			$this->Paginator->settings['conditions'] = $conditions;
			$this->Paginator->settings['order'] = array('id'=>'DESC');
			if (!empty($this->request->named['from_date']) && !empty($this->request->named['to_date'])) {
				$options = array(
			        'date(BookedSlot.datetime) BETWEEN ? AND ?' => array(
			        	$this->request->named['from_date'], 
			        	$this->request->named['to_date']
			        ) 
				);
				$this->request->data['Search']['from_date'] = $this->request->named['from_date'];
				$this->request->data['Search']['to_date'] = $this->request->named['to_date'];
			} else {
				$options = array();
			}
			$this->Paginator->settings['contain'] = array(
				'Ground', 
				'User', 
				'BookedSlot' => array(
					'conditions' => $options
				)
			);
			// pr($this->Paginator->paginate());
			// exit;
			$this->set ( 'bookings', $this->Paginator->paginate() );
			$this->set ( 'ground', $this->Ground->find('first',array('conditions'=>array('Ground.id'=>$id))));
		}
	}
	
	public function my_books()
	{
		if($this->Auth->user ( "role" ) == 'user'){
			$this->Booking->recursive = 1;
			$this->Paginator->settings['conditions'] = array('Booking.user_id'=>$this->Auth->user('id'));
			$this->Paginator->settings['order'] = array('id'=>'DESC');
			$this->set ( 'bookings', $this->Paginator->paginate() );
		}
		else
			$this->redirect ( $this->referer() );
	}
	/**
	 * view method
	 *
	 * @throws NotFoundException
	 * @param string $id        	
	 * @return void
	 */
	public function view($id = null) {
		if (! $this->Booking->exists ( $id )) {
			throw new NotFoundException ( __ ( 'Invalid booking' ) );
		}
		$options = array (
				'conditions' => array (
						'Booking.' . $this->Booking->primaryKey => $id 
				) 
		);
		$this->set ( 'booking', $this->Booking->find ( 'first', $options ) );
	}
	
	/**
	 * add method
	 *
	 * @return void
	 */
	public function add() {
		if ($this->request->is ( 'post' )) {
			$this->Booking->create ();
			if ($this->Booking->save ( $this->request->data )) {
				$this->Session->setFlash ( __ ( 'The booking has been saved' ) );
				$this->redirect ( array (
						'action' => 'index' 
				) );
			} else {
				$this->Session->setFlash ( __ ( 'The booking could not be saved. Please, try again.' ) );
			}
		}
		$grounds = $this->Booking->Ground->find ( 'list' );
		$users = $this->Booking->User->find ( 'list' );
		$this->set ( compact ( 'grounds', 'users' ) );
	}
	
	/**
	 * edit method
	 *
	 * @throws NotFoundException
	 * @param string $id        	
	 * @return void
	 */
	public function edit($id = null) {
		if (! $this->Booking->exists ( $id )) {
			throw new NotFoundException ( __ ( 'Invalid booking' ) );
		}
		if ($this->request->is ( 'post' ) || $this->request->is ( 'put' )) {
			if ($this->Booking->save ( $this->request->data )) {
				$this->Session->setFlash ( __ ( 'The booking has been saved' ) );
				$this->redirect ( array (
						'action' => 'index' 
				) );
			} else {
				$this->Session->setFlash ( __ ( 'The booking could not be saved. Please, try again.' ) );
			}
		} else {
			$options = array (
					'conditions' => array (
							'Booking.' . $this->Booking->primaryKey => $id 
					) 
			);
			$this->request->data = $this->Booking->find ( 'first', $options );
		}
		$grounds = $this->Booking->Ground->find ( 'list' );
		$this->set ( compact ( 'grounds' ) );
	}
	
	/**
	 * delete method
	 *
	 * @throws NotFoundException
	 * @param string $id        	
	 * @return void
	 */
	public function delete($id = null) {
		$this->Booking->id = $id;
		if (! $this->Booking->exists ()) {
			throw new NotFoundException ( __ ( 'Invalid booking' ) );
		}
		$this->request->onlyAllow ( 'post', 'delete' );
		if ($this->Booking->delete ()) {
			$this->Session->setFlash ( __ ( 'Booking deleted' ) );
			$this->redirect ( array (
					'action' => 'index' 
			) );
		}
		$this->Session->setFlash ( __ ( 'Booking was not deleted' ) );
		$this->redirect ( array (
				'action' => 'index' 
		) );
	}
	
	public function cancel($id = null) {
		$this->Booking->id = $id;
		if (! $this->Booking->exists ()) {
			throw new NotFoundException ( __ ( 'Invalid booking' ) );
		}
		
		//Checking for Gowner
		$this->loadModel('Ground');
		if($this->Auth->user ( "role" ) == 'gowner'){
			if(!$this->Ground->isOwner($this->Booking->field('ground_id'),$this->Auth->user ( "id" ))){
				$this->Session->setFlash ( __ ( 'Invalid access!' ) );
				$this->redirect ($this->referer());
			}
		}
		//Checking for User
		if($this->Auth->user ( "role" ) == 'user'){
			if($this->Booking->field('user_id') != $this->Auth->user ( "id" )){
				$this->Session->setFlash ( __ ( 'Invalid access!' ) );
				$this->redirect ($this->referer());
			}
		}
		
		$this->request->onlyAllow ( 'post', 'cancel' );
		if($this->Booking->field('status') != 'CANCELLED'){
			if ($this->Booking->saveField ('status','CANCELLED') && $this->Booking->saveField('BookedSlot.locked',0)) {
				$this->Booking->BookedSlot->updateAll(array('BookedSlot.locked'=>0),array('BookedSlot.booking_id'=>$this->Booking->id));
				$this->Session->setFlash ( __ ( 'Booking cancelled!' ) );
				$this->redirect ($this->referer());
			}
		}
		$this->Session->setFlash ( __ ( 'Booking was not deleted' ) );
		$this->redirect ($this->referer());
	}
	
	public function mark_paid($id = null) {
		$this->Booking->id = $id;
		if (! $this->Booking->exists ()) {
			throw new NotFoundException ( __ ( 'Invalid booking' ) );
		}
	
		//Checking for Gowner
		$this->loadModel('Ground');
		if($this->Auth->user ( "role" ) == 'gowner'){
			if(!$this->Ground->isOwner($this->Booking->field('ground_id'),$this->Auth->user ( "id" ))){
				$this->Session->setFlash ( __ ( 'Invalid access!' ) );
				$this->redirect ($this->referer());
			}
		}
	
		$this->request->onlyAllow ( 'post', 'mark_paid' );
		if($this->Booking->field('status') != 'CANCELLED'){
			if ($this->Booking->saveField ('status','SUCCESS')) {
				$this->Session->setFlash ( __ ( 'Booking marked as paid!' ) );
				$this->redirect ($this->referer());
			}
		}
		$this->Session->setFlash ( __ ( 'Booking was not deleted' ) );
		$this->redirect ($this->referer());
	}
	
	public function payment() {
		$this->layout = 'default';
		//pr($this->request->data);
		if ($this->request->is ( 'post' )){
			if(!isset($this->request->data['Booking']['selected_court']))
				$this->request->data['Booking']['selected_court'] = 1;
				
			$reqData = $this->request->data;
			
			//Validation
			if(empty($reqData['slots'])){
				$this->Session->setFlash ( __ ( 'No slots selected !' ) );
				$this->redirect ($this->referer());
			}
			
			//Process User
			$this->loadModel('User');
			if(isset($reqData['Booking']['user_id']) && $this->User->exists($reqData['Booking']['user_id']))
			{
				$user = $this->User->find("first",array("conditions"=>array('User.id'=>$reqData['Booking']['user_id'],'User.role !='=>'admin'),'recursive'=>-1));
				$this->set(compact('user'));
			} elseif ($this->Auth->user()) {
				$user['User'] = $this->Auth->user();
				$this->set(compact('user'));
			}
			
			//Process ground
			if(isset($reqData['Booking']['ground_id'])){
				$this->loadModel('Ground');
				$temp = $this->Ground->find('first',array('conditions'=>array('Ground.id'=>$reqData['Booking']['ground_id']),'recursive'=>0));
				//pr($reqData);
				if(!empty($temp)){
					$reqData['Ground'] = $temp['Ground'];
					$reqData['Type'] = $temp['Type'];
				}
				$reqData['Ground']['date'] = $this->request->data['Ground']['date'];
				unset($reqData['ground_id']);
			}
			
			//Process Slots
			if(!empty($reqData['slots'])){
				$reqData['processed_slots'] = array();
				foreach($reqData['slots'] as $slot){
					$i=0;
					while($i<$reqData['Booking']['selected_court']){
						$reqData['processed_slots'][] = date("F j g:i a",strtotime($this->Booking->BookedSlot->process_private_key_for_date($slot)));
						$i++;
					}
				}
			}
			
			// price calculation 
			$dynamic_price = array();
			$final_calc = array();
			if(!empty($this->request->data['Booking']['ground_id'])) { 
				$this->Ground->id = $this->request->data['Booking']['ground_id'];
				if (! $this->Ground->exists ()) {
					throw new NotFoundException ( __ ( 'Invalid ground' ) );
				}
				$slot_prices = $this->Ground->getPriceBasedOnGround ($this->request->data['Booking']['ground_id'], $this->request->data['Ground']['date']);
				
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
					$base = $base + ($g_price * $this->request->data['Booking']['selected_court']);
				}
			} else {
				$base = 0;
				if(!empty($this->request->data['slots'])) {
					foreach($this->request->data['slots'] as $slot_value) {
						$base = $base + (100 * $this->request->data['Booking']['selected_court']);
					}
				}
			}
			$total = $base;
			$final_calc['base'] = $base;
			$final_calc['total'] = $total;
			//pr($final_calc);
			
			$this->set(compact('reqData', 'final_calc'));
		}
		else{
			$this->Session->setFlash ( __ ( 'Invalid access !' ) );
			$this->redirect ($this->referer());
		}
	}
	public function process_book(){
		if ($this->request->is ( 'post' )){
			$reqData = $this->request->data;			
			
			//Validation
			if(empty($reqData['User']['email']) || empty($reqData['User']['display']) || empty($reqData['User']['age']) || empty($reqData['User']['phone'])){
				$this->Session->setFlash ( __ ( 'Invalid Data! Try again!' ) );
				$this->redirect ($this->referer());
			}
			
			//Setting Ground Id
			$reqData['BookedSlot']['ground_id'] = $reqData['ground_id'];
			unset($reqData['ground_id']);
			
			
			//Process User
			if(isset($reqData['User']['email'])){
				$this->loadModel('User');
				$data = $this->User->find ( "first", array (
						"conditions" => array (
								"User.email" => $this->request->data['User']['email']
						)
				,'recursive'=>-1) );
				
				$reqData['BookedSlot']['emai'] = $reqData['User']['email'];
				$reqData['BookedSlot']['phone'] = $reqData['User']['phone'];
				$reqData['BookedSlot']['age'] = $reqData['User']['age'];
				//$reqData['BookedSlot']['sex'] = $reqData['User']['sex'];
				if(empty($data)){
					//Prepare data for booking
					$saveData = array();
					$saveData['User']['display_name'] = $reqData['User']['display'];
					$saveData['User']['username'] = $reqData['User']['email'];
					$saveData['User']['email'] = $reqData['User']['email'];
					$saveData['User']['password'] = $reqData['User']['email'].substr(md5(microtime()),rand(0,26),5);
					$saveData['User']['phone'] = $reqData['User']['phone'];
					$saveData['User']['age'] = $reqData['User']['age'];
					$saveData['User']['role'] = 'guest';
					$saveData['User']['active'] = 1;
					if (!$this->User->save ( $saveData )) {
						$this->Session->setFlash ( __ ( 'Issue in Payment process. Code:uid!' ) );
						$this->redirect ($this->referer());
					}else {
						$reqData['BookedSlot']['user_id'] = $this->User->id;
					}
				}
				else
				{
					//Setting old id
					$reqData['BookedSlot']['user_id'] = $data['User']['id'];
				}
			}
			else{
				$this->Session->setFlash ( __ ( 'Issue in Payment process. Code:uid!' ) );
				$this->redirect ($this->referer());
			}
			
			//Unsetting waste data 
			unset($reqData['sex']);
			unset($reqData['submitted']);
			
			unset($reqData['ground_id']);
			//Redirect with session set
			$this->Session->write('BookData',$reqData);
			$this->redirect (array('controller'=>'BookedSlots','action' => 'book'));
		}
		else{
			$this->Session->setFlash ( __ ( 'Invalid access !' ) );
			$this->redirect ($this->referer());
		}
	}
	
	public function payment_status($status = 1,$bid = null){
		$this->layout = 'default';
		$this->set(compact('bid'));
		if($status == 1){
			$this->render('success');
		}
		if($status == 0){
			$this->render('failure');
		}
	}
}