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

namespace Admin;

class RenderController extends \MasterController
{

    public $notification;

    function __construct($notification)
    {
        parent::__construct();
        UserController::getNotifications();
        \Locales::getLocale();
    }

    /********************************************************
     *                     GLOBAL                          *
     ******************************************************/
    public function login()
    {
        self::render('admin/content/login.html.twig');
    }

    public function index()
    {

       // var_dump(PaymentController::getSellLast());
       // var_dump(PaymentController::getStadisticPayment());
       self::$viewparams["sell_last"] = PaymentController::getSellLast();
        self::$viewparams["sell_stadistic"] = PaymentController::getStadisticPayment();
        self::render('admin/content/index.html.twig');

    }

    public function profile()
    {
        self::render('admin/content/config/profile.html.twig');

    }
    public function notification()
    {
        self::render('admin/content/config/notification.html.twig');

    }

    public function config()
    {
        self::$viewparams["data"] = SettingController::getInfoWebsite();
        self::render('admin/content/config/config.html.twig');

    }


    /*****************************************************
     *                      CLIENTES                     *
     ****************************************************/

    public function client_list(){
        self::$viewparams["table"] = ClientController::getAllClientData();
        self::render('admin/content/client/list.html.twig');
    }
    public function client_add(){
        self::render('admin/content/client/add.html.twig');
    }

    public function client_edit(){
        self::$viewparams["data"] = ClientController::getClientEdit();
        self::render('admin/content/client/edit.html.twig');
    }


    /*****************************************************
     *                      PROVEEDORES                  *
     ****************************************************/

    public function provider_list(){
        self::$viewparams["table"] = ProviderController::getAllProviderData();
        self::render('admin/content/provider/list.html.twig');
    }
    public function provider_add(){
        self::render('admin/content/provider/add.html.twig');
    }

    public function provider_edit(){
        self::$viewparams["data"] = ProviderController::getProviderEdit();
        self::render('admin/content/provider/edit.html.twig');
    }

    /*****************************************************
     *                      PRODUCTOS                  *
     ****************************************************/

    public function product_list(){
        self::$viewparams["table"] = ProductController::getAllProductData();
        self::render('admin/content/product/list.html.twig');
    }
    public function product_add(){
        self::$viewparams["marks"] = MarkController::getAllMarkData();
        self::$viewparams["providers"] = ProviderController::getaLLProviderData();
        self::$viewparams["categories"] = CategoryController::getAllCategoryData();
        self::render('admin/content/product/add.html.twig');
    }

    public function product_edit(){
        self::$viewparams["marks"] = MarkController::getAllMarkData();
        self::$viewparams["providers"] = ProviderController::getaLLProviderData();
        self::$viewparams["categories"] = CategoryController::getAllCategoryData();
        self::$viewparams["data"] = ProductController::getProductEdit();
        self::render('admin/content/product/edit.html.twig');
    }


    /*****************************************************
     *                      CATEGORIAS                  *
     ****************************************************/

    public function category_list(){
        self::$viewparams["table"] = CategoryController::getAllCategoryData();
        self::render('admin/content/category/list.html.twig');
    }
    public function category_add(){
        self::render('admin/content/category/add.html.twig');
    }

    public function category_edit(){
        self::$viewparams["data"] = CategoryController::getCategoryEdit();
        self::render('admin/content/category/edit.html.twig');
    }


    /*****************************************************
     *                      SLIDER SHOW                  *
     ****************************************************/

    public function slider_list(){
        self::$viewparams["table"] = SliderController::getAllSliderData();
        self::render('admin/content/slider/list.html.twig');
    }
    public function slider_add(){
        self::render('admin/content/slider/add.html.twig');
    }

    public function slider_edit(){
        self::$viewparams["data"] = SliderController::getSliderEdit();
        self::render('admin/content/slider/edit.html.twig');
    }


    /*****************************************************
     *                      CATEGORIAS                  *
     ****************************************************/

    public function mark_list(){
        self::$viewparams["table"] = MarkController::getAllMarkData();
        self::render('admin/content/mark/list.html.twig');
    }
    public function mark_add(){
        self::render('admin/content/mark/add.html.twig');
    }

    public function mark_edit(){
        self::$viewparams["data"] = MarkController::getMarkEdit();
        self::render('admin/content/mark/edit.html.twig');
    }



    /*****************************************************
     *                      USUARIOS                     *
     ****************************************************/
    public function user_list()
    {
        self::$viewparams['autorizers'] = UserController::getAutorizersData();
        self::$viewparams['table'] = UserController::getAllUserData();
        self::$viewparams['rols'] =  UserController::getAllRollData();
        self::render('admin/content/user/list.html.twig');
    }

    public function user_add(){
        self::$viewparams['autorizers'] = UserController::getAutorizersData();
        self::$viewparams['rols'] = UserController::getAllRollData();
        self::render('admin/content/user/add.html.twig');
    }

    public function user_edit(){
        self::$viewparams['autorizers'] = UserController::getAutorizersData();
        self::$viewparams['rols'] = UserController::getAllRollData();
        self::$viewparams["data"] = UserController::getUserEdit();
        self::render('admin/content/user/edit.html.twig');
    }


    /*****************************************************
     *                      VENTAS                       *
     ****************************************************/
    public function sells()
    {
        self::$viewparams["data"] = PaymentController::getAllPaymentsData();
        self::render('admin/content/sells/list.html.twig');
    }

    public function sell_op()
    {
        self::$viewparams["data"] = PaymentController::getInfoPayment();
        self::render('admin/content/sells/detaills.html.twig');
    }


}