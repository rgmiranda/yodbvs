<?php

namespace yodbvs;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;

class Log {
    protected $logger;
    protected $logFile;
    private static $instance;

    private function __construct () {
        $config = Config::getInstance();
        $this->logFile = $config->get('misc.logfile');
        $this->logger = new Logger('YodbvsLogger');
        $this->logger->pushHandler(new StreamHandler($this->logFile, Logger::DEBUG));
    }

    public static function getInstance() {
        if (empty(Log::$instance)) {
            Log::$instance = new Log();
        }
        return Log::$instance;
    }

    public function log($level, $message, array $context = []) {
        $this->logger->log($level, $message, $context);
    }
}
