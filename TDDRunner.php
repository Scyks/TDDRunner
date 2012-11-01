#!/usr/bin/env php
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

require 'TDDRunner' . DIRECTORY_SEPARATOR . 'Version.php';
require 'TDDRunner' . DIRECTORY_SEPARATOR . 'Configuration.php';
require 'TDDRunner' . DIRECTORY_SEPARATOR . 'Runner.php';

// set arguments
$aArguments = ((array_key_exists('argv', $_SERVER)) ? $_SERVER['argv'] : array());

// remove file attribute
array_shift($aArguments);

// instantiate Configuration Object
$oConfig = new \TDDRunner\Configuration($aArguments);

// instantiate Runner
$oRunner = new \TDDRunner\Runner($oConfig);

// run
$oRunner->run();