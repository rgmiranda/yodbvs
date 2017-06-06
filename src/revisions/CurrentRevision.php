<?php

namespace yodbvs\revisions;
use \yodbvs\Config;

class CurrentRevision {
    private $filename;
    private static $instance = NULL;

    private function __construct () {
        $cfg = Config::getInstance();
        $this->filename = $cfg->get('misc.datafile');
    }

    public static function getInstance() {
        if (empty(CurrentRevision::$instance)) {
            CurrentRevision::$instance = new CurrentRevision();
        }
        return CurrentRevision::$instance;
    }

    public function get() {
        if (!file_exists($this->filename)) {
            return NULL;
        }
        if (!is_readable($this->filename)) {
            throw new RuntimeException("El archivo de datos no es legible");
        }
        return intval(file_get_contents($this->filename));
    }

    public function set($rev) {
        $rev = intval($rev);
        if ($rev <= 0) {
            throw new InvalidArgumentException('El número de revisión debe ser positivo');
        }
        return file_put_contents($this->filename, intval($rev));
    }
}