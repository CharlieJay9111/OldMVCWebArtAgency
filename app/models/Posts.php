<?php

namespace app\models;

use app\core\utils\Utils;

class Posts extends \app\core\Model 
{
    protected $tableName = "posts";
    public $count;

    public function get($link)
    {
        return $this->table()->where("link = ?", [$link])->fetch();
    }

    public function getAll($order = "id")
    {
        return $this->table("posts p, categories c")
            ->select("p.*, c.name as category")
            ->where("p.category_id = c.id")
            ->order($order)
            ->fetchAll();
    }

    public function count(){
        return $this->table()->count();
    }

    public function getPage($page, $limit = 10)
    {
        $offset = ($page - 1) * $limit;
        return $this->table()->order("created_at DESC")->limit($limit, $offset)->fetchAll();
    }

    public function getCategories()
    {
        return $this->table("categories")->fetchAll();
    }

    public function getPopular()
    {
        return $this->table()->order("views DESC")->limit(10)->fetchAll();
    }

    public function getCategory($link)
    {
        return $this->table("categories")->where("link = ?", [$link])->fetch();
    }

    public function getPageCategory($page, $id, $limit = 10)
    {
        $offset = ($page - 1) * $limit;
        return $this->table()->where("category_id = $id")->order("created_at DESC")->limit($limit, $offset)->fetchAll();
    }

    public function countCategory($id)
    {
        return $this->table()->where("category_id = $id")->count();
    }

    function getSearch($value, $page, $limit = 10){
        $offset = ($page - 1) * $limit;
        $link = Utils::generateLink($value);
        return $this->table()
            ->where("title LIKE ? OR content LIKE ? OR link LIKE ?", ["%$value%" , "%$value%", "%$link%" ])
            ->order("id DESC")
            ->limit($limit, $offset)
            ->fetchAll();
    }

    function countSearch($value){
        $link = Utils::generateLink($value);
        return $this->table()
            ->where("title LIKE ? OR content LIKE ? OR link LIKE ?", ["%$value%" , "%$value%", "%$link%" ])
            ->count();
    }
}