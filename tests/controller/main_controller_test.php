<?php
/**
*
* Board Rules extension for the phpBB Forum Software package.
*
* @copyright (c) 2014 phpBB Limited <https://www.phpbb.com>
* @license GNU General Public License, version 2 (GPL-2.0)
*
*/

namespace phpbb\boardrules\tests\controller;

class main_controller_test extends \phpbb_test_case
{
	/**
	* Test data for the test_display() function
	*
	* @return array Array of test data
	*/
	public function display_data()
	{
		return array(
			array(200, 'boardrules_controller.html'),
		);
	}

	/**
	* Test controller display
	*
	* @dataProvider display_data
	*/
	public function test_display($status_code, $page_content)
	{
		global $config, $user, $phpbb_dispatcher, $phpbb_root_path, $phpEx;

		// Global vars called upon during execution
		$config = new \phpbb\config\config(array('boardrules_enable' => 1));
		$user = new \phpbb\user('\phpbb\datetime');
		$user->data['lang_id'] = 1;
		$phpbb_dispatcher = new \phpbb_mock_event_dispatcher();

		// Mock the rule operator and return an empty array for get_rules method
		$rule_operator = $this->getMockBuilder('\phpbb\boardrules\operators\rule')
			->disableOriginalConstructor()
			->getMock();
		$rule_operator->expects($this->any())
			->method('get_rules')
			->will($this->returnValue(array()));

		$controller = new \phpbb\boardrules\controller\main_controller(
			$config,
			new \phpbb\boardrules\tests\mock\controller_helper(),
			$rule_operator,
			new \phpbb\boardrules\tests\mock\template(),
			$this->getMock('\phpbb\user', array(), array('\phpbb\datetime')),
			$phpbb_root_path,
			$phpEx
		);

		$response = $controller->display();
		$this->assertInstanceOf('\Symfony\Component\HttpFoundation\Response', $response);
		$this->assertEquals($status_code, $response->getStatusCode());
		$this->assertEquals($page_content, $response->getContent());
	}
}
