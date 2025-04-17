<?php
/**
 * Created by PhpStorm.
 * User: Eddie
 * Date: 10/09/2017
 * Time: 1:26
 */

namespace Land;
use PHPMailer\PHPMailer\PHPMailer;
use Mail;
use Query;
class ClientController extends \MasterController
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




    public static function getOrders(){
        $data_user = self::getSessionInstance("session_data_user");
        return Query::qWhere("common_sell","id_user",$data_user["id_user"],true);

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






    // Funcion de guardado de informacion //
    public function save()
    {
        $data = self::httpPost('data');
        $user = Query::qMultiWhere("common_user", array("email" => $data["email"],"id_user" => $data["id_user"]), "OR");
        $dir_website = \Base::instance()->get('DIR_BASE');
        $name =  $data["name"];
        $data["id_rol"] = "CLIENT";
        $data["password"] = password_hash($data["password"], PASSWORD_DEFAULT);
        if (count($user) <= 0) {

            Mail::sendHtml(
                $data["email"],
                "Registro completo",
                "<b>¡Gracias por la oportunidad de servirle!</b><br><br>
                Estimado cliente: $name<br>
                En nuestra tienda en linea creemos firmemente en las relaciones de sociedad a largo plazo con
                nuestros clientes. Buscamos tener la oportunidad de crecer y mejorar con ustedes,
                logrando metas comunes que nos unan cada día más. Deseamos que dicha relación no se
                refiera únicamente a la compra, sino que vaya evolucionando y permeando en los
                distintos niveles de nuestras organizaciones para que más que vernos como cliente y
                proveedor, nos terminemos viendo como socios comerciales.<br> 
                Ahora puedes acceder desde nuestra area de clientes.<br><br>
                 Ingresa desde <a href='$dir_website/login'>$dir_website/login</a>
                ");
            self::model("common_user")->add($data);

            \Responses::message("Te has registrado correctamente, ahora puedes iniciar sesion y procesar tus pedidos " . $data["id_user"], "CORRECTO", false, true,"",$dir_website."/login");
        } else {
            \Responses::message("El usuario " . $data["id_user"] . " ya existe\nIntenta con un nuevo usuario", "ERROR", false, false,"");
        }
    }

    function update()
    {

        $data = self::httpPost('data');
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