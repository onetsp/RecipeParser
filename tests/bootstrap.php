<?php

define('RECIPECLIPPER_APP_ROOT', dirname(dirname(__FILE__)));

include RECIPECLIPPER_APP_ROOT . '/lib/_autoload.php';

set_include_path( '.'
                . PATH_SEPARATOR . RECIPECLIPPER_APP_ROOT . '/lib'
                . PATH_SEPARATOR . get_include_path()
                );

