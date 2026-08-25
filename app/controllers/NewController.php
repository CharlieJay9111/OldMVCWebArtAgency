<?php

namespace app\controllers;

use MVC\Controller;

use app\models\Offers;
use app\models\Provides;
use app\models\Movies;
use app\models\Actions;

class NewController extends Controller
{
    function index(Movies $movies, Offers $offers, Provides $provides, Actions $actions)
    {
        $data = [
            "movies" => $movies->getByIDs([1, 2, 3]),
            "offers" => $offers->getByIDs([1, 2, 3]),
            "provides" => $provides->getByIDs([1, 2, 3]),
            "actions" => $actions->getByIDs([4, 3, 1])
        ];

        $this->view("new/index", $data);
    }
}