<?php
App::uses('Congregation', 'Model');

/**
 * Congregation Test Case
 *
 */
class CongregationTest extends CakeTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'app.congregation',
		'app.publisher',
		'app.reservation'
	);

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->Congregation = ClassRegistry::init('Congregation');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->Congregation);

		parent::tearDown();
	}

}
