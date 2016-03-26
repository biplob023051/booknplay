<?php
App::uses ( 'AppController', 'Controller' );
/**
 * Users Controller
 *
 * @property User $User
 */
class UsersController extends AppController {
	public $components = array('Paginator');
	public $priv = array (
			"guest" => array (
					"login",
					"reset",
					"forget",
					"logout",
					"check_uname",
					"check_password" ,
					"signup",
					"contact_email",
			),
			"admin" => array (
					"logout",
					"add",
					"index",
					"delete",
					"admin_password",
					"redirection",
					"contact_email"
			),
			"gowner" => array('admin_password'),
			"user" => array("contact_email","change_password")
	);
	/**
	 * index method
	 *
	 * @return void
	 */
	public function index($cond = null) {
		$this->User->recursive = 0;
		$this->Paginator->settings['conditions'] = array('role != '=>'admin');
		if($cond == 'gowner')
			$this->Paginator->settings['conditions'] = array('role'=>'gowner');
		
		if($cond == 'user')
			$this->Paginator->settings['conditions'] = array('role'=>'user');
		
		if($cond == 'guest')
			$this->Paginator->settings['conditions'] = array('role'=>'guest');
		
		$this->set ( 'users', $this->Paginator->paginate() );
	}
	
	/**
	 * view method
	 *
	 * @throws NotFoundException
	 * @param string $id        	
	 * @return void
	 */
	public function view($id = null) {
		if (! $this->User->exists ( $id )) {
			throw new NotFoundException ( __ ( 'Invalid user' ) );
		}
		$options = array (
				'conditions' => array (
						'User.' . $this->User->primaryKey => $id 
				) 
		);
		$this->set ( 'user', $this->User->find ( 'first', $options ) );
	}
	
	/**
	 * add method
	 *
	 * @return void
	 */
	public function add() {
		if ($this->request->is ( 'post' )) {
			$this->User->create ();
			
			$this->request->data['User']['active'] = 1;
			$this->request->data['User']['email'] = trim($this->request->data['User']['email']);
			$this->request->data['User']['username'] = $this->request->data['User']['email'];
			if ($this->User->save ( $this->request->data )) {
				$this->Session->setFlash ( __ ( 'The user has been saved' ) );
				$this->redirect ( array (
						'action' => 'index' 
				) );
			} else {
				$this->Session->setFlash ( __ ( 'The user could not be saved. Please, try again.' ) );
			}
		}
	}
	
	public function signup() {
		$this->layout = "default";
		if ($this->request->is ( 'post' )) {
			$check_user = $this->User->findByEmail(trim($this->request->data['User']['email']));
			if(!empty($check_user) && $check_user['User']['role'] == 'guest') {
				$this->request->data['User']['id'] = $check_user['User']['id'];
				$this->request->data['User']['active'] = 1;
				$this->request->data['User']['password'] = $this->Auth->password($this->data['User']['password']);
				$this->request->data['User']['role'] = 'user';
				$this->request->data['User']['email'] = trim($this->request->data['User']['email']);
				$this->request->data['User']['username'] = $this->request->data['User']['email'];
				if ($this->User->save ( $this->request->data )) {
					$this->Session->setFlash ( __ ( 'The user has been saved' ) );
					$this->redirect ( array (
							'action' => 'index'
					) );
				} else {
					$this->Session->setFlash ( __ ( 'The user could not be saved. Please, try again.' ) );
				}
			} else if(empty($check_user)) { 
				$this->User->create ();
				$this->request->data['User']['active'] = 1;
				$this->request->data['User']['role'] = 'user';
				$this->request->data['User']['email'] = trim($this->request->data['User']['email']);
				$this->request->data['User']['username'] = $this->request->data['User']['email'];
				if ($this->User->save ( $this->request->data )) {
					$this->Session->setFlash ( __ ( 'The user has been saved' ) );
					$this->redirect ( array (
							'action' => 'index'
					) );
				} else {
					$this->Session->setFlash ( __ ( 'The user could not be saved. Please, try again.' ) );
				}
			} else {
				$this->Session->setFlash ( __ ( 'It seems your account already exists.' ) );
			}
			
		}
	}
	
