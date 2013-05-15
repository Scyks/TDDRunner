<?
/**
 *
 * @author          Ronald Marske <r.marske@secu-ring.de>
 * @filesource      TestRunner.php
 *
 * @package         TDD.Tests
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
require_once 'TDDRunner' . DIRECTORY_SEPARATOR . 'Runner.php';
require_once 'TDDRunner' . DIRECTORY_SEPARATOR . 'Version.php';

class RunnerTest extends PHPUnit_Framework_TestCase {

	protected $stdOut = null;

	/**
	 * Tear down method - calls after each test
	 */
	public function tearDown() {

		if (null !== $this->stdOut) {
			fclose($this->stdOut);
		}
	}

	/**
	 * get a configuration object
	 *
	 * @param array $aOptions
	 *
	 * @return TDD\Configuration
	 */
	protected function getConfig(array $aOptions = array()) {

		return new \TDDRunner\Configuration($aOptions);
	}

	/**
	 * return an instance of \TDD\Runner
	 *
	 * @param array $aOptions
	 *
	 * @return TDD\Runner
	 */
	protected function getRunner(array $aOptions = array()) {

		$oConfig = $this->getConfig($aOptions);
		$oRunner = new \TDDRunner\Runner($oConfig);

		$this->stdOut = fopen('php://memory', 'rw');

		$streamProp = new ReflectionProperty($oRunner, 'rStdOut');
		$streamProp->setAccessible(true);
		$streamProp->setValue($oRunner, $this->stdOut);

		$streamProp = new ReflectionProperty($oRunner, 'rStdErr');
		$streamProp->setAccessible(true);
		$streamProp->setValue($oRunner, $this->stdOut);

		return $oRunner;
	}

	/**
	 * @test
	 */
	public function __construct_testIfAllVariablesSettet() {

		$oConfig = $this->getConfig();
		$oRunner = new \TDDRunner\Runner($oConfig);

		$this->assertAttributeEquals($oConfig, 'oConfig', $oRunner);
		$this->assertAttributeInternalType('resource', 'rStdErr', $oRunner);
		$this->assertAttributeInternalType('resource', 'rStdOut', $oRunner);
	}

	/**
	 * @test
	 */
	public function printUsage_provideOptionHelp_getUsage() {

		$oRunner = $this->getRunner(array('-h'));

		// Main Loop
		$oRunner->run();

		fseek($this->stdOut, 0);

		$sUsage = "PHPUnit TDDRunner Help.\n\n"
			. "usage:    php test-runner.php [PHPUnit options] [--phpunit-path <path>]\n"
			. "                              [--watch-path <path>] [--test-path <path>]\n"
			. "example:  php test-runner.php --group=myTestGroup /var/www/myProject /var/www/myProject/tests\n\n"
			. "   PHPUnit options           see PHPUnit documentation below\n"
			. "   --phpunit-path <path>     global path to phpunit executable\n"
			. "   --watch-path <path>       global path to folder that been watched for write changes, default: current directory\n"
			. "   --test-path <path>        global path to folder that been watched for write changes, default: current directory\n"
			. "   --help, -h                show this help\n\n";

		$this->assertSame($sUsage, stream_get_contents($this->stdOut));

	}

	/**
	 * @test
	 */
	public function printVersion_provideOptionVersin_getVersionInformation() {

		$oRunner = $this->getRunner(array('-v'));

		// Main Loop
		$oRunner->run();

		fseek($this->stdOut, 0);

		$sUsage = \TDDRunner\Version::getVersionString();

		$this->assertSame($sUsage, stream_get_contents($this->stdOut));

	}

	/**
	 * @test
	 */
	public function run_TestPathFolderThatDoesNotExists_ShowMessageToStdErr() {

		$oRunner = $this->getRunner(array('--test-path', '/my/project/test/path/that/does/not/exists'));

		// Main Loop
		$oRunner->run();

		fseek($this->stdOut, 0);

		$this->assertSame("\033[0;31mERROR: \033[0mThe given test path does not exists",
			stream_get_contents($this->stdOut)
		);

	}

	/**
	 * @test
	 */
	public function run_TestPathFolderThatDoesNotContainTests_ShowMessageToStdErr() {

		$oRunner = $this->getRunner(array('--test-path', __DIR__));

		// Main Loop
		$oRunner->run();

		fseek($this->stdOut, 0);

		$this->assertSame("\033[0;31mERROR: \033[0mThe given test path does not contain folder named "
				. "\"test, Test, tests, Tests\" or does not contain \"phpunit.xml, phpunit.xml.dist\" "
				. "or there are no configuration for PHPUnit given.",
			stream_get_contents($this->stdOut)
		);

	}

	/**
	 * @test
	 */
	public function run_WatchPathFolderThatDoesNotExists_ShowMessageToStdErr() {

		$oRunner = $this->getRunner(array('--watch-path', '/my/project/test/path/that/does/not/exists'));

		// Main Loop
		$oRunner->run();

		fseek($this->stdOut, 0);

		$this->assertSame("\033[0;31mERROR: \033[0mThe given watch path does not exists",
			stream_get_contents($this->stdOut)
		);

	}
}
