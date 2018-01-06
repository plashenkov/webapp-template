<?php

namespace App\Controllers;

use App\Core\BaseController;

class ApiController extends BaseController
{
    public function someMethod()
    {
        return [
            'items' => [
                'item 1',
                'item 2',
                'item 3',
            ]
        ];
    }
}
