<?php 
	require_once('business/FileHandler.class.php');
	require_once('business/DirCreator.class.php');
	
	if(isset($_POST['file_upload'])) 
	{
		if($type == 'nav') {
			if($_FILES['ufile']['size'][0] != 0) {
				$nav = get_navbyid($_GET['value']);
				$curr_nav = mysql_fetch_array($nav);
				$url = $curr_nav['url'];
				$typeOfFile = $_POST['pathToUpload'];
				$d = new DirCreator ();
				if($curr_nav['isactive']) {
					$src = "../".$url;
					$des = "../temp/".$url;
					$success = $d -> copyDir($src, $des);
				}			
				$path = "../temp/";				
				
				if($typeOfFile == 'banner') {
					$path = $path.$url."/images/banner/";
					
				} else if($typeOfFile == 'icon') {
					
					$path = "../images/icons/";
				}
				$imgUploader = new FileHandler();
				$uploaded = $imgUploader -> uploader($path);
				
				if($uploaded == 1) {
					$name = $_FILES['ufile']['name'][0];
					$sql = "UPDATE nav SET {$typeOfFile}=:name WHERE id=:id";
					$params = array(':name' => $name, ':id' => $_GET['value']);
					$result = DatabaseHandler::Execute($sql, $params);
					
					
				} else {
					
				}
				inactivate('nav', $value);
			}
		}
		if($type == 'subnav') 
		{
			if($_FILES['ufile']['size'][0] != 0) {
				$typeOfFile = $_POST['pathToUpload'];
				$subnav = get_subnavbyid($value);
				$curr_subnav = mysql_fetch_array($subnav);
				$nav = get_navbyid($curr_subnav['nav_id']);
				$curr_nav = mysql_fetch_array($nav);
				$url = $curr_subnav['url'];
				$nav_url = $curr_nav['url'];
				$d = new DirCreator ();
				
				if($curr_subnav['isactive']) {
					$src = "../" .$nav_url. "/" .$url;
					$des = "../temp/".$url;
					$success = $d -> copyDir($src, $des);
				}
				$path = "../temp/";
				if($typeOfFile == 'banner') {
					$path = $path.$url."/images/banner/";
					
				} else if($typeOfFile == 'gridimg') {
					
					$path = $path.$url."/images/gridimage/";;
				} else {
					$path = $path.$url."/uploads/";
				}
				$imgUploader = new FileHandler();
				$uploaded = $imgUploader -> uploader($path);
				if($uploaded == 1 && ($typeOfFile == 'banner' || $typeOfFile == 'gridimg')) {
					$name = $_FILES['ufile']['name'][0];
					$sql = "UPDATE subnav SET {$typeOfFile}=:name WHERE id=:id";
					$params = array(':name' => $name, ':id' => $_GET['value']);
					$result = DatabaseHandler::Execute($sql, $params);
					
				}
				inactivate('subnav', $value);
			}
		}
		
		if ($type == 'rpanel') {
			if($_FILES['ufile']['size'][0] != 0) {
				if($value == 0) {
					$path  = "../uploads/";
				} else {
					$path = "../images/icons/";
				}
				$n = explode(".", $_FILES['ufile']['name'][0]);
				$n[0] .= (string) get_random_no();
				$name = $n[0].".".$n[1];
				$_FILES['ufile']['name'][0] = $name;
				$imgUploader = new FileHandler();
				$uploaded = $imgUploader -> uploader($path);

				if($uploaded == 1) {
					$link = "/uploads/".$_FILES['ufile']['name'][0];
				}
				if($value != 0) {
					$link = "../images/icons/".$_FILES['ufile']['name'][0];
					$sql = "UPDATE right_panel_topics SET icon =:name WHERE id=:id";
					$params = array(':name' => $name, ':id' => $_GET['value']);
					$result = DatabaseHandler::Execute($sql, $params);
				}
				
			}
		}
		
		if ($type == 'editor') {
			if($_FILES['ufile']['size'][0] != 0) {
				if($value == 0) {
					$path  = "../uploads/";
				
					$n = explode(".", $_FILES['ufile']['name'][0]);
					$n[0] .= (string) get_random_no();
					$name = $n[0].".".$n[1];
					$_FILES['ufile']['name'][0] = $name;
					$imgUploader = new FileHandler();
					$uploaded = $imgUploader -> uploader($path);

					if($uploaded == 1) {
						$link = "/uploads/".$_FILES['ufile']['name'][0];
					}
				}
				
			}
		}
		
		if ($type == 'events') {
			if($_FILES['ufile']['size'][0] != 0) {
				$typeOfFile = $_POST['pathToUpload'];
				$event = mysql_fetch_array(get_eventbyid($value));
				$path  = "../uploads/events/";
				
				
				$n = explode(".", $_FILES['ufile']['name'][0]);
				$n[0] .= (string) get_random_no();
				$name = $n[0].".".$n[1];
				$_FILES['ufile']['name'][0] = $name;
				$imgUploader = new FileHandler();
				$uploaded = $imgUploader -> uploader($path);
				if($uploaded == 1 && ($typeOfFile == 'front_image' || $typeOfFile == 'inner_image')) {
					$name = $_FILES['ufile']['name'][0];
					$sql = "UPDATE events SET {$typeOfFile}=:name WHERE id=:id";
					$params = array(':name' => $name, ':id' => $value);
					$result = DatabaseHandler::Execute($sql, $params);
				}
			}
		}
	
		if ($type == 'carousel') {
			if($_FILES['ufile']['size'][0] != 0) {
				$path  = "../uploads/carousel/";
				
				$n = explode(".", $_FILES['ufile']['name'][0]);
				$n[0] .= (string) get_random_no();
				$name = $n[0].".".$n[1];
				$_FILES['ufile']['name'][0] = $name;
				$imgUploader = new FileHandler();
				$uploaded = $imgUploader -> uploader($path);
				if($uploaded == 1) {
					$name = $_FILES['ufile']['name'][0];
					$sql = "UPDATE carousel SET image=:name WHERE id=:id";
					$params = array(':name' => $name, ':id' => $_GET['value']);
					$result = DatabaseHandler::Execute($sql, $params);
					
				}
			}
		}
		if($type == 'tenders') {
			if($_FILES['ufile']['size'][0] != 0) {
				$path  = "../downloads/tenders/uploads/";
				
				$n = explode(".", $_FILES['ufile']['name'][0]);
				$n[0] .= (string) get_random_no();
				$name = $n[0].".".$n[1];
				$_FILES['ufile']['name'][0] = $name;
				$imgUploader = new FileHandler();
				$uploaded = $imgUploader -> uploader($path);
				if($uploaded == 1) {
					$name = $_FILES['ufile']['name'][0];
					$sql = "UPDATE tenders SET file=:name WHERE id=:id";
					$params = array(':name' => $name, ':id' => $_GET['value']);
					$result = DatabaseHandler::Execute($sql, $params);
				}
			}
		
		}
		if($type == 'announcements') {
			if($_FILES['ufile']['size'][0] != 0) {
				$path  = "../downloads/announcements/uploads/";
				
				$n = explode(".", $_FILES['ufile']['name'][0]);
				$n[0] .= (string) get_random_no();
				$name = $n[0].".".$n[1];
				$_FILES['ufile']['name'][0] = $name;
				$imgUploader = new FileHandler();
				$uploaded = $imgUploader -> uploader($path);
				if($uploaded == 1) {
					$name = $_FILES['ufile']['name'][0];
					$sql = "UPDATE announcements SET file=:name WHERE id=:id";
					$params = array(':name' => $name, ':id' => $_GET['value']);
					$result = DatabaseHandler::Execute($sql, $params);
				}
			}
		
		}
		
		if ($type == 'gfiles') {
			if($_FILES['ufile']['size'][0] != 0) {
				$typeOfFile = $_POST['pathToUpload'];
				$event = mysql_fetch_array(get_eventbyid($value));
				$path  = "";
					
				if($typeOfFile <= 5)
					$path = "../images/";
				if($typeOfFile > 5 && $typeOfFile <= 9 )
					$path = "../images/banners/";
				if($typeOfFile > 9)
					$path = "../images/icons/";
				
				if($typeOfFile == 1)
					$filename = "headBack.png";
				
				if($typeOfFile == 2)
					$filename = "header.png";
				
				
				if($typeOfFile == 3)
					$filename = "h-nav.png";
				
				
				if($typeOfFile == 4)
					$filename = "footer.png";
				
				
				if($typeOfFile == 5)
					$filename = "flogo.jpg";
				
				if($typeOfFile == 6)
					$filename = "event_banner.jpg";
				
				
				if($typeOfFile == 7)
					$filename = "news_banner.jpg";
				
				if($typeOfFile == 8)
					$filename = "announcement_banner.jpg";
				
				if($typeOfFile == 9)
					$filename = "tender_banner.jpg";
				
				if($typeOfFile == 10)
					$filename = "iconEvent.png";
				
				if($typeOfFile == 11)
					$filename = "iconAnnounce.png";
				
				if($typeOfFile == 12)
					$filename = "iconNews.png";
				
				if($typeOfFile == 13)
					$filename = "iconQuery.png";
				
				if($typeOfFile == 14)
					$filename = "iconSubscribe.png";
				if($typeOfFile==0)
					echo  "<p style='color:red'>Select the type of file you want to upload</p>";
				else {
					$_FILES['ufile']['name'][0] = $filename;
					$imgUploader = new FileHandler();
					$uploaded = $imgUploader -> uploader($path);
					if($uploaded == 1 ) {
						$name = $_FILES['ufile']['name'][0];
					} else {
						$name = '';
					}
				}
				
			} else {
				echo "<p style='color:red'>Select a file </p>";
			}
		}
		
	}
	
	if($type == 'nav') {
		 $imghandler = new FileHandler();
		$uploadLink = Link::uploadNav($value);
		$uploadCompleteLink = Link::viewNav();
		echo "<h4>Upload Banner Image and Icon for the Nav.</h4><h5>(Do not forget to reactivate the Nav after uploading)</h5></br>";
		$imghandler -> listFiles($type, $value);
		
		echo "<form action = '{$uploadLink}' method='post' enctype='multipart/form-data'>";
			$imghandler -> chooser("");
		echo "<select name='pathToUpload'>
			  <option value='banner'>Banner</option>
			  <option value='icon'>Icon Image</option>
			 </select><br/>"; 
		echo "<input type='submit' name='file_upload' value='Upload Files' style='color:#359; background:#ffd;'/>
				<a href='{$uploadCompleteLink}'>Upload Complete</a>
			</form>";
	}
	else if($type == 'subnav') {
		 $imghandler = new FileHandler();
		$uploadLink = Link::uploadSubNav($value);
		$uploadCompleteLink = Link::viewNav();
		echo "<h4>Upload Banner Image, Grid Image and Image for the Subnav.</h4><h5>(Do not forget to reactivate the Subnav after uploading)</h5></br>";
		$imghandler -> listFiles($type, $value);
		
		echo "<form action = '{$uploadLink}' method='post' enctype='multipart/form-data'>";
			$imghandler -> chooser("");
		echo "<select name='pathToUpload'>
			  <option value='banner'>Banner</option>
			  <option value='gridimg'>Grid Image</option>
			  <option value='image'>Image</option>
			  <option value='uploads'>Other</option>
			 </select><br/>"; 
		echo "<input type='submit' name='file_upload' value='Upload Files' style='color:#359; background:#ffd;'/>
				<a href='{$uploadCompleteLink}'>Upload Complete</a>
			</form>";
	}
	
	else if($type == 'rpanel') 
	{
		$imghandler = new FileHandler();
		$uploadLink = Link::uploadRpanel($value);
		$uploadCompleteLink = Link::viewRpanelTopic();
		echo "<h4>Upload Files and Icon for the Rpanel.</h4></br>";
		if($value==0) {
			if(isset($link)) 
				echo "Copy this Link For using in link field of Rpanel .<br/> <font color='green'>$link</font><br/><br/>";
		}
		else if(isset($link)){
			echo "<img src='{$link}' style='height:20px; width:20px;'/><br/>$name<br/><br/>";
		}
		echo "<form action = '{$uploadLink}' method='post' enctype='multipart/form-data'>";
			$imghandler -> chooser("");
		
		echo "<input type='submit' name='file_upload' value='Upload Files' style='color:#359; background:#ffd;'/>
				<a href='{$uploadCompleteLink}'>Upload Complete</a>
			</form>";
	}
	
	else if($type == 'editor') 
	{
		$imghandler = new FileHandler();
		$uploadLink = Link::uploadEditor($value);
		$uploadCompleteLink = Link::viewEditor();
		echo "<h4>Upload General Files.</h4></br>";
		if($value==0) {
			if(isset($link)) 
				echo "Copy this Link to use anywhere.<br/> <font color='green'>$link</font><br/><br/>";
		}
		
		echo "<form action = '{$uploadLink}' method='post' enctype='multipart/form-data'>";
			$imghandler -> chooser("");
		
		echo "<input type='submit' name='file_upload' value='Upload Files' style='color:#359; background:#ffd;'/>
				<a href='{$uploadCompleteLink}'>Upload Complete</a>
			</form>";
	}
	
	
	else if($type == 'events') 
	{
		$imghandler = new FileHandler();
		$uploadLink = Link::uploadEvent($value);
		$uploadCompleteLink = Link::viewEvent();
		echo "<h4>Upload Front Image and Inner Image for the Event.</h4></br>";
		if(isset($name)) 
			echo "<img src='../uploads/events/{$name}'  style='height:170px; width:220px;'/> <br/> $name<br/>";
		echo "<form action = '{$uploadLink}' method='post' enctype='multipart/form-data'>";
			$imghandler -> chooser("");
		echo "<select name='pathToUpload'>
			  <option value='front_image'>Front Image</option>
			  <option value='inner_image'>Inner Image</option>
			  </select><br/>"; 
		echo "<input type='submit' name='file_upload' value='Upload Files' style='color:#359; background:#ffd;'/>
				<a href='{$uploadCompleteLink}'>Upload Complete</a>
			</form>";
	}
	 
	else if($type == 'carousel') {	
		$imghandler = new FileHandler();
		$uploadLink = Link::uploadCarousel($value);
		$uploadCompleteLink = Link::viewCarousel();
		echo "<h4>Upload the Carousel Image .</h4></br>";
		if(isset($name)) 
			echo "<img class='img-circle' src='../uploads/carousel/{$name}'  style='height:333px; width:490px;'/> <br/> $name<br/>";
		echo "<form action = '{$uploadLink}' method='post' enctype='multipart/form-data'>";
			$imghandler -> chooser("");
		
		echo "<input type='submit' name='file_upload' value='Upload Files' style='color:#359; background:#ffd;'/>
				<a href='{$uploadCompleteLink}'>Upload Complete</a>
			</form>";
	} 
	
	else if($type == 'tenders') {
		$imghandler = new FileHandler();
		$uploadLink = Link::uploadTender($value);
		$uploadCompleteLink = Link::viewTender();
		echo "<h4>Upload Tender file.</h4></br>";
		if(isset($name)) 
			echo $name."<br/>";
		echo "<form action = '{$uploadLink}' method='post' enctype='multipart/form-data'>";
			$imghandler -> chooser("");
		
		echo "<input type='submit' name='file_upload' value='Upload Files' style='color:#359; background:#ffd;'/>
				<a href='{$uploadCompleteLink}'>Upload Complete</a>
			</form>";
	}
	
	else if($type == 'announcements') {
		$imghandler = new FileHandler();
		$uploadLink = Link::uploadAnnouncement($value);
		$uploadCompleteLink = Link::viewAnnouncement();
		echo "<h4>Upload the Announcement file.</h4></br>";
		if(isset($name)) 
			echo $name."<br/>";
		echo "<form action = '{$uploadLink}' method='post' enctype='multipart/form-data'>";
			$imghandler -> chooser("");
		
		echo "<input type='submit' name='file_upload' value='Upload Files' style='color:#359; background:#ffd;'/>
				<a href='{$uploadCompleteLink}'>Upload Complete</a>
			</form>";
	}
	
	else if($type == 'gfiles') 
	{
		
		$imghandler = new FileHandler();
		$uploadLink = Link::uploadGfiles();
		$uploadCompleteLink = Link::viewGfiles();
		echo "<br/><br/>";
		if(isset($typeOfFile) && $typeOfFile != 0) {
			if($typeOfFile <= 5) {
				$path = "../images/".$name;
				echo "<img src='{$path}'  style='height:150; width:850;'/> <br/> $name<br/>";
			}
			if($typeOfFile > 5 && $typeOfFile <= 9 ) {
				$path = "../images/banners/".$name;
				echo "<img src='{$path}'  style='height:150; width:850;'/> <br/> $name<br/>";
			}
			if($typeOfFile > 9) {
				$path = "../images/icons/".$name;
				echo "<img src='{$path}'  style='height:20; width:20;'/> <br/> $name<br/>";
			}
		}
		echo "<form action = '{$uploadLink}' method='post' enctype='multipart/form-data'>";
			$imghandler -> chooser("");
		echo "<select name='pathToUpload'>
				  <option value='0'>Select..</option>
				  <option value='1'>Site Header Back</option>
				  <option value='2'>Site Header Front</option>
				  <option value='3'>Nav Background</option>
				  <option value='4'>Site Footer Back</option>
				  <option value='5'>Site Footer Logo</option>
				  <option value='6'>Event Banner</option>
				  <option value='7'>News Banner</option>
				  <option value='8'>Announcement Banner</option>
				  <option value='9'>Tender Banner</option>
				  <option value='10'>Event Icon</option>
				  <option value='11'>Announcement Icon</option>
				  <option value='12'>News Icon</option>
				  <option value='13'>Query Icon</option>
				  <option value='14'>Subscribe Icon</option>
			  </select><br/>";
		echo "<input type='submit' name='file_upload' value='Upload Files' style='color:#359; background:#ffd;'/>
				<a href='{$uploadCompleteLink}'>Upload Complete</a>
			</form>";
			
		
	}
?>
