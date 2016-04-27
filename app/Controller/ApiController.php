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

			// Biplob added condition if request payment
			if (empty($this->request->data['request_payment'])) {
				$saveData ['Booking'] ['status'] = (isset($this->request->data['payment_method']) && ($this->request->data['payment_method'] == 'DIRECT'))?'PENDING':'INITIATED';
			} else{
				$saveData ['Booking'] ['status'] = 'INITIATED';				
			}
			
			
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

			if (!empty($this->request->data['request_payment'])) {
				$saveData ['Booking'] ['amount'] = !empty($this->request->data['changed_amount']) ? $this->request->data['changed_amount'] : $total;
			}
			
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
						if (!empty($this->request->data['request_payment'])) { // Request payment button clicked

							$payment_link = 'http://' . $_SERVER['SERVER_NAME'] . $this->base . '/payment_gateway/pg/forward/payu/'.$this->Booking->id . '/1';
							$msg = "Your booking has been initiated with the following details " .
									"Booking Id:".$this->Booking->id.
									", Name:".$this->request->data['User']['display'].
									", Phone:".$this->request->data['User']['phone'].
									", Slots:".$slot_summary.
									", Court:".$cdata['Ground']['name'].
									". Please pay clicking on the following link to confirm your booking " . $payment_link .
									". Please note that the link expires in 20 mins";

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
						} else {
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
					}
					
					if (empty($this->request->data['request_payment'])) {
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
        
        
	public function sign_up() {
		$this->layout = "default";
		if ($this->request->is ( 'post' )) {
			$this->loadModel('User');
                        $data=$this->request->input('json_decode', true );
                        
                        
                        if($data["email"]==null){
                            $this->api_resp['status'] = 201;
			    $this->api_resp['message'] = 'Email Required';
                            
                        }else if($data["password"]==null){
                            $this->api_resp['status'] = 201;
			    $this->api_resp['message'] = 'Password Required';
                            
                        }
                        else if($data["active"]==null){
                            $this->api_resp['status'] = 201;
			    $this->api_resp['message'] = 'Active Required';
                        }
                        else if($data["email"]==""){
                            $this->api_resp['status'] = 201;
			    $this->api_resp['message'] = 'Email Should Not Empty';
                            
                        }else if($data["password"]==""){
                            $this->api_resp['status'] = 201;
			    $this->api_resp['message'] = 'Password Should Not Empty';
                            
                        }
                        else if($data["active"]==""){
                            $this->api_resp['status'] = 201;
			    $this->api_resp['message'] = 'Active Filed Should Not Empty';
                        }
                        else if(!($data["active"]==1 || $data["active"]==0)){
                            $this->api_resp['status'] = 201;
			    $this->api_resp['message'] = 'Invalid Active Field.Allowed Active Values (1,0)'.$data["active"];
                        }
                        else{
                        
			$check_user = $this->User->findByEmail(trim($data['email']));

			if(!empty($check_user) && $check_user['User']['role'] == 'guest') {
				$data["id"] = $check_user['User']['id'];
				$data["password"] = $this->Auth->password($data['password']);
                                $data['role'] = 'user';
				$data['username']=$data['email'];
				if ($this->User->save ($data)) {
					$this->api_resp['status'] = 200;
					$this->api_resp['message'] = 'The user has been saved !';
                                        $this->api_resp['data'] = $data["id"];
				} else {
					$this->api_resp['status'] = 201;
					$this->api_resp['message'] = 'The user could not be saved. Please, try again.';
				}
			}
                        else if(empty($check_user)) { 
				$this->User->create ();
                                $data['username']=$data['email'];
                                $data['role'] = 'user';
				if ($this->User->save ($data )) {
					$this->api_resp['status'] = 200;
					$this->api_resp['message'] = 'The user has been saved !';
            
                                       $this->api_resp['data'] = $this->User->id;
                                        
				} else {
					$this->api_resp['status'] = 201;
					$this->api_resp['message'] = 'The user could not be saved. Please, try again.';
				}
			} else {
				$this->api_resp['status'] = 201;
				$this->api_resp['message'] = 'It seems your account already exists.';
			}
                        }
                }
		else    {
				$this->api_resp['status'] = 201;
				$this->api_resp['message'] = 'Invalid request';
			}
                
		$this->set(array(
		   'api_resp' => $this->api_resp,   
		   '_serialize' => 'api_resp'
		  ));
                
	}
        public function sign_in() {
		$this->layout = "default";
		if ($this->request->is ( 'post' )) {
			$this->loadModel('User');
                        $data=$this->request->input('json_decode', true );
                        if($data["email"]==null){
                            $this->api_resp['status'] = 201;
			    $this->api_resp['message'] = 'Email Required';
                            
                        }else if($data["password"]==null){
                            $this->api_resp['status'] = 201;
			    $this->api_resp['message'] = 'Password Required';
                            
                        } else if($data["email"]==""){
                            $this->api_resp['status'] = 201;
			    $this->api_resp['message'] = 'Email Should Not Empty';
                            
                        }else if($data["password"]==""){
                            $this->api_resp['status'] = 201;
			    $this->api_resp['message'] = 'Password Should Not Empty';
                            
                        }
                        else{    
                            $check_user_name = $this->User->findByEmail(trim($data['email']));
                            if($check_user_name){
                                $o_pass=$this->Auth->password($data['password']);
                                $check_user_password = $this->User->findByEmailAndPassword(trim($data['email']),trim($o_pass));
                                if($check_user_password){
                                    $this->api_resp['status'] = 200;
                                    $this->api_resp['message'] = 'Login Success';
                                     $loggedInUserId = $check_user_password['User']['id'];
                                       $this->api_resp['data'] = $loggedInUserId;
                                }else{
                                    $this->api_resp['status'] = 201;
                                    $this->api_resp['message'] = 'Invalid Password';
                                }
                            }else{
                                $this->api_resp['status'] = 201;
                                $this->api_resp['message'] = 'Invalid E-Mail';
                            }
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
        public function change_password() {
		$this->layout = "default";
		if ($this->request->is ( 'post' )) {
			$this->loadModel('User');
                        $data=$this->request->input('json_decode', true );
                        if($data["id"]==null){
                            $this->api_resp['status'] = 201;
			    $this->api_resp['message'] = 'ID Required';
                            
                        }else if($data["old_password"]==null){
                            $this->api_resp['status'] = 201;
			    $this->api_resp['message'] = 'Old Password Required';
                            
                        }else if($data["new_password"]==null){
                            $this->api_resp['status'] = 201;
			    $this->api_resp['message'] = 'New Password Required';
                            
                        }
                        else if($data["confirm_password"]==null){
                            $this->api_resp['status'] = 201;
			    $this->api_resp['message'] = 'Confirm Password Required';
                            
                        } else if($data["id"]==""){
                            $this->api_resp['status'] = 201;
			    $this->api_resp['message'] = 'ID Should Not Empty';
                            
                        }
                        else if($data["old_password"]==""){
                            $this->api_resp['status'] = 201;
			    $this->api_resp['message'] = 'Old Password Should Not Empty';  
                        }
                        else if($data["new_password"]==""){
                            $this->api_resp['status'] = 201;
			    $this->api_resp['message'] = 'New Password Should Not Empty';  
                        }
                        else if($data["confirm_password"]==""){
                            $this->api_resp['status'] = 201;
			    $this->api_resp['message'] = 'Confirm Password Should Not Empty';  
                        }
                        else{
                            $o_pass = $this->Auth->password ($data["old_password"]);
                            $logins = $this->User->read ( null, $data["id"]);
                            if($logins){
                                if(!empty($logins) && $logins['User']['role'] != 'guest') {
                                $newPassword = $logins ['User'] ['password'];
                                if ($o_pass != $newPassword){
                                    $this->api_resp['status'] = 201;
                                    $this->api_resp['message'] = "Old Password Doesn't Match";
                                }else if ($data["new_password"] != $data["confirm_password"]){
                                    $this->api_resp['status'] = 201;
                                    $this->api_resp['message'] = "The Two Passwords Doesn't Match";
                                }else{
                                    
                                    $this->request->data ['User'] ['password'] = $data["confirm_password"];
                                    $this->request->data ['User'] ['id'] = $data["id"];
			$logins ['User'] ['id'] =$data["id"];
			$logins ['User'] ['password'] = $data["confirm_password"];
			$this->User->id = $data["id"];
                        
                        if ($this->User->save ( $this->request->data, true, array (
					"password" 
			) )) {
				$this->api_resp['status'] = 200;
                                $this->api_resp['message'] = 'Password Changed Successfully';
			} else {
				$this->api_resp['status'] = 201;
                                $this->api_resp['message'] = 'Could Not Change Password';
			}
                                } 
                                }else{
                                $this->api_resp['status'] = 201;
                                $this->api_resp['message'] ="Guest Users Cant Changes The Password Here.Please Use Fogot Password Option";
                            }
                                
                        }else{
                                $this->api_resp['status'] = 201;
                                $this->api_resp['message'] = 'User Not Found For Given User ID';
                            }
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
        public function my_bookings($userId)
	{
		$this->loadModel ( 'Booking' );
		if($this->request->is ( "post" )){
                
                    $this->loadModel('User');
                        $data=$this->request->input('json_decode', true );
                        
                    if($data["username"]==null){
                            $this->api_resp['status'] = 201;
			    $this->api_resp['message'] = 'UserName Required';
                            
                        }else if($data["password"]==null){
                            $this->api_resp['status'] = 201;
			    $this->api_resp['message'] = 'Password Required';
                            
                        }
                        else if($data["username"]==""){
                            $this->api_resp['status'] = 201;
			    $this->api_resp['message'] = 'UserName Should Not Empty';
                            
                        }else if($data["password"]==""){
                            $this->api_resp['status'] = 201;
			    $this->api_resp['message'] = 'Password Should Not Empty';
                            
                        }
                      
                        else{
                        
                        $logins = $this->User->find ( 'first', array('conditions'=>array('User.username'=>$data["username"])));
                        if(!empty($logins)) {
                         $newPassword = $logins ['User'] ['password'];
                         $o_pass = $this->Auth->password ($data["password"]);
                                if ($o_pass == $newPassword){
                        
                $ground = $this->Booking->find ( 'all', array (
		'fields' => array (
		  'Booking.*'
		),
				'conditions' => array (
				  'Booking.user_id' => $userId
				),
				'order' => array('Booking.id DESC'),
				'recursive' => 1
			  ) );
			$allBookings=[];
                foreach($ground as $eachGround) {
                                            array_push($allBookings,$eachGround);
                                                    }
			$this->api_resp['status'] = 200;
			$this->api_resp['message'] = 'Your all bookings';
			$this->api_resp['data'] = $allBookings;
		}else{
                    $this->api_resp['status'] = 201;
			$this->api_resp['message'] ="Un-Authorized User";
                }
                        }else{
                    $this->api_resp['status'] = 201;
			$this->api_resp['message'] ="Un-Authorized User";
                }
                }
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
         public function list_of_grounds($userId)
	{
             $this->loadModel ( 'Ground' );
             $this->loadModel ( 'User' );
             if($this->request->is ( "post" )){
                 
                    $data=$this->request->input('json_decode', true );
                        
                    if($data["username"]==null){
                            $this->api_resp['status'] = 201;
			    $this->api_resp['message'] = 'UserName Required';
                            
                        }else if($data["password"]==null){
                            $this->api_resp['status'] = 201;
			    $this->api_resp['message'] = 'Password Required';
                            
                        }
                        else if($data["username"]==""){
                            $this->api_resp['status'] = 201;
			    $this->api_resp['message'] = 'UserName Should Not Empty';
                            
                        }else if($data["password"]==""){
                            $this->api_resp['status'] = 201;
			    $this->api_resp['message'] = 'Password Should Not Empty';
                            
                        }
                        
                        else{
                        
                        $user_logins = $this->User->find ( 'first', array('conditions'=>array('User.username'=>$data["username"])));
                        if(!empty($user_logins)) {
                         $newPassword = $user_logins ['User'] ['password'];
                         
                                $o_pass = $this->Auth->password ($data["password"]);
                                if ($o_pass == $newPassword){
                        
                       	
                 $logins = $this->User->find ( 'first', array('conditions'=>array('User.id'=>$userId )));
			$userRole = $logins ['User'] ['role'];
                        
                        if($userRole == 'gowner'){
                        
                $ground = $this->Ground->find ( 'all', array (
		'fields' => array (
		  'Ground.*'
		),
				'conditions' => array (
				  'Ground.user_id' => $userId
				),
				'order' => array('Ground.id DESC'),
				'recursive' => 1
			  ) );
			$grounds = Set::extract('/Ground/.', $ground);
                
			$this->api_resp['status'] = 200;
			$this->api_resp['message'] = 'Your all Grounds';
			$this->api_resp['data'] = $grounds;
		}else{
                   	$this->api_resp['status'] = 201;
			$this->api_resp['message'] = 'User is not a Ground Owner';
		 
                }
                                }
                else{
                    $this->api_resp['status'] = 201;
			$this->api_resp['message'] ="Un-Authorized User";
                }
                        }else{
                    $this->api_resp['status'] = 201;
			$this->api_resp['message'] ="Un-Authorized User";
                }
                        }
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
        public function add_schedule() {
		$this->layout = 'admin';
		//Validation
		//Id exist
                
                if ($this->request->is ( 'post' )) {
			$this->loadModel('User');
                        $this->loadModel('Schedule');
                        
                        $data=$this->request->input('json_decode', true );
                        if($data["groundId"]==null){
                            $this->api_resp['status'] = 201;
			    $this->api_resp['message'] = 'groundId Required';
                            
                        }else if($data["groundId"]==""){
                            $this->api_resp['status'] = 201;
			    $this->api_resp['message'] = 'groundId should not be empty';
                            
                        }
                        else if($data["userId"]==null){
                            $this->api_resp['status'] = 201;
			    $this->api_resp['message'] = 'userId Required';
                            
                        }else if($data["userId"]==""){
                            $this->api_resp['status'] = 201;
			    $this->api_resp['message'] = 'userId should not be empty';
                            
                        }
                        else if($data["prices"]==null){
                            $this->api_resp['status'] = 201;
			    $this->api_resp['message'] = 'prices Required';
                            
                        }else if($data["prices"]==""){
                            $this->api_resp['status'] = 201;
			    $this->api_resp['message'] = 'prices should not be empty';
                            
                        }
                        else if($data["date"]==null){
                            $this->api_resp['status'] = 201;
			    $this->api_resp['message'] = 'date Required';
                            
                        }else if($data["date"]==""){
                            $this->api_resp['status'] = 201;
			    $this->api_resp['message'] = 'date should not be empty';
                            
                        } 
                         else if($data["slots"]==null){
                            $this->api_resp['status'] = 201;
			    $this->api_resp['message'] = 'slots Required';
                            
                        }else if($data["slots"]==""){
                            $this->api_resp['status'] = 201;
			    $this->api_resp['message'] = 'slots should not be empty';
                            
                        }
                        
                    else if($data["username"]==null){
                            $this->api_resp['status'] = 201;
			    $this->api_resp['message'] = 'UserName Required';
                            
                        }else if($data["password"]==null){
                            $this->api_resp['status'] = 201;
			    $this->api_resp['message'] = 'Password Required';
                            
                        }
                        else if($data["username"]==""){
                            $this->api_resp['status'] = 201;
			    $this->api_resp['message'] = 'UserName Should Not Empty';
                            
                        }else if($data["password"]==""){
                            $this->api_resp['status'] = 201;
			    $this->api_resp['message'] = 'Password Should Not Empty';
                            
                        }
                        
                        else{
                        
                        $user_logins = $this->User->find ( 'first', array('conditions'=>array('User.username'=>$data["username"])));
                        if(!empty($user_logins)) {
                         $newPassword = $user_logins ['User'] ['password'];
                                $o_pass = $this->Auth->password ($data["password"]);
                                if ($o_pass == $newPassword){
                        
                            
                            $this->loadModel('Ground');
                            if(!$this->Ground->exists($data["groundId"])){
                            $this->api_resp['status'] = 201;
                                $this->api_resp['message'] = 'Ground Not Found';
                            }else{
                                
                                
                                        $logins = $this->User->find ( 'first', array('conditions'=>array('User.id'=>$data["userId"] )));
                                $userRole = $logins ['User'] ['role'];

                                if($userRole != 'gowner'){
                                $this->api_resp['status'] = 201;
                                $this->api_resp['message'] = 'User Not Found';
                                }
                                else{
                                    
                                            $save_status = false;
                                            $prices = '';
                                            if(isset($data["prices"]) && is_array($data["prices"])) {
                                                    $prices = implode(',', $data["prices"]);
                                            }
                                            $this->request->data['Schedule']['prices'] =$prices;
                                            $slots = '';
                                            if(isset($data["slots"]) && is_array($data["slots"])) {
                                                    $slots = implode(',', $data["slots"]);
                                            }
                                            $this->request->data['Schedule']['slots'] =$slots;
                                            $this->request->data['Schedule']['ground_id'] = $data['groundId'];
                                            if(isset($data['date'])) {
                                                    foreach($data['date'] as $sch_date) {
                                                            $this->request->data['Schedule']['date'] = date('Y-m-d H:i:s',strtotime($sch_date));
                                                            $this->Schedule->create ();
                                                            if($this->Schedule->save ( $this->request->data )) {
                                                                    $save_status = true;
                                                            }
                                                    }
                                            }
                                            if ($save_status) {
                                                    $this->api_resp['status'] = 200;
                                                    $this->api_resp['message'] = 'The schedule has been saved';
                                                    $this->api_resp['data'] =  $this->Schedule->id;
                                      
                                                    
                                            } else {
                                                    $this->api_resp['status'] = 201;
                                                    $this->api_resp['message'] = 'The schedule could not be saved. May be issue with same date or past date selection.';
                                             }
                                }
                            }
                        }
                                
                else{
                    $this->api_resp['status'] = 201;
			$this->api_resp['message'] ="Un-Authorized User";
                }
                        }else{
                    $this->api_resp['status'] = 201;
			$this->api_resp['message'] ="Un-Authorized User";
                }
                        }
                }else
		{
			$this->api_resp['status'] = 201;
			$this->api_resp['message'] = 'Invalid Request!!!';
		}
                
                $this->set(array(
		   'api_resp' => $this->api_resp,   
		   '_serialize' => 'api_resp'
		  ));
                	}
                        
                        public function view_schedule($scheduleId)
	{
             
             if($this->request->is ( "post" )){
                 
                 $data=$this->request->input('json_decode', true );
                        
                    if($data["username"]==null){
                            $this->api_resp['status'] = 201;
			    $this->api_resp['message'] = 'UserName Required';
                            
                        }else if($data["password"]==null){
                            $this->api_resp['status'] = 201;
			    $this->api_resp['message'] = 'Password Required';
                            
                        }
                        else if($data["username"]==""){
                            $this->api_resp['status'] = 201;
			    $this->api_resp['message'] = 'UserName Should Not Empty';
                            
                        }else if($data["password"]==""){
                            $this->api_resp['status'] = 201;
			    $this->api_resp['message'] = 'Password Should Not Empty';
                            
                        }
                        
                        else{
                        $this->loadModel('User');
                        $user_logins = $this->User->find ( 'first', array('conditions'=>array('User.username'=>$data["username"])));
                        if(!empty($user_logins)) {
                         $newPassword = $user_logins ['User'] ['password'];
                               $o_pass = $this->Auth->password ($data["password"]);
                                if ($o_pass == $newPassword){
                    
                       	
                 $this->loadModel('Schedule');
                 if (! $this->Schedule->exists ( $scheduleId )) {
			$this->api_resp['status'] = 201;
			$this->api_resp['message'] = 'Invalid Schedule!!!';
		}
                else{
		$options = array (
				'conditions' => array (
						'Schedule.' . $this->Schedule->primaryKey => $scheduleId 
				) 
		);
		$schedule=$this->Schedule->find ( 'first', $options );
                
                  $this->loadModel('BookedSlot');
                    
                    $bookedSlots = $this->BookedSlot->find ( 'all', array (
		'fields' => array (
		  'BookedSlot.*'
		),
				'conditions' => array (
				  'BookedSlot.ground_id' => $schedule["Schedule"]["ground_id"]
				),
				'order' => array('BookedSlot.id DESC'),
				'recursive' => 1
			  ) );
                    
                    
            
                    $schedule["BookedSlots"]=$bookedSlots;
                     
			$this->api_resp['status'] = 200;
			$this->api_resp['message'] = 'Schedule';
			$this->api_resp['data'] = $schedule;
		
             }
             }
                                
                else{
                    $this->api_resp['status'] = 201;
			$this->api_resp['message'] ="Un-Authorized User";
                }
                        }else{
                    $this->api_resp['status'] = 201;
			$this->api_resp['message'] ="Un-Authorized User";
                }
                        }
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
	        
                        public function view_schedules($GroundId)
	{
             
             if($this->request->is ( "post" )){
                 
                     $data=$this->request->input('json_decode', true );
                        
                    if($data["username"]==null){
                            $this->api_resp['status'] = 201;
			    $this->api_resp['message'] = 'UserName Required';
                            
                        }else if($data["password"]==null){
                            $this->api_resp['status'] = 201;
			    $this->api_resp['message'] = 'Password Required';
                            
                        }
                        else if($data["username"]==""){
                            $this->api_resp['status'] = 201;
			    $this->api_resp['message'] = 'UserName Should Not Empty';
                            
                        }else if($data["password"]==""){
                            $this->api_resp['status'] = 201;
			    $this->api_resp['message'] = 'Password Should Not Empty';
                            
                        }
                        
                        else{
                        $this->loadModel('User');
                        $user_logins = $this->User->find ( 'first', array('conditions'=>array('User.username'=>$data["username"])));
                        if(!empty($user_logins)) {
                         $newPassword = $user_logins ['User'] ['password'];
                                $o_pass = $this->Auth->password ($data["password"]);
                                if ($o_pass == $newPassword){
             
                       	
                 $this->loadModel('Ground');
                 $this->loadModel('Schedule');
                 
                 if (! $this->Ground->exists ( $GroundId )) {
			$this->api_resp['status'] = 201;
			$this->api_resp['message'] = 'Invalid Ground!!!';
		}
                else{
                    
                     $todayDate=date ( 'Y-m-d H:i:s', strtotime("-1 days"));
                     $nextMonthDate=date('Y-m-d H:i:s', strtotime("+30 days"));
                              
                        $schedules = $this->Schedule->find ( 'all', array (
		'fields' => array (
		  'Schedule.*'
		),
				'conditions' => array (
				  'Ground.id' => $GroundId,
                                  'date(Schedule.date) BETWEEN ? AND ?'=>array($todayDate,$nextMonthDate),
				),
				'order' => array('Schedule.date ASC'),
				'recursive' => 1
			  ) );
                        
                         $this->loadModel('BookedSlot');
                         $allSchedules=[];
                    $allMostRecentBookings=[];
                    $allBookings = $this->BookedSlot->find ( 'all', array (
		'fields' => array (
		  'BookedSlot.*'
		),
				'conditions' => array (
				  'BookedSlot.ground_id' => $GroundId,
                                  'date(BookedSlot.datetime) >='=>$todayDate,
                                   
				),
				'order' => array('BookedSlot.datetime DESC'),
				'recursive' => 1
			  ) );
                                     
                                      $allDates=[];
                foreach($allBookings as $eachBooking) {          
                                array_push($allDates,  split(" ",$eachBooking["BookedSlot"]["datetime"])[0]);
                }
                $allUniqueDates=[];
                foreach($allDates as $eachDate){
                    $isExists=false;
                 foreach($allUniqueDates as $eachUniqueDate){
                    if($eachDate==$eachUniqueDate){
                        $isExists=true;
                    }
                }
                if(!$isExists){
                 array_push($allUniqueDates,$eachDate);
                }
                }   
                
                
             
                 foreach($allUniqueDates as $eachUniqueDate){
                     
                     $no = ltrim(substr ( $eachUniqueDate, 0, -1 ),'s');
		$hr = substr ( $eachUniqueDate, -1 );
               
                     $eachUniqueStartDate=date ( 'Y-m-d H:i:s', strtotime ( $eachUniqueDate." 00:00:00" ) );
                     $eachUniqueEndDate=date ( 'Y-m-d H:i:s', strtotime ( $eachUniqueDate." 23:59:59" ) );
                                     $eachDateBookings = $this->BookedSlot->find ( 'all', array (
		'fields' => array (
		  'BookedSlot.*'
		),
				'conditions' => array (
				  'date(BookedSlot.datetime) BETWEEN ? AND ?'=>array($eachUniqueStartDate,$eachUniqueEndDate),
                                  'BookedSlot.ground_id'=>$GroundId
				),
				'order' => array('BookedSlot.datetime DESC'),
				'recursive' => 1
			  ));
                     
                     $slots=[];
                     $slotsTime=[];
                   foreach($eachDateBookings as $eachDateBooking){
                        
                       $slotTime=split(" ",$eachDateBooking["BookedSlot"]["datetime"])[1];
                       $slotHour=(int)split(":",$slotTime)[0];
                       if($slotHour>12 && $slotHour!=0){
                           $slotTime=($slotHour-12).'-'.($slotHour-11).' PM';
                       }else if($slotHour<12 && $slotHour!=0){
                            $slotTime=$slotHour.'-'.($slotHour+1).' AM';
                       }else if($slotHour==12){
                           $slotTime=$slotHour.'-1 PM';
                       }else if($slotHour==0){
                           $slotTime='12-1 AM';
                       }
                                array_push($slots,$slotTime);
                       
                   }
                         
                                     $allMostRecentBookings[$eachUniqueDate]=$slots;
                                     
                 }
                                                    
			//$schedules = Set::extract('/Schedule/.', $schedule);
                     
                        $responseData["Schedules"]=$schedules;
                        $responseData["GroundBookedSlots"]=$allMostRecentBookings;
			$this->api_resp['status'] = 200;
			$this->api_resp['message'] = 'Ground Schedules';
			$this->api_resp['data'] = $responseData;
		
             }
             }
                                
                else{
                    $this->api_resp['status'] = 201;
			$this->api_resp['message'] ="Un-Authorized User";
                }
                        }else{
                    $this->api_resp['status'] = 201;
			$this->api_resp['message'] ="Un-Authorized User";
                }
                        }
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
	
        public function edit_schedule($scheduleId) {
		$this->layout = 'admin';
		//Validation
		//Id exist
                
                if ($this->request->is ( 'put' )) {
			$this->loadModel('User');
                        $this->loadModel('Schedule');
                        
                        if (! $this->Schedule->exists ( $scheduleId )) {
			$this->api_resp['status'] = 201;
			$this->api_resp['message'] = 'Invalid Schedule!!!';
		}
                else{
              
                        $data=$this->request->input('json_decode', true );
                        if($data["groundId"]==null){
                            $this->api_resp['status'] = 201;
			    $this->api_resp['message'] = 'groundId Required';
                            
                        }else if($data["groundId"]==""){
                            $this->api_resp['status'] = 201;
			    $this->api_resp['message'] = 'groundId should not be empty';
                            
                        }
                        else if($data["userId"]==null){
                            $this->api_resp['status'] = 201;
			    $this->api_resp['message'] = 'userId Required';
                            
                        }else if($data["userId"]==""){
                            $this->api_resp['status'] = 201;
			    $this->api_resp['message'] = 'userId should not be empty';
                            
                        }
                        else if($data["prices"]==null){
                            $this->api_resp['status'] = 201;
			    $this->api_resp['message'] = 'prices Required';
                            
                        }else if($data["prices"]==""){
                            $this->api_resp['status'] = 201;
			    $this->api_resp['message'] = 'prices should not be empty';
                            
                        }
                        else if($data["date"]==null){
                            $this->api_resp['status'] = 201;
			    $this->api_resp['message'] = 'date Required';
                            
                        }else if($data["date"]==""){
                            $this->api_resp['status'] = 201;
			    $this->api_resp['message'] = 'date should not be empty';
                            
                        } 
                         else if($data["slots"]==null){
                            $this->api_resp['status'] = 201;
			    $this->api_resp['message'] = 'slots Required';
                            
                        }else if($data["slots"]==""){
                            $this->api_resp['status'] = 201;
			    $this->api_resp['message'] = 'slots should not be empty';
                            
                        }
                        
                    else if($data["username"]==null){
                            $this->api_resp['status'] = 201;
			    $this->api_resp['message'] = 'UserName Required';
                            
                        }else if($data["password"]==null){
                            $this->api_resp['status'] = 201;
			    $this->api_resp['message'] = 'Password Required';
                            
                        }
                        else if($data["username"]==""){
                            $this->api_resp['status'] = 201;
			    $this->api_resp['message'] = 'UserName Should Not Empty';
                            
                        }else if($data["password"]==""){
                            $this->api_resp['status'] = 201;
			    $this->api_resp['message'] = 'Password Should Not Empty';
                            
                        }
                        
                        else{
                        
                        $user_logins = $this->User->find ( 'first', array('conditions'=>array('User.username'=>$data["username"])));
                        if(!empty($user_logins)) {
                         $newPassword = $user_logins ['User'] ['password'];
                                $o_pass = $this->Auth->password ($data["password"]);
                                if ($o_pass == $newPassword){
                        
                            
                            $this->loadModel('Ground');
                            if(!$this->Ground->exists($data["groundId"])){
                            $this->api_resp['status'] = 201;
                                $this->api_resp['message'] = 'Ground Not Found';
                            }else{
                                
                                
                                        $logins = $this->User->find ( 'first', array('conditions'=>array('User.id'=>$data["userId"] )));
                                $userRole = $logins ['User'] ['role'];

                                if($userRole != 'gowner'){
                                $this->api_resp['status'] = 201;
                                $this->api_resp['message'] = 'User Not Found';
                                }
                                else{
                                    
                                            $save_status = false;
                                            $prices = '';
                                            if(isset($data["prices"]) && is_array($data["prices"])) {
                                                    $prices = implode(',', $data["prices"]);
                                            }
                                            $this->request->data['Schedule']['prices'] =$prices;
                                            $slots = '';
                                            if(isset($data["slots"]) && is_array($data["slots"])) {
                                                    $slots = implode(',', $data["slots"]);
                                            }
                                            $this->request->data['Schedule']['slots'] =$slots;
                                            $this->request->data['Schedule']['id'] =$scheduleId;
                                            $this->request->data['Schedule']['ground_id'] = $data['groundId'];
                                            if(isset($data['date'])) {
                                                    foreach($data['date'] as $sch_date) {
                                                            $this->request->data['Schedule']['date'] = date('Y-m-d H:i:s',strtotime($sch_date));
                                                            if($this->Schedule->save ( $this->request->data )) {
                                                                    $save_status = true;
                                                            }
                                                    }
                                            }
                                            if ($save_status) {
                                                    $this->api_resp['status'] = 200;
                                                    $this->api_resp['message'] = 'The schedule has been saved';
                                                    $this->api_resp['data'] =  $this->Schedule->id;
                                      
                                                    
                                            } else {
                                                    $this->api_resp['status'] = 201;
                                                    $this->api_resp['message'] = 'The schedule could not be saved. May be issue with same date or past date selection.';
                                             }
                                }
                            }
                        }
                                
                else{
                    $this->api_resp['status'] = 201;
			$this->api_resp['message'] ="Un-Authorized User";
                }
                        }else{
                    $this->api_resp['status'] = 201;
			$this->api_resp['message'] ="Un-Authorized User";
                }
                        
                }
                
                }
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
        
                        
           public function delete_schedule($scheduleId)
	{
             
             if($this->request->is ( "delete" )){
                 
                     $data=$this->request->input('json_decode', true );
                        
                    if($data["username"]==null){
                            $this->api_resp['status'] = 201;
			    $this->api_resp['message'] = 'UserName Required';
                            
                        }else if($data["password"]==null){
                            $this->api_resp['status'] = 201;
			    $this->api_resp['message'] = 'Password Required';
                            
                        }
                        else if($data["username"]==""){
                            $this->api_resp['status'] = 201;
			    $this->api_resp['message'] = 'UserName Should Not Empty';
                            
                        }else if($data["password"]==""){
                            $this->api_resp['status'] = 201;
			    $this->api_resp['message'] = 'Password Should Not Empty';
                            
                        }
                        
                        else{
                        $this->loadModel('User');
                        
                        $user_logins = $this->User->find ( 'first', array('conditions'=>array('User.username'=>$data["username"])));
                        if(!empty($user_logins)) {
                         $newPassword = $user_logins ['User'] ['password'];
                                $o_pass = $this->Auth->password ($data["password"]);
                                if ($o_pass == $newPassword){
                    
            
                 $this->loadModel('Schedule');
                 if (! $this->Schedule->exists ( $scheduleId )) {
			$this->api_resp['status'] = 201;
			$this->api_resp['message'] = 'Invalid Schedule!!!';
		}
                else{
                    $this->Schedule->id = $scheduleId;
                    if ($this->Schedule->delete ()) {
			$this->api_resp['status'] = 200;
			$this->api_resp['message'] = 'Schedule Deleted';	
		}
                else{
                    $this->api_resp['status'] = 201;
			$this->api_resp['message'] = 'Schedule Not Deleted';
		
                }	
             }
              }
                                
                else{
                    $this->api_resp['status'] = 201;
			$this->api_resp['message'] ="Un-Authorized User";
                }
                        }else{
                    $this->api_resp['status'] = 201;
			$this->api_resp['message'] ="Un-Authorized User";
                }
                        }
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
        
        public function forgot_password() {
		$this->loadModel ( 'User' );
		if ($this->request->is ( "post" )) {
                    
                     $data=$this->request->input('json_decode', true );
                        
                    if($data["email"]==null){
                            $this->api_resp['status'] = 201;
			    $this->api_resp['message'] = 'Email Required';
                            
                        }
                        else if($data["email"]==""){
                            $this->api_resp['status'] = 201;
			    $this->api_resp['message'] = 'Email Should Not Empty';
                            
                        }
                        else{
			$this->User->recursive = - 1;
			$logins = $this->User->find ('first', array('conditions'=>array('User.email'=>$data["email"])));
                        if(!empty($logins)) {
			$this->User->id = $logins ['User'] ['id'];
			$data['User']['id'] = $this->User->id;
			$data['User']['password'] = substr(str_shuffle('abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789') , 0 , 10 );
			if ($this->User->save ( $data, true, array (
					"password"
			))) {
				$msg = "Hi,
						Use the following credentials to access your booknplay account.".
				", Username:".$data["email"].
				", Password:".$data['User']['password'].".
						Please change your password once you log in";
					
				try{
					//Mail Notification
					$Email = new CakeEmail();
					$Email->to(trim($data["email"]));
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
                        else{
                            $this->api_resp['status'] = 201;
					$this->api_resp['message'] = "User Not Found For The Given Email";
                        }
		}
                }else
		{
			$this->api_resp['status'] = 201;
			$this->api_resp['message'] = 'Invalid Request!!!';
		}
		$this->set(array(
		   'api_resp' => $this->api_resp,   
		   '_serialize' => 'api_resp'
		  ));
	}
        
        
        public function new_booking() {
            
		if ($this->request->is ( 'post' )) {
                                  
                         $data=$this->request->input('json_decode', true );
                        if($data["payment_method"]==null){
                            $this->api_resp['status'] = 201;
			    $this->api_resp['message'] = 'Payment Method Required';
                            
                        }else if($data["payment_method"]==""){
                            $this->api_resp['status'] = 201;
			    $this->api_resp['message'] = 'Payment Method should not be empty';
                            
                        }
                        else if($data["BookedSlot"]["slots"]==null){
                            $this->api_resp['status'] = 201;
			    $this->api_resp['message'] = 'slots Required';
                            
                        }else if($data["BookedSlot"]["slots"]==""){
                            $this->api_resp['status'] = 201;
			    $this->api_resp['message'] = 'slots should not be empty';
                            
                        }
                        else if($data["selected_court"]==null){
                            $this->api_resp['status'] = 201;
			    $this->api_resp['message'] = 'Selected Court Required';
                            
                        }else if($data["selected_court"]==""){
                            $this->api_resp['status'] = 201;
			    $this->api_resp['message'] = 'Selected Court should not be empty';
                            
                        }
                        else if($data["initiator"]==null){
                            $this->api_resp['status'] = 201;
			    $this->api_resp['message'] = 'BookedSlot Initiator Required';
                            
                        }else if($data["initiator"]==""){
                            $this->api_resp['status'] = 201;
			    $this->api_resp['message'] = 'BookedSlot Initiator should not be empty';
                            
                        }
                          else if($data["ground_id"]==null){
                            $this->api_resp['status'] = 201;
			    $this->api_resp['message'] = 'BookedSlot Ground Id Required';
                            
                        }else if($data["ground_id"]==""){
                            $this->api_resp['status'] = 201;
			    $this->api_resp['message'] = 'BookedSlot Ground Id should not be empty';
                            
                        }
                        
                        
                    else if($data["username"]==null){
                            $this->api_resp['status'] = 201;
			    $this->api_resp['message'] = 'UserName Required';
                            
                        }else if($data["password"]==null){
                            $this->api_resp['status'] = 201;
			    $this->api_resp['message'] = 'Password Required';
                            
                        }
                        else if($data["username"]==""){
                            $this->api_resp['status'] = 201;
			    $this->api_resp['message'] = 'UserName Should Not Empty';
                            
                        }else if($data["password"]==""){
                            $this->api_resp['status'] = 201;
			    $this->api_resp['message'] = 'Password Should Not Empty';
                            
                        }
                        
                        else{
                        
                    $this->loadModel('User');
                        
                        $user_logins = $this->User->find ( 'first', array('conditions'=>array('User.username'=>$data["username"])));
                        if(!empty($user_logins)) {
                         $newPassword = $user_logins ['User'] ['password'];
                                $o_pass = $this->Auth->password ($data["password"]);
                                if ($o_pass == $newPassword){
                        
                            
     
			$saveData = array ();
			$saveData ['Booking'] ['status'] = (isset($this->request->data['payment_method']) && ($this->request->data['payment_method'] == 'DIRECT'))?'PENDING':'INITIATED';
			$saveData ['Booking'] ['payment_method'] = (isset($this->request->data['payment_method']) && ($this->request->data['payment_method'] == 'PAYU'))?'PAYU':'DIRECT';
			$saveData ['Booking'] ['amount'] = 0;
			$saveData ['Booking'] ['initiator'] = (isset($this->request->data['initiator']))?$this->request->data['initiator']:'ADMIN';
			$saveData ['Booking'] ['ground_id'] = $this->request->data['ground_id'];
			$saveData ['Booking'] ['name'] = isset($this->request->data ['User'] ['display'])?$this->request->data['User']['display']:NULL;
			$saveData ['Booking'] ['phone'] = isset($this->request->data ['User'] ['phone'])?$this->request->data ['User'] ['phone']:NULL;
			$saveData ['Booking'] ['sex'] = isset($this->request->data ['User'] ['sex'])?$this->request->data ['User'] ['sex']:NULL;
			$saveData ['Booking'] ['age'] = isset($this->request->data ['User'] ['age'])?$this->request->data ['User'] ['age']:NULL;
			                            
                        $this->loadModel ( 'BookedSlot' );
                        // Processing slots with ground and user
			$slot_summary = "";
			if (! empty ( $this->request->data ["BookedSlot"]['slots'] )) {
				$temp = array ();
				$i = 0;
				foreach ( $this->request->data ["BookedSlot"]['slots'] as $key => $datum ) {
					$datetime = $datum ;
					if (! $datetime)
						continue;
		$slot_summary .= date ( 'j M , g:i a', strtotime ( $datetime ) ).'('.$this->request->data['selected_court'].'), ';
					$j=0;
					while($j<$this->request->data['selected_court']){
						$saveData ['BookedSlot'] [$i] ['datetime'] = date ( 'Y-m-d H:i:s', strtotime ( $datetime ) );
						$saveData ['BookedSlot'] [$i] ['locked'] = 1;
						$saveData ['BookedSlot'] [$i] ['ground_id'] = $this->request->data['ground_id'];
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
                        $dynamic_price1 = array();
                        $dynamic_price2 = array();
			$final_calc = array();
			if(!empty($saveData['Booking']['ground_id'])) { 
				$this->loadModel ( 'Ground' );
				$slot_prices = $this->Ground->getPriceBasedOnGround ($saveData['Booking']['ground_id'], $this->request->data['date']);
				$dynamic_price2=$slot_prices;
				if(!empty($slot_prices)) {
					$slot_prices = explode(',', $slot_prices['Schedule']['prices']);
					if(!empty($this->request->data["BookedSlot"]['slots']) && !empty($slot_prices[0])) {
						foreach($this->request->data["BookedSlot"]['slots'] as $avl_key => $avl_value) {
                                                    $dynamic_price1[$avl_key]=$avl_value;
                                                    
                                                    $slotTime=split(" ",$avl_value)[1];
                       $slotHour=(int)split(":",$slotTime)[0];
                       
                       
                       
							$dynamic_price[$slotHour] = $slot_prices[$slotHour];
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
				if(!empty($this->request->data["BookedSlot"]['slots'])) {
					foreach($this->request->data["BookedSlot"]['slots'] as $slot_value) {
						$base = $base + (100 * $this->request->data['selected_court']);
					}
				}
			}
			$total = $base;
			$saveData ['Booking'] ['amount'] = $total;
			
			$this->loadModel ( 'Booking' );
                        
                        
                        
                        $check_user = $this->User->findByEmail(trim($this->request->data ['User'] ['email']));
			//pr($check_user);die;
			if(!empty($check_user)) {
				$saveData ['Booking'] ['user_id'] = $check_user['User']['id'];
			} else if(empty($check_user)) { 
				$userData['User']=[];
				$this->User->create ();
				$userData['User']['active'] = 1;
				$userData['User']['role'] = 'user';
				$userData['User']['email'] = trim($this->request->data ['User'] ['email']);
				$userData['User']['username'] = $this->request->data ['User'] ['email'];
                                $userData['User']['display_name'] = $this->request->data['User']['display'];
                                $userData['User']['phone'] = $this->request->data['User']['phone'];
                                $userData['User']['password'] = substr(str_shuffle('abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789') , 0 , 10 );
				if ($this->User->save ( $userData )) {
					$saveData ['Booking'] ['user_id'] = $this->User->id;
				} else {
					
				}
			}
                        
                        
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
							$Email->to(isset($this->request->data ['User'] ['email'])?$this->request->data ['User'] ['email']:'booknplay@gmail.com');
							$Email->from(array('booknplay@gmail.com' => 'Book N Play!'));
							$Email->subject('Booked successfully ! Booking Id:'.$this->Booking->id);
							//$Email->send($msg);
						} catch(Exception $ex) {
							
                                                         $this->api_resp['status'] = 201;
			$this->api_resp['message'] = $ex->getMessage();
							//die();
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
							
                                                        $this->api_resp['status'] = 201;
			$this->api_resp['message'] = $ex->getMessage();
                        
							//die();
						}
					}
                                        
                                     
					$this->api_resp['status'] = 200;
			$this->api_resp['message'] = 'Booking completed !';
                        $this->api_resp['data'] = $this->Booking->id;
                        
				}
			} else {
				$this->api_resp['status'] = 201;
			$this->api_resp['message'] = 'The booked slot could not be saved. Please, try again.';
                        
                        }
		
                }else{
                    $this->api_resp['status'] = 201;
			$this->api_resp['message'] ="Un-Authorized User";
                }
                        }else{
                    $this->api_resp['status'] = 201;
			$this->api_resp['message'] ="Un-Authorized User";
                }
                        }
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
                public function view_bookings($groundId)
	{
		$this->loadModel ( 'Booking' );
		if($this->request->is ( "post" )){
                
                    $this->loadModel('User');
                        $data=$this->request->input('json_decode', true );
                        
                    if($data["username"]==null){
                            $this->api_resp['status'] = 201;
			    $this->api_resp['message'] = 'UserName Required';
                            
                        }else if($data["password"]==null){
                            $this->api_resp['status'] = 201;
			    $this->api_resp['message'] = 'Password Required';
                            
                        }
                        else if($data["username"]==""){
                            $this->api_resp['status'] = 201;
			    $this->api_resp['message'] = 'UserName Should Not Empty';
                            
                        }else if($data["password"]==""){
                            $this->api_resp['status'] = 201;
			    $this->api_resp['message'] = 'Password Should Not Empty';
                            
                        }
                      
                        else{
                        
                        $logins = $this->User->find ( 'first', array('conditions'=>array('User.username'=>$data["username"])));
                        if(!empty($logins)) {
                         $newPassword = $logins ['User'] ['password'];
                         $o_pass = $this->Auth->password ($data["password"]);
                                if ($o_pass == $newPassword){
                        
                $grounds = $this->Booking->find ( 'all', array (
		'fields' => array (
		  'Booking.*'
		),
				'conditions' => array (
				  'Booking.ground_id' => $groundId
				),
				'order' => array('Booking.id DESC'),
				'recursive' => 1
			  ) );
                $allBookings=[];
                foreach($grounds as $eachGround) {
                                            array_push($allBookings,$eachGround);
                                                    }
			$this->api_resp['status'] = 200;
			$this->api_resp['message'] = "Ground Bookings";
			$this->api_resp['data'] = $allBookings;
		}else{
                    $this->api_resp['status'] = 201;
			$this->api_resp['message'] ="Un-Authorized User";
                }
                        }else{
                    $this->api_resp['status'] = 201;
			$this->api_resp['message'] ="Un-Authorized User";
                }
                }
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
        
        
        public function list_of_all_grounds()
	{
             $this->loadModel ( 'Ground' );
             $this->loadModel ( 'User' );
             if($this->request->is ( "post" )){
                 
                    $data=$this->request->input('json_decode', true );
                        
                    if($data["username"]==null){
                            $this->api_resp['status'] = 201;
			    $this->api_resp['message'] = 'UserName Required';
                            
                        }else if($data["password"]==null){
                            $this->api_resp['status'] = 201;
			    $this->api_resp['message'] = 'Password Required';
                            
                        }
                        else if($data["username"]==""){
                            $this->api_resp['status'] = 201;
			    $this->api_resp['message'] = 'UserName Should Not Empty';
                            
                        }else if($data["password"]==""){
                            $this->api_resp['status'] = 201;
			    $this->api_resp['message'] = 'Password Should Not Empty';
                            
                        }
                        else{
                        $user_logins = $this->User->find ( 'first', array('conditions'=>array('User.username'=>$data["username"])));
                        if(!empty($user_logins)) {
                         $newPassword = $user_logins ['User'] ['password'];
                         
                                $o_pass = $this->Auth->password ($data["password"]);
                                if ($o_pass == $newPassword){
                        
                       
                $ground = $this->Ground->find ( 'all', array (
		'fields' => array (
		  'Ground.*'
		),
				'order' => array('Ground.id DESC'),
				'recursive' => 1
			  ) );
			$grounds = Set::extract('/Ground/.', $ground);
                
			$this->api_resp['status'] = 200;
			$this->api_resp['message'] = 'All Grounds';
			$this->api_resp['data'] = $grounds;
                                }
                else{
                    $this->api_resp['status'] = 201;
			$this->api_resp['message'] ="Un-Authorized User";
                }
                        }else{
                    $this->api_resp['status'] = 201;
			$this->api_resp['message'] ="Un-Authorized User";
                }
                        }
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
        
        public function view_most_recent_bookings($groundId)
	{
		$this->loadModel ( 'Booking' );
                $this->loadModel ( 'BookedSlot' );
                
		
		if($this->request->is ( "post" )){
                
                    $this->loadModel('User');
                        $data=$this->request->input('json_decode', true );
                        
                    if($data["username"]==null){
                            $this->api_resp['status'] = 201;
			    $this->api_resp['message'] = 'UserName Required';
                            
                        }else if($data["password"]==null){
                            $this->api_resp['status'] = 201;
			    $this->api_resp['message'] = 'Password Required';
                            
                        }
                        else if($data["username"]==""){
                            $this->api_resp['status'] = 201;
			    $this->api_resp['message'] = 'UserName Should Not Empty';
                            
                        }else if($data["password"]==""){
                            $this->api_resp['status'] = 201;
			    $this->api_resp['message'] = 'Password Should Not Empty';
                            
                        }
                      
                        else{
                        
                        $logins = $this->User->find ( 'first', array('conditions'=>array('User.username'=>$data["username"])));
                        if(!empty($logins)) {
                         $newPassword = $logins ['User'] ['password'];
                         $o_pass = $this->Auth->password ($data["password"]);
                                if ($o_pass == $newPassword){
                        
               
                                     $allBookings = $this->Booking->find ( 'all', array (
		'fields' => array (
		  'Booking.*'
		),
				'conditions' => array (
				  'Booking.ground_id' => $groundId
				),
				'order' => array('Booking.created DESC'),
				'recursive' => 1
			  ) );
                $allDates=[];
                foreach($allBookings as $eachBooking) {          
                                array_push($allDates,  split(" ",$eachBooking["Booking"]["created"])[0]);
                }
                $allUniqueDates=[];
                foreach($allDates as $eachDate){
                    $isExists=false;
                 foreach($allUniqueDates as $eachUniqueDate){
                    if($eachDate==$eachUniqueDate){
                        $isExists=true;
                    }
                }
                if(!$isExists){
                 array_push($allUniqueDates,$eachDate);
                }
                }                                    
                
                
                $allMostRecentBookings=[];
                 foreach($allUniqueDates as $eachUniqueDate){
                     
                     $no = ltrim(substr ( $eachUniqueDate, 0, -1 ),'s');
		$hr = substr ( $eachUniqueDate, -1 );
               
                     $eachUniqueStartDate=date ( 'Y-m-d H:i:s', strtotime ( $eachUniqueDate." 00:00:00" ) );
                     $eachUniqueEndDate=date ( 'Y-m-d H:i:s', strtotime ( $eachUniqueDate." 23:59:59" ) );
                                     $eachDateBookings = $this->Booking->find ( 'all', array (
		'fields' => array (
		  'Booking.*'
		),
				'conditions' => array (
				  'date(Booking.created) BETWEEN ? AND ?'=>array($eachUniqueStartDate,$eachUniqueEndDate),
                                  'ground_id'=>$groundId
				),
				'group' => array('Booking.user_id'),
				'order' => array('Booking.created DESC'),
				'recursive' => 1
			  ));
                     $userBookings=[];
                     foreach($eachDateBookings as $eachDateBooking){
                         $userData["user_id"]=$eachDateBooking["Booking"]["user_id"];
                         $userName="";
                         $phoneNo="";
                         $noOfSlots="";
                         
                        
                         $userName=$eachDateBooking["Booking"]["name"];
                         $phoneNo=$eachDateBooking["Booking"]["phone"];
                        
                        $userData["user_name"]=$userName;
                        $userData["phone_no"]=$phoneNo;
                     
                   $eachDateUserBookings = $this->Booking->find ( 'all', array (
		'fields' => array (
		  'Booking.*'
		),
				'conditions' => array (
				  'date(Booking.created) BETWEEN ? AND ?'=>array($eachUniqueStartDate,$eachUniqueEndDate),
                                  'ground_id'=>$groundId,   
                                  'Booking.user_id'=>$userData["user_id"]
				),
                       'order' => array('Booking.id DESC'),
				'recursive' => 1
			  ));
                   
                   foreach($eachDateUserBookings as $eachDateUserBooking){
                       $noOfSlots=$noOfSlots+count($eachDateUserBooking["BookedSlot"]);
                   }
                   $userData["no_of_slots_booked"]=$noOfSlots;
                        $userData["Bookings"]=$eachDateUserBookings;
                         array_push($userBookings, $userData);
                    }
                     
                     $eachDateBookingUsers["date"]=$eachUniqueDate;
                     $eachDateBookingUsers["userBookings"]=$userBookings;
                     array_push($allMostRecentBookings,$eachDateBookingUsers);
                 }
                 
                
                 
                        $this->api_resp['status'] = 200;
			$this->api_resp['message'] = "Most Recent Bookings";
			$this->api_resp['data'] = $allMostRecentBookings;
		}else{
                    $this->api_resp['status'] = 201;
			$this->api_resp['message'] ="Un-Authorized User";
                }
                        }else{
                    $this->api_resp['status'] = 201;
			$this->api_resp['message'] ="Un-Authorized User";
                }
                }
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
        
        
        public function view_upcoming_bookings($groundId)
	{
		$this->loadModel ( 'Booking' );
                $this->loadModel ( 'BookedSlot' );
                $this->loadModel ( 'User' );
		
		if($this->request->is ( "post" )){
                
                    $this->loadModel('User');
                        $data=$this->request->input('json_decode', true );
                        
                    if($data["username"]==null){
                            $this->api_resp['status'] = 201;
			    $this->api_resp['message'] = 'UserName Required';
                            
                        }else if($data["password"]==null){
                            $this->api_resp['status'] = 201;
			    $this->api_resp['message'] = 'Password Required';
                            
                        }
                        else if($data["username"]==""){
                            $this->api_resp['status'] = 201;
			    $this->api_resp['message'] = 'UserName Should Not Empty';
                            
                        }else if($data["password"]==""){
                            $this->api_resp['status'] = 201;
			    $this->api_resp['message'] = 'Password Should Not Empty';
                            
                        }
                      
                        else{
                        
                        $logins = $this->User->find ( 'first', array('conditions'=>array('User.username'=>$data["username"])));
                        if(!empty($logins)) {
                         $newPassword = $logins ['User'] ['password'];
                         $o_pass = $this->Auth->password ($data["password"]);
                                if ($o_pass == $newPassword){
                        
                 $todayDate=date ( 'Y-m-d H:i:s');
                 
                                     $allBookings = $this->BookedSlot->find ( 'all', array (
		'fields' => array (
		  'BookedSlot.*'
		),
				'conditions' => array (
				  'BookedSlot.ground_id' => $groundId,
                                  'date(BookedSlot.datetime) >='=>$todayDate,
                                   
				),
				'order' => array('BookedSlot.datetime DESC'),
				'recursive' => 1
			  ) );
                                     
                                      $allDates=[];
                foreach($allBookings as $eachBooking) {          
                                array_push($allDates,  split(" ",$eachBooking["BookedSlot"]["datetime"])[0]);
                }
                $allUniqueDates=[];
                foreach($allDates as $eachDate){
                    $isExists=false;
                 foreach($allUniqueDates as $eachUniqueDate){
                    if($eachDate==$eachUniqueDate){
                        $isExists=true;
                    }
                }
                if(!$isExists){
                 array_push($allUniqueDates,$eachDate);
                }
                }   
                
                
             
                
                $allMostRecentBookings=[];
                 foreach($allUniqueDates as $eachUniqueDate){
                     
                     $no = ltrim(substr ( $eachUniqueDate, 0, -1 ),'s');
		$hr = substr ( $eachUniqueDate, -1 );
               
                     $eachUniqueStartDate=date ( 'Y-m-d H:i:s', strtotime ( $eachUniqueDate." 00:00:00" ) );
                     $eachUniqueEndDate=date ( 'Y-m-d H:i:s', strtotime ( $eachUniqueDate." 23:59:59" ) );
                                     $eachDateBookings = $this->BookedSlot->find ( 'all', array (
		'fields' => array (
		  'BookedSlot.*'
		),
				'conditions' => array (
				  'date(BookedSlot.datetime) BETWEEN ? AND ?'=>array($eachUniqueStartDate,$eachUniqueEndDate),
                                  'BookedSlot.ground_id'=>$groundId
				),
				'order' => array('BookedSlot.datetime DESC'),
				'recursive' => 1
			  ));
                     
                                      $bookingIdsArr=[];
                                      $bookingIds=0;
                                     foreach($eachDateBookings as $eachDateBooking){
                                         array_push($bookingIdsArr,$eachDateBooking["BookedSlot"]["booking_id"] );
                                     }
                                     $bookingIds=implode ( ',', $bookingIdsArr );
                                     
                                     
                                     $eachDateBookings1 = $this->Booking->find ( 'all', array (
		'fields' => array (
		  'Booking.*'
		),
				'conditions' => array (
				  'Booking.id'=>$bookingIdsArr,
                                  'ground_id'=>$groundId
				),
				'group' => array('Booking.user_id'),
				'order' => array('Booking.created DESC'),
				'recursive' => 1
			  ));
                                     
                                     
                     $userBookings=[];
                     foreach($eachDateBookings1 as $eachDateBooking){
                         $userData["user_id"]=$eachDateBooking["Booking"]["user_id"];
                         $userName="";
                         $phoneNo="";
                         $noOfSlots="";
                             
                         $userName=$eachDateBooking["Booking"]["name"];
                         $phoneNo=$eachDateBooking["Booking"]["phone"];
                        
                        $userData["user_name"]=$userName;
                        $userData["phone_no"]=$phoneNo;
                     
                   $eachDateUserBookings = $this->Booking->find ( 'all', array (
		'fields' => array (
		  'Booking.*'
		),
				'conditions' => array (
				   'Booking.id'=>$bookingIdsArr,
                                  'ground_id'=>$groundId,   
                                  'Booking.user_id'=>$userData["user_id"]
				),
                       'order' => array('Booking.id DESC'),
				'recursive' => 1
			  ));
                   
                      $slots=[];
                     $slotsTime=[];
                   foreach($eachDateUserBookings as $eachDateUserBooking){
                        $noOfSlots=$noOfSlots+count($eachDateUserBooking["BookedSlot"]);
                        foreach($eachDateUserBooking["BookedSlot"] as $eachSlot){
                       $slotTime=split(" ",$eachSlot["datetime"])[1];
                       $slotHour=(int)split(":",$slotTime)[0];
                       if($slotHour>12 && $slotHour!=0){
                           $slotTime=($slotHour-12).'-'.($slotHour-11).' PM';
                       }else if($slotHour<12 && $slotHour!=0){
                            $slotTime=$slotHour.'-'.($slotHour+1).' AM';
                       }else if($slotHour==12){
                           $slotTime=$slotHour.'-1 PM';
                       }else if($slotHour==0){
                           $slotTime='12-1 AM';
                       }
                                array_push($slots,$slotTime);
                        }
                       
                   }
                         
                                                       
                   $userData["no_of_slots_booked"]=$noOfSlots;
                   
                   $userData["slots"]=$slots;
                        $userData["Bookings"]=$eachDateUserBookings;
                         array_push($userBookings, $userData);
                    }
                     //                $allMostRecentBookings[$eachUniqueDate]=$userBookings;
                                     
                                     
                     $eachDateBookingUsers["date"]=$eachUniqueDate;
                     $eachDateBookingUsers["userBookings"]=$userBookings;
                     array_push($allMostRecentBookings,$eachDateBookingUsers);
                                     
                 }
                        $this->api_resp['status'] = 200;
			$this->api_resp['message'] = "Upcoming Bookings";
			$this->api_resp['data'] = $allMostRecentBookings;
		}else{
                    $this->api_resp['status'] = 201;
			$this->api_resp['message'] ="Un-Authorized User";
                }
                        }else{
                    $this->api_resp['status'] = 201;
			$this->api_resp['message'] ="Un-Authorized User";
                }
                }
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
public function mark_paid_booking($bookingId) {
		
		if($this->request->is ( "post" )){
                    $this->loadModel('User');
                        $data=$this->request->input('json_decode', true );
                        
                    if($data["username"]==null){
                            $this->api_resp['status'] = 201;
			    $this->api_resp['message'] = 'UserName Required';
                            
                        }else if($data["password"]==null){
                            $this->api_resp['status'] = 201;
			    $this->api_resp['message'] = 'Password Required';
                            
                        }
                        else if($data["username"]==""){
                            $this->api_resp['status'] = 201;
			    $this->api_resp['message'] = 'UserName Should Not Empty';
                            
                        }else if($data["password"]==""){
                            $this->api_resp['status'] = 201;
			    $this->api_resp['message'] = 'Password Should Not Empty';
                            
                        }
                      
                        else{
                        
                        $logins = $this->User->find ( 'first', array('conditions'=>array('User.username'=>$data["username"])));
                        if(!empty($logins)) {
                         $newPassword = $logins ['User'] ['password'];
                         $o_pass = $this->Auth->password ($data["password"]);
                                if ($o_pass == $newPassword){
                                $this->loadModel('Booking');
                                    $this->Booking->id = $bookingId;
		if (! $this->Booking->exists ()) {
			$this->api_resp['status'] = 201;
			$this->api_resp['message'] ="Invalid Booking";
		}else{
                    
                    $this->loadModel('Ground');
		if($logins ['User']['role'] == 'gowner'){
                    
                    if(!$this->Ground->isOwner($this->Booking->field('ground_id'),$logins ['User']['id'])){
				 $this->api_resp['status'] = 201;
			$this->api_resp['message'] ="Invalid Access.You are not the ground owner.You cant mark this as paid1";
                    
			}else{
		
                   $this->request->onlyAllow ( 'post', 'mark_paid' );
		if($this->Booking->field('status') != 'CANCELLED'){
			if ($this->Booking->saveField ('status','SUCCESS')) {
				$this->api_resp['status'] = 200;
			$this->api_resp['message'] ="Booking marked as paid!";
			}
		}else{
                    $this->api_resp['status'] = 201;
			$this->api_resp['message'] ="Booking already cancelled.You cant mark this as paid";
                }
                        }
                }else{
                $this->api_resp['status'] = 201;
			$this->api_resp['message'] ="Invalid Access.You are not the ground owner.You cant mark this as paid";
                    
                }
                }
                 
                }else{
                    $this->api_resp['status'] = 201;
			$this->api_resp['message'] ="Un-Authorized User";
                }
                        }else{
                    $this->api_resp['status'] = 201;
			$this->api_resp['message'] ="Un-Authorized User";
                        }
                        }
                        } else
		{
			$this->api_resp['status'] = 201;
			$this->api_resp['message'] = 'Invalid Request!!!';
		}
		
		$this->set(array(
		   'api_resp' => $this->api_resp,   
		   '_serialize' => 'api_resp'
		  ));
	}
        
public function mark_cancel_booking($bookingId) {
		
		if($this->request->is ( "post" )){
                    $this->loadModel('User');
                        $data=$this->request->input('json_decode', true );
                        
                    if($data["username"]==null){
                            $this->api_resp['status'] = 201;
			    $this->api_resp['message'] = 'UserName Required';
                            
                        }else if($data["password"]==null){
                            $this->api_resp['status'] = 201;
			    $this->api_resp['message'] = 'Password Required';
                            
                        }
                        else if($data["username"]==""){
                            $this->api_resp['status'] = 201;
			    $this->api_resp['message'] = 'UserName Should Not Empty';
                            
                        }else if($data["password"]==""){
                            $this->api_resp['status'] = 201;
			    $this->api_resp['message'] = 'Password Should Not Empty';
                            
                        }
                      
                        else{
                        
                        $logins = $this->User->find ( 'first', array('conditions'=>array('User.username'=>$data["username"])));
                        if(!empty($logins)) {
                         $newPassword = $logins ['User'] ['password'];
                         $o_pass = $this->Auth->password ($data["password"]);
                                if ($o_pass == $newPassword){
                                $this->loadModel('Booking');
                                    $this->Booking->id = $bookingId;
		if (! $this->Booking->exists ()) {
			$this->api_resp['status'] = 201;
			$this->api_resp['message'] ="Invalid Booking";
		}else{
                    
                    $this->loadModel('Ground');
		if($logins ['User']['role'] == 'gowner'){
                    
                    if(!$this->Ground->isOwner($this->Booking->field('ground_id'),$logins ['User']['id'])){
				 $this->api_resp['status'] = 201;
			$this->api_resp['message'] ="Invalid Access.You are not the ground owner.You cant mark this as paid1";
                    
			}else{
		
                   $this->request->onlyAllow ( 'post', 'cancel' );
		if($this->Booking->field('status') != 'CANCELLED'){
			if ($this->Booking->saveField ('status','CANCELLED') && $this->Booking->saveField('BookedSlot.locked',0)) {
				$this->Booking->BookedSlot->updateAll(array('BookedSlot.locked'=>0),array('BookedSlot.booking_id'=>$this->Booking->id));
				$this->api_resp['status'] = 200;
			$this->api_resp['message'] ="Booking marked as cannelled!";
			}
		}else{
                    $this->api_resp['status'] = 201;
			$this->api_resp['message'] ="Booking already cancelled.You cant mark this as paid";
                }
                        }
                }else{
                $this->api_resp['status'] = 201;
			$this->api_resp['message'] ="Invalid Access.You are not the ground owner.You cant mark this as paid";
                    
                }
                }
                 
                }else{
                    $this->api_resp['status'] = 201;
			$this->api_resp['message'] ="Un-Authorized User";
                }
                        }else{
                    $this->api_resp['status'] = 201;
			$this->api_resp['message'] ="Un-Authorized User";
                        }
                        }
                        } else
		{
			$this->api_resp['status'] = 201;
			$this->api_resp['message'] = 'Invalid Request!!!';
		}
		
		$this->set(array(
		   'api_resp' => $this->api_resp,   
		   '_serialize' => 'api_resp'
		  ));
	}
}
