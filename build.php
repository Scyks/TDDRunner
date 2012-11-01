#!/usr/bin/env php
<?php
/**
 * PHAR build script
 *
 * @author          Ronald Marske <r.marske@secu-ring.de>
 * @filesource      build.php
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
 *
 */

$aDefaultArgs = array(
	'name'    => 'TDDRunner',
	'version' => 'x.x.x',
);

$aArguments = getopt('', array('name:', 'version:'));
$aArguments = array_merge($aDefaultArgs, $aArguments);

$sName = sprintf('%s-%s.phar', $aArguments['name'], $aArguments['version']);

$buildRoot = __DIR__ . DIRECTORY_SEPARATOR . 'build';

$phar = new Phar($buildRoot . DIRECTORY_SEPARATOR . $sName);

$phar->startBuffering();
// Get the default stub. You can create your own if you have specific needs
$stub = $phar->createDefaultStub('TDDRunner.php');

$phar->buildFromDirectory(__DIR__ . '/TDDRunner');
$phar->addFile('TDDRunner.php');

// Create a custom stub to add the shebang
$stub = "#!/usr/bin/env php \n" . $stub;

// Add the stub
$phar->setStub($stub);

$phar->stopBuffering();