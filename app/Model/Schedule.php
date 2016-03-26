<?php
App::uses ( 'AppModel', 'Model' );
/**
 * Schedule Model
 *
 * @property Ground $Ground
 */
class Schedule extends AppModel {
	
	/**
	 * Validation rules
	 *
	 * @var array
	 */
	public $validate = array (
			'date' => array (
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
			
			'slots' => array (
					'notempty' => array (
							'rule' => array (
									'notempty' 
							),
							'message' => 'Cannot be empty' 
					),
					
					// 'allowEmpty' => false,
					// 'required' => false,
					// 'last' => false, // Stop validation after this rule
					// 'on' => 'create', // Limit validation to 'create' or 'update' operations
					'minLength' => array (
							'rule' => array (
									'minLength',
									47 
							),
							'message' => 'Invalid slots' 
					),
					'maxLength' => array (
							'rule' => array (
									'maxLength',
									47 
							),
							'message' => 'Invalid slots' 
					),
					'pattern' => array (
							'rule' => '~^[0-1](,[0-1])*$~i',
							'message' => 'Not a valid data format',
							'last' => false 
					) 
			),
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
			),
			
			// 'message' => 'Your custom message here',
			// 'allowEmpty' => false,
			// 'required' => false,
			// 'last' => false, // Stop validation after this rule
			// 'on' => 'create', // Limit validation to 'create' or 'update' operations
			
			'ground_id' => array (
					'numeric' => array (
							'rule' => array (
									'numeric' 
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
	 * belongsTo associations
	 *
	 * @var array
	 */
	public $belongsTo = array (
			'Ground' => array (
					'className' => 'Ground',
					'foreignKey' => 'ground_id',
					'conditions' => '',
					'fields' => '',
					'order' => '' 
			) 
	);
	public function beforeSave($options = array()) {
	    if (!empty($this->data['Schedule']['date']) &&
	        !empty($this->data['Schedule']['ground_id'])
	    ) {
	    	if (!$this->id && !isset($this->data[$this->alias][$this->primaryKey])){
	    		$data = $this->find('first',array('conditions'=>array('date'=>$this->data['Schedule']['date'],'ground_id'=>$this->data['Schedule']['ground_id']),'recursive'=>-1));
	    		if(!empty($data))
	    			return false;
	    	}
	    }
	    else{
	    	return false;
	    }
	    return true;
	}
}
