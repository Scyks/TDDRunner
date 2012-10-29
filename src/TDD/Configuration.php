<?php
/**
 * TDD Runner Configuration Object. Parses input arguments and configure TDDRunner.
 *
 * @author          Ronald Marske <r.marske@secu-ring.de>
 * @filesource      TestRunner.php
 *
 * @copyright       Copyright (c) 2012 Ronald Marske
 *
 * @package         TDD
 */

namespace TDD;

/**
 * Parses Configuration
 */
class Configuration {

	/**
	 * Watch path for filechanges
	 *
	 * @var string
	 */
	protected $sWatchPath = null;

	/**
	 * PHPUnit executable path
	 *
	 * @var string
	 */
	protected $sPHPUnitPath = 'phpunit';

	/**
	 * test folder, where to find all tests or phpunit.xml or phpunit.xml.dist file
	 *
	 * @var string
	 */
	protected $sTestPath = __DIR__;

	/**
	 * flag that defines if usage have to be shown
	 * @var bool
	 */
	protected $bHelp = false;

	/**
	 * PHPUnit Arguments
	 * @var array
	 */
	protected $aPHPUnitArguments = array();

	/**
	 *
	 */
	public function __construct(array $aArguments = array()) {

		// set Defaults
		$this->sWatchPath = dirname(dirname(dirname(__DIR__)));
		$this->sTestPath = dirname(dirname(dirname(__DIR__)));

		// iterate arguments
		for($i = 0; $i < count($aArguments); $i++) {

			switch($aArguments[$i]) {

				// watch path for file canges
				case '--watch-path':
					// set path
					$this->sWatchPath = $aArguments[++$i];

					// remove arguments
					unset($aArguments[$i--], $aArguments[$i]);

					break;

				case '--phpunit-path':
					// set path
					$this->sPHPUnitPath = $aArguments[++$i];

					// remove arguments
					unset($aArguments[$i--], $aArguments[$i]);
					break;

				case '--test-path':
					// set path
					$this->sTestPath = $aArguments[++$i];

					// remove arguments
					unset($aArguments[$i--], $aArguments[$i]);
					break;

				case '--help':
				case '-h':

					// set Help
					$this->bHelp = true;

					// remove arguments
					unset($aArguments[$i]);
					break;
			}
		}

		// set PHPUnit Arguments and reset array keys
		foreach($aArguments as $sArg) {
			$this->aPHPUnitArguments[] = $sArg;
		}

	}

	/**
	 * return watch path configuration
	 * @return string
	 */
	public function getWatchPath() {

		return $this->sWatchPath;
	}

	/**
	 * return path to PHPunit executable
	 * @return string
	 */
	public function getPHPUnitPath() {

		return $this->sPHPUnitPath;
	}

	/**
	 * returns Test destination path
	 * @return string
	 */
	public function getTestPath() {

		return $this->sTestPath;
	}

	/**
	 * flag if Help is settet
	 * @return boolean
	 */
	public function getHelp() {

		return $this->bHelp;
	}

	/**
	 * return arguments for PHPUnit
	 * @return array
	 */
	public function getPHPUnitArguments() {

		return $this->aPHPUnitArguments;
	}
}
