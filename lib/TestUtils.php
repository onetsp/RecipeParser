<?php

class TestUtils {

    static public function getDataPath($file="") {
        $path = RECIPECLIPPER_APP_ROOT . "/tests/data";
        if ($file) {
            $path .= "/" . $file;
        }
        return $path;
    }

}
