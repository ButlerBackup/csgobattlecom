<?php
define('START_TIME', microtime(1));
/**
 * Error constants
 */
define('ERROR_NO_BASEPATH', 'No direct script access allowed');

/**
 * The main path constants
 */
// DIR
define('_DIR_', '/');

// Path to the application folder
define('_BASEPATH_', $_SERVER['DOCUMENT_ROOT'] . _DIR_);

// The name of THIS file
define('_SELF_', pathinfo(__FILE__, PATHINFO_BASENAME));

// Path to the front controller (this file)
define('_FCPATH_', str_replace(_SELF_, '', __FILE__));

// Path to the application folder
define('_SYSDIR_', _BASEPATH_ . 'app/');

// Path to the styles folder
define('_SITEDIR_', _DIR_ . 'app/');

// URI
define('_URI_', mb_substr($_SERVER['REQUEST_URI'], mb_strlen(_DIR_) - 1));

/**
 * LOAD SYSTEM
 */
require_once _SYSDIR_ . 'system/Core.php';

$core = new Core;

define('END_TIME', microtime(1));
//echo 'Движок: '.(END_TIME-START_TIME).'<hr/>';

echo 'URI: ' . _URI_ . '<br/>';
echo 'DIR: ' . _DIR_ . '<br/>';
echo 'SELF: ' . _SELF_ . '<br/>';
echo 'SITEDIR: ' . _SITEDIR_ . '<br/>';
echo 'BASEPATH: ' . _BASEPATH_ . '<br/>';
echo 'SYSDIR: ' . _SYSDIR_ . '<br/>';
echo 'FCPATH: ' . _FCPATH_ . '<br/>';

/* End of file */