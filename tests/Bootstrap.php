<?php
/**
 *
 * @author          Ronald Marske <r.marske@secu-ring.de>
 * @filesource      TestRunner.php
 *
 * @copyright       Copyright (c) 2012 Ronald M
 *
 * @package         CL.Tests
 */

// extend include path
set_include_path(
	dirname(__DIR__) . DIRECTORY_SEPARATOR . 'src' .
		PATH_SEPARATOR . get_include_path()
);
