<?php
/**
 *
 * CONTROLADOR DE RENDERIZACIONES
 * Desde este controlador se llevara acabo
 * las renderizaciones de cada una de las v
 * istas generadas
 *
 * Fecha: 24/08/2017
 * Hora: 04:39 PM
 *
 */

namespace Land;

use Admin\CategoryController;
use Admin\SettingController;
use Admin\SliderController;
use Land\ProductController;

class RenderController extends MarkController
{

    function __construct($notification)
    {
        parent::__construct();
        self::$viewparams["ws_info"] = SettingController::getInfoWebsite();
        self::$viewparams["hd_sliders"] = SliderController::getAllPublicSlider();
        self::$viewparams["ft_shopping"] = ShoppingController::getShopping();
        self::$viewparams["hd_categories"] = CategoryController::getAllCategoryData();
        self::$viewparams["hd_mark"] =  MarkController::getAllMarkData();
        self::$viewparams["hd_promotion"];

    }

    public function index()
    {
        self::$viewparams["product_top"] = ProductController::getAllProductTop();
        self::render('land/content/index.html.twig');

    }


    public function product_view()
    {
      self::$viewparams["data"] = ProductController::getProductByID();
      self::render("land/content/product.html.twig");
    }


    public function shopping()
    {
       // var_dump(ShoppingController::getShopping());
      self::render("land/content/shopping.html.twig");
    }


    public function complete_payment()
    {
        self::render("land/content/complete.html.twig");
    }



    public function search()
    {
       // print_r(json_encode(ProductController::getInfoSearch()));
        self::$viewparams["data"] = ProductController::search();
       self::render('land/content/search.html.twig');

    }

    /**
     * CLIENTE
     */
    public function client_login(){
        self::render('land/content/client/login.html.twig');
    }

    public function client_register(){
        self::render('land/content/client/register.html.twig');
    }

    public function client_profile(){
        self::render('land/content/client/profile.html.twig');
    }
    public function client_purchases(){
        self::$viewparams["orders"] = ClientController::getOrders();
        self::render('land/content/client/purchases.html.twig');
    }

    public function pucharse(){

        self::$viewparams["pucharse"] = ShoppingController::getInfoPucharse();
        self::render('land/content/client/purchase.html.twig');
    }



}