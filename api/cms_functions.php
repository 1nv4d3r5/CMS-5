<?php	
	
	function check_subnav($url, $nav_id)
	{
		global $connection;
		$query = "SELECT * FROM subnav WHERE url = '{$url}' AND nav_id = {$nav_id} AND isdeleted = 0";
		$result = mysql_query($query, $connection);
		if(mysql_num_rows($result) > 0)
			return 0;
		else
			return 1;
	}
	
	function chk_privilege($type) {
		if(isset($_SESSION['_iiita_cms_username_'])) {
			$topic_id = get_topic_id($type);
			$uid = get_userid($_SESSION['_iiita_cms_username_']);
		
			if(exits_in_users_roles($uid, $topic_id)) {
				return 1;
			}
		}
		return 0;
	}
	
	function chk_role($uid, $rid) {
		global $connection;
		$query = "SELECT * FROM users_roles WHERE uid={$uid} AND rid={$rid}";
		$result = mysql_query($query, $connection);
		if($result) {
			if(mysql_fetch_array($result))
				return 1;
			else 
				return 0;	
		} else {
			return 0;
		}
	}

	function user_exists($username) {
		global $connection;
		$query = "SELECT * FROM users WHERE username= '{$username}' AND isdeleted = 0";
		$result = mysql_query($query, $connection);
		if($result){
			if($u = mysql_fetch_array($result))
				return $u;
			else 
				return 0;
		}
		return 0;
	}
	
	function get_user($id) { 
		global $connection;
		$query = "SELECT * FROM users WHERE id={$id}";
		$result = mysql_query($query, $connection);
		return $result;
	}
	
	
	function get_userid($username) {
		global $connection;
		$query = "SELECT * FROM users WHERE username='{$username}' AND isdeleted = 0";
		$result = mysql_query($query, $connection);
		$user = mysql_fetch_array($result);
		return $user['id'];
	}
	
	function get_all_users() {
		global $connection;
		$query = "SELECT * FROM users WHERE isdeleted = 0 ";
		$result = mysql_query($query, $connection);
		return $result;
	}
	
	function exits_in_users_roles($uid, $rid) {
		global $connection;
		$query = "SELECT * FROM users_roles WHERE uid = {$uid} AND rid = {$rid}";
		$result = mysql_query($query, $connection);
		if($result){
			if($u = mysql_fetch_array($result))
				return 1;
			else 
				return 0;
		}
		return 0;
	}

	function get_topic_id($topic) {
		global $connection;
		$query = "SELECT * FROM cms_topics WHERE topic='{$topic}'";
		$result = mysql_query($query, $connection);
		$topic = mysql_fetch_array($result);
		return $topic['id'];
	}
	
	function get_all_cms_topics() {
		global $connection;
		$query = "SELECT * FROM cms_topics; ";
		$result = mysql_query($query, $connection);
		return $result;
	}
	
	function chk_user($uname, $pass) {
		global $connection;
		$query = "SELECT * FROM users where username= '{$uname}' AND isactive=1 AND isdeleted=0";
		$result = mysql_query($query, $connection);
		if($r = mysql_fetch_array($result)) {
			if($r['pass'] == sha1($pass)) 
				return 1;
		}
		return 0;
	}
	
	function login($user) {
		global $connection;
		$query = "UPDATE users SET login = Now() WHERE username='{$user}'";
		$result = mysql_query($query, $connection);
		return $result;
	}
	
	function inactivate($type, $value)
	{
		$user = $_SESSION['_iiita_cms_username_'];
		global $connection;
		$sql = "UPDATE {$type} SET isactive = 0, modified_on = NOW(), modified_by = '{$user}'  WHERE id = {$value}";
		$r = mysql_query($sql, $connection);
		if($r)
			return 1;
		else
			return 0;
	}
	
	function activate($type, $value)
	{
		$user = $_SESSION['_iiita_cms_username_'];
		global $connection;
		$sql = "UPDATE {$type} SET isactive = 1, modified_on = NOW(), modified_by = '{$user}' WHERE id = {$value}";
		$r = mysql_query($sql, $connection);
		if($r)
			return 1;
		else
			return 0;
	}
	
	function setdelete($type, $value)
	{
		$user = $_SESSION['_iiita_cms_username_'];
		global $connection;
		$sql = "UPDATE {$type} SET isactive = 0, isdeleted = 1, modified_on = NOW(), modified_by = '{$user}' WHERE id = {$value}";
		$r = mysql_query($sql, $connection);
		if($r)
			return 1;
		else
			return 0;
	}
	
	function setsub_delete($type, $value)
	{
		global $connection;
		$sql = "UPDATE {$type} SET isdeleted = 1 WHERE id = {$value}";
		$r = mysql_query($sql, $connection);
		if($r)
			return 1;
		else
			return 0;
	}
	
	function get_random_no() {
		$rev = rand(100, 999);
		return $rev;
	}
	
	function get_page_count($table)
	{	
		global $connection;
		$count_query = "SELECT COUNT(*) as num FROM {$table} WHERE isdeleted = 0";
		$count = mysql_fetch_array(mysql_query($count_query, $connection));
		$count = $count['num'];
		
		if($table == 'events')
			$per_page = cmsEVENT_PER_PAGE;
		if($table == 'news')
			$per_page = cmsNEWS_PER_PAGE;
		if($table == 'announcements')
			$per_page = cmsANNOUNCEMENT_PER_PAGE;
		if($table == 'tenders')
			$per_page = cmsTENDER_PER_PAGE;
			
		
		if($count % $per_page == 0)
			$count = $count / $per_page;
		else	
			$count = ($count / $per_page) + 1;
		return $count;
	}
	
	function get_events($page = 1) {
		global $connection;
		$start_index = cmsEVENT_PER_PAGE * ($page - 1);
		$end_index = (int)cmsEVENT_PER_PAGE;
		$query = "SELECT * FROM events WHERE isdeleted = 0 ORDER BY event_date desc LIMIT {$start_index}, {$end_index}";
		$result = mysql_query($query, $connection);
		return $result;
	}
	
	function get_eventbyid($id) 
	{
		global $connection;
		$query = "SELECT * FROM events where id= {$id} AND isdeleted = 0";
		$result = mysql_query($query, $connection);
		return $result;
	}
	
	function get_newsbyid($id) 
	{
		global $connection;
		$query = "SELECT * FROM news where id= {$id} AND isdeleted = 0";
		$result = mysql_query($query, $connection);
		return $result;
	}
	
	function get_announcementbyid($id) 
	{
		global $connection;
		$query = "SELECT * FROM announcements where id= {$id} AND isdeleted = 0";
		$result = mysql_query($query, $connection);
		return $result;
	}
	
	function get_tenderbyid($id) 
	{
		global $connection;
		$query = "SELECT * FROM tenders where id= {$id} AND isdeleted = 0";
		$result = mysql_query($query, $connection);
		return $result;
	}
	
	function get_carouselbyid($id)
	{
		global $connection;
		$query = "SELECT * FROM carousel where id= {$id} AND isdeleted = 0";
		$result = mysql_query($query, $connection);
		return $result;
	}
	
	
	function get_news($page = 1) {
		global $connection;
		$start_index = cmsEVENT_PER_PAGE * ($page - 1);
		$end_index = (int)cmsEVENT_PER_PAGE;
		$query = "SELECT * FROM news WHERE isdeleted = 0 ORDER BY added_on desc LIMIT {$start_index}, {$end_index}";
		$result = mysql_query($query, $connection);
		return $result;
	}
	
	function get_announcements($page = 1) {
		global $connection;
		$start_index = cmsEVENT_PER_PAGE * ($page - 1);
		$end_index = (int)cmsEVENT_PER_PAGE;
		$query = "SELECT * FROM announcements WHERE isdeleted = 0 ORDER BY added_on desc LIMIT {$start_index}, {$end_index}";
		$result = mysql_query($query, $connection);
		return $result;
	}
	
	function get_tender($page = 1) {
		global $connection;
		$start_index = cmsEVENT_PER_PAGE * ($page - 1);
		$end_index = (int)cmsEVENT_PER_PAGE;
		$query = "SELECT * FROM tenders WHERE isdeleted = 0 ORDER BY added_on desc LIMIT {$start_index}, {$end_index}";
		$result = mysql_query($query, $connection);
		return $result;
	}
	
	function get_rpanel_topics()
	{
		global $connection;
		$query = "SELECT * FROM right_panel_topics WHERE isdeleted = 0 ORDER BY priority asc";
		$result = mysql_query($query, $connection);
		return $result;
	}
	
	function get_rpanel_content($topic_id)
	{
		global $connection;
		$query = "SELECT * FROM right_panel_content WHERE topic_id = {$topic_id} AND isdeleted = 0 ORDER BY priority asc";
		$result = mysql_query($query, $connection);
		return $result;
	}
	
	function get_carousel()
	{
		global $connection;
		$query = "SELECT * FROM carousel WHERE isdeleted = 0 ORDER BY weight desc";
		$result = mysql_query($query, $connection);
		return $result;
	}
	
	function get_allnav() 
	{
		global $connection;
		$query = "SELECT * FROM nav WHERE isdeleted=0 ORDER BY modified_on desc";
		$result = mysql_query($query, $connection);
		return $result;
	}
	
	function get_footer()
	{
		global $connection;
		$query = "SELECT * FROM footer WHERE isactive = 1 AND isdeleted = 0";
		$result = mysql_query($query, $connection);
		return $result;
	}
	
	function get_nav()
	{
		global $connection;
		$query = "SELECT * FROM nav WHERE isactive = 1 AND isdeleted = 0";
		$result = mysql_query($query, $connection);
		return $result;
	}
	
	function is_nav_active($id) {
		global $connection;
		$query = "SELECT * FROM nav WHERE id={$id} AND isdeleted = 0";
		$result = mysql_query($query, $connection);
		$n = mysql_fetch_array($result);
		if($n['isactive'])
			return 1;
		else 
			return 0;
	}
	
	function get_nav_inactive()
	{
		global $connection;
		$query = "SELECT * FROM nav WHERE isactive = 0 AND isdeleted = 0";
		$result = mysql_query($query, $connection);
		return $result;
	}
	
	function get_navbyid($id) 
	{
		global $connection;
		$query = "SELECT * FROM nav where id= {$id} AND isdeleted = 0";
		$result = mysql_query($query, $connection);
		return $result;
	}
	
	function get_navbytitle($title) {
		global $connection;
		$query = "SELECT * FROM nav where title='{$title}' AND isdeleted = 0";
		$result = mysql_query($query, $connection);
		return $result;
	}
	
	function get_allsubnav($nav_id) {
		global $connection;
		$query = "SELECT * FROM subnav WHERE nav_id = {$nav_id} AND isdeleted=0 ORDER BY modified_on desc";
		$result = mysql_query($query, $connection);
		return $result;
	}
	
	function get_subnavbyid($id) {
		global $connection;
		$query = "SELECT * FROM subnav WHERE id = {$id} AND isdeleted = 0";
		$result = mysql_query($query, $connection);
		return $result;
	}
	
	function get_subnavbytitle($title, $nav_id = 0) {
		global $connection;
		if($nav_id == 0)
			$query = "SELECT * FROM subnav WHERE title = '{$title}' AND isdeleted = 0";
		else
			$query = "SELECT * FROM subnav WHERE title = '{$title}' AND nav_id = {$nav_id} AND isdeleted = 0";
		$result = mysql_query($query, $connection);
		return $result;
	}
	
	function get_subnav($nav_id)
	{
		global $connection;
		$query = "SELECT * FROM subnav WHERE nav_id = {$nav_id} AND isactive = 1 AND isdeleted = 0 ORDER BY modified_on desc";
		$result = mysql_query($query, $connection);
		return $result;
	}
	
	function get_subnav_inactive($nav_id)
	{
		global $connection;
		$query = "SELECT * FROM subnav WHERE nav_id = {$nav_id} AND isactive = 0 AND isdeleted = 0";
		$result = mysql_query($query, $connection);
		return $result;
	}
	
	function get_sub_topics()
	{
		global $connection;
		$query = "SELECT * FROM sub_topics WHERE isdeleted = 0";
		$result = mysql_query($query, $connection);
		return $result;
	}
	
	function get_sub_content()
	{
		global $connection;
		$query = "SELECT * FROM sub_content WHERE isdeleted = 0 AND is_sent = 0";
		$result = mysql_query($query, $connection);
		return $result;
	}
	function get_sub_topicsbyid($value)
	{
		global $connection;
		$query = "SELECT * FROM sub_topics WHERE id = {$value}";
		$result = mysql_query($query, $connection);
		return $result;
	}
	
	function get_query()
	{
		global $connection;
		$query = "SELECT * FROM query_form WHERE isdeleted = 0";
		$result = mysql_query($query, $connection);
		return $result;
	}
	
