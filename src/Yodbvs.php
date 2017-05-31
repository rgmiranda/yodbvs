<?php

namespace yodbvs;

class Yodbvs {
    protected $conn;

    public function __construct ($config) {
        $this->conn = db\adapters\Adapter::createInstance($config['db']);
    }

    public function to($rev) {
        var_dump($this->conn);
    }
}