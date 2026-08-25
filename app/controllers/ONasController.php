<?php

namespace app\controllers;

use MVC\Controller;


class ONasController extends Controller
{
    function index()
    {
        $this->view("o-nas");
    }
}