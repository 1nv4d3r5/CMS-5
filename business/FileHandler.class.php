<?php

class FileHandler
{
	private $valid_image_formats = array("jpg","JPG", "jpeg", "png", "PNG", "gif", "GIF", "bmp", "BMP");
	private $valid_file_formats = array("html", "HTML", "htm","pdf", "doc", "docx", "rtf", "txt");
	
	public function __construct() {	}
	
	public function chooser($label)
	{
		echo $label."<input type='file' name='ufile[]' id='ufile[]' size='25' value ='' /><br/>";
	}
	
	public function uploader($path)
	{	

	    $conn_id = ftp_connect("localhost");
		if(!$conn_id) {
			  return false;
		}
		if (ftp_login($conn_id, FTP_USER, FTP_PASS)) {

			$error = "";
			$check = $this->check_formats($path);
			if($check == 1) 
			{
				for($i = 0; $i < count($_FILES['ufile']['name']); $i++) 
				{
					$filename = $_FILES['ufile']['name'][$i];
					
					if($_FILES['ufile']['size'][$i] != 0) 
					{
						list ($name, $ext) = explode(".",$filename);
						
						if(is_array($path)) 
						{
							$path[$i] =  str_replace(array('\'', '"'), '', $path[$i]);
							$path[$i] .= $filename;
							if(copy($_FILES['ufile']['tmp_name'][$i], $path[$i])) {
								chmod($path, 0755);
								$result = 1;
							}
							else
								$error .= "Error in copying file: ".$filename;
						}
						else 
						{
							$path =  str_replace(array('\'', '"'), '', $path);
							$path .= $filename;
							if(copy($_FILES['ufile']['tmp_name'][$i], $path)) {
								chmod($path, 0755);
								$result = 1;
							}
							else
								$error .= "Error in copying file: ".$filename;
						}
						
					} 
					else 
						$error .= "File size 0 of:".$filename;
				}
				
				if($error != "") 
					return $error;
				else
					return 1;
			} 
			else 
			{
				echo "<script>alert('Check File Format');</script>";
				return $check;
			}
	   }
	   ftp_close($conn_id);
	}
	
	public function check_formats($path) 
	{
		$filename  = explode('.', $_FILES['ufile']['name'][0]);
		$filepath = explode("/",$path);
		
		if($filepath[count($filepath)-2] == 'uploads') {
			if(in_array($filename[1], $this->valid_image_formats) || in_array($filename[1], $this->valid_file_formats)) {
				return 1;
				
			} else {
				return "File Format of :".$filename[0].".".$filename[1]." not correct";
			}
			
		} else {
			if(in_array($filename[1], $this->valid_image_formats)) {
				return 1;
			} else 
				return "File Format of :".$filename[0].".".$filename[1]." is  not correct";
		}
		return 0;
/*		if(is_array($path)) {
			for($i = 0; $i < count($_FILES['ufile']['name']); $i++) {
				$filepath = explode("/",$path[$i]);
				$filename = explode(".", $_FILES['ufile']['name'][$i]);
				if($filepath[3] == 'images') {
					if(!in_array($filename[1], $this->valid_image_formats)) {
						return "File Format of :".$filename[0].".".$filename[1]." not correct";
					}
				} else if($filepath[3] == 'uploads') {
					if(!in_array($filename[1], $this->valid_file_formats)) {
						return "File Format of :".$filename[0].".".$filename[1]." not correct";
					}
				}
			}
		} else {
			$filepath = explode("/",$path);
			for($i = 0; $i < count($_FILES['ufile']['name']); $i++) {	
				$filename = explode(".", $_FILES['ufile']['name'][$i]);
				if($filepath[2] == 'images') {
					if(!in_array($filename[1], $this->valid_image_formats)) {
						return "File Format of :".$filename[0].".".$filename[1]." not correct";
					}
				} else if($filepath[2] == 'uploads') {
					if(!in_array($filename[1], $this->valid_file_formats)) {
						return "File Format of :".$filename[0].".".$filename[1]." not correct";
					}
				}
			}
		}
		
		return 1;
	*/
	
	
	}
	
	public  function listFiles($type, $value) {
		if($type == 'nav') 
		{
			$nav = get_navbyid($value);
			$curr_nav = mysql_fetch_array($nav);
			if($curr_nav['isactive'] == 0)
				$filepath = "../temp/".$curr_nav['url'];
			else
				$filepath = "../".$curr_nav['url'];
			$dir = $filepath."/images";
			$this -> listFolderFiles($dir);
		} 
		else if($type == 'subnav') {
			$subnav = get_subnavbyid($value);
			$curr_subnav = mysql_fetch_array($subnav);
			if($curr_subnav['isactive']) {
				$n = mysql_fetch_array(get_navbyid($curr_subnav['nav_id']));
				$filepath = "../".$n['url']."/".$curr_subnav['url'];
			}
			else
				$filepath = "../temp/".$curr_subnav['url'];
			$dir = $filepath."/images";
			$this -> listFolderFiles($dir);
			$dir = $filepath."/uploads";
			$this -> listFolderFiles($dir);
		}
		
	}
	
	public function listFolderFiles($dir){
	   $conn_id = ftp_connect("localhost");
	   if(!$conn_id) {
			  return false;
	   }
	   if (ftp_login($conn_id, FTP_USER, FTP_PASS)) {

			$ffs = scandir($dir);
			$path = explode('/', $dir);
			echo "<b>".$path[count($path)-2]."/".$path[count($path)-1]."</b><br/>";
			foreach($ffs as $ff){
				if($ff != '.' && $ff != '..'){
					$deleteLink = "";
					if(is_dir($dir.'/'.$ff)) $this -> listFolderFiles($dir.'/'.$ff);
					if(!is_dir($dir.'/'.$ff)) {
					$dirArr = explode('/', $dir);
					echo "<div class='fileDisplay' >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;==>> <img src='{$dir}"."/"."{$ff}' "; if(isset($dirArr[4]) && $dirArr[4]=='banner' || $dirArr[3] == 'banner') echo "style='height:150px; width:840px;padding:3px; border:1px solid #ccc; background:#fff;' "; else if(isset($dirArr[4]) && $dirArr[4]=='gridimage') echo "style='height:120px; width:296px; padding:3px; border:1px solid #ccc;' "; else echo "style='height:200px;width:250px; padding:3px; border:1px solid #ccc;' ";  echo " onmouseover='this.style.opacity:0,5;'><br/>".$ff."&nbsp;&nbsp;&nbsp;<a href='' >Delete this file</a><br/><br/></div>";

}
					echo '';
					
				}
			}
			echo '';
	   }
	   
   	   ftp_close($conn_id);
	}
	
}
