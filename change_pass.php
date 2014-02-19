<?php 
	
	session_start();
	require_once("api/cms_functions.php");
	require_once('config/constants.php');
	require_once("models/database.php");
	require_once("business/Database_handler.class.php");
	require_once("models/session.php");
	require_once("business/Link.class.php");
	require_once("business/Logger.class.php");

	confirm_logged_in();
	
	if(isset($_POST['cancel'])){
		header("Location:/cms/?type=user_accounts&action=generate");
	}
	if($_SESSION['_iiita_cms_username_'] != 'admin') {
		if($_GET['user'] != $_SESSION['_iiita_cms_username_'] 	)
			header("Location:/cms");
	}
	if(isset($_POST['submit'])){
		$user = htmlentities($_POST['user']);
		$new_pass = htmlentities($_POST['new_pass']);
		$confirm_new_pass = htmlentities($_POST['confirm_new_pass']);
		$current_pass = htmlentities($_POST['current_pass']);
		
		if($_SESSION['_iiita_cms_username_'] == 'admin'  &&  !chk_user('admin', $current_pass)) {
				$message = "<font color='red'>Admin Password is wrong</font>";
		}
		else if($_SESSION['_iiita_cms_username_'] != 'admin'  &&  !chk_user($user, $current_pass)) {
			$message = "<font color='red'>Current Password is wrong</font>";
		}
		if($current_pass == '')
			$message = "<font color='red'>Enter Current Password</font>";
		if($current_pass == '' && $_SESSION['_iiita_cms_username_'] == 'admin' && $user != 'admin')
			$message = "<font color='red'>Enter Admin Password</font>";
	    	if($new_pass != $confirm_new_pass ) 
			$message = "<font color='red'>Password does not match</font>";
		if(strlen($new_pass) < 6)
			$message = "<font color='red'>Password length is less than 6 characters</font>";
		
		if(!isset($message)) 
		{
			$hash_pass = sha1($new_pass);
			$sql = "UPDATE users SET pass = :pass WHERE username = :user";
			$params = array(':pass' => $hash_pass, ':user' => $user);
			$result = DatabaseHandler::Execute($sql, $params);
			if($result) {
				$modified_by = $_SESSION['_iiita_cms_username_'];
				$type = "changepwd";
				$inp = $user.' $$ '.$_SESSION['_iiita_cms_username_'].' $$ ';
				$ip = $_SERVER['REMOTE_ADDR'];
				Logger::logToggle($modified_by, "password_change", $type, $inp, $ip);
				$message = "<font color='green'>Password Changed Successfully.</font>";
			}
		}
	
	}
?>
<?php require_once ($_SERVER['DOCUMENT_ROOT']."/includes/html_head.php"); ?>
<?php require_once ($_SERVER['DOCUMENT_ROOT']."/includes/site_head.php"); ?>

<link rel="stylesheet" type="text/css" href="css/cmsStyle.css"></link>	
	<div class="cms_container">
		<br/>
		<div class="span2 sideNav">
			<br/>
		</div>
		

		<div class="span11 content">
			
		<?php
	
			if (logged_in()) {
				
				echo "<a href='logout.php'><button class='btn btn-primary' style='float:right; margin-right:10px;'>Logout</button></a>";
			
			} else {
				require_once('models/login.php');
			}
		?>
			<center>
			<div class='span7 navHolder' >
				<br/>
				<?php if(isset($message)) echo $message; ?>
				<br/>
				<br/>
				<form method='post' action='change_pass.php?user=<?php echo $_GET['user']; ?>' >
					
					<input type='text' name='user' readonly='readonly' <?php if(isset($_GET['user'])) echo "value='{$_GET['user']}'"; ?> /><br/>
					<?php
						if($_SESSION['_iiita_cms_username_'] != 'admin' ||  isset($_GET['user']) && $_GET['user'] == 'admin')
							echo "<input type='password' name='current_pass'  placeholder='Current Password'/><br/>";
						else	
							echo "<input type='password' name='current_pass'  placeholder='Admin Password'/><br/>";
					 ?>
					
					<input type='password' name='new_pass'  placeholder='New Password'/><br/>
					<input type='password' name='confirm_new_pass'  placeholder='Confirm New Password'/><br/>
					<input type='submit' class='btn btn-success' name='submit' value='Submit'/>
					<input type='submit' class='btn' name='cancel' value='Cancel'/>
					<br/>
				</form>
				<br/>
			</div>
			</center>
		</div>
	</div>
</body>	
</html>
