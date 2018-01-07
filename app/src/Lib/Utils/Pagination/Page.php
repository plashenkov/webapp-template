<?php

namespace App\Lib\Utils\Pagination;

class Page
{
    /** @var int */
    public $number;

    /** @var bool */
    public $isActive = false;

    /** @var bool */
    public $skip = false;

    /**
     * Page constructor.
     * @param int $number Page number.
     * @param bool $isActive Is the page active?
     */
    public function __construct($number, $isActive = false)
    {
        if ($number === false) {
            $this->skip = true;
        } else {
            $this->number = $number;
            $this->isActive = $isActive;
        }
    }
}
