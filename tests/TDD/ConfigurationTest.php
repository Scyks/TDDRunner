<?php
/**
 *
 * @author          Ronald Marske <r.marske@secu-ring.de>
 * @filesource      TestRunner.php
 *
 * @copyright       Copyright (c) 2012 Ronald Marske
 *
 * @package         TDD.Tests
 */

require_once 'TDD' . DIRECTORY_SEPARATOR . 'Configuration.php';

class ConfigurationTest extends PHPUnit_Framework_TestCase {

	/**
	 * @test
	 */
	public function __construct_noArguments_ObjectCreated() {

		$oConfig = new \TDD\Configuration();
		$this->assertInstanceOf('\TDD\Configuration', $oConfig);

	}

	/**
	 * @test
	 */
	public function __construct_WatchPathArguments_WatchPathSettet() {

		$oConfig = new \TDD\Configuration(array('--watch-path', '/my/current/dir'));
		$this->assertSame('/my/current/dir', $oConfig->getWatchPath());

	}

	/**
	 * @test
	 */
	public function __construct_PHPUnitPathArguments_PHPUnitPathSettet() {

		$oConfig = new \TDD\Configuration(array('--phpunit-path', '/my/path/to/phpunit'));
		$this->assertSame('/my/path/to/phpunit', $oConfig->getPHPUnitPath());

	}

	/**
	 * @test
	 */
	public function __construct_TestPathArguments_TestPathSettet() {

		$oConfig = new \TDD\Configuration(array('--test-path', '/my/path/to/test/folder'));
		$this->assertSame('/my/path/to/test/folder', $oConfig->getTestPath());

	}

	/**
	 * @test
	 */
	public function __construct_ShortHelpArguments_HelpIsTrue() {

		$oConfig = new \TDD\Configuration(array('-h'));
		$this->assertTrue($oConfig->getHelp());

	}

	/**
	 * @test
	 */
	public function __construct_LongHelpArguments_HelpIsTrue() {

		$oConfig = new \TDD\Configuration(array('--help'));
		$this->assertTrue($oConfig->getHelp());

	}

	/**
	 * @test
	 */
	public function __construct_PHPUnitArguments_ArgumentArraySettet() {

		$oConfig = new \TDD\Configuration(array('--help', '--group=test'));
		$this->assertSame('--group=test', $oConfig->getPHPUnitArguments());

	}
}
