<?php

namespace App\Lib\View;

interface View
{
    /**
     * Check the template exists.
     *
     * @param string $templateName
     * @return bool
     */
    public function exists($templateName);

    /**
     * Render the template.
     *
     * @param string $templateName
     * @param array $data
     * @return string
     */
    public function render($templateName, array $data = []);
}
