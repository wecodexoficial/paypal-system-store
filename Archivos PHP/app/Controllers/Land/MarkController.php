<?php
/**
 * Created by PhpStorm.
 * User: Eddie
 * Date: 10/09/2017
 * Time: 1:26
 */

namespace Land;
use Query;

class MarkController extends \MasterController
{
    public $arr_generate;

    function __construct()
    {
        parent::__construct();
    }



    public static function getAllMarkData()
    {
        return self::model('common_mark')->all();
    }

    public static function getMarkByID()
    {
        $id = self::httpPost('id');
        \Responses::message(Query::qWhere("common_mark", "id_mark", $id),"CORRECTO");
    }


}