<?php

class Log {

	static public function fatal($msg, $namespace=null) {
        self::write($msg, 'FATAL', $namespace);
		exit;
	}

	static public function warning($msg, $namespace=null) {
        self::write($msg, 'WARNING', $namespace);
	}

	static public function notice($msg, $namespace=null) {
        self::write($msg, 'NOTICE', $namespace);
	}

	static public function debug($msg, $namespace=null) {
        self::write($msg, 'DEBUG', $namespace);
	}

    static private function write($msg, $level, $namespace) {
        $ns = ($namespace) ? $namespace : "-";
        $str = '[' . $level . '] [' . $ns . '] ' . $msg;

        if (!empty($_SERVER['VERBOSE'])) {
            error_log($str);
        }
    }

}
