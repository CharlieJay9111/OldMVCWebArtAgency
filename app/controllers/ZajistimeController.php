<?php

namespace app\controllers;

use MVC\Controller;

class ZajistimeController extends Controller
{
    function index(\app\models\Provides $model)
    {
        $data = [
            "values" => $model->getAll()
        ];

        $this->view("zajistime", $data);
    }
}