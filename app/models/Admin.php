<?php

namespace app\models;

class Admin extends \MVC\Model {

    protected $tableName = "admin";

    function add($username, $password){
        $this->table()->insert(["username" => $username , "password" => hash('ripemd256', $password)]);
    }

    function login($values){
        $values["password"] = hash('ripemd256', $values["password"]);
        $result = $this->table()
            ->where("username = ? AND password = ?", [$values["username"], $values["password"]])
            ->fetch();
        
        if($result){
            $_SESSION["admin"] = $result->id;
            return true;
        }
        else
        {
            return false;
        }
    }

    function logout(){
        session_destroy();
    }
}