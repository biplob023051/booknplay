<?php
/**
 * Static content controller.
 *
 * This file will render views from views/pages/
 *
 * PHP 5
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       app.Controller
 * @since         CakePHP(tm) v 0.2.9
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */
App::uses ( 'AppController', 'Controller' );

/**
 * Static content controller
 *
 * Override this controller by placing a copy in controllers directory of an application
 *
 * @package app.Controller
 * @link http://book.cakephp.org/2.0/en/controllers/pages-controller.html
 */
class PagesController extends AppController {
	
	/**
	 * Controller name
	 *
	 * @var string
	 */
	public $name = 'Pages';
	var $priv = array ();
	
	/**
	 * This controller does not use a model
	 *
	 * @var array
	 */
	public $uses = array ();
	
	/**
	 * Displays a view
	 *
	 * @param
	 *        	mixed What page to display
	 * @return void
	 */
	public function display() {
		$this->layout = 'default';
		$path = func_get_args ();
		
		$count = count ( $path );
		if (! $count) {
			$this->redirect ( '/' );
		}
		$page = $subpage = $title_for_layout = null;
		
		if (! empty ( $path [0] )) {
			$page = $path [0];
		}
		if (! empty ( $path [1] )) {
			$subpage = $path [1];
		}
		if (! empty ( $path [$count - 1] )) {
			$title_for_layout = Inflector::humanize ( $path [$count - 1] );
		}
		if ($page == "home") {
			$this->layout = "default";
			// Setting variable for home page
			$this->loadModel ( 'Type' );
			$types = $this->Type->find ( 'list' );
			
			$this->loadModel ( 'Group' );
			$groups = $this->Group->find ( 'list' );
			
			$this->loadModel ( 'Ground' );
			$temp_area = $this->Ground->find ( 'all', array (
					'fields' => array (
							'DISTINCT(locality)' 
					),
					'recursive' => - 1 
			) );
			// Process Area
			$area = array ();
			if (! empty ( $temp_area )) {
				foreach ( $temp_area as $a ) {
					$area [$a ['Ground'] ['locality']] = $a ['Ground'] ['locality'];
				}
			}
			
			//Setting Date
			$datelist = array();
			for($i=0;$i<14;$i++){
				$datelist[date('Y-m-d',strtotime('+'.$i.' days'))] = date('D, j M y',strtotime('+'.$i.' days'));
			}
			
			$this->set ( compact ( "types", "groups", "area", "datelist") );
		}
		$this->set ( compact ( 'page', 'subpage', 'title_for_layout' ) );
		$this->render ( implode ( '/', $path ) );
	}
}
