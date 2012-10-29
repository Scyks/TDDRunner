<?php
/**
 * TDD Runner that checks file changes on given path an calls PHPUnit if a file changes.
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
 * php TDDRunner.php --watch-path /my/Project/Folder
 *    Check recursively file changes in "/my/Project/Folder" and calls PHPUnit where TDDRunner.php is stored
 * php TDDRunner.php --watch-path /my/Project/Folder --test-path /my/Project/Folder/Tests
 *    Check recursively file changes in "/my/Project/Folder" and calls PHPUnit in "/my/Project/Folder/Tests"
 * php TDDRunner.php --phpunit-path /var/phpunit
 *    defines that the phpunit executable stored in /var/
 * php TDDRunner.php --group=test
 *    same as in line 13, but configures PHPUnit with option "--group=test"
 *
 * @author          Ronald Marske <r.marske@secu-ring.de>
 * @filesource      TestRunner.php
 *
 * @copyright       Copyright (c) 2012 Ronald Marske
 *
 * @package         TDD
 */

require 'TDD' . DIRECTORY_SEPARATOR . 'Configuration.php';

// set arguments
$aArguments = ((array_key_exists('argv', $_SERVER)) ? $_SERVER['argv'] : array());

$oConfig = new TDD\Configuration($aArguments);
