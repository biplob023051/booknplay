<?php
App::uses ( 'Schedule', 'Model' );

/**
 * Schedule Test Case
 */
class ScheduleTest extends CakeTestCase {
	
	/**
	 * Fixtures
	 *
	 * @var array
	 */
	public $fixtures = array (
			'app.schedule',
			'app.ground',
			'app.user',
			'app.booked_slot',
			'app.booking' 
	);
	
	/**
	 * setUp method
	 *
	 * @return void
	 */
	public function setUp() {
		parent::setUp ();
		$this->Schedule = ClassRegistry::init ( 'Schedule' );
	}
	
	/**
	 * tearDown method
	 *
	 * @return void
	 */
	public function tearDown() {
		unset ( $this->Schedule );
		
		parent::tearDown ();
	}
}
