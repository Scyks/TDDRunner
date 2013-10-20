<?php
/**
 * @author          Ronald Marske <r.marske@secu-ring.de>
 * @filesource      TDD/Runner.php
 *
 * @package         TDD
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
namespace TDDRunner;

/**
 * Tdd runner class that contains all business logic
 * - check if filechanges happens
 * - calls phpunit
 * - print informations
 */
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

	/**
	 * file list
	 * key => lastchangedtime
	 * @var array
	 */
	protected $aFiles = array();

	/**
	 * loop condition variable
	 * @var bool
	 */
	protected $bMainLoop = true;

	/**
	 * construct Runner
	 * - defines streams
	 *
	 * @param Configuration $oConfig
	 */
	public function __construct(Configuration $oConfig) {

		$this->oConfig = $oConfig;

		// set Streams
		$this->rStdErr = fopen('php://stderr', 'w');
		$this->rStdOut = fopen('php://stdout', 'w');

	}

	/**
	 * method stops the main loop by setting itteration condition to false
	 */
	public function stopMainLoop() {

		$this->bMainLoop = false;
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

		// print version
		if (true == $this->oConfig->getVersion()) {
			$this->printVersion();

			return true;
		}

		if (!is_dir(realpath($this->oConfig->getTestPath()))) {
			$this->error('The given test path does not exists');

			return false;
		} elseif (!is_dir(realpath($this->oConfig->getTestPath()) . DIRECTORY_SEPARATOR . 'tests') &
			!is_dir(realpath($this->oConfig->getTestPath()) . DIRECTORY_SEPARATOR . 'test') &&
			!is_dir(realpath($this->oConfig->getTestPath()) . DIRECTORY_SEPARATOR . 'Test') &&
			!is_dir(realpath($this->oConfig->getTestPath()) . DIRECTORY_SEPARATOR . 'Tests') &&
			!file_exists(realpath($this->oConfig->getTestPath()) . DIRECTORY_SEPARATOR . 'phpunit.xml') &&
			!file_exists(realpath($this->oConfig->getTestPath()) . DIRECTORY_SEPARATOR . 'phpunit.xml.dist') &&
			false === strpos($this->oConfig->getPHPUnitArguments(), '-c') &&
			false === strpos($this->oConfig->getPHPUnitArguments(), '--configuration')
		) {

			$this->error('The given test path does not contain folder named '
					. '"test, Test, tests, Tests" or does not contain "phpunit.xml, phpunit.xml.dist" '
					. 'or there are no configuration for PHPUnit given.'
			);

			return false;
		}

		if (!is_dir(realpath($this->oConfig->getWatchPath()))) {
			$this->error('The given watch path does not exists');

			return false;
		}

		$this->printVersion();
		$this->output("\n");

		$this->mainLoop();

	}

	/**
	 * Main loop
	 * - checks every 100 milliseconds if some files have been changed
	 *    - store last changed date
	 *    - call PHPUnit
	 */
	private function mainLoop() {

		$sLib = dirname(__FILE__) . DIRECTORY_SEPARATOR . 'jnotify-lib-0.94' . DIRECTORY_SEPARATOR;

		$sWatchPath = realpath($this->oConfig->getWatchPath());


		// open process
		$rHandler = popen('java -Djava.library.path=' . $sLib . ' -jar ' . $sLib . 'jnotify-0.94.jar ' . $sWatchPath , 'r');

		$this->output('listen on path: ' . $sWatchPath);
		$this->output("\n\n");

		$this->runPHPUnit();

		// read line
		while ($sLine = fgets($rHandler)) {


			// if created or modified
			if ('created' == substr($sLine, 0, 7) || 'modified' == substr($sLine, 0, 8)) {
				// get fielename
				$sFile = substr(strrchr($sLine, ' : '), 1, -1);

				$sPath = substr($sFile, 0, strrpos($sFile, '/'));

				// check for php files
				if ('.php' == substr($sFile, -4) && $this->oConfig->getExcludedPath() !== $sPath) {
					// execute PHPUnit
					$this->runPHPUnit();
				}

			}
		}

		pclose($rHandler);
	}

	/**
	 * Print Version Information
	 */
	private function printVersion() {

		$this->output(\TDDRunner\Version::getVersionString());
	}

	/**
	 * Print the usage information to screen
	 * @return void
	 */
	private function printUsage() {

		$this->output("PHPUnit TDDRunner Help.\n\n");
		$this->output("usage:    php test-runner.php [PHPUnit options] [--phpunit-path <path>]\n");
		$this->output("                              [--watch-path <path>] [--test-path <path>]\n");
		$this->output("example:  php test-runner.php --group=myTestGroup /var/www/myProject /var/www/myProject/tests\n\n"
		);
		$this->output("   PHPUnit options           see PHPUnit documentation below\n");
		$this->output("   --phpunit-path <path>     global path to phpunit executable\n");
		$this->output("   --watch-path <path>       global path to folder that been watched for write changes, default: current directory\n"
		);
		$this->output("   --test-path <path>        global path to folder that been watched for write changes, default: current directory\n"
		);
		$this->output("   --help, -h                show this help\n");
		$this->output("\n");
	}

	/**
	 * Method to write a string into rStdOut stream
	 *
	 * @param $sMessage
	 */
	private function output($sMessage) {

		fwrite($this->rStdOut, $sMessage);
	}

	/**
	 * Method to write a string into rStdErr stream
	 *
	 * @param $sMessage
	 */
	private function error($sMessage) {

		fwrite($this->rStdErr, "\033[0;31mERROR: \033[0m" . $sMessage);
	}

	/**
	 * run PHPUnit tests with arguments if configured
	 */
	private function runPHPUnit() {

		$sCurrDir = getcwd();

		chdir(realpath($this->oConfig->getTestPath()));

		// PHPUnit executable
		$sExecutable = $this->oConfig->getPHPUnitPath();

		// if there are arguments for PHPUnit
		if (null !== $this->oConfig->getPHPUnitArguments()) {
			$sExecutable .= ' ' . $this->oConfig->getPHPUnitArguments();
		}

		// execute PHPUnit
		$rPHPUnit = popen($sExecutable, 'r');

		while(!feof($rPHPUnit)) {

			// write directly to STDOUT
			$this->output(fgets($rPHPUnit, 2));
		}

		// close execution
		fclose($rPHPUnit);

		// output new line
		$this->output("\n");

		chdir($sCurrDir);

		// clear memory
		unset($sCurrDir, $rPHPUnit, $sExecutable);
	}
}