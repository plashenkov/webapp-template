<?php

namespace App\Lib;

class Config
{
    /** @var array */
    protected $config;

    /** @var array */
    protected $concatenatedConfig;

    /** @var string */
    protected $concatenationDelimiter = '.';

    /**
     * Config constructor.
     * @param array $config
     */
    public function __construct(array $config)
    {
        $this->config = $config;
        $this->concatenateConfig($config);
    }

    /**
     * Возвращает определенную настройку.
     * @param string $key Настройка, которую требуется получить.
     * @param mixed $default Значение по умолчанию.
     * @return mixed
     */
    public function get($key, $default = null)
    {
        return isset($this->concatenatedConfig[$key]) ? $this->concatenatedConfig[$key] : $default;
    }

    /**
     * Возвращает массив настроек.
     * @return array
     */
    public function getAll()
    {
        return $this->config;
    }

    private function concatenateConfig(array $config, $prefix = '')
    {
        if ($prefix !== '') {
            $prefix = $prefix . $this->concatenationDelimiter;
        }

        foreach ($config as $key => $value) {
            $this->concatenatedConfig[$prefix . $key] = $value;
            if (is_array($value)) {
                $this->concatenateConfig($value, $prefix . $key);
            }
        }
    }
}
