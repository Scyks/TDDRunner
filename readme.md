# TDD Runner

TDDRunner is a simple tool for faster test driven development. In test driven development your`e
refactoring the whole time and normaly on every save you have to call PHPUnit.
With TDDRunner this is now very simple because TDDRunner manage PHPUnit calls when a file change
was detected.

## Requirements

TDDRunner requires PHP 5.3 or later.

## Installation

There are currently 3 ways to get TDDRunner.

### PEAR Installation

To install TDDRunner you can run (as root user) the following two commands.

    pear channel-discover pear.ceow.de
    pear install ceow/TDDRunner

#### Linux 64 bit system

since version 1.0.2 there is an implementation of jnotify. When you're using a linux 64 bit system
you have to enable libnotify.so 64 bit version. To do that please see documentation below:

- go to TDDRunner folder

    cd /usr/share/pear/TDDRunner/jnotify-lib-0.94


- create new folder "32-bit_Linux"

    mkdir 32-bit_Linux


- move libnotify.so to 32-bit_Linux

    mv libjnotify.so 32-bit_Linux/libjnotify.so


- create symbolic link to 64bit version

    ln -s 64-bit_Linux/libjnotify.so libjnotify.so


If anyone know how to do this by default with PEAR, please tell me.

### PHP Archive (PHAR)

    wget http://pear.ceow.de/get/TDDRunner.phar
    chmod +x TDDRunner.phar

### Git Checkout

    git clone https://github.com/Scyks/TDDRunner

## Documentation

You can configure TDDRunner with the following arguments:

 * --watch-path: The destination where to check file changes
 * --test-path: the path where your tests are
 * --phpunit-path: the absolute path of PHPUni executable
 * -v,--version: print version of TDDRunner
 * -h, --help: print out the usage information
 * PHPUnit configuration. see documentation of PHPUnit

### Example:

Check recursively file changes at the directory where TDDRunner.php ist stored and calls PHPUnit in this directory

    php TDDRunner.php

Check recursively file changes in "/my/Project/Folder" and calls PHPUnit where TDDRunner.php is stored

    php TDDRunner.php --watch-path /my/Project/Folder

Check recursively file changes in "/my/Project/Folder" and calls PHPUnit in "/my/Project/Folder/Tests"

    php TDDRunner.php --watch-path /my/Project/Folder --test-path /my/Project/Folder/Tests

Defines that the phpunit executable stored in /var/

    php TDDRunner.php --phpunit-path /var/phpunit

Execute PHPUnit with option "--group=test"

    php TDDRunner.php --group=test
