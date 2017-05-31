<?php

namespace yodbvs\db\adapters;

class MySQL extends Adapter {
    public function getDSN($host = false, $port = false, $dbname = false) {
        return "mysql:host={$host};dbname={$dbname}" . 
            (empty($port) ? '' : ";port{$port}");
    }
}