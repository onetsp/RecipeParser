<?php

define('RECIPECLIPPER_APP_ROOT', dirname(__FILE__));

include RECIPECLIPPER_APP_ROOT . '/lib/_autoload.php';

set_include_path( '.'
                . PATH_SEPARATOR . RECIPECLIPPER_APP_ROOT . '/lib'
                . PATH_SEPARATOR . get_include_path()
                );

// Setup UTF-8 handling
@ini_set('default_charset', 'UTF-8');
@ini_set('mbstring.internal_encoding', 'UTF-8');
@ini_set('mbstring.detect_order', 'UTF-8');
@ini_set('date.timezone', 'UTC');
