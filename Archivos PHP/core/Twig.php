<?php
/**
 * User: JMartinez
 * Date: 31/07/2017
 * Time: 09:54 AM
 */

class Twig
{

    public static $twig;

    public function __construct()
    {
        $loader = new Twig_Loader_Filesystem(Base::instance()->get('TEMPLATES'));
        self::$twig= new Twig_Environment($loader, array(
            'cache' => Base::instance()->get('TEMP'),
            'debug' => true,
            'auto_reload' => true
        ));
        self::$twig->addExtension(new Twig_Extensions_Extension_Text());
        self::$twig->addExtension(new \Snilius\Twig\SortByFieldExtension());
        self::$twig->addExtension(new Twig_Extension_Debug());
        self::$twig->addGlobal('base', Base::instance()->get('DIR_BASE'));
    }

    /**
     * @return Twig_Environment
     */
    public static function loadTwig()
    {
        return self::$twig;
    }

    /**
     * @param $global
     * @param $value
     */
    public static function set($global,$value){
        self::loadTwig()->addGlobal($global,$value);
    }


}