	/**
	 * edit method
	 *
	 * @throws NotFoundException
	 * @param string $id        	
	 * @return void
	 */
	public function edit($id = null) {
		if (! $this->User->exists ( $id )) {
			throw new NotFoundException ( __ ( 'Invalid user' ) );
		}
		if ($this->request->is ( 'post' ) || $this->request->is ( 'put' )) {
			if ($this->User->save ( $this->request->data )) {
				$this->Session->setFlash ( __ ( 'The user has been saved' ) );
				$this->redirect ( array (
						'action' => 'index' 
				) );
			} else {
				$this->Session->setFlash ( __ ( 'The user could not be saved. Please, try again.' ) );
			}
		} else {
			$options = array (
					'conditions' => array (
							'User.' . $this->User->primaryKey => $id 
					) 
			);
			$this->request->data = $this->User->find ( 'first', $options );
		}
	}
	
	/**
	 * delete method
	 *
	 * @throws NotFoundException
	 * @param string $id        	
	 * @return void
	 */
	public function delete($id = null) {
		$this->User->id = $id;
		if (! $this->User->exists ()) {
			throw new NotFoundException ( __ ( 'Invalid user' ) );
		}
		$this->request->onlyAllow ( 'post', 'delete' );
		if ($this->User->delete ()) {
			$this->Session->setFlash ( __ ( 'User deleted' ) );
			$this->redirect ( array (
					'action' => 'index' 
			) );
		}
		$this->Session->setFlash ( __ ( 'User was not deleted' ) );
		$this->redirect ( array (
				'action' => 'index' 
		) );
	}
	
