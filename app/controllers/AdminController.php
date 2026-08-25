<?php

namespace app\controllers;

use MVC\Controller;
use MVC\User;

class AdminController extends Controller
{
    private User $user;

    function __construct(User $user, $action)
    {
        $this->user = $user;
        
        if($action == "index") return;

        if(!$this->user->isLogged())
        {
            $this->notFound();
        }
    }

    function index($request, \app\models\Admin $model)
    {
        if($this->user->isLogged())
        {
            $this->view("admin/index");
            return;
        }


        $form = new \MVC\Form\FormReader($request->post(), $model);
        $form->readFile("admin/forms/login");
        
        if($form->isValidate())
        {
            if($model->login($form->data))
            {
                $this->user->login($form->data);
                $this->redirect();
            }
            else
            {
                $form->addErrorTo("password", "Neplatná data");
            }

        }

        $data = ["form" => $form->get()];

        $this->view("admin/login", $data);
    }

    function logout()
    {
        $this->user->logout();
        $this->redirect("admin");
    }

    // zpravy
    function zpravy(\app\models\Messages $model)
    {
        $values = $model->getAll();

        $data = [
            "values" => $values,
        ];

        $this->view("admin/zpravy", $data);
    }

    function zprava(\app\models\Messages $model , $parrams)
    {
        $values = $model->getByID($parrams[0]);

        if(!$values) return $this->notFound();

        $data = [
            "value" => $values,
        ];

        $this->view("admin/zprava", $data);
    }

    function smazatZpravu(\app\models\Messages $model, $request, $parrams)
    {
        $this->delete($model, $parrams[0], $request, "zpravy");
    }

    // zastupuje
    function zastupujeme(\app\models\Artists $model)
    {
        $values = $model->getAll();

        $data = [
            "values" => $values,
        ];

        $this->view("admin/zastupujeme", $data);
    }

    function pridatUmelce(\app\models\Artists $model, $request)
    {
        $form = new \MVC\Form\FormReader($request->post(), $model);
        $form->readFile("admin/forms/umelec", ["url" => \MVC\Application::$properties->url, "image" => "img/icons/image-solid.svg"]);

        if($request->post() && $request->files("photo")["error"])
        {
            $msg = $form->getErrorMessages($form::RULE_REQUIRED);
            $form->addErrorTo("photo", $msg);
        }
        else if($form->isValidate())
        {
            $model->add($form->data, $request->files("photo"));
            $this->redirect("admin/zastupujeme");
        }

        $data = [
            "action" => "Přidat",
            "name" =>  "umělce",
            "form" => $form->get()
        ];

        $this->view("admin/clanek", $data);
    }

    function upravitUmelce(\app\models\Artists $model, $request, $parrams)
    {
        $values = $model->getByID($parrams[0]);

        if(!$values) return $this->notFound();

        $form = new \MVC\Form\FormReader($request->post(), $model);
        $form->edit($values);
        $form->readFile("admin/forms/umelec", ["url" => \MVC\Application::$properties->url, "image" => $values->photo]);

        if($form->isValidate())
        {
            $model->update($values, $form->data, $request->files("photo"));
            $this->redirect("admin/zastupujeme");
        }

        $data = [
            "action" => "Upravit",
            "name" =>  "umělce",
            "form" => $form->get()
        ];

        $this->view("admin/clanek", $data);
    }

    function smazatUmelce(\app\models\Artists $model, $request, $parrams)
    {
        $this->delete($model, $parrams[0], $request, "zastupujeme");
    }

    // akce
    function akce(\app\models\Actions $model)
    {
        $values = $model->getAll();

        $data = [
            "values" => $values,
        ];

        $this->view("admin/akce", $data);
    }

    function pridatAkci(\app\models\Actions $model, $request)
    {
        $this->add($model, $request, "akce", "akci");
    }

    function upravitAkci(\app\models\Actions $model, $request, $parrams)
    {
        $this->edit($model, $parrams[0], $request, "akce", "akci");
    }

    function smazatAkci(\app\models\Actions $model, $request, $parrams)
    {
        $this->delete($model, $parrams[0], $request, "akce");
    }

    // filmy
    function filmy(\app\models\Movies $model)
    {
        $values = $model->getAll();

        $data = [
            "values" => $values,
        ];

        $this->view("admin/filmy", $data);
    }

    function pridatFilm(\app\models\Movies $model, $request)
    {
        $this->add($model, $request, "filmy", "film");
    }

    function upravitFilm(\app\models\Movies $model, $request, $parrams)
    {
        $this->edit($model, $parrams[0], $request, "filmy", "film");
    }

