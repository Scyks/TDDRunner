<?php
/**
 * TDD Runner Configuration Object. Parses input arguments and configure TDDRunner.
 *
 * @author          Ronald Marske <r.marske@secu-ring.de>
 * @filesource      TestRunner.php
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
 * Parses Configuration
 */
class Configuration {

	/**
	 * Watch path for file changes
	 *
	 * @var string
	 */
	protected $sWatchPath = null;

	/**
	 * PHPUnit executable path
	 *
	 * @var string
	 */
	protected $sPHPUnitPath = '/usr/bin/phpunit';

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
	 * flag that defines if version information have to be shown
	 * @var bool
	 */
	protected $bVersion = false;

	/**
	 * PHPUnit Arguments
	 * @var string
	 */
	protected $sPHPUnitArguments = null;

	/**
	 * PHPUnit Arguments
	 * @var string
	 */
	protected $sExcludePath = null;

	/**
	 *
	 */
	public function __construct(array $aArguments = array()) {

		// set Defaults
		$this->sWatchPath = getenv('PWD');
		$this->sTestPath = getenv('PWD');

		// iterate arguments
		$iArguments = count($aArguments);
		for($i = 0; $i < $iArguments; $i++) {

			switch($aArguments[$i]) {

				// watch path for file changes
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

				case '--version':
				case '-v':

					// set Version
					$this->bVersion = true;

					// remove arguments
					unset($aArguments[$i]);
					break;

				case '--exclude-path':
					// set path
					$this->sExcludePath = $aArguments[++$i];

					// remove arguments
					unset($aArguments[$i - 1], $aArguments[$i]);
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
	 * flag if Help is set
	 * @return boolean
	 */
	public function getHelp() {

		return $this->bHelp;
	}

	/**
	 * flag if Version is set
	 * @return boolean
	 */
	public function getVersion() {

		return $this->bVersion;
	}

	/**
	 * return arguments for PHPUnit
	 * @return array
	 */
	public function getPHPUnitArguments() {

		return $this->sPHPUnitArguments;
	}

	/**
	 * return excluded path
	 * @return string
	 */
	public function getExcludedPath() {

		return $this->sExcludePath;
	}
}