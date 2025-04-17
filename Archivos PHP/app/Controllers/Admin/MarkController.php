<?php
/**
 * Created by PhpStorm.
 * User: Eddie
 * Date: 10/09/2017
 * Time: 1:26
 */

namespace Admin;
use Query;

class MarkController extends \MasterController
{
    public $arr_generate;

    function __construct()
    {
        parent::__construct();
    }



    public static function getAllMarkData()
    {
        return self::model('common_mark')->all();
    }

    public static function getMarkByID()
    {
        $id = self::httpPost('id');
        \Responses::message(Query::qWhere("common_mark", "id_mark", $id),"CORRECTO");
    }

    public static function getMarkEdit()
    {
        $id = self::httpParam('id');
        return Query::qWhere("common_mark", "id_mark", $id);
    }



    public function save()
    {
        $data = self::httpPost('');
        $image = self::httpFile('image');

        $upload = \UploadFile::upload($image,array('png','jpeg','bmp'),"2","storage/mark", true);
        $data["image"] = $upload["path"];

        self::model("common_mark")->add($data);
        \Responses::message("Se ha agregado correctamente la marca <br> " . $data["name"],  "CORRECTO", false, true,"","/admin/mark");

    }


    function update(){
        $data = self::httpPost('');
        $image = self::httpFile('image');

        if(!empty($image["name"][0])) {
            $upload = \UploadFile::upload($image, array('png', 'jpeg', 'bmp'), "2", "storage/mark", true);
            $data["image"] = $upload["path"];
        }


        Query::qUpdateAllWhere("common_mark",$data,"id_mark",$data["id_mark"]);
        \Responses::message("Se ha actualizado correctamente la marca <br>" . $data["name"], "CORRECTO");

    }


    function delete()
    {
        $id = self::httpPost("id_mark");
        self::model("common_mark")->delete("id_mark",$id);
        \Responses::message("Se ha eliminado correctamente la marca<br>","CORRECTO","","",true,"");


    }


}