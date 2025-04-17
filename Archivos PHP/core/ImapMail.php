<?php
/**
 * Created by PhpStorm.
 * User: josam
 * Date: 06/10/2017
 * Time: 23:19
 */

class ImapMail
{

    public static function connect()
    {
        return imap_open(
            Base::instance()->get('IMAP_SERVER'),
            Base::instance()->get('IMAP_USERMAIL'),
            Base::instance()->get('IMAP_USERPASS')
        );
    }

    public static function getMails()
    {
        return imap_check(self::connect());
    }

    public static function getAttachments()
    {
        return imap_fetchstructure(self::connect(),2);

    }

}