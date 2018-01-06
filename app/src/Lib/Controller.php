<?php

namespace App\Lib;

class Controller
{
    /** @var Request */
    protected $request;

    /**
     * BaseController constructor.
     * @param Request $request
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
     * Sends HTTP header.
     * @param string $name
     * @param string $value
     */
    public function header($name, $value)
    {
        header($name . ': ' . $value);
    }

    /**
     * Sets HTTP response code.
     * @param int $responseCode
     */
    public function responseCode($responseCode)
    {
        http_response_code($responseCode);
    }

    /**
     * Performs redirect.
     * @param string $location
     * @return null
     */
    public function redirect($location)
    {
        $this->header('Location', $this->request->getBasePath() . $location);

        return null;
    }

    /**
     * Sends file to a user.
     * @param string $file
     * @param bool $download
     * @param string|null $downloadFilename
     */
    public function sendFile($file, $download = true, $downloadFilename = null)
    {
        if ($download) {
            $this->header('Content-Description', 'File Transfer');
            $this->header('Content-Type', 'application/octet-stream');
            $this->header('Content-Transfer-Encoding', 'binary');
            $this->header('Connection', 'Keep-Alive');
            $this->header('Expires', '0');
            $this->header('Cache-Control', 'must-revalidate, post-check=0, pre-check=0');
            $this->header('Pragma', 'public');
            $this->header('Content-Length', filesize($file));

            if ($downloadFilename) {
                $this->header('Content-Disposition', 'attachment; filename=' . $downloadFilename);
            }
        } else {
            $this->header('Content-Type', mime_content_type($file));
            $this->header('Content-Length', filesize($file));
        }

        readfile($file);
    }
}
