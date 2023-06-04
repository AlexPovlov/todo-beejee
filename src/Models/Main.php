<?php
namespace App\Models;
use App\Core\Model;

/**
 * модель
 */

class Main extends Model 
{
    /**
     * берет массив данных с базы
     * return array table data
     */

    public function getCategory()
    {
        return $this->db->GetAllSqlRequest("SELECT * FROM `data` ORDER BY `data`.`parent_id` ASC",[]);
    }

    /**
     * берет одну строку с базы по id
     * @param int $id
     * @return array table data
     */

    public function getOneCategory($id)
    {
        return $this->db->GetOneSqlRequest("SELECT * FROM `data` WHERE `id`=:id ",['id'=>$id]);
    }

    /**
     * создает массив дерево
     * @param array $items изначальный массив
     * @param int $parent id родителя
     * @return array $tree готовый массив дерево
     */

    function buildTree(array $items, $parent = 0)
    {
        $tree = [];
        foreach ($items as $item){
            if($item['parent_id'] == $parent){
                $children = $this->buildTree($items, $item['id']);
                if($children){
                    $item['children'] = $children;
                }
                $tree[] = $item;
            }
        }
        return $tree;
    }

}
