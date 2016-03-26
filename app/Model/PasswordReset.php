<?php
App::uses ( 'AppModel', 'Model' );
/**
 * PasswordReset Model
 *
 * @property User $User
 */
class PasswordReset extends AppModel {
	
	/**
	 * Use table
	 *
	 * @var mixed False or table name
	 */
	public $useTable = 'password_reset';
	
	/**
	 * Display field
	 *
	 * @var string
	 */
	public $displayField = 'reset_code';
	
	// The Associations below have been created with all possible keys, those that are not needed can be removed
	
	/**
	 * belongsTo associations
	 *
	 * @var array
	 */
	public $belongsTo = array (
			'User' => array (
					'className' => 'User',
					'foreignKey' => 'user_id',
					'conditions' => '',
					'fields' => '',
					'order' => '' 
			) 
	);
}
