<?php 
    class DirCreator
    {
		private  $type = '';
		private  $value = 0;
		private  $dir;
		
		public function __construct($t = 0,$v = 0)
		{
			$this -> type = $t;
			$this -> value = (int)$v;
			   
		}
		public function generateStructure($path)
		{
			if($this -> type == 'nav' || $this -> type == 'subnav')  
					$this -> dirStructure($path);
			   
		}
		public function dirStructure($path)
		{      
		    $conn_id = ftp_connect("localhost");
			if(!$conn_id) {
				return false;
			}
			if (@ftp_login($conn_id, FTP_USER, FTP_PASS)) {
							   
				
		
				ftp_mkdir($conn_id, $path);
				ftp_chdir($conn_id,$path);
				chdir($path);
				ftp_mkdir($conn_id,"includes");
				ftp_mkdir($conn_id,"images");
				ftp_mkdir($conn_id,"uploads");
				ftp_mkdir($conn_id,"images/banner");
				ftp_mkdir($conn_id,"images/gridimage");
					
				if($this -> type == 'subnav'){
						$contents = "<?php require_once(\$_SERVER['DOCUMENT_ROOT'].'/includes/html_head.php'); \nrequire_once('includes/css.php'); \nrequire_once(\$_SERVER['DOCUMENT_ROOT'].'/includes/site_head.php'); \nrequire_once(\$_SERVER['DOCUMENT_ROOT'].'/includes/nav.php');\nrequire_once('includes/content.php');\nrequire_once('includes/js.php');\nrequire_once(\$_SERVER['DOCUMENT_ROOT'].'/includes/site_footer.php');\n?>";
				}
				if($this -> type == 'nav'){
						$contents = "<?php require_once(\$_SERVER['DOCUMENT_ROOT'].'/includes/html_head.php');\nrequire_once(\$_SERVER['DOCUMENT_ROOT'].'/includes/site_head.php'); \nrequire_once(\$_SERVER['DOCUMENT_ROOT'].'/includes/nav.php');\nrequire_once('includes/content.php');\nrequire_once(\$_SERVER['DOCUMENT_ROOT'].'/includes/site_footer.php');\n?>";
						chdir("includes");
						fopen("side_nav.php", 'w');
						chdir("../");
				}
			   
				$filename = "index.php";
				if (!$handle = fopen($filename, 'a')) {
						 echo "Cannot open file ($filename)";
						 exit;
				}
				chmod($filename, 0755);
				if (fwrite($handle, $contents) === FALSE) {
						echo "Cannot write to file ($filename)";
						exit;
				}
				fclose($handle);
				
				chdir("includes");
				fopen("content.php", 'w');
				chmod("content.php", 0755);
				if($this -> type == 'subnav') {
						fopen("css.php", 'w');
						chmod("css.php", 0755);
						fopen("js.php", 'w');
						chmod("js.php", 0755);
				}
			}
		   ftp_close($conn_id);
		}
	   
		public function copyDir($src, $dst) {
		
			
		    $conn_id = ftp_connect("localhost");
			if(!$conn_id) {
				return false;
			}
			if (ftp_login($conn_id, FTP_USER, FTP_PASS)) {
				if(is_dir($src))
				{
						$dir = opendir($src);
						ftp_mkdir($conn_id,$dst);
						while(false !== ( $file = readdir($dir)) ) {
								if (( $file != '.' ) && ( $file != '..' )) {
										if ( is_dir($src . '/' . $file) ) {
												$this -> copyDir($src . '/' . $file,$dst . '/' . $file);
										}
										else {
												copy($src . '/' . $file,$dst . '/' . $file);
												
										}
								}
						}
						closedir($dir);
				}
				else
						return false;
				return true;
		
			}
		   ftp_close($conn_id);
		}
	   
		public function removeDir($dir) {
			
		     $conn_id = ftp_connect("localhost");
			if(!$conn_id) {
				return false;
			}
			if (ftp_login($conn_id, FTP_USER, FTP_PASS)) {
			
				foreach(glob($dir . '/*') as $file) {
						if(is_dir($file))
								$this -> removeDir($file);
						else
								unlink($file);
				}
				ftp_rmdir($conn_id, $dir);
				return true;
			}
		   ftp_close($conn_id);
		}
	   
		public function renameDir($src, $des)
		{
			
		     $conn_id = ftp_connect("localhost");
			if(!$conn_id) {
				return false;
			}
			if (ftp_login($conn_id, FTP_USER, FTP_PASS)) {
				if(is_dir($src))
				{
						ftp_rename($conn_id, $src, $des);
				}
				else
						return false;
				return true;
			}
		    ftp_close($conn_id);
		}
    }
    ?>

