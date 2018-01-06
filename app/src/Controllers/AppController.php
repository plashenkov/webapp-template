<?php

namespace App\Controllers;

use App\Core\BaseController;

class AppController extends BaseController
{
    public function loadApp() {
        return $this->view->render('index');
    }
}
