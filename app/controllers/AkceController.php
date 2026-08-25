<?php

namespace app\controllers;

use MVC\Controller;

class AkceController extends Controller
{
    function index(\app\models\Actions $model)
    {
        $data = [
            "values" => $model->getAll()
        ];

        $this->view("akce", $data);
    }
}