<?php
App::uses ( 'AppController', 'Controller' );
/**
 * Grounds Controller
 *
 * @property Ground $Ground
 */
class GroundsController extends AppController {
	var $priv = array (
			'admin' => '*',
			'gowner' =>array('index','select_list'),
			'guest' => array('search','booking_layout','area_filter','area_filter_list','booking_price_for_ground', 'short_distance_area', 'short_distance_area_list') 
	);
	public $components = array('Paginator');
	public $adminLayouts = "*";
	public function beforeFilter() {
		parent::beforeFilter ();
		$this->Security->unlockedActions [] = "search";
		$this->Security->unlockedActions [] = "booking_price_for_ground";
		
	}
	/**
	 * index method
	 *
	 * @return void
	 */
	public function index() {
		$this->Ground->recursive = 0;
		if($this->Auth->user('role') == 'gowner'){
			$this->layout = 'admin';
			$this->Paginator->settings['conditions'] = array('user_id'=>$this->Auth->user('id'));
			$this->Paginator->settings['order'] = array('id'=>'DESC');
		}
		$this->set ( 'grounds', $this->Paginator->paginate());
	}
	
	public function select_list() {
		$this->Ground->recursive = 0;
		if($this->Auth->user('role') == 'gowner'){
			$this->layout = 'admin';
			$this->Paginator->settings['conditions'] = array('user_id'=>$this->Auth->user('id'));
			$this->Paginator->settings['order'] = array('id'=>'DESC');
		}
		$this->set ( 'grounds', $this->Paginator->paginate() );
	}
	
	/**
	 * view method
	 *
	 * @throws NotFoundException
	 * @param string $id        	
	 * @return void
	 */
	public function view($id = null) {
		if (! $this->Ground->exists ( $id )) {
			throw new NotFoundException ( __ ( 'Invalid ground' ) );
		}
		
		$data = $this->Ground->available_slots ( $id );
		
		$options = array (
				'conditions' => array (
						'Ground.' . $this->Ground->primaryKey => $id 
				) 
		);
		$this->set ( 'ground', $this->Ground->find ( 'first', $options ) );
	}
	
	/**
	 * add method
	 *
	 * @return void
	 */
	public function add() {
		if ($this->request->is ( 'post' )) {
			$this->Ground->create ();
			if ($this->Ground->save ( $this->request->data )) {
				$this->Session->setFlash ( __ ( 'The ground has been saved' ) );
				$this->redirect ( array (
						'action' => 'index' 
				) );
			} else {
				$this->Session->setFlash ( __ ( 'The ground could not be saved. Please, try again.' ) );
			}
		}
		$users = $this->Ground->User->find ( 'list', array (
				'conditions' => array (
						'role' => 'GOWNER' 
				) 
		) );
		$types = $this->Ground->Type->find ( 'list' );
		$this->set ( compact ( 'users', 'types' ) );
	}
	
	/**
	 * edit method
	 *
	 * @throws NotFoundException
	 * @param string $id        	
	 * @return void
	 */
	public function edit($id = null) {
		if (! $this->Ground->exists ( $id )) {
			throw new NotFoundException ( __ ( 'Invalid ground' ) );
		}
		if ($this->request->is ( 'post' ) || $this->request->is ( 'put' )) {
			if ($this->Ground->save ( $this->request->data )) {
				$this->Session->setFlash ( __ ( 'The ground has been saved' ) );
				$this->redirect ( array (
						'action' => 'index' 
				) );
			} else {
				$this->Session->setFlash ( __ ( 'The ground could not be saved. Please, try again.' ) );
			}
		} else {
			$options = array (
					'conditions' => array (
							'Ground.' . $this->Ground->primaryKey => $id 
					) 
			);
			$this->request->data = $this->Ground->find ( 'first', $options );
		}
		$users = $this->Ground->User->find ( 'list' );
		$types = $this->Ground->Type->find ( 'list' );
		$this->set ( compact ( 'users', 'types' ) );
	}
	
	/**
	 * delete method
	 *
	 * @throws NotFoundException
	 * @param string $id        	
	 * @return void
	 */
	public function delete($id = null) {
		$this->Ground->id = $id;
		if (! $this->Ground->exists ()) {
			throw new NotFoundException ( __ ( 'Invalid ground' ) );
		}
		$this->request->onlyAllow ( 'post', 'delete' );
		if ($this->Ground->delete ()) {
			$this->Session->setFlash ( __ ( 'Ground deleted' ) );
			$this->redirect ( array (
					'action' => 'index' 
			) );
		}
		$this->Session->setFlash ( __ ( 'Ground was not deleted' ) );
		$this->redirect ( array (
				'action' => 'index' 
		) );
	}
	public function search() {
		$start_date = null;
		//pr($this->request->data);
		if ($this->request->is ( 'post' )){
			if($this->request->data['Ground']['date'] >= date('Y-m-d',time()) && $this->request->data['Ground']['date'] <= date('Y-m-d',time()+ (13*24*60*60))) {
				//Setting Variable
				$reqData = $this->request->data;
				unset($this->request->data);
				$this->loadModel('Group');
				$groups = $this->Group->find ( 'list' );
				$req_gid = $reqData['Ground']['group_id'];
				$reqData['Ground']['req_gid'] = $reqData['Ground']['group_id'];
				$temp_data = $this->Group->find('first',array('conditions'=>array('Group.id'=>$reqData['Ground']['group_id']),'recurssive'=>-1));
				if(!empty($temp_data))
					$reqData['Ground']['group_id'] = $temp_data['Group']['type_group']; 
				
				$this->layout = "default";
				$this->Ground->recursive = 0;
				$conditions = array('Type.group_id'=>$req_gid,'Ground.locality'=> !empty($reqData['Ground']['all_area']) ? $reqData['Ground']['all_area'] : $reqData['Ground']['area'],'Ground.active'=>1);
				if($this->Auth->user('role') == 'gowner'){
					$conditions['Ground.user_id'] = $this->Auth->user('id'); 
				}
				$grounds = $this->Ground->find ( 'all' ,array('conditions'=>$conditions,'recursive'=>0));
				if(!empty($grounds)){
					foreach($grounds as $k=>$ground){
						$grounds[$k]['Ground']['gallery'] = $this->FileUpload->getMedia('gallery',$ground['Ground']['id']);
					}
				}
				
				$this->set(compact('reqData','start_date','grounds','groups'));
			} else {
				$this->Session->setFlash ( __ ( 'Please select a date within the  next 15 days' ) );
				$this->redirect ('/');
			}
		}
		else{
			$this->redirect ('/');
		}
	}
	
