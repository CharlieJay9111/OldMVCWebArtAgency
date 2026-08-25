<?php

namespace app\controllers;

use MVC\Controller;

class KontaktController extends Controller
{
    function index($request, \app\models\Messages $model)
    {
        $form = new \MVC\Form\FormReader($request->post());
        $form->readFile("forms/kontakt");
        
        if($form->isValidate())
        {
            $model->add($form->data);
            $this->redirect();
        }

        $data = ["form" => $form->get()];

        $this->view("kontakt", $data);
    }
}