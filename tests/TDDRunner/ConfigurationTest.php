<?php
/**
 *
 * @author          Ronald Marske <r.marske@secu-ring.de>
 * @filesource      TestRunner.php
 *
 * @package         TDDRunner.Tests
 *
 * @copyright       Copyright (c) 2012 Ronald Marske, All rights reserved.
 *
 * Redistribution and use in source and binary forms, with or without
 * modification, are permitted provided that the following conditions
 * are met:
 *
 *     * Redistributions of source code must retain the above copyright
 *       notice, this list of conditions and the following disclaimer.
 *
 *     * Redistributions in binary form must reproduce the above copyright
 *       notice, this list of conditions and the following disclaimer in
 *       the documentation and/or other materials provided with the
 *       distribution.
 *
 *     * Neither the name of Ronald Marske nor the names of his
 *       contributors may be used to endorse or promote products derived
 *       from this software without specific prior written permission.
 *
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS
 * "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT
 * LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS
 * FOR A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE
 * COPYRIGHT OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT,
 * INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING,
 * BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES;
 * LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER
 * CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT
 * LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN
 * ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE
 * POSSIBILITY OF SUCH DAMAGE.
 */

require_once 'TDDRunner' . DIRECTORY_SEPARATOR . 'Configuration.php';

class ConfigurationTest extends PHPUnit_Framework_TestCase {

	/**
	 * @test
	 */
	public function __construct_noArguments_ObjectCreated() {

		$oConfig = new \TDDRunner\Configuration();
		$this->assertInstanceOf('\TDDRunner\Configuration', $oConfig);

	}

	/**
	 * @test
	 */
	public function __construct_WatchPathArguments_WatchPathSettet() {

		$oConfig = new \TDDRunner\Configuration(array('--watch-path', '/my/current/dir'));
		$this->assertSame('/my/current/dir', $oConfig->getWatchPath());

	}

	/**
	 * @test
	 */
	public function __construct_PHPUnitPathArguments_PHPUnitPathSettet() {

		$oConfig = new \TDDRunner\Configuration(array('--phpunit-path', '/my/path/to/phpunit'));
		$this->assertSame('/my/path/to/phpunit', $oConfig->getPHPUnitPath());

	}

	/**
	 * @test
	 */
	public function __construct_TestPathArguments_TestPathSettet() {

		$oConfig = new \TDDRunner\Configuration(array('--test-path', '/my/path/to/test/folder'));
		$this->assertSame('/my/path/to/test/folder', $oConfig->getTestPath());

	}

	/**
	 * @test
	 */
	public function __construct_ShortHelpArguments_HelpIsTrue() {

		$oConfig = new \TDDRunner\Configuration(array('-h'));
		$this->assertTrue($oConfig->getHelp());

	}

	/**
	 * @test
	 */
	public function __construct_LongHelpArguments_HelpIsTrue() {

		$oConfig = new \TDDRunner\Configuration(array('--help'));
		$this->assertTrue($oConfig->getHelp());

	}

	/**
	 * @test
	 */
	public function __construct_ShortVersionArguments_HelpIsTrue() {

		$oConfig = new \TDDRunner\Configuration(array('-v'));
		$this->assertTrue($oConfig->getVersion());

	}

	/**
	 * @test
	 */
	public function __construct_LongVersionArguments_HelpIsTrue() {

		$oConfig = new \TDDRunner\Configuration(array('--version'));
		$this->assertTrue($oConfig->getVersion());

	}

	/**
	 * @test
	 */
	public function __construct_PHPUnitArguments_ArgumentArraySettet() {

		$oConfig = new \TDDRunner\Configuration(array('--help', '--group=test'));
		$this->assertSame('--group=test', $oConfig->getPHPUnitArguments());

	}

	/**
	 * @test
	 */
	public function __construct_TestPathArguments_TestExcludePathSet() {

		$oConfig = new \TDDRunner\Configuration(array('--exclude-path', '/my/path/to/test/folder'));
		$this->assertSame('/my/path/to/test/folder', $oConfig->getExcludedPath());

	}
}