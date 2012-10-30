<?php
/**
 *
 * @author          Ronald Marske <r.marske@secu-ring.de>
 * @filesource      TDD/Version.php
 *
 * @copyright       Copyright (c) 2012 Ronald Marske
 *
 * @package         TDD
 */

namespace TDD;

/**
 * contains version information
 */
class Version {

	/**
	 * version
	 * @var string
	 */
	const VERSION = '1.0.0';

	/**
	 * returns the Version information
	 * @return string
	 */
	public static function getVersionString() {

		return 'TDDRunner ' . self::VERSION . ' by Ronald Marske';
	}
}

