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
     * Возвращает определенную настройку.
     * @param string $key Настройка, которую требуется получить.
     * @param mixed $default Значение по умолчанию.
     * @return mixed
     */
    public function get($key, $default = null)
    {
        return getArrayItem($this->config, $key, $default);
    }

    /**
     * Возвращает массив настроек.
     * @return array
     */
    public function getAll()
    {
        return $this->config;
    }
}
