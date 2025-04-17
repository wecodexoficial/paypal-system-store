<?php
/**
 * Created by PhpStorm.
 * User: Eddie
 * Date: 10/09/2017
 * Time: 1:26
 */

namespace Land;
use Query;
class ProductController extends \MasterController
{
    public $arr_generate;

    function __construct()
    {
        parent::__construct();
    }


    public static function search()
    {
        $cat = self::httpGet("category");
        $product = self::httpGet("product");
        $mark = self::httpGet("mark");
        if (isset($product)) {
            return self::getInfoSearch();
        } elseif (isset($cat)) {
            return self::getInfoSearchCategory();
        } elseif (isset($mark)) {
            return self::getInfoSearchMark();
        }
    }

    public static function getInfoSearchMark(){

        $id = self::httpGet("mark");
        $mark_s = Query::qWhere("common_mark","short_name",$id);
        $products =  Query::qWhere("common_product","id_mark",$mark_s["id_mark"],true);
        $response = null;
        foreach ($products as $product) {
            $mark = Query::qWhere("common_mark", "id_mark", $product["id_mark"], false);
            $provider = Query::qWhere("common_provider", "id_provider", $product["id_provider"], false);
            $category = Query::qWhere("common_category", "id_category", $product["id_category"], false);
            $images = Query::qWhere("common_product_storage","id_product",$product["id_product"],true);

            $response[] = array("product" => $product, "images" => $images,"mark" => $mark, "provider" => $provider, "category" => $category);
        }
        return array("search" => $mark_s["name"],"response" =>$response);
    }

    public static function getInfoSearchCategory(){

        $id = self::httpGet("category");
        $category_s = Query::qWhere("common_category","short_name",$id);
        $products =  Query::qWhere("common_product","id_category",$category_s["id_category"],true);
        $response = null;
        foreach ($products as $product) {
            $mark = Query::qWhere("common_mark", "id_mark", $product["id_mark"], false);
            $provider = Query::qWhere("common_provider", "id_provider", $product["id_provider"], false);
            $category = Query::qWhere("common_category", "id_category", $product["id_category"], false);
            $images = Query::qWhere("common_product_storage","id_product",$product["id_product"],true);

            $response[] = array("product" => $product, "images" => $images,"mark" => $mark, "provider" => $provider, "category" => $category);
        }
        return array("search" => $category_s["name"],"response" =>$response);
    }

    public static function getInfoSearch(){

        $id = self::httpGet("product");

        $products =  Query::qWhereLike("common_product","name",$id,true);
        $response = null;
        foreach ($products as $product) {
            $mark = Query::qWhere("common_mark", "id_mark", $product["id_mark"], false);
            $provider = Query::qWhere("common_provider", "id_provider", $product["id_provider"], false);
            $category = Query::qWhere("common_category", "id_category", $product["id_category"], false);
            $images = Query::qWhere("common_product_storage","id_product",$product["id_product"],true);

            $response[] = array("product" => $product, "images" => $images,"mark" => $mark, "provider" => $provider, "category" => $category);
        }
        if(empty($id)) {
            return array("search" => $id, "response" => null);
        }else{
            return array("search" => $id, "response" => $response);
        }
    }


    /**
     * Obtiene todos los productos y toda su informacion de referencia
     * @return array|null
     */
    public static function getAllProductData()
    {
        $products = self::model('common_product')->all();
        $response = null;
        foreach ($products as $product) {
            $mark = Query::qWhere("common_mark", "id_mark", $product["id_mark"], false);
            $provider = Query::qWhere("common_provider", "id_provider", $product["id_provider"], false);
            $category = Query::qWhere("common_category", "id_category", $product["id_category"], false);
            $images = Query::qWhere("common_product_storage","id_product",$product["id_product"],true);

            $response[] = array("product" => $product, "images" => $images,"mark" => $mark, "provider" => $provider, "category" => $category);
        }
        return $response;
    }

    /**
     * Obtiene los productos top
     * @return mixed
     */
    public static function getAllProductTop()
    {
        $products = Query::qWhere("common_product","is_top", 1,true);
        $response = null;
        foreach($products as $product){
            $images = Query::qWhere("common_product_storage","id_product",$product["id_product"]);
            $response[] = array("product" => $product, "images" => $images);
        }
        return $response;

    }


    /**
     * Obtiene todos los productos por categoria
     * @return mixed
     */
    public static function getAllProductByCategory()
    {
       $id = self::httpParam("id");
       return Query::qWhere("common_product","id_category",$id);

    }


    /**
     * Obtiene los datos por producto
     * @return array
     */

    public static function getProductByID()
    {
        $id = self::httpParam('id');
        $product = Query::qWhere("common_product", "id_product", $id);
        $images = Query::qWhere("common_product_storage","id_product",$id);
        $mark = Query::qWhere("common_mark", "id_mark", $product["id_mark"], false);
        $provider = Query::qWhere("common_provider", "id_provider", $product["id_provider"], false);
        $category = Query::qWhere("common_category", "id_category", $product["id_category"], false);
        return array("product" => $product, "mark" => $mark, "provider" => $provider, "category" => $category, "images" => $images);

    }



}