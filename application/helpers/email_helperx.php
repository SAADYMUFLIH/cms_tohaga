<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require APPPATH . 'libraries/phpmailer/src/Exception.php';
require APPPATH . 'libraries/phpmailer/src/PHPMailer.php';
require APPPATH . 'libraries/phpmailer/src/SMTP.php';


function send_email_notif($recipient, $subject, $message, $attachment_file = '')
{

    $email = new PHPMailer();
    $default_sender_name = get_sys_setting("023");
    $default_sender_alias_name = get_sys_setting("027");
    $email->SetFrom($default_sender_name, $default_sender_alias_name); //Name is optional
    $email->Subject   = $subject; // 'Dokumen Kontrak - '.$nm_pegawai;
    //Send HTML or Plain Text email
    $email->isHTML(true);

    $email->Body = $message;

    $email->AddAddress($recipient);   
    $email->AddAttachment($attachment_file);

   return $email->Send();
}


function send_email_notif_check($recipient, $subject,  $list_additional_recipient, $message, $attachment_file = '')
{

    $email = new PHPMailer();
    $default_sender_name = get_sys_setting("023");
    $default_sender_alias_name = get_sys_setting("027");
    $email->SetFrom($default_sender_name, $default_sender_alias_name); //Name is optional
    $email->Subject   = $subject; // 'Dokumen Kontrak - '.$nm_pegawai;
    //Send HTML or Plain Text email
    $email->isHTML(true);

    $email->Body = $message;

    // if (is_array($list_additional_recipient)) {
    //     foreach ($list_additional_recipient as $dt) {
    //         $email->AddAddress($dt['email_address']);
    //     }
    // }

    // if (is_array($list_recipient)) {
    //     foreach ($list_recipient as $dt) {
    //         $email->AddAddress($dt['email']);            
    //         //$email->AddAddress($dt['email_karyawan']);
    //     }
    // }

    $email->AddAddress('jaja.suparja@gmail.com');   

    $email->AddAttachment($attachment_file);

   return $email->Send();
}