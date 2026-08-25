<?php

namespace app\models;

use MVC\Utils\Utils;

class Artists extends \MVC\Model {

    protected $tableName = "artists";
    protected $uploadPath = "upload/zastupujeme/";

    public $count;
    public $limit = 21;

    function add($values, $file){

        $values["link"] = Utils::generateLink($values["first_name"] . "-" . $values["last_name"]);
        $link = Utils::generateLink($values["first_name"] . "-" . $values["last_name"]);
        $artist = $this->getArtist($link);
        $i = 2;
        while($artist){
            $link = Utils::generateLink($values["first_name"] . "-" . $values["last_name"]. "-" . $i);
            $artist = $this->getArtist($link);
            $i++;
        }
        $values["link"] = $link;
        $values["photo"] = Utils::upload($file,$this->uploadPath, $values["link"]);

        $this->table()->insert($values);
    }

    function getAll($order = "id DESC"){
        return $this->table()->order($order)->fetchAll();
    } 

    function get($page){
        $offset = $this->limit * ($page - 1);
        $this->count = $this->table()->count();
        if($this->count == 0) return [];
        return $this->table()->order("first_name, last_name")->limit($this->limit, $offset)->fetchAll();
    }

    function getByID($id){
        return $this->table()->where("id = ?", [$id])->fetch();
    }

    function getArtist($link){
        return $this->table()->where("link = ?", [$link])->fetch();
    }

    function getByProfession($profession, $page){
        $offset = $this->limit * ($page - 1);
        $where = "";
        switch($profession){
            case "herce" : $where = "profession = 'Herec' OR profession = 'Herecka'"; break;
            case "zpevaky" : $where = "profession = 'Zpevak' OR profession = 'Zpevacka'"; break;
            case "moderatory" : $where = "profession = 'Moderator' OR profession = 'Moderatorka'"; break;
        }

        $this->count = $this->table()->where($where)->count();
        return $this->table()->where($where)->order("first_name, last_name")->limit($this->limit, $offset)->fetchAll();
    }

    function update($original, $values, $file){


        $link = Utils::generateLink($values["first_name"] . "-" . $values["last_name"]);
        $artist = $this->getArtist($link);
        $i = 2;
        while($artist){
            $link = Utils::generateLink($values["first_name"] . "-" . $values["last_name"]. "-" . $i);
            $artist = $this->getArtist($link);
            $i++;
        }
        $values["link"] = $link;

        if($original->link != $values["link"]){
            $ext = explode(".", $original->photo);
            $ext = $ext[count($ext) - 1];

            $values["photo"] = $this->uploadPath. $values["link"].".". $ext;
            rename($original->photo, $values["photo"] );
        }
        
        if(!empty($file) && !$file["error"]){
            $path = $values["photo"] ?? $original->photo;
            unlink($path);
            
            $values["photo"] = Utils::upload($file, $this->uploadPath,$values["link"] );
            
        }

        $this->table()->update($values, "id = $original->id");
    }

    function delete($id){
        $path = $this->getByID($id)->photo;
        unlink($path);
        $this->table()->delete("id = $id");
    }

    function search($search, $page){

        $search = trim($search);
        $offset = $this->limit * ($page - 1);

        if(strpos($search, " ")){

            $s = explode(" ", $search);
            $first = $s[0];
            $last = $s[1];


            $result = $this->table()
                ->where("first_name LIKE ? AND last_name LIKE ? 
                    OR first_name LIKE ? AND last_name LIKE ?", 
                    ["%$first%", "%$last%","%$last%", "%$first%"])
                ->order("first_name")
                ->limit($this->limit, $offset)
                ->fetchAll();
        
            $this->count = $this->table()
                ->where("first_name LIKE ? AND last_name LIKE ? 
                    OR first_name LIKE ? AND last_name LIKE ?", 
                    ["%$first%", "%$last%","%$last%", "%$first%"])
                ->count();
        }
        else
        {
            $result = $this->table()
                ->where("first_name LIKE ? OR last_name LIKE ?", ["%$search%", "%$search%"])
                ->order("first_name")
                ->limit($this->limit, $offset)
                ->fetchAll();
        
            $this->count = $this->table()
                ->where("first_name LIKE ? OR last_name LIKE ?", ["%$search%", "%$search%"])
                ->count();
        }

        return $result;
    }

}