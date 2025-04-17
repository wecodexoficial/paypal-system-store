<?php
/**
 * Created by PhpStorm.
 * User: Eddie
 * Date: 10/09/2017
 * Time: 1:26
 */

namespace Admin;
use Query;
class UserController extends \MasterController
{
    public $arr_generate;

    function __construct()
    {
        parent::__construct();
    }


    public static function getAutorizersData()
    {
        $data_autorizer = Query::qWhere("common_user", "id_rol", "AUTORIZER");
        return array($data_autorizer);
    }

    public static function getAllRollData(){
        return self::model('common_rol')->all();
    }


    public static function getAllUserData()
    {
        return self::model('common_user')->all();
    }


    public static function getUserByID()
    {
        $id = self::httpPost('id');
        \Responses::message(Query::qWhere("common_user", "id_user", $id), "CORRECTO");
    }

    public static function getUserEdit()
    {
        $id = self::httpParam('id');
        return Query::qWhere("common_user", "id_user", $id);
    }


    public static function getNotifications()
    {

        $user_auth = self::getSessionInstance("session_user_id");
        NotificationController::getNotification($user_auth);
        NotificationController::getNoReaderNotification($user_auth);

    }




    // Funcion de guardado de informacion //
    public function save()
    {
        $data = self::httpPost('');
        $user = Query::qWhere("common_user", "id_user", $data["id_user"]);

        $data["password"] = password_hash($data["password"], PASSWORD_DEFAULT);
        if (count($user) <= 0) {
            self::model("common_user")->add($data);
            \Responses::message("Se ha agregado correctamente al usuario " . $data["id_user"], "CORRECTO", false, true,"","/admin/user");
        } else {
            \Responses::message("El usuario " . $data["id_user"] . " ya existe\nIntenta con un nuevo usuario", "ERROR", false, false,"");
        }
    }

    function update()
    {

        $data = self::httpPost('');
        if(!empty($data["password"])) {
            $data["password"] = password_hash($data["password"], PASSWORD_DEFAULT);
            Query::qUpdateAllWhere("common_user", $data, "id_user", $data["id_user"]);
            \Responses::message("Se ha actualizado correctamente el usuario <br>" . $data["name"]."<br>Se ha actualizado la contraseña", "CORRECTO");
        }else{
            unset($data["password"]);
            Query::qUpdateAllWhere("common_user", $data, "id_user", $data["id_user"]);
            \Responses::message("Se ha actualizado correctamente el usuario <br>" . $data["name"], "CORRECTO");
        }


    }


    function delete()
    {
        $id = self::httpPost("id_user");
        $id_rol = self::httpPost("id_rol");

        if($id_rol == "MASTER"){
            \Responses::message("No se pueden eliminar usuarios maestros","ERROR",true,"","","");
            exit();
        }
        self::model("common_user")->delete("id_user",$id);
        \Responses::message("Se ha eliminado correctamente el usuario<br>","CORRECTO","","",true,"");

    }


}