    function smazatFilm(\app\models\Movies $model, $request, $parrams)
    {
        $this->delete($model, $parrams[0], $request, "filmy");
    }

    // zajistime
    function zajistime(\app\models\Provides $model)
    {
        $values = $model->getAll();

        $data = [
            "values" => $values,
        ];

        $this->view("admin/zajistime", $data);
    }

    function pridatZajistime(\app\models\Provides $model, $request)
    {
        $this->add($model, $request, "zajistime", "zajistíme");
    }

    function upravitZajistime(\app\models\Provides $model, $request, $parrams)
    {
        $this->edit($model, $parrams[0], $request, "zajistime", "zajistíme");
    }

    function smazatZajistime(\app\models\Provides $model, $request, $parrams)
    {
        $this->delete($model, $parrams[0], $request, "zajistime");
    }

    // nabizime
    function nabizime(\app\models\Offers $model)
    {
        $values = $model->getAll();

        $data = [
            "values" => $values,
        ];

        $this->view("admin/nabizime", $data);
    }

    function pridatNabizime(\app\models\Offers $model, $request)
    {
        $this->add($model, $request, "nabizime", "nabízíme");
    }

    function upravitNabizime(\app\models\Offers $model, $request, $parrams)
    {
        $this->edit($model, $parrams[0], $request, "nabizime", "nabízíme");
    }

    function smazatNabizime(\app\models\Offers $model, $request, $parrams)
    {
        $this->delete($model, $parrams[0], $request, "nabizime");
    }

    // reference
    function reference($request)
    {
        $files = \MVC\Utils\Utils::getFiles("img/reference");
        $form = new \MVC\Form\FormReader($request->post());
        $form->readFile("admin/forms/reference");

        if(!$form->errors && $request->files())
        {
            $file = $request->files()["file"];
            $name = preg_replace("/\..*$/", "", $file["name"]);
            \MVC\Utils\Utils::upload($file, "img/reference/", $name);

            $this->redirect("admin/reference");
        }

        $data = [ 
            "files" => $files,
            "form" => $form->get() 
        ];
        

        $this->view("admin/reference", $data);
    }

    function smazatReference($request)
    {
        $form = new \MVC\Form\FormReader($request->post());
        $form->readFile("admin/forms/smazat");
        $file = $request->get("file");

        if($form->isValidate())
        {
            if($form->data["send"] == "yes")
            {
                unlink("img/reference/$file");
            }

            $this->redirect("admin/reference");
        }

        $data = [
            "form" => $form->get(),
            "file" => $file
        ];

        $this->view("admin/smazat-obrazek", $data);
    }

    // methods
    private function add($model, $request, $link, $name)
    {
        $form = new \MVC\Form\FormReader($request->post(), $model);
        $form->readFile("admin/forms/clanek", ["url" => \MVC\Application::$properties->url, "image" => "img/icons/image-solid.svg"]);

        if($request->post() && $request->files("photo")["error"])
        {
            $msg = $form->getErrorMessages($form::RULE_REQUIRED);
            $form->addErrorTo("photo", $msg);
        }
        else if($form->isValidate())
        {
            $model->add($form->data, $request->files("photo"));
            $this->redirect("admin/$link");
        }

        $data = [
            "action" => "Přidat",
            "name" =>  "$name",
            "form" => $form->get()
        ];

        $this->view("admin/clanek", $data);
    }

    private function edit($model, $id, $request, $link, $name)
    {
        $values = $model->getByID($id);

        if(!$values) return $this->notFound();

        $form = new \MVC\Form\FormReader($request->post(), $model);
        $form->edit($values);
        $form->readFile("admin/forms/clanek", ["url" => \MVC\Application::$properties->url, "image" => $values->photo]);

        if($form->isValidate())
        {
            $model->updatePage($values, $form->data, $request->files("photo"));
            $this->redirect("admin/$link");
        }

        $data = [
            "action" => "Upravit",
            "name" =>  "$name",
            "form" => $form->get()
        ];

        $this->view("admin/clanek", $data);
    }

    private function delete($model, $id, $request, $link)
    {
        $form = new \MVC\Form\FormReader($request->post());
        $form->readFile("admin/forms/smazat");
        $values = $model->getByID($id);

        if(!$values) return $this->notFound();

        if($form->isValidate())
        {
            if($form->data["send"] == "yes")
            {
                $model->delete($id);
            }

            $this->redirect("admin/$link");
        }

        $data = [
            "form" => $form->get(),
            "name" => $values->name ?? $values->first_name . " " . $values->last_name
        ];

        $this->view("admin/smazat", $data);
    }
}