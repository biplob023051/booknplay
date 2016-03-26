<?php
App::uses ( 'AppController', 'Controller' );

class ApiController extends AppController {
	
	
	 public $components = array('RequestHandler');
	/**
	 * Controller name
	 *
	 * @var string
	 */
	public $name = 'Api';
	var $priv = array ();
	
	/**
	 * This controller does not use a model
	 *
	 * @var array
	 */
	public $uses = array ();
	
	
	public function __construct($request, $response) {
		parent::__construct($request, $response);
		$this->api_resp = array(
			'status' => 200,
			'message' => 'Valid request',
			'data' => (object) array(),
		);
	}
	
	function beforeFilter() {
		parent::beforeFilter();
		if (isset($this->Security)) {
			$this->Security->csrfCheck = false;
			$this->Security->validatePost = false;
		}
	}
	
	/**
	 * Displays a view
	 *
	 * @param
	 *        	mixed What page to display
	 * @return void
	 */
	public function getSportTypes() {
		
		$this->loadModel ( 'Group' );
		$this->Group->unbindModel(array('hasMany' => array('Type')));
		$groups = $this->Group->find ( 'all', array('fields'=> array('id','type_group as sport_type'))) ;
		$groups = Set::extract('/Group/.', $groups);	
		$this->api_resp['data'] = $groups;
		$this->set(array(
			'api_resp' => $this->api_resp,			
			'_serialize' => 'api_resp'
		));
		
		
	}
	/**
	 * Displays a view
	 *
	 * @param
	 *        	mixed What page to display
	 * @return void
	 */
	public function getSportTypes1() {
		
		$this->loadModel ( 'Group' );
		$this->Group->unbindModel(array('hasMany' => array('Type')));
		$groups = $this->Group->find ( 'all', array('fields'=> array('id','type_group as sport_type'))) ;
		$groups = Set::extract('/Group/.', $groups);	
		$this->api_resp['data'] = $groups;
		$this->set(array(
			'api_resp' => $this->api_resp,			
			'_serialize' => 'api_resp'
		));
		
		
	}
	public function area_filter_list($group_id){
	  $this->loadModel ('Ground');
	  $ground = $this->Ground->find ( 'all', array (
		'fields' => array (
		  'DISTINCT(locality) as locality'
		),
		'conditions' => array (
		  'Type.group_id' => $group_id,
		  'Ground.active' => 1
		),
		'order' => array('locality ASC'),
		'recursive' => 0
	  ) );
	  $ground = Set::extract('/Ground/.', $ground); 
	  $this->api_resp['data'] = $ground;
	  $this->set(array(
	   'api_resp' => $this->api_resp,   
	   '_serialize' => 'api_resp'
	  ));
	  
	 }
	 
	 public function search() {
		 $this->loadModel ('Ground');
		 //pr($this->request->data);
		$start_date = null;
		$latitude = null;
		$longitude = null;
		if ($this->request->is ( 'post' )){
			if($this->request->data['date'] >= date('Y-m-d',time()) && $this->request->data['date'] <= date('Y-m-d',time()+ (13*24*60*60))) {
				//Setting Variable
				$reqData = $this->request->data;
				unset($this->request->data);
				$this->loadModel('Group');
				$groups = $this->Group->find ( 'list' );
				$req_gid = $reqData['group_id'];
				$reqData['req_gid'] = $reqData['group_id'];
				$temp_data = $this->Group->find('first',array('conditions'=>array('Group.id'=>$reqData['group_id']),'recurssive'=>-1));
				
				if(!empty($temp_data))
					$reqData['group_id'] = $temp_data['Group']['type_group']; 
				
				$this->layout = "default";
				$this->Ground->recursive = 0;
				
				if($this->Auth->user('role') == 'gowner'){
					$conditions['Ground.user_id'] = $this->Auth->user('id'); 
				}
				
				$conditions = array('Type.group_id'=>$req_gid,'Ground.locality'=>$reqData['area'],'Ground.active'=>1);
				if(!empty($reqData['latitude']) && !empty($reqData['longitude'])) { 
					$current_latitude = $reqData['latitude'];
					$current_longitude = $reqData['longitude'];
					
					$this->Ground->virtualFields = array('distance' => "( 3959 * acos( cos( radians($current_latitude) ) * cos( radians( Ground.latitude ) ) * cos( radians( Ground.longitude ) - radians($current_longitude) ) + sin( radians($current_latitude) ) * sin( radians( Ground.latitude ) ) ) )");
					
					$conditions['distance <'] = 100;
				}
				$grounds = $this->Ground->find ( 'all', array('conditions'=>$conditions,'recursive'=>0));
				if(!empty($grounds)){
					foreach($grounds as $k=>$ground){
						$grounds[$k]['Ground']['gallery'] = $this->FileUpload->getMedia('gallery',$ground['Ground']['id']);
					}
				}
					$ground_details = Set::extract('/Ground/.', $grounds); 
				  $this->api_resp['data'] = $ground_details;
				
			} else {
				$this->api_resp['status'] = 201;
			$this->api_resp['message'] = 'Invalid argumentsssss';
			}
		}
		else{
			$this->api_resp['status'] = 201;
			$this->api_resp['message'] = 'Invalid arguments';
		}
			  $this->set(array(
			   'api_resp' => $this->api_resp,   
			   '_serialize' => 'api_resp'
			   ));
	}
	
