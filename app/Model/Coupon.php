<?php
App::uses ( 'AppModel', 'Model' );
/**
 * Coupon Model
 */
class Coupon extends AppModel {

	// default category
	public $userOptions;
	public $statusOptions;
	public function __construct($id = false , $table = null , $ds = null ){
		parent::__construct($id,$table,$ds);
		// initialize gender constant
		$this->userOptions = array('1' => __('New User'), '2' => __('Registered User'));
		$this->statusOptions = array('1' => __('Active'), '2' => __('Inactive'));
	}

	public $validate = array(
        'code' => array(
            'notempty' => array(
                'rule'       => 'notEmpty',
                'message'    => 'Coupon code is required',
                'allowEmpty' => false,
                'required'   => false,
            ),
            'unique' => array(
				'rule' => array('isUnique'),
				'message' => 'Already Used Coupon Code',
				'required' =>  false,
				'allowEmpty'=> false,
			),
        ),
        'amount' => array(
            'notempty' => array(
                'rule'       => 'notEmpty',
                'message'    => 'Discount amount is required',
                'allowEmpty' => false,
                'required'   => false,
            )
        )
    );
}
