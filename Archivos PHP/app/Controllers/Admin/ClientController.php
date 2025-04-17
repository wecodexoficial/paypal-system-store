<?php
/**
 * Created by PhpStorm.
 * User: Eddie
 * Date: 10/09/2017
 * Time: 1:26
 */

namespace Admin;
use Query;

class ClientController extends \MasterController
{
    public $arr_generate;

    function __construct()
    {
        parent::__construct();
    }



    public static function getAllClientData()
    {
        return Query::qWhere("common_user","id_rol","CLIENT",true);
    }


    public static function getClientByID()
    {
        $id = self::httpPost('id');
        \Responses::message(Query::qWhere("common_user", "id_user", $id),"CORRECTO");
    }

    public static function getClientEdit()
    {
        $id = self::httpParam('id');
        return Query::qWhere("common_user", "id_user", $id);
    }



    public function save()
    {
        $data = self::httpPost('');
        $data["id_rol"] = "CLIENT";
        self::model("common_user")->add($data);
        \Responses::message("Se ha agregado correctamente el cliente <br>" . $data["name"], "CORRECTO", false, true,"","/admin/client");

    }


    function update(){
        $data = self::httpPost('');
        if(!empty($data["password"])) {
            $data["password"] = password_hash($data["password"], PASSWORD_DEFAULT);
            $data["id_rol"] = "CLIENT";
            Query::qUpdateAllWhere("common_user", $data, "id_user", $data["id_user"]);
            \Responses::message("Se ha actualizado correctamente el cliente <br>" . $data["name"]."<br>Se ha actualizado la contraseña", "CORRECTO");
        }else{
            unset($data["password"]);
            $data["id_rol"] = "CLIENT";
            Query::qUpdateAllWhere("common_user", $data, "id_user", $data["id_user"]);
            \Responses::message("Se ha actualizado correctamente el cliente <br>" . $data["name"], "CORRECTO");
        }



    }

    function delete()
    {
        $id = self::httpPost("id_user");
        self::model("common_client")->delete("id_user",$id);
        \Responses::message("Se ha eliminado correctamente el cliente<br>","CORRECTO","","",true,"");

    }


}