/*	function add_nav($title, $tagline, $overview) 
	{
		$query = "INSERT INTO nav (title, overview, tagline) values('{$title}', '{$overview}', '{$tagline}') ";
		global $connection;
		$result = mysql_query($query, $connection);
		if($result) {
			$nav= get_navbytitle($title);
			$n = mysql_fetch_array($nav);
			$makeDir = new DirCreator("nav",(int)$n['id']);
			$makeDir -> generateStructure();
			$ourFileName = $path."/index.php";
			$ourFileHandle = fopen($ourFileName, 'w') or die("can't open file");
			fclose($ourFileHandle);
			return true;
		}
		else
			return false;
	} */
	
/*	function cms_head($active) 
	{
		echo "
				<!DOCTYPE html5>
				<html lang='en'>
				<head>
					<meta charset='utf-8'>
					<title></title>
					<meta name='viewport' content='width=device-width, initial-scale=1.0'>
					<meta name='description' content=''>
					<meta name='author' content='#Adi'>
				<head>
				<title>Project Pheonix | CMS</title>
				<link rel='SHORTCUT ICON' href='/images/logo.ico'>
				<link rel='stylesheet' href='/css/bootstrap.css'  type='text/css'/>
				
				<link rel='stylesheet' href='/css/reset.css' type='text/css' media='all'>
				<link rel='stylesheet' href='/css/menu.css' type='text/css'/>
				<link rel='stylesheet' href='/css/style.css'  type='text/css'/>
				<link rel='stylesheet' href='/css/grid.css'  type='text/css'/>
				<link rel='stylesheet' href='/css/jquery-ui-1.8.5.custom.css' type='text/css' media='all'>				
				<link rel='stylesheet' href='css/cmsStyle.css' type='text/css' media='all'>				
				<!-- Le HTML5 shim, for IE6-8 support of HTML5 elements -->
				<!--[if lt IE 9]>
					<script src='/js/html5shiv.js'></script>
				<![endif]-->
				
				<script type='text/javascript' src='/js/jquery.scrollerota.min.js'></script>
				<script type='text/javascript' src='/js/ancmnts.js'></script>
				
				<script type='text/javascript' src='/js/bootstrap-collapse.js'></script>
				<script type='text/javascript' src='/js/bootstrap-tab.js'></script>
				<script type='text/javascript' src='/js/myown.js'></script>
				
				<script type='text/javascript' src='/js/jquery-1.6.2.min.js'></script>
				<script type='text/javascript' src='/js/jquery.cycle.all.js'></script>
				<script type='text/javascript' src='/js/jquery-ui-1.8.5.custom.min.js'></script>
				
	
				</head>
				
				<body style='background:url(../../images/waterMark.png) fixed ;  background-size:100% auto;'>
					<div class='header head-style'>
					<div class='container head-container'>
						<br/>
						
						<p style='color:#fff;font-family:sansation;font-weight:15px;;font-weight:bold;  margin-top:-6px;'>&nbsp;&nbsp;&nbsp;&nbsp;<script type='text/javascript' src='/js/date.js'></script></p>
						
						<div class='row'>
							
							<div class='span5' style='height:140px;'>
								
								<a href='/' title='Home'>
								<div class='span2' style='height:140px'>
								</div>
								</a>
								
							</div>
						
							
							<div class='span3' style='float:; margin-top:-2px;margin-left:275px; -moz-border-radius:10px; -webkit-border-radius:10px; border-radius:10px; background:#fff; height:24px; width:170px'>
								<i style='margin-top:5px; margin-right:200px; ' class='icon-large icon-search'>	<form class='form-search' style='margin-left:19px;margin-top:-9px;'>
								 &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <input id='search-box' type='text' style='width:150px;height:24px; margin-top:-10px;' class='input-medium search-query' placeholder='Search web/people...'>
								
								</form></i>
							
							</div>
							<div class='logg'>
							<ul>
								<li id='login'>
									<a id='login-trigger' href='#' >
										My IIITA <span>&#x25BC;</span>
									</a>
									<div id='login-content'>
										<br/>
										<form>
											<fieldset id='inputs'>
												<input id='username' type='text' name='mCode' placeholder='Username'>   
												<input id='password' type='password' name='Password' placeholder='Password'>
											</fieldset>
											<fieldset id='actions'>
												<input type='submit' id='submit' value='Log in'>
												<p>&nbsp &nbsp<input type='checkbox' checked='checked'> Remember me.</p>
											</fieldset>
										</form>
									</div>                     
								</li>
							</ul>
							</div>	
						</div>
							<br/>
					</div>
					 <div class='header h-nav-header'>
					";
								
				echo  "
					</div>
				</div>
				";

	}	
*/

?>
