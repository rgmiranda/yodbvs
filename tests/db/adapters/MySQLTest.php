<?php

namespace yodbvs\db\adapters;
use yodbvs\Config;

class MySQLTest extends \PHPUnit_Framework_TestCase {
    public function testConnection() {
        $cfg = Config::getInstance();
        $dbCfg = $cfg->get('db');
        $adpt = Adapter::createInstance($dbCfg);
        $this->assertNotNull($adpt);
    }
}
