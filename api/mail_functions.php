<?php

require_once($_SERVER['DOCUMENT_ROOT']."/cms/config/constants.php");
require_once($_SERVER['DOCUMENT_ROOT']."/cms/business/Phpmailer.class.php");

function send($body, $subject, $subscriberEmail) 
{
		$timezone = "Asia/Calcutta";
		date_default_timezone_set($timezone);
		$mail  = new PHPMailer();
		$mail->CharSet = 'UTF-8';
		$mail->IsSMTP();    // set mailer to use SMTP
		$mail->Host = MAIL_HOST;    // specify main and backup server
		$mail->SMTPAuth = true;    // turn on SMTP authentication
		$mail->Username = MAIL_FROM;    // Gmail username for smtp.gmail.com -- CHANGE --
		$mail->Password = MAIL_FROM_PWD;    // SMTP password -- CHANGE --
		$mail->Port = MAIL_PORT;    // SMTP Port
		$mail->Subject = $subject;
		$mail->MsgHTML($body);
		$mail->IsHTML(true);
		$mail->SetFrom(MAIL_FROM, 'Subscribe IIITA');
		$mail->From = MAIL_FROM;    //From Address -- CHANGE --
		$mail->FromName = "IIITA-Subscriptions";    //From Name -- CHANGE --
		$mail->AddAddress($subscriberEmail, "");    //To Address -- CHANGE --
		
		if(!$mail->Send()) {
			return 0;
		}
		return 1;
}

function get_mailtopic_byid($id)
{
	$qry = "SELECT * FROM sub_topics WHERE id = :id";
	$params = array(':id' => $id);
	$result = DatabaseHandler::GetAll($qry , $params);
	return $result;
}

?>
