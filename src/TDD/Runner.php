<?php
/**
 * @author          Ronald Marske <r.marske@secu-ring.de>
 * @filesource      TDD/Runner.php
 *
 * @copyright       Copyright (c) 2012 Ronald Marske
 *
 * @package         TDD
 */
namespace TDD;

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
		}

		if (!is_dir(realpath($this->oConfig->getWatchPath()))) {
			$this->error('The given watch path does not exists');

			return false;
		}

		// can't test that
		/*if (!is_file($this->oConfig->getPHPUnitPath())) {
			$this->error('The given PHPUnit executable does not exists');
			return false;
		}*/

		//echo realpath($this->oConfig->getTestPath());
		//echo "\n";
		//echo realpath($this->oConfig->getWatchPath());
		//echo "\n";
		//echo realpath($this->oConfig->getPHPUnitPath());
		//echo "\n";

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

		// main loop
		while(true == $this->bMainLoop) {

			// Directory Iterator
			$oDirectory = new \RecursiveDirectoryIterator(realpath($this->oConfig->getWatchPath()));
			$oIterator = new \RecursiveIteratorIterator($oDirectory);
			$oRegexIterator = new \RegexIterator($oIterator, '/^.+\.php$/i', \RecursiveRegexIterator::GET_MATCH);

			// flag if test should be executed
			$bExecute = false;

			// iterate over phpfiles
			foreach($oRegexIterator as $sFilename => $oFile) {

				// get last changed date
				$iTime = filemtime($sFilename);

				// if file not imn storage - add
				if (!array_key_exists($sFilename, $this->aFiles)) {
					$this->aFiles[$sFilename] = $iTime;

					// could be a new file - or at start - execute Tests
					$bExecute = true;

					// continue iterating
					continue;
				}

				// if file was changed
				if ($this->aFiles[$sFilename] < $iTime) {
					$this->aFiles[$sFilename] = $iTime;

					// mark execution
					$bExecute = true;

				}

			}

			// if test should be executet
			if (true == $bExecute) {
				// execute PHPUnit
				$this->runPHPUnit();
			}

			// important to clear statcache
			clearstatcache();

			// sleep 100 ms
			usleep(10000);

			unset($bExecute, $iTime, $sFilename, $oFile, $oDirectory, $oIterator, $oRegexIterator);

		}
	}

	/**
	 * Print Version Information
	 */
	private function printVersion() {

		$this->output(\TDD\Version::getVersionString() . "\n");
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
