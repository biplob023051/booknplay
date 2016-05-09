<?php
App::uses ( 'AppController', 'Controller' );
/**
 * Coupons Controller
 *
 * @property Coupon $Coupon
 */
class CouponsController extends AppController {
	var $priv = array (
			'admin' => '*',
	);
	public $components = array('Paginator');
	public $adminLayouts = "*";
	public function beforeFilter() {
		parent::beforeFilter ();
	}
	/**
	 * index method
	 *
	 * @return void
	 */
	public function index() {
		$this->Coupon->recursive = 0;
		if($this->Auth->user('role') == 'admin') {
			$this->layout = 'admin';
			//$this->Paginator->settings['conditions'] = array('isactive' => 1);
			$this->Paginator->settings['order'] = array('id'=>'DESC');
		}
		$this->set ( 'coupons', $this->Paginator->paginate());
		$this->set('userOptions', $this->Coupon->userOptions);
		$this->set('statusOptions', $this->Coupon->statusOptions);
	}
	
	/**
	 * add method
	 *
	 * @return void
	 */
	public function add() {
		if ($this->request->is ( 'post' )) {
			$this->Coupon->create();
			if ($this->Coupon->save($this->request->data)) {
				$this->Session->setFlash( __('The coupon has been saved'));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash ( __ ( 'The coupon could not be saved. Please, try again.' ) );
			}
		}
		$this->set('userOptions', $this->Coupon->userOptions);
	}
	
	/**
	 * edit method
	 *
	 * @throws NotFoundException
	 * @param string $id        	
	 * @return void
	 */
	public function edit($id = null) {
		if (! $this->Coupon->exists ( $id )) {
			throw new NotFoundException ( __ ( 'Invalid coupon' ) );
		}
		if ($this->request->is ( 'post' ) || $this->request->is ( 'put' )) {
			if ($this->Coupon->save ( $this->request->data )) {
				$this->Session->setFlash ( __ ( 'The coupon has been saved' ) );
				$this->redirect ( array (
						'action' => 'index' 
				) );
			} else {
				$this->Session->setFlash ( __ ( 'The coupon could not be saved. Please, try again.' ) );
			}
		} else {
			$options = array(
				'conditions' => array(
					'Coupon.' . $this->Coupon->primaryKey => $id 
				) 
			);
			$this->request->data = $this->Coupon->find ( 'first', $options );
		}
		$this->set('userOptions', $this->Coupon->userOptions);
		$this->set('statusOptions', $this->Coupon->statusOptions);
	}
	
	/**
	 * delete method
	 *
	 * @throws NotFoundException
	 * @param string $id        	
	 * @return void
	 */
	public function delete($id = null) {
		$this->Coupon->id = $id;
		if (! $this->Coupon->exists ()) {
			throw new NotFoundException ( __ ( 'Invalid coupon' ) );
		}
		$this->request->onlyAllow ( 'post', 'delete' );
		if ($this->Coupon->delete ()) {
			$this->Session->setFlash ( __ ( 'Coupon deleted' ) );
			$this->redirect ( array (
					'action' => 'index' 
			) );
		}
		$this->Session->setFlash ( __ ( 'Coupon was not deleted' ) );
		$this->redirect ( array (
				'action' => 'index' 
		) );
	}
	
}
