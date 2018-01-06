<?php

namespace App\Lib;

class Config
{
    /** @var array */
    protected $config;

    /**
     * Config constructor.
     * @param array $config
     */
    public function __construct(array $config)
    {
        $this->config = $config;
    }

    /**
     * Returns config option.
     * You can use the "dot" syntax to get nested values.
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    public function get($key, $default = null)
    {
        return arrayGetItem($this->config, $key, $default);
    }

    /**
     * Returns all config options.
     * @return array
     */
    public function getAll()
    {
        return $this->config;
    }
}
