<?php
App::uses ( 'Asdf', 'Model' );

/**
 * Asdf Test Case
 */
class AsdfTest extends CakeTestCase {
	
	/**
	 * Fixtures
	 *
	 * @var array
	 */
	public $fixtures = array (
			'app.asdf' 
	);
	
	/**
	 * setUp method
	 *
	 * @return void
	 */
	public function setUp() {
		parent::setUp ();
		$this->Asdf = ClassRegistry::init ( 'Asdf' );
	}
	
	/**
	 * tearDown method
	 *
	 * @return void
	 */
	public function tearDown() {
		unset ( $this->Asdf );
		
		parent::tearDown ();
	}
}
