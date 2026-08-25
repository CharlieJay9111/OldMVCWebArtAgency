<?php 

namespace app\models;

use MVC\Utils\Utils;
use MVC\Utils\Image;

abstract class PageModel extends \MVC\Model {

    protected $tableName;
    protected $uploadPath;

    function add($values, $file)
    {
        $values["link"] = Utils::generateLink($values["name"]);
        $values["photo"] = $this->upload($file["tmp_name"], $values["link"]);

        $this->table()->insert($values);
    }

    function get($link)
    {
        return $this->table()->where("link = ?", [$link])->fetch();
    }

    function getByID($id)
    {
        return $this->table()->where("id = ?", [$id])->fetch();
    }

    function getByIDs($ids)
    {
        $cols = array_map(fn() => "?", array_keys($ids));
        $cols = implode(" , ",  $cols);

        return $this->table()->where("id IN ($cols)", $ids)->fetchAll();
    }

    function getAll($order = "ASC")
    {
        return $this->table()->order("id $order")->fetchAll();
    }

    function updatePage($original, $values, $file)
    {
        $values["link"] = Utils::generateLink($values["name"]);

        if($original->link != $values["link"]){
            $ext = explode(".", $original->photo);
            $ext = $ext[count($ext) - 1];

            $values["photo"] = $this->uploadPath. $values["link"].".". $ext;
            rename($original->photo, $values["photo"] );
        }
        
        if(!empty($file) && !$file["error"]){
            $path = $values["photo"] ?? $original->photo;
            unlink($path);
            
            $name = $values["link"] . "-" . ceil(microtime(true));
            $values["photo"] = $this->upload($file["tmp_name"], $name);
        }

        $this->table()->update($values, "id = $original->id");
    }

    function delete($id){
        $path = $this->getByID($id)->photo;
        unlink($path);
        $this->table()->delete("id = $id");
    }

    function upload($file, $name)
    {
        $image = new Image($file);
        $image->convert(IMAGETYPE_WEBP);
        //$image->resize(520,800);
        $name = $this->uploadPath . $name . ".webp";
        $image->save($name);

        return $name;
    }

}