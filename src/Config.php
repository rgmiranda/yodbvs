<?php

namespace yodbvs;

class Config {
    private static $instance = NULL;
    private $configParams;
    private function __construct () {
        $configParams = include implode(DIRECTORY_SEPARATOR, array (__DIR__, '..', 'config.php'));
    }

    public static function getInstance() {
        if (empty(Config::$instance)) {
            Config::$instance = new Config();
        }
        return Config::$instance;
    }

    public function get($prop) {
        $props = explode('.', $prop);
        $currentGroup = $this->configParams;
        while (count($props) > 0) {
            $key = array_shit($props);
            if (!is_array($currentGroup)) {
                return NULL;
            }
            if (!array_key_exists($key, $currentGroup)) {
                return NULL;
            }
            $currentGroup = $currentGroup[$key];
        }
        return $currentGroup;
    }
}