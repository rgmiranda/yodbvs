<?php

namespace yodbvs;

class ConfigTest extends \PHPUnit_Framework_TestCase {
    public function testGetScalar() {
        $cfg = Config::getInstance();
        $this->assertEquals('MySQL', $cfg->get('db.adapter'));
    }

    public function testGetArray() {
        $cfg = Config::getInstance();
        $this->assertEquals(array (
            'adapter'   => 'MySQL',
            'host'      => 'localhost',
            'user'      => 'root',
            'pass'      => 'sacha666',
            'dbname'    => 'sigepe',
            'port'      => '3306',
        ), $cfg->get('db'));
    }

    public function testGetNull() {
        $cfg = Config::getInstance();
        $this->assertNull($cfg->get('none'));
    }
}
