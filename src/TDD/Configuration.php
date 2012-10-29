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
	 * @var string
	 */
	protected $sPHPUnitArguments = null;

	/**
	 *
	 */
	public function __construct(array $aArguments = array()) {

		// set Defaults
		$this->sWatchPath = dirname(dirname(dirname(__DIR__)));
		$this->sTestPath = dirname(dirname(dirname(__DIR__)));

		// iterate arguments
		$iArguments = count($aArguments);
		for($i = 0; $i < $iArguments; $i++) {

			switch($aArguments[$i]) {

				// watch path for file canges
				case '--watch-path':
					// set path
					$this->sWatchPath = $aArguments[++$i];
					// remove arguments
					unset($aArguments[$i - 1], $aArguments[$i]);

					break;

				case '--phpunit-path':
					// set path
					$this->sPHPUnitPath = $aArguments[++$i];

					// remove arguments
					unset($aArguments[$i - 1], $aArguments[$i]);
					break;

				case '--test-path':
					// set path
					$this->sTestPath = $aArguments[++$i];

					// remove arguments
					unset($aArguments[$i - 1], $aArguments[$i]);
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
		$this->sPHPUnitArguments = implode(' ', $aArguments);

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

		return $this->sPHPUnitArguments;
	}
}
