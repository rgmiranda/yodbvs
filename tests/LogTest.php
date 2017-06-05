<?php

namespace yodbvs;

class LogTest extends \PHPUnit_Framework_TestCase {
    public function testLog() {
        $log = Log::getInstance();
        $cfg = Config::getInstance();
        $log->log('debug', 'Message', array (
            'context' => 'DEBUG'
        ));
        $this->assertFileExists($cfg->get('misc.logfile'));
    }
}