	// Base additions
	public function login($clear = null) {
		$this->layout = "default";
		if ($clear == "clear") {
			$this->Cookie->delete ( "remember" );
			$this->redirect ( $this->referer () );
		}
		
		if (! $this->Cookie->read ( "remember" ) && ! $this->Cookie->read ( "remember.user" )) {
			// No remember cookie has been set
		} else {
			// If remember cookie is set
			$userDetails = $this->User->read ( null, $this->Cookie->read ( "remember.user" ) );
			$this->set ( compact ( "userDetails" ) );
		}
		
		if ($this->request->is ( 'post' )) {
			if ($this->Auth->login ()) {
				if ($this->request->data ( "User.remember" )) {
					if (! $this->request->is ( "ajax" )) {
						$this->Cookie->write ( "remember", array (
								"user" => $this->Auth->user ( "id" ) 
						), true, '30 days' );
					} else {
						$this->Session->write ( "pendingCookies", array (
								"user" => $this->Auth->user ( "id" ) 
						) );
					}
				}
				
				// Setting User_data
				switch ($this->Auth->user ( 'role' )) {
					case 'admin' :
						$this->redirect (array('controller'=>'grounds','action'=>'index'));
						break;
					case 'gowner' :
						$this->redirect (array('controller'=>'grounds','action'=>'index'));
						break;
					case 'user' :
						$this->redirect (array('controller'=>'bookings','action'=>'my_books'));
						break;
					default :
						break;
				}
				
				if (! $this->request->is ( "ajax" )) {
					$this->redirect ( $this->Auth->redirect () );
				} else {
					die ( "1" );
				}
			} else {
				if (! $this->request->is ( "ajax" )) {
					$this->Session->setFlash ( __ ( 'Invalid username or password, try again' ) );
					$this->redirect ( $this->referer () );
				} else {
					die ( __ ( 'Invalid username or password, try again' ) );
				}
			}
		}
	}
	public function reset($resetCode = null) {
		if (! $resetCode) {
			throw new NotFoundException ( __ ( "Reset Code is invalid" ) );
		}
		
		$resetCode = urlencode ( $resetCode );
		if ($this->request->is ( "post" )) {
			if ($this->request->data ['User'] ['password'] != $this->request->data ['User'] ['confirm_password']) {
				$error = __ ( "Password & confirmation do not match" );
			} else {
				$this->loadModel ( "PasswordReset" );
				$resetData = $this->PasswordReset->find ( "first", array (
						"conditions" => array (
								"PasswordReset.reset_code" => $resetCode 
						),
						"recursive" => 0 
				) );
				
				if ($resetData ['User'] ['username'] != $this->request->data ['User'] ['username']) {
					$error = __ ( "Could not change the password due to username mismatch" );
				} else {
					$updatedUserData ['User'] = array (
							"id" => $resetData ['User'] ['id'],
							"password" => $this->request->data ['User'] ['password'] 
					);
					if ($this->User->save ( $updatedUserData, true, array (
							"password" 
					) )) {
						$this->PasswordReset->deleteAll ( array (
								"reset_code" => $resetCode,
								"user_id" => $resetData ['User'] ['id'] 
						) );
						/*
						 * $email = new CakeEmail('default');
						 * $email->to($this->request->data['User']['email']);
						 * $email->subject(__("Your request for password reset"));
						 * $email->viewVars(array(
						 * "password"=>$resetData['User']['password']
						 * ));
						 * $email->template("successful_reset");
						 * try {
						 * $email->send();
						 * } catch (SocketException $ex) {}
						 */
						
						$this->redirect ( array (
								"action" => "login" 
						), null, true );
					} else {
						$invalidFields = $this->User->invalidFields ();
						$error = (isset ( $invalidFields ['password'] [0] )) ? $invalidFields ['password'] [0] : "Unknown error";
					}
				}
			}
			
			if (isset ( $error )) {
				$this->set ( compact ( "error" ) );
			}
		}
	}
	public function forget() {
		if ($this->request->is ( "post" )) {
			$this->User->recursive = - 1;
			$logins = $this->User->find ( 'first', array('conditions'=>array('User.email'=>$this->request->data ['User']['email'] )));
			$this->User->id = $logins ['User'] ['id'];
			$data['User']['id'] = $this->User->id;
			$data['User']['password'] = substr(str_shuffle('abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789') , 0 , 10 );
			if ($this->User->save ( $data, true, array (
					"password"
			))) {
				$msg = "Hi,
						Use the following credentials to access your booknplay account.".
				", Username:".$this->request->data ['User']['email'].
				", Password:".$data['User']['password'].".
						Please change your password once you log in";
					
				try{
					//Mail Notification
					$Email = new CakeEmail();
					$Email->to(trim($this->request->data ['User']['email']));
					$Email->from(array('booknplay@gmail.com' => 'Book N Play!'));
					$Email->subject('BookNPlay - Reset password!');
					$Email->send($msg);
				} catch(Exception $ex) {
					echo $ex->getMessage();
					die();
				}
				$this->Session->setFlash ( "Kindly check your email for the temporary password to login to your account!");
				$this->redirect ( $this->referer() );
			} else {
				$this->Session->setFlash ( "Could not change the password!!!" );
				$this->request->data = array ();
			}
		}
	}
	public function logout() {
		$this->redirect ( $this->Auth->logout () );
	}
	public function redirection() {
	}
	public function beforeFilter() {
		parent::beforeFilter ();
		$this->Security->unlockedActions [] = "contact_email";
		$this->Security->unlockedActions [] = "forget";
	}
	
