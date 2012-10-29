<?
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
require_once 'TDD' . DIRECTORY_SEPARATOR . 'Runner.php';

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

		return new \TDD\Configuration($aOptions);
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
		$oRunner = new \TDD\Runner($oConfig);

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
		$oRunner = new \TDD\Runner($oConfig);

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