	public function booking_layout(){
		$this->loadModel ('Ground');
		//pr($this->request->data);
		if ($this->request->is ( 'post' )){
			$reqData = $this->request->data;
			$id = $reqData['id'];
			$start_date = $reqData['start_date'];
			$selected_court = $reqData['selected_court'];
			if (! $this->Ground->exists ()) {
				//$this->api_resp['message'] = 'Invalid ground';
			}
			$start_date_no = 0;
			$count_no = Configure::read('display_days');
			if($start_date!=null )
			{
				$now = time();
				$start = strtotime($start_date);
				$datediff = $start - $now;
				$diff_no = floor($datediff/(60*60*24));
 				if($diff_no < 15 && $diff_no > 0){
 					$start_date_no = $diff_no+1;
 					$count_no = Configure::read('display_days');
 				}
			}
			else
				$start_date = date('Y-m-d');
			
			$slots = $this->Ground->available_slots ( $id ,Configure::read('display_days'), $start_date_no, $selected_court);
			$ground_details = $this->Ground->find('first',array('conditions'=>array('Ground.id'=>$id),'recursive'=>-1));
			$ground_booking_details['ground_details'] = Set::extract('/Ground/.', $ground_details); 
			$ground_booking_details['slots'] = $slots; 
			$ground_booking_details['start_date_no'] =  $start_date_no; 
			$ground_booking_details['selected_court'] =  $selected_court;
			$ground_booking_details['count_no'] =  $count_no;
			$ground_booking_details['start_date'] =  $start_date;	
			  $this->api_resp['data'] = $ground_booking_details;
			  } else {
				$this->api_resp['status'] = 201;
				$this->api_resp['message'] = 'Invalid arguments';
			}
			  $this->set(array(
			   'api_resp' => $this->api_resp,   
			   '_serialize' => 'api_resp'
			  ));
	}
	
