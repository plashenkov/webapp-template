<?php

namespace App\Controllers;

use App\Lib\Controller;
use App\Lib\View\View;

class LoadApp extends Controller
{
    public function __invoke(View $view)
    {
        return $view->render('index');
    }
}
