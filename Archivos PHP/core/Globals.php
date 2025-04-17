
<?php
/**
 * Core System Created by JoSaMaTo.
 * User: JMartinez
 * Date: 23/08/2017
 * Time: 02:17 PM
 */

class Globals extends Twig
{

    public function __construct()
    {
        parent::__construct();
        /** LOAD all global Variables */
    }

    /**
     * Call here NO AUTHENTICATED GLOBAL VARIABLES
     */
    public static function frontGlobals(){



    }

    /**
     * Call here only SESSION AUTHENTICATED GLOBALS
     */
    public static function authGlobals()
    {
        /**
         * SESSION VARIABLES
         * @var  $key
         * @var  $value
         */
        foreach($_SESSION as $key => $value) {
            Twig::set($key, $value);
        }
    }

    /**
     * Instance this method in a index.php
     */
    public static function globasVariablesLoader()
    {

        $twig =new self();

        if (Base::instance()->get('SESSION')) {
            self::authGlobals();
        }
        self::frontGlobals();

        unset($twig);
    }

}

