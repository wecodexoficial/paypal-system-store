<?php
/**
 * Created by PhpStorm.
 * User: Eddie
 * Date: 10/09/2017
 * Time: 1:26
 */

namespace Admin;
use Query;

class ProductController extends \MasterController
{
    public $arr_generate;

    function __construct()
    {
        parent::__construct();
    }


    public static function getAllProductData()
    {
        $products = self::model('common_product')->all();
        $response = array();
        if(!empty($products)) {
            foreach ($products as $product) {
                $mark = Query::qWhere("common_mark", "id_mark", $product["id_mark"], false);
                $provider = Query::qWhere("common_provider", "id_provider", $product["id_provider"], false);
                $category = Query::qWhere("common_category", "id_category", $product["id_category"], false);
                $response[] = array("product" => $product, "mark" => $mark, "provider" => $provider, "category" => $category);
            }
        }
        return $response;
    }

    public static function getProductByID()
    {
        $id = self::httpPost('id');
        $product = Query::qWhere("common_product", "id_product", $id);
        $mark = Query::qWhere("common_mark", "id_mark", $product["id_mark"], false);
        $provider = Query::qWhere("common_provider", "id_provider", $product["id_provider"], false);
        $category = Query::qWhere("common_category", "id_category", $product["id_category"], false);
        return array("product" => $product, "mark" => $mark, "provider" => $provider, "category" => $category);

    }

    public static function getProductEdit()
    {
        $id = self::httpParam('id');
        $product = Query::qWhere("common_product", "id_product", $id);
        $mark = Query::qWhere("common_mark", "id_mark", $product["id_mark"], false);
        $provider = Query::qWhere("common_provider", "id_provider", $product["id_provider"], false);
        $category = Query::qWhere("common_category", "id_category", $product["id_category"], false);
        $images = Query::qWhere("common_product_storage", "id_product", $product["id_product"]);
        return array("product" => $product, "mark" => $mark, "provider" => $provider, "category" => $category, "images" => $images);

    }


    public function save()
    {
        $data = self::httpPost('');
        $files = self::httpFile("images");
        $code = \TextGenerator::genCode();

        $data["code"] = $code;
        $id_product_last = self::model("common_product")->add($data);


        $files_up = \UploadFile::uploadMultiple($files, array("png", "jpg", "jpge"), "1", "storage/product/" . $code . "/");
        if ($files_up["status"] == 1) {
            foreach ($files_up["dirs"] as $dir) {
                $file_save = array("rute" => $dir, "id_product" => $id_product_last);
                self::model("common_product_storage")->add($file_save);
            }
            \Responses::message("Se ha agregado correctamente el producto <br> " . $data["name"], "CORRECTO", false, true, "", "/admin/product");

        } else {
            \Responses::message("Ha ocurrido un error al guardar el producto<br> " . ($files_up["messages"]), "ERROR", false, true, "", "/admin/product");
        }
    }


    function update()
    {
        $data = self::httpPost('data');
        $inv = self::httpPost('inv');
        $files = self::httpFile("images");
        $delete_files = self::httpPost("delete_images");

        if(empty($data["is_top"])){$data["is_top"] = 0; }
        $code = $data["code"];
        $message_inv = null;

        if (!empty($inv["quantity"])) {
            $inventary = (intval($inv["inventary"]) + intval($inv["quantity"]));
            $data["inventary"] = $inventary;

            $inventary_data = array(
                "id_product" => $data["id_product"],
                "id_invoice" => null,
                "quantity" => $inv["quantity"],
                "type" => 2,
                "id_user" => self::getSessionInstance("session_user_id"),
                "date_re" => date("Y-m-d")
            );

            self::model("common_inventary")->add($inventary_data);
            $message_inv = "Se han agregado " . $inv["quantity"] . " unidades al inventario";
        }

        if (empty($delete_files)) {
            \UploadFile::delFolder("storage/product/" . $code);
            Query::qDeleteWhere("common_product_storage", "id_product", $data["id_product"]);
        }

        Query::qUpdateAllWhere("common_product", $data, "id_product", $data["id_product"]);


        if (!empty($files["name"][0])) {
            $files_up = \UploadFile::uploadMultiple($files, array("png", "jpg", "jpge"), "1", "storage/product/" . $code . "/");
            if ($files_up["status"] == 1) {
                foreach ($files_up["dirs"] as $dir) {
                    $file_save = array("rute" => $dir, "id_product" => $data["id_product"]);
                    self::model("common_product_storage")->add($file_save);
                }
                \Responses::message("Se ha actualizado correctamente el producto <br><br>" .$message_inv. "<br>". $data["name"], "CORRECTO", true, "", true);

            } else {
                \Responses::message("Ha ocurrido un error al guardar el producto<br> " . ($files_up["messages"]), "ERROR", true, "", "", "/admin/product");

            }
        }
        \Responses::message("Se ha actualizado correctamente el producto <br>" .$message_inv. "<br>". $data["name"], "CORRECTO", true, "", true);

    }


    function delete()
    {
        $id = self::httpPost("id_product");
        self::model("common_product")->delete("id_product", $id);
        \Responses::message("Se ha eliminado correctamente el producto<br>", "CORRECTO", "", "", true, "");


    }


}