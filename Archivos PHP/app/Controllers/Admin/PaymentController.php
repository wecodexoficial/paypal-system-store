<?php
/**
 * Created by PhpStorm.
 * User: Eddie
 * Date: 10/09/2017
 * Time: 1:26
 */

namespace Admin;
use Query;
use Mail;

class PaymentController extends \MasterController
{
    public $arr_generate;

    function __construct()
    {
        parent::__construct();
    }


    public static function getStadisticPayment(){

        $data = self::model('common_sell')->all();
        $total_fin = Query::qWhere("common_sell", "status",5,true);
        $total_money = 0;
        $total_tax = 0;
        if(!empty($data)) {
            foreach ($data as $sell) {
                $total_money = +$sell["atm"];
                $total_tax = +$sell["taxatm"];
            }
        }

        return array("total_money" =>$total_money , "total_tax" => $total_tax, "total_sell" => count($total_fin));
    }

    public static function getSellLast(){
        $data_response = array();
        $data = self::model('common_sell')->all(20);
        if(!empty($data)) {
            foreach ($data as $sell) {
                $client = Query::qWhere("common_user", "id_user", $sell["id_user"]);
                $data_response[] = array("client" => $client, "sell" => $sell);
            }
        }
        return $data_response;
    }


    public static function getAllPaymentsData()
    {
        $data_response = array();
        $data = self::model('common_sell')->all();
        if(!empty($data)) {
            foreach ($data as $sell) {
                $client = Query::qWhere("common_user", "id_user", $sell["id_user"]);
                $data_response[] = array("client" => $client, "sell" => $sell);
            }
        }
        return $data_response;
    }


    public static function getInfoPayment()
    {
        $txnid = self::httpParam('txnid');
        $products_arr = array();

        $data = Query::qWhere("common_sell", "transaction_id", $txnid);
        $client = Query::qWhere("common_user","id_user",$data["id_user"]);
        $products = Query::qWhere("common_product_sell","transaction_id",$txnid,true);

        if(!empty($data)) {
            foreach ($products as $product) {
                $products_arr[] = array(
                    "info" => Query::qWhere("common_product", "code", $product["code"]),
                    "sell" => $product
                );
            }
        }
        return array("sell" => $data,"client" => $client,"products" => $products_arr);
    }




    function update(){

        $data = self::httpPost('');
        $n_order = self::httpPost('transaction_id');
        Query::qUpdateAllWhere("common_sell",$data,"transaction_id",$data["transaction_id"]);
        $client = Query::qWhere("common_user","id_user",$data["id_user"]);
        $dir_website = \Base::instance()->get('DIR_BASE');
         if($data["status"] == 1) {
             $status = "Orden confirmada";
         }elseif($data["status"] == 2) {
             $status = "Orden de procesamiento";
         }elseif($data["status"] == 3) {
             $status = "Control de calidad";
         }elseif($data["status"] == 4) {
             $status = "Producto enviado";
         }elseif($data["status"] == 5) {
             $status = "Producto entregado";

        }
        Mail::sendHtml(
            $client["email"],
            "Estado de compra",
            "Estimado cliente, le informamos que su orden ha cambiado de estado<br><br>
                   <b>Numero de orden:</b> $n_order<br>
                   <b>Estado de orden:</b> $status<br><br>
                   Porfavor de acceder a su cuenta para mas detalles, gracias<br>
                   Ingresa desde <a href='$dir_website/login'>$dir_website/login</a>
                ");
        \Responses::message("Se ha actualizado el estado de la orden", "CORRECTO", true);

    }


    function delete()
    {
        $id = self::httpPost("id_category");
        self::model("common_category")->delete("id_category",$id);
        \Responses::message("Se ha eliminado correctamente la categoria<br>","CORRECTO","","",true,"");


    }


}