	// Check username
	public function check_uname() {
		$string = $_GET ['fieldValue'];
		$fieldId = $_GET ['fieldId'];
		$this->layout = $this->autoRender = false;
		
		if ($string == null) {
			echo json_encode ( array (
					0 => $fieldId,
					1 => false 
			) );
			exit ();
		}
		
		$this->User->recursive = - 1;
		$data = $this->User->find ( "first", array (
				"conditions" => array (
						"User.username" => $string 
				) 
		) );
		
		if (! $data) {
			echo json_encode ( array (
					0 => $fieldId,
					1 => true 
			) );
			exit ();
		} else {
			echo json_encode ( array (
					0 => $fieldId,
					1 => false 
			) );
			exit ();
		}
	}
	public function change_password() {
		if ($this->request->is ( "post" )) {
			$userId = $this->Auth->user ( "id" );
			if (! isset ( $this->request->data ['User'] ['old_pass'] ) || ! isset ( $this->request->data ['User'] ['new_pass'] ) || ! isset ( $this->request->data ['User'] ['password'] )) {
				$this->Session->setFlash ( __ ( "Please submit the form properly" ) );
				$this->redirect ( "/users/change_password" );
			}
			
			$o_pass = $this->Auth->password ( $this->request->data ['User'] ['old_pass'] );
			$n_pass = ($this->request->data ['User'] ['new_pass']);
			$c_pass = ($this->request->data ['User'] ['password']);
			$msg = "";
			
			$this->User->recursive = - 1;
			$logins = $this->User->read ( null, $this->Auth->user ( 'id' ) );
			$newPassword = $logins ['User'] ['password'];
			if ($o_pass != $newPassword)
				$msg .= __ ( "Old password does not match" ) . "<br />";
			
			if ($n_pass != $c_pass)
				$msg .= __ ( "The 2 passwords do not match" );
			
			if ($msg != "") {
				$this->Session->setFlash ( $msg );
				return;
			}
			$this->request->data ['User'] ['password'] = $c_pass;
			$logins ['User'] ['id'] = $this->Auth->user ( "id" );
			$logins ['User'] ['password'] = $n_pass;
			$this->User->id = $this->Auth->user ( "id" );
			if ($this->User->save ( $this->request->data, true, array (
					"password" 
			) )) {
				$this->Session->setFlash ( "Password Changed Successfully" );
				$this->redirect ( $this->referer() );
			} else {
				$this->Session->setFlash ( "Could not change the password!!!" );
				$this->request->data = array ();
			}
		}
	}
	
	public function admin_password() {
		$this->layout = "admin";
		if($this->request->is("post")) {
	
			$userId = $this->Auth->user("id");
			if(!isset($this->request->data['User']['old_pass']) || !isset($this->request->data['User']['new_pass']) || !isset($this->request->data['User']['password']))  {
				$this->Session->setFlash(__("Please submit the form properly"));
				$this->redirect("/users/admin_pasword");
			}
	
			$o_pass=$this->Auth->password($this->request->data['User']['old_pass']);
			$n_pass=($this->request->data['User']['new_pass']);
			$c_pass=($this->request->data['User']['password']);
			$msg="";
	
			$this->User->recursive=-1;
			$logins=$this->User->read(null,$this->Auth->user('id'));
			$newPassword=$logins['User']['password'];
			if($o_pass != $newPassword)
				$msg.=__("Old password does not match")."<br />";
	
			if($n_pass != $c_pass)
				$msg.=__("The 2 passwords do not match");
	
			if($msg!="") {
				$this->Session->setFlash($msg);
				return ;
			}
			$this->request->data['User']['password']=$c_pass;
			$logins['User']['id']=$this->Auth->user("id");
			$logins['User']['password']=$n_pass;
			$this->User->id = $this->Auth->user("id");
			if($this->User->save($this->request->data,true,array("password"))) {
				$this->Session->setFlash("Password Changed Successfully");
				$this->referer();
			}
			else {
				$this->Session->setFlash("Could not change the password!!!");
				$this->request->data = array();
			}
		}
	}
	
	public function contact_email(){
		if($this->request->is("post")) {
			$msg = "Name:".$this->request->data['contactName'].
				   ", Email:".$this->request->data['email'].
				   ", Phone:".$this->request->data['phone'].
				   ", Message:".$this->request->data['comments'];
			
			try{
				//Mail Notification
				$Email = new CakeEmail();
				$Email->to('helpdesk.booknplay.in@gmail.com');
				$Email->from(array('booknplay@gmail.com' => 'Book N Play!'));
				$Email->subject('Contact page information');
				$Email->send($msg);
			} catch(Exception $ex) {
				echo $ex->getMessage();
				die();
			}
			$this->Session->setFlash ( "Email has been sent !" );
			$this->redirect ( "/", null, true );
		}
	}
}
