<?php

namespace app\controllers;

use MVC\Controller;

class ReferenceController extends Controller
{
    function index()
    {
        $files = \MVC\Utils\Utils::getFiles("img/reference");

        $data["files"] = $files;

        $this->view("reference", $data);
    }
}