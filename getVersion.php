<?php
/**
 * retrieve the current version, because phing
 * cant add a regexfilter to properties
 *
 * If someone knows, how to do that, please let me know.
 *
 * @author          Ronald Marske <r.marske@secu-ring.de>
 * @filesource      getVersion.php
 *
 * @copyright       Copyright (c) 2012 Ronald Marske
 *
 */

$sVersion = `php src/index.php --version`;

if (preg_match('/([0-9\.]+)/', $sVersion, $aMatch)) {
	echo $aMatch[1];
}