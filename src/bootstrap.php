<?php

namespace TechDivision;

define('DS', DIRECTORY_SEPARATOR);
define('PS', PATH_SEPARATOR);
define('BP', __DIR__);

$paths[] = __DIR__ . DS . 'app' . DS . 'code' . DS . 'local';
$paths[] = __DIR__ . DS . 'app' . DS . 'code' . DS . 'community';
$paths[] = __DIR__ . DS . 'app' . DS . 'code' . DS . 'core';
$paths[] = __DIR__ . DS . 'app' . DS . 'code' . DS . 'lib'; // directory for PEAR libraries
$paths[] = __DIR__ . DS . 'app' . DS . 'code' . DS . 'vendor'; // directory for Composer libraries

// set the new include path
set_include_path(implode(PS, $paths) . PS . get_include_path());

require 'TechDivision/SplClassLoader.php';

$classLoader = new SplClassLoader();
$classLoader->register();