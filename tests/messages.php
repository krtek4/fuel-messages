<?php

namespace Messages;

/**
 * Messages class tests
 * @group Messages
 */
class Tests_Messages extends \Fuel\Core\TestCase {
	/**
	 * @test
	 */
	public function test_forge() {
		$m1 = Messages::forge();
		$this->assertTrue($m1 instanceof Messages);

		$m2 = Messages::forge('test');
		$this->assertTrue($m2 instanceof Messages);

		$this->assertNotEquals($m1, $m2);
	}

	/**
	 * @test
	 */
	public function test_forge2() {
		$m1 = Messages::forge('test2');
		$m2 = Messages::forge('test2');
		$this->assertEquals($m1, $m2);
	}

	/**
	 * @test
	 */
	public function test_instance() {
		// already exists
		$m1 = Messages::instance();
		$this->assertTrue($m1 instanceof Messages);

		// new one
		$m2 = Messages::instance('test3');
		$this->assertTrue($m2 instanceof Messages);

		$this->assertNotEquals($m1, $m2);
	}

	/**
	 * @test
	 */
	public function test_forge_instance() {
		// test default name
		$m1 = Messages::forge();
		$m2 = Messages::instance();
		$this->assertEquals($m1, $m2);

		// new one
		$m1 = Messages::forge('test4');
		$m2 = Messages::instance('test4');
		$this->assertEquals($m1, $m2);
	}

	/**
	 * @test
	 */
	public function test_message() {
		$m = Messages::instance('test5');
		$m->message('success', 'Test message');
	}

	/**
	 * @test
	 */
	public function test_get() {
		$data = 'Test message';

		$m = Messages::instance('test6');
		$m->message('success', $data);

		$result = $m->get('success', false);
		$this->assertEquals(count($result), 1);
		$this->assertEquals($result[0], $data);
	}

	/**
	 * @test
	 */
	public function test_get2() {
		$data = 'Test message';
		$data2 = 'Second test message';

		$m = Messages::instance('test7');
		$m->message('success', $data);
		$m->message('success', $data2);

		$result = $m->get('success');
		$this->assertEquals(count($result), 2);
		$this->assertEquals($result[0], $data);
		$this->assertEquals($result[1], $data2);
	}

	/**
	 * @test
	 */
	public function test_get3() {
		$data = 'Test message';
		$data2 = 'Second test message';

		$m = Messages::instance('test8');
		$m->message('success', $data);
		$m->message('success', $data2);

		$result = $m->get('success', false);
		$this->assertEquals(count($result), 2);
		$result = $m->get('success');
		// data should still be here
		$this->assertEquals(count($result), 2);
		$result = $m->get('success');
		// data should be cleared
		$this->assertEquals(count($result), 0);
	}

	/**
	 * @test
	 */
	public function test_get_all() {
		$data = 'Test message';
		$data2 = 'Second test message';

		$m = Messages::instance('test9');
		$m->message('success', $data);
		$m->message('error', $data2);
		$result = $m->get();
		// 6 groups total
		$this->assertEquals(count($result), 6);
		foreach($result as $name => $messages)
			if($name == 'success' || $name == 'error')
				$this->assertEquals(count($messages), 1);
			else
				$this->assertEquals(count($messages), 0);

		// each group should be cleared now
		$result = $m->get();
		foreach($result as $name => $messages)
				$this->assertEquals(count($messages), 0);
	}

	/**
	 * @test
	 */
	public function test_clear() {
		$data = 'Test message';
		$data2 = 'Second test message';

		$m = Messages::instance('test10');
		$m->message('success', $data);
		$m->message('success', $data2);

		$m->clear('success');
		$result = $m->get('success');
		$this->assertEquals(count($result), 0);
	}

	/**
	 * @test
	 */
	public function test_clear_all() {
		$data = 'Test message';
		$data2 = 'Second test message';

		$m = Messages::instance('test11');
		$m->message('success', $data);
		$m->message('error', $data2);

		$m->clear();
		$result = $m->get();
		// 6 groups total
		$this->assertEquals(count($result), 6);
		foreach($result as $name => $messages)
				$this->assertEquals(count($messages), 0);
	}

	/**
	 * @test
	 */
	public function test_session() {
		$data = 'Test message';
		$data2 = 'Second test message';

		$m = Messages::instance('test12');
		$this->assertNull(\Session::get('messages.test12.success', null), null);
		$m->message('success', $data);
		$this->assertNotEquals(\Session::get('messages.test12.success', null), null);
		$m->clear('success');
		$this->assertNull(\Session::get('messages.test12.success', null), null);
		$m->message('success', $data);
		$m->clear();
		$this->assertNull(\Session::get('messages.test12.success', null), null);
	}

	/**
	 * @test
	 */
	public function test_show() {
		$data = 'Test message';

		$m = Messages::instance('test13');
		$this->assertEquals(strlen($m->show()), 0);
		$m->message('success', $data);
		$this->assertNotEquals(strlen($m->show()), 0);
		$m->message('success', $data);
		$this->assertNotEquals(strlen($m->show('success')), 0);
	}

	/**
	 * @test
	 * @expectedException OutOfRangeException
	 */
	public function test_wrong_group_message() {
		$m = Messages::instance('test13');
		$m->message('toto', '');
	}

	/**
	 * @test
	 * @expectedException OutOfRangeException
	 */
	public function test_wrong_group_get() {
		$m = Messages::instance('test13');
		$m->get('toto', '');
	}

	/**
	 * @test
	 * @expectedException OutOfRangeException
	 */
	public function test_wrong_group_show() {
		$m = Messages::instance('test13');
		$m->show('toto', '');
	}

	/**
	 * @test
	 * @expectedException OutOfRangeException
	 */
	public function test_wrong_group_clear() {
		$m = Messages::instance('test13');
		$m->clear('toto', '');
	}
}