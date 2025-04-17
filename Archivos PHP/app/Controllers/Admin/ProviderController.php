<?php
/**
 * Created by PhpStorm.
 * User: Eddie
 * Date: 10/09/2017
 * Time: 1:26
 */

namespace Admin;
use Query;

class ProviderController extends \MasterController
{
    public $arr_generate;

    function __construct()
    {
        parent::__construct();
    }



    public static function getALLProviderData()
    {
        return self::model('common_provider')->all();
    }


    public static function getClientByID()
    {
        return self::model('common_client')->all();
    }


    public static function getProviderEdit()
    {
        $id = self::httpParam('id');
        return Query::qWhere("common_provider", "id_provider", $id);
    }


    public function save()
    {
        $data = self::httpPost('');
        self::model("common_provider")->add($data);
        \Responses::message("Se ha agregado correctamente el proveedor <br>" . $data["name"], "CORRECTO", false, true,"","/admin/provider");

    }


    function update(){
        $data = self::httpPost('');
        Query::qUpdateAllWhere("common_provider",$data,"id_provider",$data["id_provider"]);
        \Responses::message("Se ha actualizado correctamente el proveedor <br>" . $data["name"], "CORRECTO");

    }

    function delete()
    {
        $id = self::httpPost("id_provider");
        self::model("common_provider")->delete("id_provider",$id);
        \Responses::message("Se ha eliminado correctamente el proveedor<br>","CORRECTO","","",true,"");


    }


}