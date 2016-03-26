<?php
App::uses ( 'AppController', 'Controller' );
/**
 * Schedules Controller
 *
 * @property Schedule $Schedule
 */
class SchedulesController extends AppController {
	var $priv = array (
			'admin' => '*',
			'gowner' =>array('add','index','edit','delete')
	);
	public $components = array('Paginator');
	public $adminLayouts = "*";
	public function beforeFilter() {
		parent::beforeFilter ();
		$this->Security->unlockedActions [] = "add";
		$this->Security->unlockedActions [] = "edit";
	}
	/**
	 * index method
	 *
	 * @return void
	 */
	public function index($id = null) {
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
			
			if(!empty($id)){
				$this->Schedule->recursive = 0;
				$this->Paginator->settings['conditions'] = array('ground_id'=>$id);
				$this->Paginator->settings['order'] = array('Schedule.created'=>'DESC');
				$this->set ( 'schedules', $this->Paginator->paginate());
				$this->set ( 'ground', $this->Ground->find('first',array('conditions'=>array('Ground.id'=>$id))));
			}
	}
	
	/**
	 * view method
	 *
	 * @throws NotFoundException
	 * @param string $id        	
	 * @return void
	 */
	public function view($id = null) {
		$this->redirect ($this->referer());
		if (! $this->Schedule->exists ( $id )) {
			throw new NotFoundException ( __ ( 'Invalid schedule' ) );
		}
		$options = array (
				'conditions' => array (
						'Schedule.' . $this->Schedule->primaryKey => $id 
				) 
		);
		$this->set ( 'schedule', $this->Schedule->find ( 'first', $options ) );
	}
	
	/**
	 * add method
	 *
	 * @return void
	 */
	public function add($id = null) {
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
		
		if ($this->request->is ( 'post' )) {
			//Process Data
			$save_status = false;
			$prices = '';
			if(isset($this->request->data['prices']) && is_array($this->request->data['prices'])) {
				$prices = implode(',', $this->request->data['prices']);
				$this->request->data['Schedule']['prices'] = $prices;
			}
			
			if(isset($this->request->data['Schedule']['date'])) {
				$sch_multiple_dates = explode(',', $this->request->data['Schedule']['date']);
				foreach($sch_multiple_dates as $sch_date) {
					$this->request->data['Schedule']['date'] = date('Y-m-d H:i:s',strtotime($sch_date));
					$this->Schedule->create ();
					if($this->Schedule->save ( $this->request->data )) {
						$save_status = true;
					}
				}
			}
			if ($save_status) {
				
				$this->Session->setFlash ( __ ( 'The schedule has been saved' ) );
				$this->redirect ($this->referer());
			} else {
				$this->Session->setFlash ( __ ( 'The schedule could not be saved. May be issue with same date or past date selection.' ) );
			}
		}
		
		//$grounds = $this->Schedule->Ground->find ( 'list', array('conditions'=>array('Ground.id'=>$id)));
		$this->set ( compact ( 'id' ) );
	}
	
	/**
	 * edit method
	 *
	 * @throws NotFoundException
	 * @param string $id        	
	 * @return void
	 */
	public function edit($id = null) {
		$this->layout = 'admin';
		if (! $this->Schedule->exists ( $id )) {
			throw new NotFoundException ( __ ( 'Invalid schedule' ) );
		}
		if ($this->request->is ( 'post' ) || $this->request->is ( 'put' )) {
			//Process Data
			if(isset($this->request->data['Schedule']['date'])){
				$this->request->data['Schedule']['date'] = date('Y-m-d H:i:s',strtotime($this->request->data['Schedule']['date']));
			}
			
			$this->request->data['Schedule']['prices'] = implode(',',$this->request->data['prices']);
			if ($this->Schedule->save ( $this->request->data )) {
				$this->Session->setFlash ( __ ( 'The schedule has been saved' ) );
				$this->redirect ($this->referer());
			} else {
				$this->Session->setFlash ( __ ( 'The schedule could not be saved. May be issue with same date or past date selection.' ) );
			}
		} else {
			$options = array (
					'conditions' => array (
							'Schedule.' . $this->Schedule->primaryKey => $id 
					) 
			);
			$this->request->data = $this->Schedule->find ( 'first', $options );
		}
		$grounds = $this->Schedule->Ground->find ( 'list' );
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
		$this->Schedule->id = $id;
		if (! $this->Schedule->exists ()) {
			throw new NotFoundException ( __ ( 'Invalid schedule' ) );
		}
		$this->request->onlyAllow ( 'post', 'delete' );
		if ($this->Schedule->delete ()) {
			$this->Session->setFlash ( __ ( 'Schedule deleted' ) );
			$this->redirect ($this->referer());
		}
		$this->Session->setFlash ( __ ( 'Schedule was not deleted' ) );
		$this->redirect ($this->referer());
	}
}
