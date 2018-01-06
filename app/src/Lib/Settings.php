<?php

namespace App\Lib;

class Settings
{
    /** @var array */
    protected $settings;

    /** @var array */
    protected $concatenatedSettings;

    /** @var string */
    protected $concatenationDelimiter = '.';

    /**
     * Settings constructor.
     * @param array $settings
     */
    public function __construct(array $settings)
    {
        $this->settings = $settings;
        $this->concatenateSettings($settings);
    }

    /**
     * Возвращает определенную настройку.
     * @param string $setting Настройка, которую требуется получить.
     * @param mixed $default Значение по умолчанию.
     * @return mixed
     */
    public function get($setting, $default = null)
    {
        return isset($this->concatenatedSettings[$setting]) ? $this->concatenatedSettings[$setting] : $default;
    }

    /**
     * Возвращает массив настроек.
     * @return array
     */
    public function getAll()
    {
        return $this->settings;
    }

    private function concatenateSettings(array $settings, $prefix = '')
    {
        if ($prefix !== '') {
            $prefix = $prefix . $this->concatenationDelimiter;
        }

        foreach ($settings as $setting => $value) {
            $this->concatenatedSettings[$prefix . $setting] = $value;
            if (is_array($value)) {
                $this->concatenateSettings($value, $prefix . $setting);
            }
        }
    }
}
