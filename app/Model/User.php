<?php
App::uses ( 'AppModel', 'Model' );
/**
 * User Model
 *
 * @property Ground $Ground
 */
class User extends AppModel {
	var $displayField = 'username';
	/**
	 * Validation rules
	 *
	 * @var array
	 */
	public $validate = array (
			'display_name' => array (
					'notempty' => array (
							'rule' => array (
									'notempty' 
							) 
					) 
			),
			
			// 'message' => 'Your custom message here',
			// 'allowEmpty' => false,
			// 'required' => false,
			// 'last' => false, // Stop validation after this rule
			// 'on' => 'create', // Limit validation to 'create' or 'update' operations
			
			'username' => array (
					'notempty' => array (
							'rule' => array (
									'notempty' 
							) 
					) 
			),
			
			// 'message' => 'Your custom message here',
			// 'allowEmpty' => false,
			// 'required' => false,
			// 'last' => false, // Stop validation after this rule
			// 'on' => 'create', // Limit validation to 'create' or 'update' operations
			
			'password' => array (
					'notempty' => array (
							'rule' => array (
									'notempty' 
							) 
					) 
			),
			
			// 'message' => 'Your custom message here',
			// 'allowEmpty' => false,
			// 'required' => false,
			// 'last' => false, // Stop validation after this rule
			// 'on' => 'create', // Limit validation to 'create' or 'update' operations
			
			'email' => array (
					'email' => array (
							'rule' => array (
									'email' 
							) 
					) 
			),
			
			// 'message' => 'Your custom message here',
			// 'allowEmpty' => false,
			// 'required' => false,
			// 'last' => false, // Stop validation after this rule
			// 'on' => 'create', // Limit validation to 'create' or 'update' operations
			
			'role' => array (
					'notempty' => array (
							'rule' => array (
									'notempty' 
							),
							'message' => 'Role is required' 
					),
					
					// 'allowEmpty' => false,
					// 'required' => false,
					// 'last' => false, // Stop validation after this rule
					// 'on' => 'create', // Limit validation to 'create' or 'update' operations
					'enum' => array (
							'rule' => array (
									'inlist',
									array (
											'user',
											'admin',
											'guest',
											'gowner' 
									) 
							),
							'message' => array (
									'It must one among these USER,ADMIN,GUEST,GOWNER' 
							) 
					) 
			),
			'active' => array (
					'numeric' => array (
							'rule' => array (
									'numeric' 
							) 
					) 
			),
			
			// 'message' => 'Your custom message here',
			// 'allowEmpty' => false,
			// 'required' => false,
			// 'last' => false, // Stop validation after this rule
			// 'on' => 'create', // Limit validation to 'create' or 'update' operations
			
			'created' => array (
					'datetime' => array (
							'rule' => array (
									'datetime' 
							) 
					) 
			),
			
			// 'message' => 'Your custom message here',
			// 'allowEmpty' => false,
			// 'required' => false,
			// 'last' => false, // Stop validation after this rule
			// 'on' => 'create', // Limit validation to 'create' or 'update' operations
			
			'modified' => array (
					'datetime' => array (
							'rule' => array (
									'datetime' 
							) 
					) 
			) 
	)
	// 'message' => 'Your custom message here',
	// 'allowEmpty' => false,
	// 'required' => false,
	// 'last' => false, // Stop validation after this rule
	// 'on' => 'create', // Limit validation to 'create' or 'update' operations
	
	;
	
	// The Associations below have been created with all possible keys, those that are not needed can be removed
	
	/**
	 * hasMany associations
	 *
	 * @var array
	 */
	public $hasMany = array (
			'Ground' => array (
					'className' => 'Ground',
					'foreignKey' => 'user_id',
					'dependent' => false,
					'conditions' => '',
					'fields' => '',
					'order' => '',
					'limit' => '',
					'offset' => '',
					'exclusive' => '',
					'finderQuery' => '',
					'counterQuery' => '' 
			) 
	);
	public function beforeSave($options = array()) {
		if (in_array ( "password", $options ['fieldList'] ) || ($this->id == null && $this->data ['User'] ['password'])) {
			App::import ( "Component", "Auth" );
			$auth = new AuthComponent ( new ComponentCollection () );
			$this->data ['User'] ['password'] = $auth->password ( $this->data ['User'] ['password'] );
		}
		return true;
	}
}
