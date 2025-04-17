<?php
/**
 * Created by PhpStorm.
 * User: Eddie
 * Date: 10/09/2017
 * Time: 1:26
 */

namespace Admin;

use Query;

class SliderController extends \MasterController
{
    public $arr_generate;

    function __construct()
    {
        parent::__construct();
    }


    public static function getAllSliderData()
    {
        return self::model('common_slider')->all();
    }

    public static function getSliderByID()
    {
        $id = self::httpPost('id');
        \Responses::message(Query::qWhere("common_slider", "id_slider", $id), "CORRECTO");
    }

    public static function getSliderEdit()
    {
        $id = self::httpParam('id');
        return Query::qWhere("common_slider", "id_slider", $id);
    }

    public static function getAllPublicSlider()
    {

        return Query::qWhere("common_slider", "is_public", 1,true);
    }


    public function save()
    {
        $data = self::httpPost('');

        $image = self::httpFile("image");

        $file = \UploadFile::upload($image, array("png", "jpg", "jpge"), "1", "storage/slider", true);
        if ($file["status"] == 1) {
            $data["image"] = $file["path"];
            self::model("common_slider")->add($data);
            \Responses::message("Se ha agregado correctamente el slider <br> " . $data["title"], "CORRECTO", false, true, "", "/admin/slider");

        } else {
            \Responses::message("Ha ocurrido un error al guardar el producto", "ERROR",true);

        }


    }


    function update()
    {
        if(empty($data["is_top"])){$data["is_top"] = 0; }
        $data = self::httpPost('');
        $image = self::httpFile("image");

        if(count($image)>= 1)
        {
            $file = \UploadFile::upload($image, array("png", "jpg", "jpge"), "1", "storage/slider",true);
            if ($file["status"] == 1) {
                $data["image"] = $file["path"];
            }
        }
        Query::qUpdateAllWhere("common_slider",$data,"id_slider",$data["id_slider"]);
        \Responses::message("Se ha actualizado correctamente el slider <br>" . $data["title"], "CORRECTO");




    }


    function delete()
    {
        $id = self::httpPost("id_slider");
        self::model("common_slider")->delete("id_slider", $id);
        \Responses::message("Se ha eliminado correctamente el slider<br>", "CORRECTO", "", "", true, "");


    }


}