<?php
/**
 * Created by PhpStorm.
 * User: Eddie
 * Date: 10/09/2017
 * Time: 1:26
 */

namespace Admin;
use Query;

class CategoryController extends \MasterController
{
    public $arr_generate;

    function __construct()
    {
        parent::__construct();
    }



    public static function getAllCategoryData()
    {
        return self::model('common_category')->all();
    }

    public static function getCategotyByID()
    {
        $id = self::httpPost('id');
        \Responses::message(Query::qWhere("common_category", "id_category", $id),"CORRECTO");
    }

    public static function getCategoryEdit()
    {
        $id = self::httpParam('id');
        return Query::qWhere("common_category", "id_category", $id);
    }



    public function save()
    {
        $data = self::httpPost('');
        self::model("common_category")->add($data);
        \Responses::message("Se ha agregado correctamente la categotria <br> " . $data["name"],  "CORRECTO", false, true,"","/admin/category");

    }


    function update(){
        $data = self::httpPost('');
        Query::qUpdateAllWhere("common_category",$data,"id_category",$data["id_category"]);
        \Responses::message("Se ha actualizado correctamente la categoria <br>" . $data["name"], "CORRECTO");

    }


    function delete()
    {
        $id = self::httpPost("id_category");
        self::model("common_category")->delete("id_category",$id);
        \Responses::message("Se ha eliminado correctamente la categoria<br>","CORRECTO","","",true,"");


    }


}