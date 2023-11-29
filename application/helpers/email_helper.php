<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require APPPATH . 'libraries/phpmailer/src/Exception.php';
require APPPATH . 'libraries/phpmailer/src/PHPMailer.php';
require APPPATH . 'libraries/phpmailer/src/SMTP.php';

function sendEmail($destinationEmail, $pass)
{

	$from = "admin@umkmtohaga.com";
	$to = $destinationEmail;
	$subject = "Reset Password";
	$message = "Halo " . $destinationEmail . "<BR><br>";
	$message .= "Password baru kamu adalah " . $pass . "<br>";
	$message .= "Silahkan login dengan password ini.<br><br>";
	$message .= "Terima kasih,";

	$separator = md5(time());
	$header = "From: UMKM Tohaga <admin@umkmtohaga.com>\r\n";
	$header .= "Reply-To:" . $from . "\r\n" .
		"X-Mailer: PHP/" . phpversion();
	$header .= "Organization: UMKM Tohaga\r\n";
	$header .= "Return-Path: UMKM Tohaga <admin@umkmtohaga.com>\r\n";
	$header .= "MIME-Version: 1.0\r\n";
	$header .= "Content-Type: text/html; charset: utf8; boundary=\"" . $separator . "\"";
	$header .= "Content-Transfer-Encoding: 7bit";

	mail($to, $subject, $message, $header);
	// echo "Pesan email sudah terkirim.";
}

function send_email_notif($recipient, $subject, $message, $attachment_file = '', $attachement_name='')
{

    $email = new PHPMailer();

    $email->SMTPDebug =0;                                 // Enable verbose debug output
    $email->isSMTP();                                      // Set mailer to use SMTP
    $email->Host = 'srv102.niagahoster.com';  // Specify main and backup SMTP servers
    $email->SMTPAuth = true;                               // Enable SMTP authentication
    $email->Username = 'esign.sjs@sinarjernihsuksesindo.com';                 // SMTP username
    $email->Password = 'Mu5t1kaR@tuJkt!';                           // SMTP password
    $email->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
    $email->Port = 587;                      
    
    
    $default_sender_name = get_sys_setting("023");
    $default_sender_alias_name = get_sys_setting("027");
  //  $email->SetFrom($default_sender_name, $default_sender_alias_name); //Name is optional
    $email->SetFrom($default_sender_name); //Name is optional
    $email->Subject   = $subject; // 'Dokumen Kontrak - '.$nm_pegawai;
    //Send HTML or Plain Text email
    $email->isHTML(true);
    $separator = md5(time());
    $headers  = "MIME-Version: 1.0" ;
    $headers .= "Content-Type: multipart/mixed; boundary=\"" . $separator . "\"" ;
    $headers .= "Content-Transfer-Encoding: 7bit" ;
    $email->AddCustomHeader($headers); 
  //  $email->AddCustomHeader('Content-Type: multipart/mixed'); 
  //  $email->AddCustomHeader("X-Mailer: PHP/" . phpversion()); 
    $email->addReplyTo($default_sender_name, "PKWT Online SJS");

    $email->Body = $message;

    $email->AddAddress($recipient);   
    $email->AddAttachment($attachment_file,$attachement_name);

   return $email->Send();
}


function send_email_test()
{

    $recipient = 'jaja.suparja@gmail.com';
    $subject =" Email pkwt "; 
    $message = "Hi Jajas,<br><br>Selamat karena sudah menjadi bagian dari keluarga besar SJS<br><br>Silahkan melakukan penandatangan Kontrak di link berikut<br>Link akan expired setelah 14 hari";
    $attachment_file = 'docs/pdf/PKWT_SJB001010520_I.pdf';
    $email = new PHPMailer();
    $default_sender_name = get_sys_setting("023");
    $default_sender_alias_name = get_sys_setting("027");
    $email->SetFrom($default_sender_name); 
    $email->Subject   = $subject; 

    //Send HTML or Plain Text email
    $email->isHTML(true);
    $separator = md5(time());
    $headers  = "MIME-Version: 1.0" ;
    $headers .= "Content-Type: multipart/mixed; boundary=\"" . $separator . "\"" ;
    $headers .= "Content-Transfer-Encoding: 7bit" ;
    $email->AddCustomHeader($headers); 
  //  $email->AddCustomHeader('Content-Type: multipart/mixed'); 
  //  $email->AddCustomHeader("X-Mailer: PHP/" . phpversion()); 
    $email->addReplyTo($default_sender_name, "PKWT Online SJS");

    $email->Body = $message;

    $email->AddAddress($recipient);   
    $email->AddAttachment($attachment_file);

   return $email->Send();
}


function send_email_notif_check($recipient, $subject, $message)
{

    $email = new PHPMailer();
    
    $email->SMTPDebug =0;                                 // Enable verbose debug output
    $email->isSMTP();                                      // Set mailer to use SMTP
    $email->Host = 'srv102.niagahoster.com';  // Specify main and backup SMTP servers
    $email->SMTPAuth = true;                               // Enable SMTP authentication
    $email->Username = 'esign.sjs@sinarjernihsuksesindo.com';                 // SMTP username
    $email->Password = 'Mu5t1kaR@tuJkt!';                           // SMTP password
    $email->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
    $email->Port = 587;   
    
    $default_sender_name = get_sys_setting("023");
    $default_sender_alias_name = get_sys_setting("027");
  //  $email->SetFrom($default_sender_name, $default_sender_alias_name); //Name is optional
    $email->SetFrom($default_sender_name); //Name is optional
    $email->Subject   = $subject; // 'Dokumen Kontrak - '.$nm_pegawai;
    //Send HTML or Plain Text email
    $email->isHTML(true);
    $email->AddCustomHeader('MIME-Version: 1.0" . "\r\n'); 
    $email->AddCustomHeader('Content-type: text/html; charset=iso-8859-1" . "\r\n'); 
    $email->AddCustomHeader("X-Mailer: PHP/" . phpversion()); 
    $email->addReplyTo($default_sender_name, "PKWT Online SJS");

    $email->Body = $message;

    $email->AddAddress($recipient);   

    return $email->Send();
}