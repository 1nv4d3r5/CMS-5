<?php 
require_once($_SERVER['DOCUMENT_ROOT']."/cms/config/constants.php");
require_once($_SERVER['DOCUMENT_ROOT']."/cms/business/Database_handler.class.php");
require_once($_SERVER['DOCUMENT_ROOT']."/cms/business/Phpmailer.class.php");
require_once($_SERVER['DOCUMENT_ROOT']."/cms/api/mail_functions.php");
		
		$qry_subscriptions = "SELECT * FROM sub_subscriptions WHERE is_valid = 1 AND isdeleted = 0";
		$result_subscriptions = DatabaseHandler::GetAll($qry_subscriptions);
				
		$flag = 0;
		global $topics_cont;
		
		for ($i = $c = 0; $i < count($result_subscriptions); $i++)
		{
			$body = "";
			$qry_unsub = "SELECT * FROM sub_subscribers WHERE email = :email AND isdeleted = 0";
			$params = array(':email' => $result_subscriptions[$i]['subscriber_id']);
			$result_unsub = DatabaseHandler::GetAll($qry_unsub, $params);
		
			if (!$result_unsub) {
				$flag = 1;
				echo "SQL Error. Mail could not be sent!";
				break;
			}
		
			$unsub_url = URL.'features/unsubscribe.php?unsub_id=';
			$unsub_url .= $result_unsub[0]['confirmation_code'];
			$qry_topics = "SELECT * FROM sub_subscriptions WHERE subscriber_id = :subscriber_id AND isdeleted = 0 AND is_valid = 1";
			$params = array(':subscriber_id' => $result_subscriptions[$i]['subscriber_id']);
			$result_topics = DatabaseHandler::GetAll($qry_topics, $params);
		
			if (!$result_topics) {
				$flag = 1;
				echo "SQL Error. Mail could not be sent! Topic Could not be fetched.";
				break;
			}
		
			$topics = "";
			for ($j = 0; $j < count($result_topics); $j++)
			 {
				$result_mailtopics = get_mailtopic_byid($result_topics[$j]['type']);
				
				if (!$topics) 
					$topics = ucFirst($result_mailtopics[0]['title']);
				else 
					$topics = $topics. ', '.ucFirst($result_mailtopics[0]['title']);
			}

			$body_header = "<html><body><h4>This is an auto-generated mail from IIIT-A Subscriptions!</h4><br>";
			$body_footer = "<br><br>
			<b>For unsubscribing from IIIT-A Feeds click here -></b><u><a href = '$unsub_url'>Unsubscribe.</a></u>
			<br><br><br>
			------------------------------------------------------------------------<br>
			You are currently subscribed to mailing list for: '{$topics}'.<br>
			<br>To Edit your subscriptions, please resubscribe with the desired topics.<br>
			This email has been sent using ArithMail at <br>
			'Indian Institute of Information Technology, Allahabad, U.P, INDIA' <br>
			Web: http://www.iiita.ac.in, Email: contact@iiita.ac.in
			</body></html>
			";
			
			$top_type = $result_subscriptions[$i]['type'];
			$result_fetch_topics = get_mailtopic_byid($top_type);
		//	$qry_fetch_topics = "SELECT * FROM sub_topics WHERE id = :id";
		//	$params = array(':id' => $top_type);
		//	$res_fetch_topics = DatabaseHandler::GetAll($qry_fetch_topics, $params);
		
			$topic_title = strtolower($result_fetch_topics[0]['title']);
			
			$qry_content = "SELECT * FROM sub_content WHERE type = :type AND is_sent = 0 AND isdeleted=0";
			$params = array(':type' => $topic_title);
			$result_content = DatabaseHandler::GetAll($qry_content, $params);
			
			if (count($result_content)) 
			{
				for ($j = 0; $j < count($topics_cont); $j++) {
					if ($result_subscriptions[$i]['type'] == $topics_cont[$j]) {
						break;
					}
				}
				if ($j == count($topics_cont)) {
					$topics_cont[$c++] = $result_subscriptions[$i]['type'];
				}
				for ($k = 0; $k < count($result_content); $k++) {
					$body = "";
					$body = $body .  $body_header;
					$type = ucFirst($result_content[$k]['type']);
					$title = ucFirst($result_content[$k]['inner_title']);
					$link = URL.$result_content[$k]['type'].".php";
					$body .= "<b>$type Feed - $title</b><br>".$result_content[$k]['data']."<br><br>Read about it more - <a href = '$link'>$link</a>".$body_footer;				
					$mailto = $result_subscriptions[$i]['subscriber_id'];
					$mail_Subject = '[NEW] IIITA '.ucFirst($type).' Feed - '.$result_content[$k]['front_title'];
					if(!send($body, $mail_Subject, $mailto)) {
						echo "Message could not be sent.<br>";
						exit;
					}
				}
			}
		}
		if (1) {
//			for ($i = 0; $i < count($topics_cont); $i++){
		//		$sel_qry = "SELECT * FROM sub_content WHERE type = :type AND is_sent = 0 ND isdeleted = 0";
				$sel_qry_content = "SELECT * FROM sub_content WHERE is_sent = 0 AND isdeleted = 0";
		//		$params = array(':type' => $topics_cont[$i]);
				$res_sel_content = DatabaseHandler::GetAll($sel_qry_content);
				for ($j = 0; $j < count($res_sel_content); $j++) {
					$upd_content_qry = "UPDATE sub_content SET is_sent = 1 WHERE id = :id";
					$params = array(':id' => $res_sel_content[$j]['id']);
					$result_upd_content_qry = DatabaseHandler::Execute($upd_content_qry, $params);	
				}
//			}
			
			echo "Sent Successfully!";
		} else {
			echo "Content could not be emailed! SQL Error!";
			$flag = 1;
		}
		
?>
