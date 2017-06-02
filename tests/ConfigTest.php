<?php

namespace yodbvs;

class ConfigTest extends \PHPUnit_Framework_TestCase {
    public function testGet() {
        $cfg = Config::getInstance();
        $this->assertEquals('MySQL', $cfg->get('db.adapter'));
    }
}
