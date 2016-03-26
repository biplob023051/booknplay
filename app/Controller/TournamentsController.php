<?php
App::uses ( 'AppController', 'Controller' );
/**
 * Grounds Controller
 *
 * @property Ground $Ground
 */
class TournamentsController extends AppController {
	
	/**
	 * index method
	 *
	 * @return void
	 */
	function beforeFilter() {
		$this->Auth->allow ('index','register1');
		$this->Auth->allow ('index','register2');
		parent::beforeFilter();
	}
	public function index() {
		
	}
	
	public function register() {
		
	}
        public function register1() {
		
	}
        public function register2() {
		
	}
}
