<?php
/**
 * ReservationFixture
 *
 */
class ReservationFixture extends CakeTestFixture {

/**
 * Fields
 *
 * @var array
 */
	public $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'primary'),
		'congregation_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'index'),
		'day' => array('type' => 'date', 'null' => false, 'default' => null),
		'timeslot_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'index'),
		'publisher1_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'index'),
		'publisher2_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'index'),
		'guestname' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 100, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'indexes' => array(
			'PRIMARY' => array('column' => 'id', 'unique' => 1),
			'fk_reservation_timeslot_idx' => array('column' => 'timeslot_id', 'unique' => 0),
			'fk_reservation_user1_idx' => array('column' => 'publisher1_id', 'unique' => 0),
			'fk_reservation_user2_idx' => array('column' => 'publisher2_id', 'unique' => 0),
			'fk_reservations_congregations_idx' => array('column' => 'congregation_id', 'unique' => 0)
		),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB')
	);

/**
 * Records
 *
 * @var array
 */
	public $records = array(
		array(
			'id' => 1,
			'congregation_id' => 1,
			'day' => '2014-09-26',
			'timeslot_id' => 1,
			'publisher1_id' => 1,
			'publisher2_id' => 1,
			'guestname' => 'Lorem ipsum dolor sit amet'
		),
	);

}
