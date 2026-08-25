<?php

namespace app\models;

class Messages extends \MVC\Model {

    protected $tableName = "messages";

    function add($values){
        $this->table()->insert($values);
    }

    function getAll(){
        return $this->table()->order("id DESC")->fetchAll();
    }

    function getByID($id){
        return $this->table()->where("id = ?", [$id])->fetch();
    }

    function delete($id)
    {
        $this->table()->delete("id = $id");
    }
}