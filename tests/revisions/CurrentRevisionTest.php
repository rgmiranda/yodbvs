<?php

namespace yodbvs\revisions;
use \yodbvs\Config;

class CurrentRevisionTest extends \PHPUnit_Framework_TestCase {
    protected $filename;
    protected $tmpfilename;

    protected function setUp() {
        $this->filename = Config::getInstance()->get('misc.datafile');
        if (file_exists($this->filename)) {
            $this->tmpfilename = tempnam(sys_get_temp_dir(), 'yodbvs-');
            file_put_contents($this->tmpfilename, file_get_contents($this->filename));
            unlink($this->filename);
        }
    }

    protected function tearDown() {
        if (!empty($this->tmpfilename)) {
            file_put_contents($this->filename, file_get_contents($this->tmpfilename));
            unlink($this->tmpfilename);
        }
    }

    public function testGetNull() {
        $curr = CurrentRevision::getInstance();
        if (file_exists($this->filename)) {
            unlink($this->filename);
        }
        $this->assertNull($curr->get());
    }

    public function testGet() {
        $curr = CurrentRevision::getInstance();
        file_put_contents($this->filename, '46');
        $this->assertEquals(46, $curr->get());
    }

    public function testSet() {
        $curr = CurrentRevision::getInstance();
        $curr->set(789);
        $this->assertEquals(789, $curr->get());
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testSetNegative() {
        $curr = CurrentRevision::getInstance();
        $curr->set(-789);
    }
}
