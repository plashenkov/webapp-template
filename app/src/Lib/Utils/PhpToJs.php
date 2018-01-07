<?php

namespace App\Lib\Utils;

class PhpToJs
{
    /** @var string|null */
    protected $namespace;

    /**
     * PhpToJs constructor.
     * @param string|null $namespace JavaScript namespace to export variables to.
     */
    public function __construct($namespace = 'php2js')
    {
        $this->namespace = $namespace;
    }

    /**
     * Export variables to JavaScript, default namespace.
     * @param array $variables Variables to export.
     * @return string
     */
    public function export(array $variables)
    {
        return $this->exportToNamespace($this->namespace, $variables);
    }

    /**
     * Export variables to JavaScript, custom namespace.
     * @param string|null $namespace JavaScript namespace to export variables to.
     * @param array $variables Variables to export.
     * @return string
     */
    public function exportToNamespace($namespace, array $variables)
    {
        $js = '';

        if (empty($namespace)) {
            $namespace = 'window';
        }

        if ($namespace !== 'window') {
            $js = "window.$namespace = window.$namespace || {};";
        }

        foreach ($variables as $key => $value) {
            $js .= "$namespace.$key = " . json_encode($value) . ";";
        }

        return "<script>$js</script>";
    }
}
