<?php

namespace app\controllers;

use MVC\Controller;

class NabizimeController extends Controller
{
    function index(\app\models\Offers $model)
    {
        $data = [
            "values" => $model->getAll()
        ];

        $this->view("nabizime", $data);
    }
}