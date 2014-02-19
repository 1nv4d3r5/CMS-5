<?php
	require_once("business/DirCreator.class.php");
	if(isset($_POST['activate'])) 
	{
		if($type == 'nav') 
		{
			$nav = get_navbyid($value);
			$curr_nav = mysql_fetch_array($nav);
			$url = $curr_nav['url'];
		   
			$dirCreate = new DirCreator('nav', $value);
			
			$src = "../temp/".$url;
			$dst = "../".$url;
			if(is_dir($dst))
					$dirCreate -> removeDir($dst);
			$dirCreate -> copyDir($src, $dst);
			$dirCreate -> removeDir($src);
		   
			$y = activate('nav', $value);
			
			writeNav();
			writeNavContent($value);
			writeSideNav($value);
			$modified_by = $_SESSION['_iiita_cms_username_'];
			if($y) {
				$ip = $_SERVER['REMOTE_ADDR'];
				Logger::logToggle($modified_by, "activate", $type, $value, $ip);
				header('Location:'.Link::viewNav());
			}
		}
		if($type=='subnav') 
		{
			$subnav = get_subnavbyid($value);
			$curr_subnav = mysql_fetch_array($subnav);
			$s_url = $curr_subnav['url'];
			$nav_id = $curr_subnav['nav_id'];
			$curr_nav = mysql_fetch_array(get_navbyid($nav_id));
			$n_url = $curr_nav['url'];

			if(!is_nav_active($curr_nav['id'])) {
				echo "Nav for this subnav is  not active. Please Activate that first.<br/><br/>";
				$link = Link::viewNav();
				echo "<a href='{$link}'><buttton class='btn' >Go back </button></a>";
			}
			else {
				$dirCreate = new DirCreator('subnav', $value);
				$src = "../temp/".$s_url;
				$dst = "../".$n_url."/".$s_url;
				if(is_dir($dst))
					$dirCreate -> removeDir($dst);
				$dirCreate -> copyDir($src, $dst);
				$dirCreate -> removeDir($src);
				
				$x = writeSubnavFiles($value);
				
				
				$y = activate('subnav', $value);
				
				$a = writeSideNav($nav_id);
				$b = writeNavContent($nav_id);
				$c = writeNav();
				$modified_by = $_SESSION['_iiita_cms_username_'];
				if($y) {
					$ip = $_SERVER['REMOTE_ADDR'];
					Logger::logToggle($modified_by, "activate", $type, $value, $ip);
					header('Location:' . Link::viewNav());
				}
			}
		}
		
		if($type == "events")
		{
			$y = activate('events', $value);
			$modified_by = $_SESSION['_iiita_cms_username_'];
			if($y) {
				$ip = $_SERVER['REMOTE_ADDR'];
				Subscription::Add($type, $value, $modified_by);
                Logger::logToggle($modified_by, "activate", $type, $value, $ip);
				header('Location:' . Link::viewEvent());
			}
		}
		
		if($type == "news")
		{
			$y = activate('news', $value);
			$modified_by = $_SESSION['_iiita_cms_username_'];
			if($y) {
				$ip = $_SERVER['REMOTE_ADDR'];
				Subscription::Add($type, $value, $modified_by);
                Logger::logToggle($modified_by, "activate", $type, $value, $ip);
				header('Location:' . Link::viewNews());
			}
		}
		
		if($type == "announcements")
		{	
			$y = activate('announcements', $value);
			$modified_by = $_SESSION['_iiita_cms_username_'];
			if($y) {
				$ip = $_SERVER['REMOTE_ADDR'];
				Subscription::Add($type, $value, $modified_by);
                Logger::logToggle($modified_by, "activate", $type, $value, $ip);
				header('Location:' . Link::viewAnnouncement());
			}
		}
		if($type == "tenders")
		{
			$y = activate('tenders', $value);
			$modified_by = $_SESSION['_iiita_cms_username_'];
			if($y) {
				$ip = $_SERVER['REMOTE_ADDR'];
				Subscription::Add($type, $value, $modified_by);
                Logger::logToggle($modified_by, "activate", $type, $value, $ip);
				header('Location:' . Link::viewTender());
			}
		}
		if($type == "carousel")
		{
			$y = activate('carousel', $value);
			$modified_by = $_SESSION['_iiita_cms_username_'];
			if($y) {
				$ip = $_SERVER['REMOTE_ADDR'];
                Logger::logToggle($modified_by, "activate", $type, $value, $ip);
				header('Location:' . Link::viewCarousel());
			}
		}
		if($type == "rpanel")
		{
			$y = activate('right_panel_topics', $value);
			$modified_by = $_SESSION['_iiita_cms_username_'];
			if($y) {
				$ip = $_SERVER['REMOTE_ADDR'];
                Logger::logToggle($modified_by, "activate", $type, $value, $ip);
				header('Location:' . Link::viewRpanelTopic());
			}
		}
		
		if($type == "rpanelcontent")
		{
			$y = activate('right_panel_content', $value);
			$modified_by = $_SESSION['_iiita_cms_username_'];
			if($y) {
				$ip = $_SERVER['REMOTE_ADDR'];
                Logger::logToggle($modified_by, "activate", $type, $value, $ip);
				header('Location:' . Link::viewRpanelTopic());
			}
		}
		
		if($type == "user_accounts")
		{
			$y = activate('users', $value);
			$modified_by = $_SESSION['_iiita_cms_username_'];
			if($y) {
				$ip = $_SERVER['REMOTE_ADDR'];
                Logger::logToggle($modified_by, "activate", $type, $value, $ip);
				header('Location:' . Link::viewUsers());
			}
		}


	}
	
	else { 
		if($type == 'nav')
		{
			$actLink = Link::actNav($value);
			$nav = get_navbyid($value);
			$curr_nav = mysql_fetch_array($nav);
			$title = $curr_nav['title'];
			$tagline = $curr_nav['tagline'];
			$inner_tagline = $curr_nav['inner_tagline'];
			$banner = $curr_nav['banner'];
			$links = $curr_nav['links'];
			$added_on = $curr_nav['added_on'];
			$added_by = $curr_nav['added_by'];
			$modified_by = $_SESSION['_iiita_cms_username_'];
			
			echo "
				<table>
				<tr>
				<td>Title:</td><td> {$title}</td>
				</tr>
				<tr>
				<td>Tagline:</td> <td> {$tagline}</td>
				</tr>
				<tr>
				<td>Inner Tagline: </td><td>{$inner_tagline}</td>
				</tr>
				<tr>
				<td>Banner:</td><td> {$banner}</td>
				</tr>
				<tr>
				<td>Links:</td><td> {$links}</td>
				</tr>
				<tr>
				<td>Added By:</td><td> {$added_by}</td>
				</tr>
				<tr>
				<td>Added On: </td><td>{$added_on}</td>
				</tr>
				</table>
				<br/>
				<form action='{$actLink}' method='post'>
				<input type='submit' class='btn btn-success' name='activate' value='Activate' />&nbsp;&nbsp;	
				<input type='submit' class='btn' name='cancel' value='Cancel' />
				</form>";
		}
		
		if($type=='subnav') 
		{
			$actLink = Link::actSubnav($value);
			
			echo "<form action='{$actLink}' method='post'>
				<input type='submit' class='btn btn-success' name='activate' value='Activate' />&nbsp;&nbsp;	
				<input type='submit' class='btn' name='cancel' value='Cancel' />
				</form>";
		}
		
		if($type=='rpanel') 
		{
			$actLink = Link::actRpanelTopic($value);
			echo "<form action='{$actLink}' method='post'>
				<input type='submit' class='btn btn-success' name='activate' value='Activate' />&nbsp;&nbsp;	
				<input type='submit' class='btn' name='cancel' value='Cancel' />
				</form>";
		}
		
		if($type=='rpanelcontent') 
		{
			$actLink = Link::actRpanelContent($value);
			echo "<form action='{$actLink}' method='post'>
				<input type='submit' class='btn btn-success' name='activate' value='Activate' />&nbsp;&nbsp;	
				<input type='submit' class='btn' name='cancel' value='Cancel' />
				</form>";
		}
		
		if($type=='events') 
		{
			$actLink = Link::actEvent($value);
			echo "<form action='{$actLink}' method='post'>
				<input type='submit' class='btn btn-success' name='activate' value='Activate' />&nbsp;&nbsp;	
				<input type='submit' class='btn' name='cancel' value='Cancel' />
				</form>";
		}
		
		if($type=='news') 
		{
			$actLink = Link::actNews($value);
			echo "<form action='{$actLink}' method='post'>
				<input type='submit' class='btn btn-success' name='activate' value='Activate' />&nbsp;&nbsp;	
				<input type='submit' class='btn' name='cancel' value='Cancel' />
				</form>";
		}
		
		if($type=='announcements') 
		{
			$actLink = Link::actAnnouncement($value);
			echo "<form action='{$actLink}' method='post'>
				<input type='submit' class='btn btn-success' name='activate' value='Activate' />&nbsp;&nbsp;	
				<input type='submit' class='btn' name='cancel' value='Cancel' />
				</form>";
		}
		if($type=='tenders') 
		{
			$actLink = Link::actTender($value);
			echo "<form action='{$actLink}' method='post'>
				<input type='submit' class='btn btn-success' name='activate' value='Activate' />&nbsp;&nbsp;
				<input type='submit' class='btn' name='cancel' value='Cancel' />
				</form>";
		}
		if($type=='carousel') 
		{
			$actLink = Link::actCarousel($value);
			echo "<form action='{$actLink}' method='post'>
				<input type='submit' class='btn btn-success' name='activate' value='Activate' />&nbsp;&nbsp;
				<input type='submit' class='btn' name='cancel' value='Cancel' />
				</form>";
		}
		
		if($type=='user_accounts') 
		{
			$actLink = Link::actUser($value);
			echo "<form action='{$actLink}' method='post'>
				<input type='submit' class='btn btn-success' name='activate' value='Activate' />&nbsp;&nbsp;
				<input type='submit' class='btn' name='cancel' value='Cancel' />
				</form>";
		}
		
	}
?>
