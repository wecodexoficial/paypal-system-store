<?php
/**
 * Created by PhpStorm.
 * User: Eddie
 * Date: 10/09/2017
 * Time: 1:26
 */

namespace Admin;
use Query;

class SettingController extends \MasterController
{
    public $arr_generate;

    function __construct()
    {
        parent::__construct();
    }


    public static function getInfoWebsite(){
        return Query::qWhere("core_settings","id_config","1",false);
    }


   public static function update(){
        $data = self::httpPost('');
       $image = self::httpFile("image");

       if(count($image)>= 1)
       {
           $file = \UploadFile::upload($image, array("png", "jpg", "jpge"), "1", "storage/logos",true);
           if ($file["status"] == 1) {
               $data["ws_logo"] = $file["path"];
           }
       }
       Query::qUpdateAllWhere("core_settings",$data,"id_config",1);


        \Responses::message("Se han actualizando las configuraciones", "CORRECTO");

    }



}