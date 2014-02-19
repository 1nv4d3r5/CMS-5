<?php
require_once('business/DirCreator.class.php');
if(!isset($_POST['delete']))
{
	if($type == 'nav')
	{
		$actLink = Link::delNav($value);
		$cancelLink = Link::viewNav();
		$nav = get_navbyid($value);
		$curr_nav = mysql_fetch_array($nav);
		$title = $curr_nav['title'];
		$tagline = $curr_nav['tagline'];
		$inner_tagline = $curr_nav['inner_tagline'];
		$overview = $curr_nav['overview'];
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
			<tr>
			<td>Overview </td><td>{$overview}</td>
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
			<button name='delete'  style='color:red;'>Delete</button>&nbsp;&nbsp;
			<a href='{$cancelLink}' >Cancel</a>
			</form>
		";
	}
	if($type == 'subnav') 
	{
		$actLink = Link::delSubnav($value);
		$cancelLink = Link::viewNav();
		echo "<form action='{$actLink}' method='post'>
				<button name='delete'  style='color:red;'>Delete</button>&nbsp;&nbsp;
				<a href='{$cancelLink}' >Cancel</a>
			</form>";
	}
	if($type == 'events')
	{
		$actLink = Link::delEvent($value);
		$cancelLink = Link::viewEvent();
		echo "<form action='{$actLink}' method='post'>
				<button class='btn btn-danger' name='delete' >Delete</button>&nbsp;&nbsp;
				<button class='btn' name='cancel'>Cancel</button>
			</form>";
	}
	if($type == 'user_accounts')
	{
		$delLink = Link::delUser($value);
		$cancelLink = Link::viewUsers();
		echo "<form action='{$delLink}' method='post'>
				<button class='btn btn-danger' name='delete' >Delete</button>&nbsp;&nbsp;
				<button class='btn' name='cancel'>Cancel</button>
			</form>";
	}
	if($type == 'news')
	{
		$actLink = Link::delNews($value);
		$cancelLink = Link::viewNews();
		echo "<form action='{$actLink}' method='post'>
				<button class='btn btn-danger' name='delete' >Delete</button>&nbsp;&nbsp;
				<button class='btn' name='cancel'>Cancel</button>
			</form>";
	}
	if($type == 'announcements')
	{
		$actLink = Link::delAnnouncement($value);
		$cancelLink = Link::viewAnnouncement();
		echo "<form action='{$actLink}' method='post'>
				<button class='btn btn-danger' name='delete' >Delete</button>&nbsp;&nbsp;
				<button class='btn' name='cancel'>Cancel</button>
			</form>";
	}
	if($type == 'tenders')
	{
		$actLink = Link::delTender($value);
		$cancelLink = Link::viewTender();
		echo "<form action='{$actLink}' method='post'>
				<button class='btn btn-danger' name='delete' >Delete</button>&nbsp;&nbsp;
				<button class='btn' name='cancel'>Cancel</button>
			</form>";
	}
	if($type == 'carousel')
	{
		$actLink = Link::delCarousel($value);
		$cancelLink = Link::viewCarousel();
		echo "<form action='{$actLink}' method='post'>
				<button class='btn btn-danger' name='delete' >Delete</button>&nbsp;&nbsp;
				<button class='btn' name='cancel'>Cancel</button>
			</form>";
	}
	if($type == 'rpanel')
	{
		$actLink = Link::delRpanelTopic($value);
		$cancelLink = Link::viewRpanelTopic();
		echo "<form action='{$actLink}' method='post'>
				<button class='btn btn-danger' name='delete' >Delete</button>&nbsp;&nbsp;
				<button class='btn' name='cancel'>Cancel</button>
			</form>";
	}
	if($type == 'rpanelcontent')
	{
		$actLink = Link::delRpanelContent($value);
		$cancelLink = Link::viewRpanelContent();
		echo "<form action='{$actLink}' method='post'>
				<button class='btn btn-danger' name='delete' >Delete</button>&nbsp;&nbsp;
				<button class='btn' name='cancel'>Cancel</button>
			</form>";
	}
	if($type == 'feeds')
	{
	
		$actLink = Link::deleteFeed($value);
		$cancelLink = Link::viewFeeds();
		echo "<form action='{$actLink}' method='post'>
				<button class ='btn btn-danger' name='delete'>Delete</button>&nbsp;&nbsp;
				<a href='{$cancelLink}'><button class='btn' name='cancel'>Cancel</button></a>
			</form>";
	}
	if($type == 'sub_content')
	{
		$actLink = Link::delSubContent($value);
		$cancelLink = Link::viewSubTopic();
		echo "<form action='{$actLink}' method='post'>
				<button class = 'btn btn-danger' name='delete'>Delete</button>&nbsp;&nbsp;
				<a href='{$cancelLink}' ><button class='btn' name='cancel'>Cancel</button></a>
			</form>";
	}
	if($type == 'query')
	{
		$actLink = Link::delQuery($value);
		$cancelLink = Link::viewQuery();
		echo "<form action='{$actLink}' method='post'>
				<button class='btn btn-danger' name='delete'>Delete</button>&nbsp;&nbsp;
				<a href='{$cancelLink}' ><button class='btn' name='cancel'>Cancel</button></a>
			</form>";
	}
}
else
{
	if($type == 'nav')
	{
		//isdeleted = 0
		//move folder
		//generate nav
	   
		$curr_nav = mysql_fetch_array(get_navbyid($value));
		$url = $curr_nav['url'];
		$modified_by = $_SESSION['_iiita_cms_username_'];
		if($curr_nav['isactive'])
				$src = "../".$url;
		else
				$src = "../temp/".$url;
	  	$rdm = rand(100, 999);
		$dest = "../temp/deleted/".$url."$rdm/";
		$dirCreate = new DirCreator('nav', $curr_nav['id']);
		$dirCreate -> copyDir($src, $dest);
		$dirCreate -> removeDir($src);
	   
		$x = setdelete('nav', $value);
		writeNav();
		$curr_subnav = get_subnav($value);
		$y = 1;
		while($s = mysql_fetch_array($curr_subnav))
				$y = setdelete('subnav', $s['id']);
	   
		if($x && $y) {
			$ip = $_SERVER['REMOTE_ADDR'];
			Logger::logToggle($modified_by, "delete", $type, $value, $ip);
			echo "Delete Successful!";
			$cancelLink = Link::viewNav();
			header('Location: $cancelLink');
		}
		else
				echo "Error!!!";
	}
	if($type == 'subnav') 
	{
		$curr_subnav = mysql_fetch_array(get_subnavbyid($value));
		$curr_nav = mysql_fetch_array(get_navbyid($curr_subnav['nav_id']));
		$url = $curr_subnav['url'];
		
		if($curr_subnav['isactive'])
				$src = "../".$curr_nav['url']."/".$url;
		else
				$src = "../temp/".$url;
	  	 
	  	$rdm = rand(100, 999);
		$dest = "../temp/deleted/".$url."$rdm/";
		$dirCreate = new DirCreator('subnav', $curr_subnav['id']);
		$dirCreate -> copyDir($src, $dest);
		$dirCreate -> removeDir($src);
		$modified_by = $_SESSION['_iiita_cms_username_'];
		$x = setdelete('subnav', $value);
		writeNav();
		writeSideNav($curr_nav['id']);
		writeNavContent($curr_nav['id']);
		if($x) {
			$ip = $_SERVER['REMOTE_ADDR'];
			Logger::logToggle($modified_by, "delete", $type, $value, $ip);
			echo "Delete Successful!";
		}
		else
				echo "Error!!!";
	}
	
	if($type == 'rpanel')
	{
		$modified_by = $_SESSION['_iiita_cms_username_'];
		$x = setdelete('right_panel_topics', $value);
		if ($x) {	
			$ip = $_SERVER['REMOTE_ADDR'];
			Logger::logToggle($modified_by, "delete", $type, $value, $ip);
			echo "Delete Successful!";
		}
	}
	if($type == 'rpanelcontent')
	{
		$modified_by = $_SESSION['_iiita_cms_username_'];
		$x = setdelete('right_panel_content', $value);
		if($x) {
			$ip = $_SERVER['REMOTE_ADDR'];
			Logger::logToggle($modified_by, "delete", $type, $value, $ip);
			echo "Delete Successful!";
		}
	}
	
	if($type == 'events')
	{
		$modified_by = $_SESSION['_iiita_cms_username_'];
		$x = setdelete('events', $value);
		if ($x) {
			$ip = $_SERVER['REMOTE_ADDR'];
			Subscription::Delete($type, $value);
			Logger::logToggle($modified_by, "delete", $type, $value, $ip);
			echo "Delete Successful!";
		}
	}
	
	if($type == 'news')
	{
		$modified_by = $_SESSION['_iiita_cms_username_'];
		$x = setdelete('news', $value);
		if($x) {
			$ip = $_SERVER['REMOTE_ADDR'];
			Subscription::Delete($type, $value);
			Logger::logToggle($modified_by, "delete", $type, $value, $ip);
			echo "Delete Successful!";
		}
	}
	if($type == 'announcements')
	{
		$modified_by = $_SESSION['_iiita_cms_username_'];
		$x = setdelete('announcements', $value);
		if($x) {
			$ip = $_SERVER['REMOTE_ADDR'];
			Subscription::Delete($type, $value);
			Logger::logToggle($modified_by, "delete", $type, $value, $ip);
			echo "Delete Successful!";	
		}
	}
	if($type == 'tenders')
	{
		$modified_by = $_SESSION['_iiita_cms_username_'];
		$x = setdelete('tenders', $value);
		if($x) {
			$ip = $_SERVER['REMOTE_ADDR'];
			Subscription::Delete($type, $value);
			Logger::logToggle($modified_by, "delete", $type, $value, $ip);
			echo "Delete Successful!";	

		}
	}
	if($type == 'carousel')
	{
		$modified_by = $_SESSION['_iiita_cms_username_'];
		$x = setdelete('carousel', $value);
		if($x) {
			$ip = $_SERVER['REMOTE_ADDR'];
			Logger::logToggle($modified_by, "delete", $type, $value, $ip);
			echo "Delete Successful!";	
		}
	}
	
	if($type == 'user_accounts')
	{
		$modified_by = $_SESSION['_iiita_cms_username_'];
		$x = setdelete('users', $value);
		if($x) {
			$ip = $_SERVER['REMOTE_ADDR'];
			Logger::logToggle($modified_by, "delete", $type, $value, $ip);
			echo "Delete Successful!";
		}
	}
	if($type == 'subscriptions')
	{
		$modified_by = $_SESSION['_iiita_cms_username_'];
		$x = setsub_delete('sub_topics', $value);
		if($x) {
			$ip = $_SERVER['REMOTE_ADDR'];
			Logger::logToggle($modified_by, "delete", $type, $value, $ip);
			echo "Delete Successful!";
		}
	}
	
	if($type == 'feeds')
	{
		$modified_by = $_SESSION['_iiita_cms_username_'];
		$x = setsub_delete('sub_content', $value);
		$viewFeedsLink = Link::viewFeeds();
		if($x) {
			$ip = $_SERVER['REMOTE_ADDR'];
			Logger::logToggle($modified_by, "delete", $type, $value, $ip);
			echo "Delete Successful! <a href='{$viewFeedsLink}' >Go Back</a>";
		}
	}
	if($type == 'query')
	{
		$modified_by = $_SESSION['_iiita_cms_username_'];
		$x = setsub_delete('query_form', $value);
		if($x) {
			$ip = $_SERVER['REMOTE_ADDR'];
			Logger::logToggle($modified_by, "delete", $type, $value, $ip);
			echo "Delete Successful!";
		}
	}
}
           
    ?>

