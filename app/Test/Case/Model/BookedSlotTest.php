<?php
App::uses ( 'BookedSlot', 'Model' );

/**
 * BookedSlot Test Case
 */
class BookedSlotTest extends CakeTestCase {
	
	/**
	 * Fixtures
	 *
	 * @var array
	 */
	public $fixtures = array (
			'app.booked_slot',
			'app.booking',
			'app.ground' 
	);
	
	/**
	 * setUp method
	 *
	 * @return void
	 */
	public function setUp() {
		parent::setUp ();
		$this->BookedSlot = ClassRegistry::init ( 'BookedSlot' );
	}
	
	/**
	 * tearDown method
	 *
	 * @return void
	 */
	public function tearDown() {
		unset ( $this->BookedSlot );
		
		parent::tearDown ();
	}
}