	public function book() {
		if ($this->request->is ( 'post' )){
			$reqData = $this->request->data;			
			
			//Validation
			if(empty($reqData['email']) || empty($reqData['display']) || empty($reqData['age']) || empty($reqData['phone'])){
				$this->api_resp['status'] = 201;
				$this->api_resp['message'] = 'Invalid Data! Try again!';
			}
			
			
			
			
			//Process User
			if(isset($reqData['email'])){
				$this->loadModel('User');
				$data = $this->User->find ( "first", array (
						"conditions" => array (
								"User.email" => $this->request->data['email']
						)
				,'recursive'=>-1) );
				
				
				if(empty($data)){
					//Prepare data for booking
					$saveData = array();
					$saveData['display_name'] = $reqData['display'];
					$saveData['username'] = $reqData['email'];
					$saveData['email'] = $reqData['email'];
					$saveData['password'] = $reqData['email'].substr(md5(microtime()),rand(0,26),5);
					$saveData['phone'] = $reqData['phone'];
					$saveData['age'] = $reqData['age'];
					$saveData['role'] = 'guest';
					$saveData['active'] = 1;
					if (!$this->User->save ( $saveData )) {
						$this->api_resp['status'] = 201;
						$this->api_resp['message'] = 'Issue in Payment process. Code:uid!';
					}else {
						$reqData['user_id'] = $this->User->id;
					}
				}
				else
				{
					//Setting old id
					$reqData['user_id'] = $data['User']['id'];
				}
			}
			else{
				$this->api_resp['status'] = 201;
				$this->api_resp['message'] = 'Issue in Payment process. Code:uid!';
			}
			
			//Unsetting waste data 
			//Redirect with session set
			$BookData = $reqData;
			if ($BookData) {
			//Setting Session data as post data
			
				$this->request->data = $BookData;
				unset($BookData);
			$saveData = array ();
			$saveData ['Booking'] ['status'] = (isset($this->request->data['payment_method']) && ($this->request->data['payment_method'] == 'DIRECT'))?'PENDING':'INITIATED';
			$saveData ['Booking'] ['payment_method'] = (isset($this->request->data['payment_method']) && ($this->request->data['payment_method'] == 'PAYU'))?'PAYU':'DIRECT';
			$saveData ['Booking'] ['amount'] = 0;
			$saveData ['Booking'] ['initiator'] = (isset($this->request->data ['initiator']))?$this->request->data ['BookedSlot'] ['initiator']:'ADMIN';
			$saveData ['Booking'] ['ground_id'] = $this->request->data ['ground_id'];
			$saveData ['Booking'] ['user_id'] = isset($this->request->data ['user_id'])?$this->request->data ['user_id']:NULL;
			$saveData ['Booking'] ['name'] = isset($this->request->data ['email'])?$this->request->data ['email']:NULL;
			$saveData ['Booking'] ['phone'] = isset($this->request->data ['phone'])?$this->request->data ['phone']:NULL;
			$saveData ['Booking'] ['sex'] = isset($this->request->data ['sex'])?$this->request->data ['sex']:NULL;
			$saveData ['Booking'] ['age'] = isset($this->request->data ['age'])?$this->request->data ['age']:NULL;
			// Processing slots with ground and user
			$slot_summary = "";
			$this->loadModel ( 'BookedSlot' );
			$slots = json_decode($this->request->data ['slots'],true);
			if (! empty ( $slots)) {
				$temp = array ();
				$i = 0;
				foreach ( $slots as $key => $datum ) {
					$datetime = $this->BookedSlot->process_private_key_for_date ( $key );
					if (! $datetime)
						continue;
					
					$slot_summary .= date ( 'Y-m-d H:i:s', strtotime ( $datetime ) ).'('.$this->request->data['selected_court'].'), ';
					$j=0;
					while($j<$this->request->data['selected_court']){
						$saveData ['BookedSlot'][$i] ['datetime'] = date ( 'Y-m-d H:i:s', strtotime ( $datetime ) );
						$saveData ['BookedSlot'][$i] ['locked'] = 1;
						$saveData ['BookedSlot'][$i] ['ground_id'] = $this->request->data ['ground_id'];
						$saveData ['Booking']['amount'] += Configure::read( 'advance_rate');
						$saveData ['Booking']['amount'] += Configure::read( 'service_charge');
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
			if(!empty($saveData['ground_id'])) { 
				$this->loadModel ( 'Ground' );
				$slot_prices = $this->Ground->getPriceBasedOnGround ($saveData['ground_id'], $this->request->data['date']);
				
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
					foreach($slots  as $slot_value) {
						$base = $base + (100 * $this->request->data['selected_court']);
					}
				}
			}
			$total = $base;
			$saveData ['Booking'] ['amount'] = $total;
			
			$this->loadModel ( 'Booking' );
			if ($this->Booking->saveAll ( $saveData )) {
				
				if($saveData ['Booking'] ['payment_method'] == 'PAYU'){
					
					//$this->redirect(array("plugin"=>"payment_gateway", "controller"=>"payment_gateway", "action"=>"forward", "payu", $this->Booking->id));
					$this->api_resp['status'] = 200;
					$this->api_resp['message'] = 'Booking completed !';
					$this->api_resp['booking_id'] =  $this->Booking->id;	
					$this->api_resp['data'] = $saveData;
				}
				if($saveData ['Booking'] ['payment_method'] == 'DIRECT'){
					$cdata = $this->BookedSlot->Ground->find('first',array('conditions'=>array('Ground.id'=>$this->request->data ['ground_id'])));
					//Send Sms notification for buyer
					if(isset($this->request->data['display'])){
						$msg = "Booking Id:".$this->Booking->id.
								", Name:".$this->request->data['display'].
								", M:".$this->request->data['phone'].
								", Date:".date('d-m-Y').
								", Slots:".$slot_summary.
								", Court:".$cdata['Ground']['name'].
								", Address:".$cdata['Ground']['address_line_1'].', '.$cdata['Ground']['address_line_2'].', '.$cdata['Ground']['locality'].', '.$cdata['Ground']['city'].
								", Map:".$cdata['Ground']['google_maps'];
						$this->Sms->sendSms($this->request->data['phone'],$msg);
						
						try{
							//Mail Notification for buyer
							$Email = new CakeEmail();
							$Email->to(isset($this->request->data ['email'])?$this->request->data['email']:'booknplay@gmail.com');
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
						", Date:".date('d-m-Y').
						", Slots:".$slot_summary;
						$this->Sms->sendSms($cdata['User']['phone'],$msg);
					
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
					
					$this->api_resp['status'] = 200;
					$this->api_resp['message'] = 'Booking completed !';
					//$this->redirect (array('controller'=>'bookings','action'=>'payment_status',1,$this->Booking->id));
				}
			} else {
				$this->api_resp['status'] = 201;
				$this->api_resp['message'] = 'The booked slot could not be saved. Please, try again.';
			}
		}
		else{
			$this->api_resp['status'] = 201;
			$this->api_resp['message'] = 'Invalid Access!';
		}
		}
		else{
			$this->api_resp['status'] = 201;
			$this->api_resp['message'] = 'Invalid access !';
		}
		$this->set(array(
		   'api_resp' => $this->api_resp,   
		   '_serialize' => 'api_resp'
		  ));
	}
	
	public function signup() {
		$this->layout = "default";
		if ($this->request->is ( 'post' )) {
			$this->loadModel('User');
			$check_user = $this->User->findByEmail(trim($this->request->data['email']));
			//pr($check_user);die;
			if(!empty($check_user) && $check_user['User']['role'] == 'guest') {
				$this->request->data['User']['id'] = $check_user['User']['id'];
				$this->request->data['User']['active'] = 1;
				$this->request->data['User']['password'] = $this->Auth->password($this->data['password']);
				$this->request->data['User']['role'] = 'user';
				$this->request->data['User']['email'] = trim($this->request->data['email']);
				$this->request->data['User']['username'] = $this->request->data['email'];
				if ($this->User->save ( $this->request->data )) {
					$this->api_resp['status'] = 200;
					$this->api_resp['message'] = 'The user has been saved !';
				} else {
					$this->api_resp['status'] = 201;
					$this->api_resp['message'] = 'The user could not be saved. Please, try again.';
				}
			} else if(empty($check_user)) { 
				$this->request->data['User'] = $this->request->data;
				$this->User->create ();
				$this->request->data['User']['active'] = 1;
				$this->request->data['User']['role'] = 'user';
				$this->request->data['User']['email'] = trim($this->request->data['email']);
				$this->request->data['User']['username'] = $this->request->data['email'];
				if ($this->User->save ( $this->request->data )) {
					$this->api_resp['status'] = 200;
					$this->api_resp['message'] = 'The user has been saved !';
				} else {
					$this->api_resp['status'] = 201;
					$this->api_resp['message'] = 'The user could not be saved. Please, try again.';
				}
			} else {
				$this->api_resp['status'] = 201;
				$this->api_resp['message'] = 'It seems your account already exists.';
			}
			
		} 
		else {
				$this->api_resp['status'] = 201;
				$this->api_resp['message'] = 'Invalid request';
			}
		$this->set(array(
		   'api_resp' => $this->api_resp,   
		   '_serialize' => 'api_resp'
		  ));
	}
	public function forget() {
		$this->loadModel ( 'User' );
		if ($this->request->is ( "post" )) {
			$this->User->recursive = - 1;
			$logins = $this->User->find ( 'first', array('conditions'=>array('User.email'=>$this->request->data ['email'] )));
			$this->User->id = $logins ['User'] ['id'];
			$data['User']['id'] = $this->User->id;
			$data['User']['password'] = substr(str_shuffle('abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789') , 0 , 10 );
			if ($this->User->save ( $data, true, array (
					"password"
			))) {
				$msg = "Hi,
						Use the following credentials to access your booknplay account.".
				", Username:".$this->request->data ['email'].
				", Password:".$data['User']['password'].".
						Please change your password once you log in";
					
				try{
					//Mail Notification
					$Email = new CakeEmail();
					$Email->to(trim($this->request->data ['email']));
					$Email->from(array('booknplay@gmail.com' => 'Book N Play!'));
					$Email->subject('BookNPlay - Reset password!');
					$Email->send($msg);
					$this->api_resp['status'] = 200;
					$this->api_resp['message'] = 'Kindly check your email for the temporary password to login to your account!';
				} catch(Exception $ex) {
					//echo $ex->getMessage();
					$this->api_resp['status'] = 201;
					$this->api_resp['message'] = $ex->getMessage();
					//die();
				}
				
			} else {
				$this->api_resp['status'] = 201;
				$this->api_resp['message'] = 'Could not change the password!!!';
				$this->request->data = array ();
			}
		}
		$this->set(array(
		   'api_resp' => $this->api_resp,   
		   '_serialize' => 'api_resp'
		  ));
	}
	public function my_books()
	{
		$this->loadModel ( 'Booking' );
		if($this->request->is ( "post" )){
			
			
			$ground = $this->Booking->find ( 'all', array (
		'fields' => array (
		  'Booking.*'
		),
				'conditions' => array (
				  'Booking.user_id' => $this->request->data ['id']
				),
				'order' => array('Booking.id DESC'),
				'recursive' => 1
			  ) );
			$ground = Set::extract('/Booking/.', $ground);
			$this->api_resp['status'] = 200;
			$this->api_resp['message'] = 'Your all bookings';
			$this->api_resp['data'] = $ground;
		}
		else
		{
			$this->api_resp['status'] = 201;
			$this->api_resp['message'] = 'Invalid Request!!!';
		}
		
		$this->set(array(
		   'api_resp' => $this->api_resp,   
		   '_serialize' => 'api_resp'
		  ));
	}
	public function booking_price_for_ground(){
		$this->loadModel ( 'Booking' );
		$this->loadModel ( 'Ground' );
		$this->request->data['Ground'] = json_decode($this->request->data ['Ground'],true);
		$this->request->data['Booking'] = json_decode($this->request->data ['Booking'],true);
		$this->request->data['slots'] = json_decode($this->request->data ['slots'],true);
		$dynamic_price = array();
		$final_calc = array();
		$this->layout = 'ajax';
		if(!empty($this->request->data['Booking']['ground_id'])) { 
			$this->Ground->id = $this->request->data['Booking']['ground_id'];
			if (! $this->Ground->exists ()) {
				$this->api_resp['status'] = 201;
				$this->api_resp['message'] = 'Invalid ground';
			} else{
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
		$service = ($this->request->data['s_number'] * Configure::read('service_charge'));
		$total = ($base + $service);
		$final_calc['base'] = $base;
		$final_calc['total'] = $total;
		$final_calc['service'] = $service;
		
		$this->api_resp['data'] = $final_calc;
		$this->api_resp['status'] = 200;
		$this->api_resp['message'] = 'Your all bookings';
		}
		$this->set(array(
		   'api_resp' => $this->api_resp,   
		   '_serialize' => 'api_resp'
		 ));
	}
	
}