#!/usr/bin/php
<?php
/**
 * TDD Runner that checks file changes on given path an calls PHPUnit if a file would changed.
 * You can configure the following things:
 *
 * - watch-path: The destination where to check file changes
 * - test-path: the path where your tests are
 * - phpunit-path: the absolute path of phpunit executable
 * - PHPUnit configuration
 *
 * Example:
 * php TDDRunner.php
 *    Check recursively file changes at the directory where TDDRunner.php ist stored and calls PHPUnit in this directory
 * php TDDRunner.php --watch-path=/my/Project/Folder
 *    Check recursively file changes in "/my/Project/Folder" and calls PHPUnit where TDDRunner.php is stored
 * php TDDRunner.php --watch-path=/my/Project/Folder --test-path=/my/Project/Folder/Tests
 *    Check recursively file changes in "/my/Project/Folder" and calls PHPUnit in "/my/Project/Folder/Tests"
 * php TDDRunner.php --phpunit-path=/var/phpunit
 *    defines that the phpunit executable stored in /var/
 * php TDDRunner.php --group=test
 *    same as in line 13, but configures PHPUnit with option "--group=test"
 *
 * @author          Ronald Marske <r.marske@secu-ring.de>
 * @filesource      TestRunner.php
 *
 * @copyright       Copyright (c) 2012 Ronald M
 *
 * @package         CL.Tests
 */
$aArguments = arguments($argv);

// STDIN / STDOUT
define('SDTOUT', fopen('php://stdout', 'w'));
define('SDTERR', fopen('php://stderr', 'w'));

// Arguments - options
define('PHPUNIT_PATH', 'phpunit-path');
define('WATCH_PATH', 'watch-path');
define('TEST_PATH', 'test-path');

// if Help - show usage
if (array_key_exists('help', $aArguments) || array_key_exists('h', $aArguments)) {
	fwrite(STDOUT, "PHPUnit TDDRunner Help.\n\n");
	fwrite(STDOUT, "usage:    php test-runner.php [PHPUnit options] [--" . PHPUNIT_PATH . "=<path>]\n");
	fwrite(STDOUT, "                              [--" . WATCH_PATH . "=<path>] [--" . TEST_PATH . "=<path>]\n");
	fwrite(STDOUT, "example:  php test-runner.php --group=myTestGroup /var/www/myProject /var/www/myProject/tests\n\n");
	fwrite(STDOUT, "   PHPUnit options           see PHPUnit documentation below\n");
	fwrite(STDOUT, "   --" . PHPUNIT_PATH . "=<path>     global path to phpunit executable\n");
	fwrite(STDOUT,
		"   --" . WATCH_PATH . "=<path>       global path to folder that been watched for write changes, default: current directory\n"
	);
	fwrite(STDOUT,
		"   --" . TEST_PATH . "=<path>        global path to folder that been watched for write changes, default: current directory\n"
	);
	fwrite(STDOUT, "   --help, -h                show this help\n");
	fwrite(STDOUT, "\n");

	exit;
}

/** check test path */
if (array_key_exists(TEST_PATH, $aArguments)) {
	if (!is_dir($aArguments[TEST_PATH])) {
		fwrite(STDERR, "\033[0;31mERROR: \033[0mgiven test-path does not exists");
		exit(1);
	}

	chdir($aArguments[TEST_PATH]);
} else {
	chdir(__DIR__);
}

/** check Watch Path */
if (array_key_exists(WATCH_PATH, $aArguments)) {
	if (!is_dir($aArguments[WATCH_PATH])) {
		fwrite(STDERR, "\033[0;31mERROR: \033[0mgiven watch-path does not exists");
		exit(1);
	}

	$sWatchPath = $aArguments[WATCH_PATH];
} else {
	$sWatchPath = __DIR__;
}

/** check phpunit path */
if (array_key_exists(PHPUNIT_PATH, $aArguments) && !is_file($aArguments[PHPUNIT_PATH])) {
	fwrite(STDERR, "\033[0;31mERROR: \033[0mgiven phpunit-path ist not a valid file");
	exit(1);
}

// object storage
$aObjects = array();

while(1) {
	$Directory = new RecursiveDirectoryIterator($sWatchPath);
	$Iterator = new RecursiveIteratorIterator($Directory);
	$Regex = new RegexIterator($Iterator, '/^.+\.php$/i', RecursiveRegexIterator::GET_MATCH);

	// flag if test should be executed
	$bExecute = false;

	// iterate over phpfiles
	foreach($Regex as $name => $object) {

		// get last changed date
		$time = filemtime($name);

		// if file not imn storage - add
		if (!array_key_exists($name, $aObjects)) {
			$aObjects[$name] = $time;

			// could be a new file - or at start - execute Tests
			$bExecute = true;

			// continue iterating
			continue;
		}

		// if file was changed
		if ($aObjects[$name] < $time) {
			$aObjects[$name] = $time;

			$bExecute = true;

			break;
		}

	}

	// if test should be executet 
	if (true == $bExecute) {
		// execute PHPUnit
		runPHPUnit($aArguments);
	}

	// important to clear statcache
	clearstatcache();

	// sleep 100 ms
	usleep(10000);
}

/**
 * Run PHP unit via popen and send output directly to STDOUT
 *
 * @param array $aArguments
 */
function runPHPUnit($aArguments) {

	// PHPUnit executable
	$sExecutable = ((array_key_exists(PHPUNIT_PATH, $aArguments)) ? $aArguments[PHPUNIT_PATH] : 'phpunit');

	// cleanup args for PHPUnit args
	$aClonedArgs = $aArguments;
	unset($aClonedArgs[PHPUNIT_PATH], $aClonedArgs[WATCH_PATH], $aClonedArgs[TEST_PATH]);

	// add Args to Executable call
	foreach($aClonedArgs as $key => $val) {
		$sExecutable .= ' --' . $key . '=' . $val;
	}

	// execute
	$h = popen($sExecutable, 'r');

	while(!feof($h)) {
		// frite directly to STDOUT
		fwrite(STDOUT, fgets($h, 2));
	}
	fclose($h);

	fwrite(STDOUT, str_repeat('=', 80) . "\n");

}

/**
 * Parse Command line arguments
 *
 * @param array $argv
 *
 * @return array
 */
function arguments($argv) {

	$ARG = array();
	array_shift($argv);
	foreach($argv as $arg) {
		if (strpos($arg, '--') === 0) {
			$compspec = explode('=', $arg);
			$key = str_replace('--', '', array_shift($compspec));
			$value = join('=', $compspec);
			$ARG[$key] = $value;
		} elseif (strpos($arg, '-') === 0) {
			$key = str_replace('-', '', $arg);
			if (!isset($ARG[$key])) {
				$ARG[$key] = true;
			}
		} else {
			$ARG[] = $arg;
		}
	}

	return $ARG;
} 