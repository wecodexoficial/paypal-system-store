<?php
/**
 * User: JMartinez
 * Date: 07/08/2017
 * Time: 10:50 AM
 */

class Locales
{
    protected static $f3;
    public static function getLocale(){
        self::$f3=Base::instance();
        self::$f3->set('LOCALES','dict/');
        if ($idioma = self::$f3->get('POST.language_selector')){
            self::$f3->set('LANGUAGE',$idioma);
            self::$f3->set('SESSION.language_selector',$idioma);
            self::$f3->set('SESSION.translate',Base::instance()->get('LANG'));
            \Responses::message('Has Cambiado el idioma correctamente',"CORRECTO");
        }else{
            self::$f3->set('LANGUAGE',self::$f3->get('SESSION.language_selector'));
            self::$f3->get('SESSION.language_selector');
            self::$f3->set('SESSION.translate',Base::instance()->get('LANG'));
        }
    }
}