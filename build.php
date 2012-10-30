#!/usr/bin/env php
<?php
/**
 * PHAR build script
 *
 * @author          Ronald Marske <r.marske@secu-ring.de>
 * @filesource      build.php
 *
 * @copyright       Copyright (c) 2012 Ronald Marske
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
$defaultStub = $phar->createDefaultStub('index.php');

$phar->buildFromDirectory(__DIR__ . '/src');

// Create a custom stub to add the shebang
$stub = "#!/usr/bin/env php \n" . $defaultStub;

// Add the stub
$phar->setStub($stub);

$phar->stopBuffering();