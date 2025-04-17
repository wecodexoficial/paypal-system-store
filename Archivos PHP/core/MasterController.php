<?php

/**
 * MasterController.php
 * Created By: Josué Saúl Martínez
 * Date: 27/04/2017
 * Time: 07:56
 */



class MasterController
{
    public static $viewparams = array();
    protected static $f3;
    protected static $twig;
    protected $db;

    function __construct()
    {

        self::$f3 = Base::instance();
    }
    /**********************
     * @param $table
     * @return MasterModel
     **********************/
    public static function model($table){
        return new MasterModel(Conexions::dbManager(),$table);
    }

    /**
     * @param $query
     * @return array|FALSE|int
     */
    public static function dbQuery($query)
    {
        $custom = Conexions::dbManager();
        return $custom->exec($query);
    }


    /***********
     * HTTP GET
     ***********/

    public static function httpParam($param){
        return self::$f3->get('PARAMS.'.$param);
    }

    /***********
     * HTTP POST
     ***********/

    public static function httpPost($param){
        return self::$f3->get('POST.'.$param);
    }


    public static function httpFile($param){
        return self::$f3->get('FILES.'.$param);
    }

    /**
     * @param $param
     * @return mixed
     */
    public static function httpGet($param){
        return self::$f3->get('GET.'.$param);
    }

    public static function httpRedir($url,$replace){
        header('Location: ' . $url, $replace);
    }

    /**
     * @param $html
     */

    public static function render($html)
    {
        echo Twig::loadTwig()->render($html, self::$viewparams);
    }

    /**
     * @param $html
     * @return string
     */
    public static function preRender($html){

        return Twig::$twig->render($html, self::$viewparams);
    }

    /*****************
     * @param $param
     * @param $object
     *****************/
    public static function  setSessionInstance($param, $object)
    {
        self::$f3->set('SESSION.' . $param, $object);
   }

    /*****************
     * @param $param
     * @param $time
     * @param $value
     *****************/
    public function setCookie($param, $time, $value)
    {
        self::$f3->set('COOKIE.' . $param, $value, $time);

    }

    /*****************
     * @param $param
     * @return mixed
     *****************/
    public function getCookie($param)
    {
        return self::$f3->get('COOKIE.' . $param);
    }

    /*****************
     * @param $param
     * @param $object
     *****************/
    public function setSessionTimer($param, $object)
    {
        self::$f3->set('SESSION.' . $param, $object, 3600);

    }

    /*****************
     * @param $param
     * @return mixed
     *****************/
    public static function getSessionInstance($param)
    {
        return self::$f3->get('SESSION.' . $param);
    }

    /*****************
     * @param $param
     *****************/

    public static function flushSessionIntance($param)
    {
        self::$f3->clear("SESSION.$param");
    }

    /**********************
     * Flush all Sessions
     **********************/

    public function flushSessionAll()
    {
        self::$f3->clear('SESSION');
    }

    public function __destruct()
    {

        $this->db = NULL;
    }
}