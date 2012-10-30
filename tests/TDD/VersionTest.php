<?php
/**
 *
 * @author          Ronald Marske <r.marske@secu-ring.de>
 * @filesource      Tdd/Versionr.php
 *
 * @copyright       Copyright (c) 2012 Ronald Marske
 *
 * @package         TDD
 */

namespace TDD;

require_once 'TDD' . DIRECTORY_SEPARATOR . 'Version.php';
/**
 * Version Test
 */
class VersionTest extends \PHPUnit_Framework_TestCase {

	/**
	 * @test
	 */
	public function getVersionString_retrieveCurrentVersion_versionTagReturned() {

		$this->assertSame('TDDRunner 1.0.0 by Ronald Marske', \TDD\Version::getVersionString());
	}
}