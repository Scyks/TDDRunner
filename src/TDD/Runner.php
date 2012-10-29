<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Scyks
 * Date: 28.10.12
 * Time: 16:39
 * To change this template use File | Settings | File Templates.
 */
namespace TDD;

class Runner {

	/**
	 * Configuration Object
	 * @var Configuration
	 */
	protected $oConfig = null;

	/**
	 * standard error resource
	 * @var resource
	 */
	protected $rStdErr = null;

	/**
	 * standard output stream
	 * @var resource
	 */
	protected $rStdOut = null;

	public function __construct(Configuration $oConfig) {

		$this->oConfig = $oConfig;

		// set Streams
		$this->rStdErr = fopen('php://stderr', 'w');
		$this->rStdOut = fopen('php://stdout', 'w');

	}

	/**
	 * main loop
	 * @return bool
	 */
	public function run() {

		// if config
		if (true == $this->oConfig->getHelp()) {
			$this->printUsage();
			return true;
		}

		if (!is_dir($this->oConfig->getTestPath())) {
			$this->error('The given test path does not exists');
			return false;
		}

		if (!is_dir($this->oConfig->getWatchPath())) {
			$this->error('The given watch path does not exists');
			return false;
		}

		if (!is_file($this->oConfig->getPHPUnitPath())) {
			$this->error('The given PHPUnit executable does not exists');
			return false;
		}
	}

	/**
	 * Print the usage information to screen
	 * @return void
	 */
	public function printUsage() {
		$this->output("PHPUnit TDDRunner Help.\n\n");
		$this->output("usage:    php test-runner.php [PHPUnit options] [--phpunit-path <path>]\n");
		$this->output("                              [--watch-path <path>] [--test-path <path>]\n");
		$this->output("example:  php test-runner.php --group=myTestGroup /var/www/myProject /var/www/myProject/tests\n\n");
		$this->output("   PHPUnit options           see PHPUnit documentation below\n");
		$this->output("   --phpunit-path <path>     global path to phpunit executable\n");
		$this->output("   --watch-path <path>       global path to folder that been watched for write changes, default: current directory\n");
		$this->output("   --test-path <path>        global path to folder that been watched for write changes, default: current directory\n");
		$this->output("   --help, -h                show this help\n");
		$this->output("\n");
	}

	/**
	 * Method to write a string into rStdOut stream
	 * @param $sMessage
	 */
	public function output($sMessage) {
		fwrite($this->rStdOut, $sMessage);
	}

	/**
	 * Method to write a string into rStdErr stream
	 * @param $sMessage
	 */
	public function error($sMessage) {
		fwrite($this->rStdErr, "\033[0;31mERROR: \033[0m" . $sMessage);
	}
}
