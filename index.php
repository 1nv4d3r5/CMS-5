<?php
session_start();
ob_start();
require_once("config/constants.php");

require_once("models/database.php");
require_once("models/session.php");

require_once("api/cms_functions.php");
require_once('api/filewrite_functions.php');

require_once("business/Database_handler.class.php");
require_once("business/Link.class.php");
require_once("business/Error_handler.class.php");
require_once('business/Logger.class.php');
require_once('business/Subscription.class.php');
	ErrorHandler::SetHandler();
	
	if(isset($_POST['cancel'])){
		header("Location:/");
	}
	if(isset($_POST['submit'])){
		$user = htmlentities(addslashes($_POST['user']));
		$pass = htmlentities(addslashes($_POST['userp']));

		if(chk_user($user, $pass) )
		{
			$_SESSION['_iiita_cms_username_'] = $user;
			$result = login($user);
			if ($result) {
				$ip = "";
				$ip = $_SERVER['REMOTE_ADDR'];
                Logger::logLogin($_SESSION['_iiita_cms_username_'], $ip);
            }                        
		}
		else {
			$message = "<em>Login failed. Incorrect username or password.</em><br/>";
		}
	} else if(isset($_GET['logout']) && $_GET['logout']==1 ){
		$message="You are now successfully logged out...<br/>";
	}
 
?>

<?php
$t="";
if(isset($_GET['type'])) {
	$type = $_GET['type'];
	$t = $type;
	if($t == 'subnav')
		$t = 'nav';
	if($t == 'rpanelcontent')
		$t = 'rpanel';
	if(!chk_privilege($t)) {
		header("Location:/cms");
	}
}
?>

<?php require_once ($_SERVER['DOCUMENT_ROOT']."/includes/html_head.php");
	echo "<title>".SITE_TITLE." | Content Management System</title>";
?>
<?php require_once ($_SERVER['DOCUMENT_ROOT']."/includes/site_head.php"); ?>
<?php require_once ($_SERVER['DOCUMENT_ROOT']."/includes/nav.php"); ?>
<link rel="stylesheet" type="text/css" href="css/cmsStyle.css"></link>	
<script type ="text/javascript" src="js/jquery.js"></script>
<script type ="text/javascript" src="datepicker/jquery.jdpicker.js"></script>
<link rel="stylesheet" href="datepicker/jdpicker.css" type="text/css" media="screen" />

	<div class="cms_container">
		<br/>
		
		<?php if(isset($_SESSION['_iiita_cms_username_']))echo "<h3 style='color:#02495d; font-weight:100;'>Welcome " . $_SESSION['_iiita_cms_username_']. " to Content Management System of IIITA website.</h3><br/>";?>
		<div class="span2 sideNav">
			
			<?php if(chk_privilege('nav')) {
				echo '<a href="?type=nav&action=generate">
				<div class="';if($t == "nav") echo "active-bar"; echo ' sideNavItem">
					<p> Navigation </p>
				</div>
			</a>';
			}
			?>
			<?php if(chk_privilege('footer')) {
			//	echo '<a href="?type=footer&action=generate">
			//	<div class="';if($t == "footer") echo "active-bar"; echo ' sideNavItem">
			//		<p> Footer </p>
			//	</div>
			//</a>';
			}
			?>
			<?php if(chk_privilege('rpanel')) {
				echo '<a href="?type=rpanel&action=generate">
				<div class="';if($t == "rpanel") echo "active-bar"; echo ' sideNavItem">
					<p> Right Panel </p>
				</div>
			</a>';
			}
			?>
			<?php if(chk_privilege('carousel')) {
				echo '<a href="?type=carousel&action=generate">
				<div class="';if($t == "carousel") echo "active-bar"; echo ' sideNavItem">
					<p> Carousel </p>
				</div>
			</a>';
			}
			?><?php if(chk_privilege('tenders')) {
				echo '<a href="?type=tenders&action=generate">
				<div class="';if($t == "tenders") echo "active-bar"; echo ' sideNavItem">
					<p> Tenders </p>
				</div>
			</a>';
			}
			?><?php if(chk_privilege('news')) {
				echo '<a href="?type=news&action=generate">
				<div class="';if($t == "news") echo "active-bar"; echo ' sideNavItem">
					<p> News </p>
				</div>
			</a>';
			}
			?><?php if(chk_privilege('events')) {
				echo '<a href="?type=events&action=generate">
				<div class="';if($t == "events") echo "active-bar"; echo ' sideNavItem">
					<p> Events </p>
				</div>
			</a>';
			}
			?><?php if(chk_privilege('announcements')) {
				echo '<a href="?type=announcements&action=generate">
				<div class="';if($t == "announcements") echo "active-bar "; echo 'sideNavItem">
					<p> Announcements </p>
				</div>
			</a>';
			}
			?><?php if(chk_privilege('feeds')) {
				echo '<a href="?type=feeds&action=generate">
				<div class="';if($t == "feeds") echo "active-bar "; echo 'sideNavItem">
					<p> Send Feeds </p>
				</div>
			</a>';
			}
			?><?php if(chk_privilege('query')) {
				echo '<a href="?type=query&action=generate">
				<div class="';if($t == "query") echo "active-bar "; echo 'sideNavItem">
					<p> Query </p>
				</div>
			</a>';
			}
			?><?php if(chk_privilege('settings')) {
				echo '<a href="?type=settings&action=generate">
				<div class="';if($t == "settings") echo "active-bar"; echo ' sideNavItem">
					<p> Settings </p>
				</div>
			</a>';
			}
			?>
			
			<?php if(chk_privilege('gfiles')) {
				echo "<a href='?type=gfiles&action=generate'>
				<div class='";if($t == "gfiles") echo "active-bar"; echo " sideNavItem'>
					<p> Global View </p>
				</div>
			</a>";
			}
			?>
			
			<?php if(chk_privilege('editor')) {
				echo "<a href='?type=editor&action=generate'>
				<div class='";if($t == "editor") echo "active-bar"; echo " sideNavItem'>
					<p> Editor </p>
				</div>
			</a>";
			}
			?>
			<?php
			if(chk_privilege('user_accounts')){
				echo "<a href='?type=user_accounts&action=generate'>
				<div class='sideNavItem ";if($t == "user_accounts") echo "active-bar"; echo "'>
					<p> User Accounts </p>
				</div>
			</a>";
			}
			?>
			<br/>
		</div>
		

		<div class="span11 content">
			
		<?php

			if (logged_in()) {
				echo "<div style='float:right;'>";
				if(isset($_SESSION['_iiita_cms_username_']) && $_SESSION['_iiita_cms_username_'] == 'admin')
					echo "<a href='/search/admin/admin.php' target='_new'><button class='btn btn-inverse'>Re-Index Site</button> </a>";
				echo "<a href='/cms'><button class='btn ' style=' margin-right:10px;'>Home</button></a>";
				echo "<a href='change_pass.php?user={$_SESSION['_iiita_cms_username_']}' ><button class='btn btn-info' style=' margin-right:10px;'>Change Password</button></a>";
				echo "<a href='logout.php'><button class='btn btn-danger ' style=' margin-right:10px;'>Logout</button></a>";
				echo "</div>";
				if(isset($_GET['action']))
					require_once('models/action.php');
				else {
					
				}
			} else {
				require_once('models/login.php');
			}
			flush();
			ob_flush();
			ob_end_clean();
		?>
		
		</div>
	</div>
</body>	
</html>
