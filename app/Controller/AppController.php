<?php
/**
 * Application level Controller
 *
 * This file is application-wide controller file. You can put all
 * application-wide controller-related methods here.
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
App::uses ( 'Controller', 'Controller' );
App::uses('CakeTime','Utility');
App::uses('CakeEmail', 'Network/Email');
App::uses('HttpSocket', 'Network/Http');

/**
 * Application Controller
 *
 * Add your application-wide methods in the class below, your controllers
 * will inherit them.
 *
 * @package app.Controller
 * @link http://book.cakephp.org/2.0/en/controllers.html#the-app-controller
 */
class AppController extends Controller {
	var $components = array (
			'Auth',
			'Session',
			'Cookie',
			'Security' => array (
					"csrfExpires" => "+30 minutes",
					"csrfCheck" => true 
			),
			"DebugKit.Toolbar",
			"Sms",
			"FileUpload"
	);
	var $priv = array ();
	var $helpers = array (
			'EBHtml',
			'Html',
			'Form',
			'Time',
			'Session',
			'Js' => array (
					'Jquery' 
			),
			"EBForm" 
	);
	var $defaultAction = false;
	function beforeFilter() {
		
		// Processing pending cookies
		if ($this->Session->read ( "pendingCookies" )) {
			$this->Cookie->write ( "remember", $this->Session->read ( "pendingCookies" ), true, '30 days' );
			$this->Session->delete ( "pendingCookies" );
		}
		
		// Settings required for Auth component
		$this->Auth->loginAction = array (
				"controller" => "users",
				"action" => "login",
				"plugin" => false 
		);
		$this->Auth->loginRedirect = "/users/redirection";
		$this->Auth->authError = __ ( "You do not have permission to access that page" );
		$this->Auth->userModel = "User";
		$this->Auth->authorize = 'Controller';
		
		// Setting a blank layout for ajax requests
		$this->layout = "default";
		if ($this->Auth->user ( "role" ) == 'admin') {
			$this->layout = "admin";
		}
		if ($this->request->is ( "ajax" )) {
			$this->layout = "ajax";
		}
		
		// Setting the variables required for all the views
		$this->set ( "role", $this->Auth->user ( "role" ) );
		$this->set ( "display_name", $this->Auth->user ( "display_name" ) );
		
		// Making the search bookmarkable & to make sure the request variables are not lost when subsequent pages (pagination) are visited
		if ($this->request->is ( "post" ) && ! $this->request->is ( "ajax" )) {
			if ($this->request->data ( "Search.proxy" ) == 1) {
				$params = array ();
				foreach ( $this->request->data ( "Search" ) as $key => $value ) {
					$params ["Search.$key"] = $value;
				}
				$this->redirect ( $params );
			}
		}
		// Converting the Search tags back to $this->request->data for programming convenience
		$requestVars = Hash::expand ( $this->request->params ['named'] );
		if (isset ( $requestVars ['Search'] )) {
			$this->request->data ['Search'] = $requestVars ['Search'];
		}
		
		// Permissions
		if ($this->params ['controller'] == 'pages') {
			// Allowing all the root pages
			$this->Auth->allow ();
			return;
		} if ($this->params ['controller'] == 'api') {
			// Allowing all the root pages
			$this->Auth->allow ();
			return;
		} else if (isset ( $this->priv ['guest'] )) {
			// Allowing guests
			$guestAccess = $this->priv ['guest'];
			if (! is_array ( $guestAccess ) && $guestAccess == "*")
				$guestAccess = null;
			
			$this->Auth->allow ( $guestAccess );
			return;
		}
	}
	function checkPriv($role, $action = null, $overrideValidateAuthorization = false) {
		if ($action == null)
			$action = $this->action;
		
		if (array_key_exists ( $role, $this->priv )) {
			$roleArr = $this->priv [$role];
			if (is_array ( $roleArr )) {
				if (in_array ( $action, $roleArr )) {
					
					if ($overrideValidateAuthorization)
						return true;
					
					if ($this->validateAuthorization ())
						return true;
					else
						return false;
				} else {
					return false;
				}
			} else {
				if ($roleArr == "*") {
					if ($this->validateAuthorization ())
						return true;
					else
						return false;
				} else
					return false;
			}
		} else {
			return $this->defaultAction;
		}
	}
	function isAuthorized() {
		$role = $this->Auth->user ( 'role' );
		if ($role == "" || ! $role) {
			$role = "guest";
		}
		
		if ($this->checkPriv ( "guest" ))
			return true;
		else {
			if ($this->checkPriv ( $role ))
				return true;
			else {
				$this->Session->setFlash ( "Please sign up to login to the website as user" );
				return false;
			}
		}
	}
	function validateAuthorization() {
		return true;
	}
}
