<?php

namespace App\Core;

use App\Lib\Controller;
use App\Lib\Request;
use League\Plates\Engine as Plates;

class BaseController extends Controller
{
    /** @var Plates */
    protected $view;

    public function __construct(
        Request $request,
        Plates $view
    ) {
        parent::__construct($request);
        $this->view = $view;
    }
}
