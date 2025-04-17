<?php

/**
 * Created by PhpStorm.
 * User: Eddie
 * Date: 30/11/2017
 * Time: 14:32
 */
class TextGenerator
{
    private static $mode = 'MCRYPT_BLOWFISH';
    private static $key = 'q!2wsd#45^532dfgTgf56njUhfrthu&^&ygsrwsRRsf';



    /**
     * @return string
     */
    public static function genCode($length = 10){
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;

    }

    public static function calIva($sin_iva) {
        $iva = 16;
        $con_iva = $sin_iva + ($iva*($sin_iva/100));
        $con_iva = round($con_iva, 2);
        return $con_iva;
    }




    public static function encrypt($buffer){
        $iv                 = mcrypt_create_iv(mcrypt_get_iv_size(constant(self::$mode), MCRYPT_MODE_ECB), MCRYPT_RAND);
        $passcrypt  = mcrypt_encrypt(constant(self::$mode), self::$key, $buffer, MCRYPT_MODE_ECB, $iv);
        $encode         = base64_encode($passcrypt);
        return $encode;
    }

    public static function decrypt($buffer){
        $decoded        = base64_decode($buffer);
        $iv                 = mcrypt_create_iv(mcrypt_get_iv_size(constant(self::$mode), MCRYPT_MODE_ECB), MCRYPT_RAND);
        $decrypted  = mcrypt_decrypt(constant(self::$mode), self::$key, $decoded, MCRYPT_MODE_ECB, $iv);
        return $decrypted;
    }


    public static function to_utf8( $str ) {

        $str = preg_replace("/%u([0-9a-f]{3,4})/i","&#x\\1;",urldecode($str));
        return html_entity_decode($str,null,'UTF-8');
    }
}