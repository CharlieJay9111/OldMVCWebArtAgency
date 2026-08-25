<?php

namespace app\controllers;

use MVC\Controller;
use app\models\Artists;

class ZastupujemeController extends Controller
{
    private Artists $model;

    function __construct(Artists $model)
    {
        $this->model = $model;
    }

    function index($url, $request)
    {
        $this->strana([1], $url, $request);
    }

    function strana($parrams, $url, $request)
    {
        $search = $request->get("hledat");

        if($search)
        {
            $artists = $this->model->search($search, $parrams[0]);
            $pUrl = $url . "zastupujeme/strana/<page>/?hledat=$search";
        }
        else
        {
            $artists = $this->model->get($parrams[0]);
            if(!$artists) return $this->notFound();

            $pUrl = $url . "zastupujeme/strana/<page>";
        }

        
        $paginator = new \MVC\Utils\Paginator($parrams[0], $this->model->count, $this->model->limit, $pUrl, 4);

        $form = new \MVC\Form\FormReader($request->get());
        $form->readFile("forms/hledat", ["url" => $url]);

        $data = [
            "values" => $artists,
            "paginator" => $paginator->get(),
            "searchForm" => $form->get()
        ];

        $this->view("zastupujeme/index", $data);
    }

    function umelec($parrams)
    {
        $artist = $this->model->getArtist($parrams[0]);

        if(!$artist) return $this->notFound();

        $data = [
            "value" => $artist
        ];

        $this->view("zastupujeme/umelec", $data);
    }

    function herce($parrams, $url, $request)
    {
        $this->profession("herce", $parrams[0] ?? 1, $url, $request);
    }

    function zpevaky( $parrams, $url, $request)
    {
        $this->profession("zpevaky", $parrams[0] ?? 1, $url, $request);
    }

    function moderatory( $parrams, $url, $request)
    {
        $this->profession("moderatory", $parrams[0] ?? 1, $url, $request);
    }

    private function profession($profesion, $page, $url, $request)
    {
        $values = $this->model->getByProfession($profesion, $page);

        if(!$values) $this->notFound();

        $pUrl = $url . "zastupujeme/$profesion/<page>";

        $paginator = new \MVC\Utils\Paginator($page, $this->model->count, $this->model->limit, $pUrl, 4);

        $form = new \MVC\Form\FormReader($request->get());
        $form->readFile("forms/hledat", ["url" => $url]);

        $data = [
            "values" => $values,
            "paginator" => $paginator->get(),
            "searchForm" => $form->get()
        ];

        $this->view("zastupujeme/index", $data);

    }
}