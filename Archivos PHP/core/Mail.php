<?php

/**
 * Created by PhpStorm.
 * User: Eddie
 * Date: 05/01/2018
 * Time: 18:29
 */
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
class Mail extends \Admin\SettingController
{

    public static function sendHtml($email,$subjet,$html)
    {
        $mail = new PHPMailer(true);
        $mail->SMTPDebug = 0;                                 // Enable verbose debug output
        $mail->isSMTP();                                      // Set mailer to use SMTP
        $mail->Host = Base::instance()->get('MAIL_HOSTS');  // Specify main and backup SMTP servers
        $mail->SMTPAuth = Base::instance()->get('MAIL_SMTPAUTH');                               // Enable SMTP authentication
        $mail->Username = Base::instance()->get('MAIL_USERNAME');                 // SMTP username
        $mail->Password = Base::instance()->get('MAIL_PASSWORD');                           // SMTP password
        $mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
        $mail->Port = 587;
        $mail->setFrom(self::getInfoWebsite()["ws_mail_info"], self::getInfoWebsite()["ws_name"]);
        $mail->addAddress($email);
        $mail->Subject = $subjet;
        $mail->isHTML(true);
        $mail->Body = $html;
        $mail->send();


    }


    public static function sendMultiMail($emails_array,$subjet,$html)
    {
        $mail = new PHPMailer;
        $mail->setFrom(self::getInfoWebsite()["ws_mail_info"], self::getInfoWebsite()["ws_name"]);
        foreach($emails_array as $mail){
            $mail->addAddress($mail);
        }
        $mail->Subject = $subjet;
        $mail->isHTML(true);
        $mail->Body = $html;
        $mail->send();

    }
}