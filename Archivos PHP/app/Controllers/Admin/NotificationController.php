<?php
/**
 * Created by PhpStorm.
 * User: Eddie
 * Date: 10/09/2017
 * Time: 1:26
 */

namespace Admin;
use Query;

class NotificationController extends \MasterController
{
    public $arr_generate;

    function __construct()
    {
        parent::__construct();
    }


    // Funcion para obtener las notificaciones  no leidas //
    public static function getNoReaderNotification($id_user){
        self::setSessionInstance("session_user_number_noreader", null);
        $data = Query::qMultiWhere("common_notification", array('id_user_receiver' => $id_user,'status' => 0), "AND","id_notification","DESC");
        self::setSessionInstance("session_user_number_noreader", $data);

    }


    // Funcion para obtener las notificaciones  no leidas //
    public static function getNotification($id_user){
        self::setSessionInstance("session_user_notification", null);
        $data = Query::qWhere("common_notification","id_user_receiver", $id_user,true,"id_notification","DESC");
        self::setSessionInstance("session_user_notification", $data);
     }

    // Funcion para leer las notificaciones //
    public static function readerNotification(){

        Query::qUpdateWhere("common_notification" , "status", 1,"id_user_receiver",self::getSessionInstance("session_user_id"));
        self::setSessionInstance("session_user_number_noreader", null);
    }

    // Funcion para guardar notificaciones //
    public static function saveNotification($title,$type,$content = null,$id_user_receiver,$id_user_transmitter,$redir = "#"){
        @date_default_timezone_get();
        $data = array();
        $data["title"] = $title;
        $data["content"] = $content;
        $data["type"] = $type;
        $data["status"] =0;
        $data["id_user_receiver"] = $id_user_receiver;
        $data["id_user_transmitter"] = $id_user_transmitter;
        $data["date_re"] = date("Y-m-d h:i:s");
        $data["redir"] = $redir;
        self::model("common_notification")->add($data); // Se guardan los 2 campos para agregar el permiso

        UserController::getNotifications();
    }



    // Funcion para la actualizacion de informacion //
    function update(){



    }

    // Funcion para la eliminacion de informacion //
    function delete(){

    }


}