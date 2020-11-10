<?php

namespace Services;

class Configuration extends Service
{
    /**
     * @var array Configuration
     */
    protected $config;

    /**
     * @param string $configFile Name of file with configuration
     */
    public function __construct($configFile)
    {
        // TODO Handle wrong file error
        $this->config = include $configFile;
    }

    /**
     * Magic method to access 1st level properties of configuration
     *
     * @param $name 1st-level key of configuration array
     * @return mixed|null
     */
    public function __get($name)
    {
        if (array_key_exists($name, $this->config)) {
            return $this->config[$name];
        }
        return null;
    }
}