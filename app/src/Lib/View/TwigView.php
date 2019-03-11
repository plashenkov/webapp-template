<?php

namespace App\Lib\View;

use Twig_Environment;

class TwigView implements View
{
    /** @var Twig_Environment */
    protected $twig;

    /** @var array */
    protected $options = [
        'extension' => 'twig',
    ];

    /**
     * TwigView constructor.
     * @param Twig_Environment $twig
     * @param array $options
     */
    public function __construct(Twig_Environment $twig, array $options = [])
    {
        $this->twig = $twig;
        $this->options = array_replace($this->options, $options);
    }

    /**
     * @inheritdoc
     */
    public function exists($templateName)
    {
        return $this->twig->getLoader()->exists($this->processTemplateName($templateName));
    }

    /**
     * @inheritdoc
     */
    public function render($templateName, array $data = [])
    {
        return $this->twig->render($this->processTemplateName($templateName), $data);
    }

    /**
     * Returns the Twig_Environment object.
     */
    public function getTwigEnvironment()
    {
        return $this->twig;
    }

    /**
     * If default extension specified, then user can omit it.
     * When default extension specified and omitted, a "." as a path separator can be used
     * (instead of "/").
     *
     * @param $templateName
     * @return string
     */
    protected function processTemplateName($templateName)
    {
        $extension = trim($this->options['extension'] ?? '');

        if ($extension && strpos($extension, '.') !== 0) {
            $extension = ".$extension";
        }

        if (!$extension || substr($templateName, -strlen($extension)) === $extension) {
            return $templateName;
        }

        return str_replace('.', '/', $templateName) . $extension;
    }
}
