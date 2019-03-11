<?php

namespace App\Controllers\API;

use App\Lib\Controller;

class SomeMethod extends Controller
{
    public function __invoke()
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
