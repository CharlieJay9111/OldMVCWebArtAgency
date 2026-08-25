<?php

namespace app\controllers;

use MVC\Controller;
use app\models\Movies;


class ProdukceFilmuController extends Controller
{
    private Movies $model;
    
    function __construct(Movies $model)
    {
        $this->model = $model;        
    }

    function index()
    {
        $data = [
            "values" => $this->model->getAll()
        ];

        $this->view("produkce-filmu/index", $data);
    }

    function film($parrams)
    {
        $movie = $this->model->get($parrams[0]);

        if(!$movie) return $this->notFound();

        $data = [
            "value" => $movie
        ];

        $this->view("produkce-filmu/film", $data);
    }
}