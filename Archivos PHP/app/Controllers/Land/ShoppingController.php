<?php
/**
 * Created by PhpStorm.
 * User: Eddie
 * Date: 10/09/2017
 * Time: 1:26
 */

namespace Land;

use Query;

class ShoppingController extends \MasterController
{


    public static function setProduct()
    {
        $product = self::httpPost('product');
        $product_data = Query::qWhere("common_product", "id_product", $product["id_product"]);
        if ($product_data["inventary"] >= $product["quantity"]) {
            $_SESSION["shopping"][] = array("id_product" => $product["id_product"], "quantity" => $product["q"]);
            \Responses::message("Producto agregado al carrito de compras", "CORRECTO", "", "", false, "/shopping");
        } else {
            \Responses::message("Lo sentimos, le informamos que no se cuenta con el inventario disponible para su compra de este producto\nPorfavor contacta a nuestro personal");
        }
    }

    public static function getInfoPucharse()
    {
        $products = array();
        $txnid = self::httpParam("txnid");
        $token = self::httpParam("token");

        $pucharse = Query::qMultiWhere("common_sell", array("transaction_id" => $txnid, "token" => $token), "AND")[0];
        $search_product = Query::qWhere("common_product_sell", "transaction_id", $pucharse["transaction_id"],true);

        foreach ($search_product as $product) {
            $products[] = Query::qUniqueWhere(array("common_product","common_product_sell"), "code", $product["code"]);
        }

        return array("products" => $products, "info" => $pucharse);

    }


    public static function getShopping()
    {

        $products = $_SESSION["shopping"];
        $data = null;

        if (!empty($products)) {
            foreach ($products as $product) {
                $product_data = Query::qWhere("common_product", "id_product", $product["id_product"]);
                if ($product_data["porcent_discount"] >= 0) {
                    $discount = ($product_data["price"] * $product_data["porcent_discount"] / 100);
                    $price = $product_data["price"];
                    $quantity = $product["quantity"];
                    $subtotal = (($price - $discount) * $product["quantity"]);
                    $iva = \TextGenerator::calIva($subtotal);

                } else {
                    $discount = 0;
                    $price = $product_data["price"];
                    $quantity = $product["quantity"];
                    $subtotal = $price * $product["quantity"];
                    $iva = \TextGenerator::calIva($subtotal);


                }
                $data[] = array("product" => $product_data,
                    "discount" => $discount,
                    "price" => $price,
                    "quantity" => $quantity,
                    "subtotal" => $subtotal,
                    "iva" => $iva
                );
            }
        }
        return $data;
    }

    public static function delProduct()
    {
        $id = self::httpPost('id_product');
        unset($_SESSION["shopping"][$id]);
        \Responses::message("Se ha eliminado correctamente el producto","CORRECTO","","",true);
    }

    public static function delShopping($response_message = true)
    {
        if ($response_message == true) {
            unset($_SESSION["shopping"]);
            \Responses::message("Se ha eliminado correctamente el carrito de compras", "CORRECTO", "", "", true);
        } else {
            unset($_SESSION["shopping"]);
        }
    }

}


