<?php

namespace App\Lib;

class UploadedFile
{
    const UPLOAD_ERR_OK = UPLOAD_ERR_OK;
    const UPLOAD_ERR_INI_SIZE = UPLOAD_ERR_INI_SIZE;
    const UPLOAD_ERR_FORM_SIZE = UPLOAD_ERR_FORM_SIZE;
    const UPLOAD_ERR_PARTIAL = UPLOAD_ERR_PARTIAL;
    const UPLOAD_ERR_NO_FILE = UPLOAD_ERR_NO_FILE;
    const UPLOAD_ERR_NO_TMP_DIR = UPLOAD_ERR_NO_TMP_DIR;
    const UPLOAD_ERR_CANT_WRITE = UPLOAD_ERR_CANT_WRITE;
    const UPLOAD_ERR_EXTENSION = UPLOAD_ERR_EXTENSION;

    /** @var array */
    protected $fileData = [];

    /**
     * Returns the original name of the file on the client machine.
     * @return string
     */
    public function getClientFilename()
    {
        return $this->getProperty('name');
    }

    /**
     * Returns the mime type of the file (if browser provided).
     * Example: "image/gif".
     * @return string
     */
    public function getMimeType()
    {
        return $this->getProperty('type');
    }

    /**
     * Returns the temporary filename of the file on the server.
     * @return string
     */
    public function getTempName()
    {
        return $this->getProperty('tmp_name');
    }

    /**
     * Returns the error code associated with this file upload.
     * @return int
     */
    public function getError()
    {
        return $this->getProperty('error');
    }

    /**
     * Returns false, if the file was successfully uploaded, and true otherwise.
     * @return bool
     */
    public function hasError()
    {
        return $this->getError() !== UPLOAD_ERR_OK;
    }

    /**
     * Returns size (in bytes) of the uploaded file.
     * @return int
     */
    public function getSize()
    {
        return $this->getProperty('size');
    }

    /**
     * Move uploaded file.
     * @param string $destination
     * @return bool
     */
    public function move($destination)
    {
        return move_uploaded_file($this->getTempName(), $destination);
    }

    /**
     * Sets file property.
     * @param string $propertyName
     * @param mixed $propertyValue
     */
    public function setProperty($propertyName, $propertyValue)
    {
        $this->fileData[$propertyName] = $propertyValue;
    }

    /**
     * Returns file property.
     * @param string $propertyName
     * @param mixed $default
     * @return mixed
     */
    protected function getProperty($propertyName, $default = null)
    {
        return isset($this->fileData[$propertyName]) ? $this->fileData[$propertyName] : $default;
    }
}