	public function booking_layout($id,$start_date = null,$selected_court = 1){
			$this->layout = 'ajax';
			$this->Ground->id = $id;
			if (! $this->Ground->exists ()) {
				throw new NotFoundException ( __ ( 'Invalid ground' ) );
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
			$this->set (compact('slots','start_date_no','count_no','ground_details', 'start_date', 'selected_court'));
	}
	public function area_filter($group_id){
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
		$data = "";
		if(!empty($ground)){
			foreach($ground as $g){
				$data .= "<option value='".$g['Ground']['locality']."'>".$g['Ground']['locality']."</option>";
			}
		}
		print_r($data);
		die();
	}
	
	public function area_filter_list($group_id){
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

		$selected_areas = array();
		if (!empty($this->request->query['data_url'])) {
			$selected_areas = explode('-', $this->request->query['data_url']);
		}

		$data = "";
		$location_id = 0;
		if(!empty($ground)){
			foreach($ground as $g){
				$jfunction = 'JavaScript:selectLocation("'.str_replace('/','_',str_replace(' ','_',strtolower($g['Ground']['locality']))).$location_id.'")';
				if (in_array($g['Ground']['locality'], $selected_areas)) {
					$data .= "<li><a class='active' href='".$jfunction."' id='".str_replace('/','_',str_replace(' ','_',strtolower($g['Ground']['locality']))).$location_id."'>".$g['Ground']['locality']."</a></li>";
				} else {
					$data .= "<li><a href='".$jfunction."' id='".str_replace('/','_',str_replace(' ','_',strtolower($g['Ground']['locality']))).$location_id."'>".$g['Ground']['locality']."</a></li>";
				}
				$location_id++;
			}
		}

		print_r($data);
		die();
	}
	public function short_distance_area($group_id, $latitude, $longitude) {
		$ground = $this->Ground->getShortDistanceLocation($group_id, $latitude, $longitude);
		$data = "";
		if(!empty($ground)){
			foreach($ground as $g){
				$data .= "<option value='".$g['grounds']['locality']."'>".$g['grounds']['locality']."</option>";
			}
		}
		print_r($data);
		die();
	}

	public function short_distance_area_list($group_id, $latitude, $longitude) {
		$selected_areas = array();
		if (!empty($this->request->query['data_url'])) {
			$selected_areas = explode('-', $this->request->query['data_url']);
		}
		$ground = $this->Ground->getShortDistanceLocation($group_id, $latitude, $longitude);
		$data = "";
		$location_id = 0;
		if(!empty($ground)){
			foreach($ground as $g){
				$jfunction = 'JavaScript:selectLocation("'.str_replace('/','_',str_replace(' ','_',strtolower($g['grounds']['locality']))).$location_id.'")';
				if (in_array($g['grounds']['locality'], $selected_areas)) {
					$data .= "<li><a class='active' href='".$jfunction."' id='".str_replace('/','_',str_replace(' ','_',strtolower($g['grounds']['locality']))).$location_id."'>".$g['grounds']['locality']."</a></li>";	
				} else {
					$data .= "<li><a href='".$jfunction."' id='".str_replace('/','_',str_replace(' ','_',strtolower($g['grounds']['locality']))).$location_id."'>".$g['grounds']['locality']."</a></li>";
				}				
				$location_id++;
			}
		}
		print_r($data);
		die();
	}

	// Common function for short distance location list
	private function short_distance($group_id, $latitude, $longitude) {
		$ground = $this->Ground->getShortDistanceLocation($group_id, $latitude, $longitude);
		$data = "";
		$location_id = 0;
		if(!empty($ground)){
			foreach($ground as $g){
				$jfunction = 'JavaScript:selectLocation("'.str_replace('/','_',str_replace(' ','_',strtolower($g['grounds']['locality']))).$location_id.'")';
				$data .= "<li><a href='".$jfunction."' id='".str_replace('/','_',str_replace(' ','_',strtolower($g['grounds']['locality']))).$location_id."'>".$g['grounds']['locality']."</a></li>";
				$location_id++;
			}
		}
		return $data;
	}
	
	public function booking_price_for_ground(){
			$dynamic_price = array();
			$final_calc = array();
			$this->layout = 'ajax';
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
			$service = ($this->request->data['s_number'] * Configure::read('service_charge'));
			$total = ($base + $service);
			$final_calc['base'] = $base;
			$final_calc['total'] = $total;
			$final_calc['service'] = $service;
			echo json_encode($final_calc);
			exit;
	}
	
	public function admin_booking_layout($id,$start_date = null,$selected_court = 1){
			$this->layout = 'ajax';
			$this->Ground->id = $id;
			if (! $this->Ground->exists ()) {
				throw new NotFoundException ( __ ( 'Invalid ground' ) );
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
			$this->set (compact('slots','start_date_no','count_no','ground_details', 'start_date', 'selected_court'));
	}
}
