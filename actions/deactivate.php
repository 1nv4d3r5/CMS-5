<?php	
	require_once('business/DirCreator.class.php');
	if(isset($_POST['deactivate'])) 
	{
		if($type == "rpanel")
		{
			$y = inactivate('right_panel_topics', $value);
			$modified_by = $_SESSION['_iiita_cms_username_'];
			if($y) {
				$ip = $_SERVER['REMOTE_ADDR'];
                Logger::logToggle($modified_by, "deactivate", $type, $value, $ip);
				header('Location:' . Link::viewRpanelTopic());
			}
		}
		if($type == "rpanelcontent")
		{
			$y = inactivate('right_panel_content', $value);
			$modified_by = $_SESSION['_iiita_cms_username_'];
			if($y) {
				$ip = $_SERVER['REMOTE_ADDR'];
                Logger::logToggle($modified_by, "deactivate", $type, $value, $ip);
				header('Location:' . Link::viewRpanelTopic());
			}
		}
		if($type == "events")
		{
			$y = inactivate('events', $value);
			$modified_by = $_SESSION['_iiita_cms_username_'];
			if($y) {
				$ip = $_SERVER['REMOTE_ADDR'];
				Subscription::Delete($type, $value);
                Logger::logToggle($modified_by, "deactivate", $type, $value, $ip);
				header('Location:' . Link::viewEvent());
			}
		}
		if($type == "news")
		{
			$y = inactivate('news', $value);
			$modified_by = $_SESSION['_iiita_cms_username_'];
			if($y) {
				$ip = $_SERVER['REMOTE_ADDR'];
				Subscription::Delete($type, $value);
                Logger::logToggle($modified_by, "deactivate", $type, $value, $ip);
				header('Location:' . Link::viewNews());
			}
		}
		if($type == "announcements")
		{
			$y = inactivate('announcements', $value);
			$modified_by = $_SESSION['_iiita_cms_username_'];
			if($y) {
				$ip = $_SERVER['REMOTE_ADDR'];
				Subscription::Delete($type, $value);
                Logger::logToggle($modified_by, "deactivate", $type, $value, $ip);
				header('Location:' . Link::viewAnnouncement());
			}
		}
		if($type == "tenders")
		{
			$y = inactivate('tenders', $value);
			$modified_by = $_SESSION['_iiita_cms_username_'];
			if($y) {
				$ip = $_SERVER['REMOTE_ADDR'];
				Subscription::Delete($type, $value);
                Logger::logToggle($modified_by, "deactivate", $type, $value, $ip);
				header('Location:' . Link::viewTender());
			}
		}
		if($type == "carousel")
		{
			$y = inactivate('carousel', $value);
			$modified_by = $_SESSION['_iiita_cms_username_'];
			if($y) {
				$ip = $_SERVER['REMOTE_ADDR'];
                Logger::logToggle($modified_by, "deactivate", $type, $value, $ip);
				header('Location:' . Link::viewCarousel());
			}
		}
		if($type == "user_accounts")
		{
			echo "In deactivate top";
			$y = inactivate('users', $value);
			echo "In deactivate";
			$modified_by = $_SESSION['_iiita_cms_username_'];
			if($y) {
				echo "Deativate Success";
				$ip = $_SERVER['REMOTE_ADDR'];
                Logger::logToggle($modified_by, "deactivate", $type, $value, $ip);
				header('Location:' . Link::viewUsers());
			}
		}

		if($type == 'subnav')
		{
			

		}
	}
	else
	{
		
		if($type == 'rpanel') 
		{
			$actLink = Link::deactRpanelTopic($value);
			echo "<form action='{$actLink}' method='post'>
				<input type='submit' name='deactivate' value='deactivate' style='color:green; background:#dfe'/>&nbsp;&nbsp;
				<input type='submit' name='cancel' value='Cancel' style='color:red; background:#fde;'/>
				</form>";
		}
		if($type == 'rpanelcontent') 
		{
			$actLink = Link::deactRpanelContent($value);
			echo "<form action='{$actLink}' method='post'>
				<input type='submit' name='deactivate' value='deactivate' style='color:green; background:#dfe'/>&nbsp;&nbsp;
				<input type='submit' name='cancel' value='Cancel' style='color:red; background:#fde;'/>
				</form>";
		}
		if($type == 'events') 
		{
			$actLink = Link::deactEvent($value);
			echo "<form action='{$actLink}' method='post'>
				<input type='submit' name='deactivate' value='deactivate' style='color:green; background:#dfe'/>&nbsp;&nbsp;
				<input type='submit' name='cancel' value='Cancel' style='color:red; background:#fde;'/>
				</form>";
		}
		if($type == 'news') 
		{
			$actLink = Link::deactNews($value);
			echo "<form action='{$actLink}' method='post'>
				<input type='submit' name='deactivate' value='deactivate' style='color:green; background:#dfe'/>&nbsp;&nbsp;
				<input type='submit' name='cancel' value='Cancel' style='color:red; background:#fde;'/>
				</form>";
		}
		if($type == 'announcements') 
		{
			$actLink = Link::deactAnnouncement($value);
			echo "<form action='{$actLink}' method='post'>
				<input type='submit' name='deactivate' value='deactivate' style='color:green; background:#dfe'/>&nbsp;&nbsp;
				<input type='submit' name='cancel' value='Cancel' style='color:red; background:#fde;'/>
				</form>";
		}
		if($type == 'tenders') 
		{
			$actLink = Link::deactTender($value);
			echo "<form action='{$actLink}' method='post'>
				<input type='submit' name='deactivate' value='deactivate' style='color:green; background:#dfe'/>&nbsp;&nbsp;
				<input type='submit' name='cancel' value='Cancel' style='color:red; background:#fde;'/>
				</form>";
		}
		if($type == 'carousel') 
		{
			$actLink = Link::deactCarousel($value);
			echo "<form action='{$actLink}' method='post'>
				<input type='submit' name='deactivate' value='deactivate' style='color:green; background:#dfe'/>&nbsp;&nbsp;
				<input type='submit' name='cancel' value='Cancel' style='color:red; background:#fde;'/>
				</form>";
		}
		if($type == 'user_accounts') 
		{
			$actLink = Link::deactUser($value);
			echo "<form action='{$actLink}' method='post'>
				<input type='submit' name='deactivate' value='deactivate' style='color:green; background:#dfe'/>&nbsp;&nbsp;
				<input type='submit' name='cancel' value='Cancel' style='color:red; background:#fde;'/>
				</form>";
		}
		if($type == 'subnav')
		{
			$actLink = Link::deactSubnav($value);
			echo "<form action='{$actLink}' method='post'>
				<input type='submit' name='deactivate' value='deactivate' style='color:green; background:#dfe'/>&nbsp;&nbsp;
				<input type='submit' name='cancel' value='Cancel' style='color:red; background:#fde;'/>
				</form>";
		}
	}
?>
