<?php
require_once('business/FileHandler.class.php');
require_once('business/DirCreator.class.php');
require_once('business/Contentfetcher.class.php');
require_once('business/ContentSanitize.class.php');

$san = new Sanitize();

if($value && $type)
{
	if($type == 'nav')
	{
		$editLink = Link::editNav($_GET['value']);
		$failLink = $editLink."&s=0";
		$successLink = $editLink."&s=1";
		if(!isset($_POST['edit_nav']))
		{
			//$cFetcher = new ContentFetcher($type, $value);
			//$result = $cFetcher->fetchData();
		   
			$result = get_navbyid($value);
			$r = mysql_fetch_array($result);
			if(isset($_GET['s']) && $_GET['s'] == 0) {
				echo "Title Field cannot be left Blank!";
			} else if (isset($_GET['s']) && $_GET['s'] == 1) {
				echo "Edit Successful!";
			}
			
			echo " <div class='span10 navHolder'>
					<p style='float:right; margin: 8px;padding:10px; background:#000; color:#fff; margin-right:45px; font-family:arial;'>Last Modified on: '" . ($r['modified_on']) ."' &nbsp;&nbsp;by '". $r['modified_by'] ."' </p>
					<div class='span5'>
					<form action='{$editLink}' method='post'>
						<label value='title'> Title: </label><input  class='span4' type='text' maxlength='25' value='{$r['title']}' name='title'/><br/>
						<label value='tagline'> Tagline: : </label> <input class='span4' type='text' maxlength='50' value='{$r['tagline']}' name='tagline'/> <br/>
						<label value='innerTagline'> Inner Tagline: : </label> <input class='span4' type='text' maxlength='50' name='inner_tagline' value='{$r['inner_tagline']}'></textarea> <br/>
						<label value='overview'> Overview:</label><textarea class='span4' rows='6' cols='10' maxlength='300' name='overview'>{$r['overview']}</textarea> <br/>
						 
					   <label value='links'> Links:</label> <textarea class='span4' rows='4' cols='30' name='links'>{$r['links']}</textarea> <br/>
						<input type='submit' class='btn-success' value='  Save  ' name='edit_nav' />
						<input type='submit' class='btn-danger' value='Cancel' name='cancel' />
					</form>
					</div>
					<div class='span4 help-msg'>
					<ul>
					<h4><u>Note:</u></h4>
					<li>1. Maximum length for 'title' is 25 characters.</li>
					<li style='color:red;'>2. Do not add special characters in title. </li>
					<li>3. Format for links: Keyword,link; </li>
					<li>4. Multiple links can be added. Ex. key1,link1;key2,link2;key3,link3; </li>
					</ul>
				
					</div>
				</div>
			";
		}
		else
		{
			$title = $san -> cleanString($_POST['title']);
			$url = strtolower(str_replace(" ", "_", $title));
			$tagline = $san -> cleanString($_POST['tagline']);
			$inner_tagline = $san -> cleanString($_POST['inner_tagline']);
			$links = $san -> cleanString($_POST['links']);
			$overview = $san -> cleanString($_POST['overview']);
			$modified_by = $_SESSION['_iiita_cms_username_'];
			//if inactive; rename folder in temp
			//if active; make a copy in temp and rename folder.
			if (!$title) {
				header('Location:'.$failLink);
			} else {
				$d = new DirCreator ();
				$n = mysql_fetch_array(get_Navbyid($value));
				$old_url = $n['url'];
				$success = false;
		   
					if($n['isactive'])
					{
							$src = "../".$old_url;
							$des = "../temp/".$url;
							$success = $d -> copyDir($src, $des);
					}
					else
					{
							$src = "../temp/".$old_url;
							$des = "../temp/".$url;
							$success = $d -> renameDir($src, $des);
					}

				//$x = update_nav($value, $title, $tagline, $inner_tagline, $links, $overview);
				$sql = 'UPDATE nav SET title = :title, url = :url, tagline = :tagline, inner_tagline = :inner_tagline, links = :links,
				overview = :overview, modified_by = :modified_by, modified_on = NOW() WHERE id = :id';
				$params = array(':title' => $title, ':url' => $url, ':tagline' => $tagline, ':inner_tagline' => $inner_tagline, ':links' => $links, ':overview' => $overview, ':id' => $value, ':modified_by' => $modified_by);
				$x = DatabaseHandler::Execute($sql, $params);
				$y = inactivate('nav', $value);
		   
				if($x && $y){
					$ip = $_SERVER['REMOTE_ADDR'];
					$input = $title.' $$ '.$url.' $$ '.$tagline.' $$ '.$inner_tagline.' $$ '.$links.' $$ '.$overview.' $$ '.$value.' $$ '; 
                    Logger::logInput($modified_by, "edit", $type, $input, $ip);
					header('Location:'.$successLink);
				}
				else
					echo "Error!!";
			}
		}
	}
	   
	else if($type == 'subnav')
	{
		$editLink = Link::editSubnav($_GET['value']);
		$failLink = $editLink."&s=0";
		$successLink = $editLink."&s=1";
		if(!isset($_POST['edit_subnav']))
		{
			
			$result = get_subnavbyid($value);
			$r = mysql_fetch_array($result);
			$s_url = $r['url'];
			$s_nav = mysql_fetch_array(get_navbyid($r['nav_id']));
			$n_url = $s_nav['url'];
			if(isset($_GET['s']) && $_GET['s'] == 0) {
				echo "Title Field cannot be left Blank!";
			} else if (isset($_GET['s']) && $_GET['s'] == 1) {
				echo "Edit Successful!";
			}
			
			if(!$r['isactive'])
			{
				$actLink = Link::actSubnav($_GET['value']);
				echo " <a href='$actLink'> Activate </a>";
			}
			echo "<div class='span10 navHolder'>
					<p style='float:right; margin: 8px;padding:10px; background:#000; color:#fff; margin-right:25px; font-family:arial;'>Last Modified on: '" . ($r['modified_on']) ."' &nbsp;&nbsp;by '". $r['modified_by'] ."' </p>
					<form action='{$editLink}' method='post'>
						<div class='span10'>
						<div class='span5'>
						<label value='title'> Title: </label> <input class='span4' type='text' maxlength='21' value='{$r['title']}' name='title'/> <br/>
						<label value='tagline'> Tagline: </label><input class='span4' type='text' name='tagline' maxlength='50' value='{$r['tagline']}' /><br/>
						<label value='overview'> Overview:  </label><textarea class='span4' rows='6' maxlength='300' name='overview'>{$r['overview']}</textarea> <br/>
						</div>
					
					<div class='span4 help-msg'>
					<ul>
					<h4><u>Note:</u></h4>
					<li>1. Maximum length for 'title' is 20 characters.</li>
					<li style='color:red;'>2. Do not add special characters in title. Also mind that title is unique. </li>
					<li>3. Upload path for any file is <span style='color:green;'>uploads/FILENAME</span> </li>
					</ul>
				
					</div>
					</div>

					<br/>
					<div class='span10'>
						<label value='content'> Content: </label>
						<b><p title='click to view preview' onclick='preview(\"$s_url\", \"$n_url\"); lightBoxMe(\"hiddenHolder\");' style='color:#02495d; font-size:15px; margin-right:100px; cursor:pointer;float:right;font-weight:900;'><img src='images/preview.png' style='width:20px; height:20px;'/>Preview</p></b>
						<textarea id='subContent' class='span9' rows='15' name='content'>{$r['content']}</textarea><br/>
						<label value='css'> CSS: </label><textarea id='cssContent' class='span9' rows='5' name='css'>{$r['css']}</textarea> <br/>
						<label value='js'> JS: </label><textarea id='jsContent' class='span9' rows='5' name='js'>{$r['js']}</textarea> <br/>
						<input type='submit' class='btn-success' value='  Save  ' name='edit_subnav' />
						<input type='submit' class='btn-danger' value='Cancel' name='cancel' />

				</form>
					</div>
				<div id='hiddenHolder' class='span12' style='display:none; height:auto; background:#fff;'>
					
					<img src='/images/close.png' style='float:right;margin-top:-25px;margin-right:-25px; cursor:pointer;' onclick='hideMe(\"hiddenHolder\");' title='Press to close	'/>
					<div class='span12 sub-banner'>
						<img src='/$n_url/$s_url/images/banner/{$r['banner']}' />
					</div>			
					<div class='row'>
					<div class='span3 v-nav-container'>
						<div class='v-grid v-first'>";

 						require_once("../$n_url/includes/side_nav.php");
				echo	"	</div>	
					</div>
					<div class='span8'>
						<div id='alertBox' class='span8' style='background-color:#74a446; color:#fff; margin-top:5px; padding:5px;border:1px solid #eee;display:none; padding-left:10px;'>
							
						</div>
						<div id='cssNjs'></div>
						<div id='hidden' class='span8' onClick=\"this.contentEditable='true';\">
						
						</div>
					</div>
					</div>
						
					<input type='submit' onclick='saveMe(\"alertBox\", \"Changes made in the preview saved successfully. But do click Save button after closing this box. \", \"$s_url\", \"$n_url\");' style='float:right;margin-right:100px' class='btn-info' value='  Save Changes ' name='edit_subnav' />
					<input type='submit' onclick='hideMe(\"hiddenHolder\");' style='float:right;margin-right:10px' class='btn' value='  Close  '  /><br/><br/><br/>
</div>
				</div>
				
			";
		}
		else
		{	
			$title = $san -> cleanString($_POST['title']);
			$url = strtolower(str_replace(" ", "_", $title));
			$tagline = $san -> cleanString($_POST['tagline']);
			$overview = $san -> cleanString($_POST['overview']);
			$content = $san -> cleanHtml($_POST['content']);
			$css = $san -> cleanHtml($_POST['css']);
			$js = $san -> cleanJs($_POST['js']);
			$modified_by = $_SESSION['_iiita_cms_username_'];
			if (!$title) {
				header('Location: '.$failLink);
			} else {
				$d = new DirCreator();
				$s = mysql_fetch_array(get_subnavbyid($value));
				$n = mysql_fetch_array(get_navbyid($s['nav_id']));
				$old_url = $s['url'];
				$nav_url = $n['url'];
				$success = false;
   
				if($s['isactive'])
				{
					$src = "../" .$nav_url. "/" .$old_url;
					$des = "../temp/".$url;
					$success = $d -> copyDir($src, $des);
				}
				else
				{
					$src = "../temp/".$old_url;
					$des = "../temp/".$url;
					$success = $d -> renameDir($src, $des);
				}
		   
				$sql = 'UPDATE subnav SET title = :title, url = :url, tagline = :tagline, overview = :overview, css = :css, js = :js, content = :content, modified_by = :modified_by, modified_on = NOW() WHERE id = :id';
				$params = array(':title' => $title, 'url' => $url, ':tagline' => $tagline, ':content' => $content, ':css' => $css, ':js' => $js, ':overview' => $overview, ':id' => $value, ':modified_by' => $modified_by);
				$x = DatabaseHandler::Execute($sql, $params);
				$y = inactivate('subnav', $value);
		   
				if($x && $y) {
					$ip = $_SERVER['REMOTE_ADDR'];
					$input = $title.' $$ '.$url.' $$ '.$tagline.' $$ '.$content.' $$ '.$css.' $$ '.$js.' $$ '.$overview.' $$ '.$value.' $$ '; 
                    Logger::logInput($modified_by, "edit", $type, $input, $ip);
					header('Location: '.$successLink);
				}
				else
					echo "Error!!";
			}
		}
	}
   
   
	else if($type == 'rpanel')
	{
		$editLink = Link::editRpanelTopic($_GET['value']);
		$failLink = $editLink."&s=0";
		$successLink = $editLink."&s=1";
		if(!isset($_POST['edit_rpanel']))
		{
				
				$cFetcher = new ContentFetcher($type, $value);
				$result = $cFetcher -> fetchData();
				$r = mysql_fetch_array($result);
				if(isset($_GET['s']) && $_GET['s'] == 0) {
					echo "Title Field cannot be left Blank!";
				} else if (isset($_GET['s']) && $_GET['s'] == 1) {
					echo "Edit Successful!";
				}
				echo "<div class='span4 navHolder'>
						<br/>
						<form action='{$editLink}' method='post'>
								<label> Title: </label><input type='text' value='{$r['title']}' maxlength='20' name='title'/> <br/>
								<label> Priority: </label> <input type='text' value='{$r['priority']}' name='priority'/> <br/>
								<input type='submit' class='btn btn-success' value='submit' name='edit_rpanel' />
								<input type='submit' class='btn' value='Cancel' name='cancel' />
						</form>
						<br/>
					</div>	
						";
		}
		else
		{
				$title = $san -> cleanString($_POST['title']);
				$priority = $san -> cleanString($_POST['priority']);
				$modified_by = $_SESSION['_iiita_cms_username_'];
				
				if (!$title) {
					header('Location:'.$failLink);
				} else {
					$result = 0;
					$sql = 'UPDATE right_panel_topics SET title = :title, priority = :priority, modified_on = NOW(), modified_by = :modified_by WHERE id = :id';
					$params = array(':title' => $title, ':priority' => $priority , ':id' => $value, ':modified_by' => $modified_by);
					$result = DatabaseHandler::Execute($sql, $params);
			
					if($result) {
						$ip = $_SERVER['REMOTE_ADDR'];
						$input = $title.' $$ '.$priority.' $$ '.$value.' $$ '.$modified_by.' $$ '; 
						Logger::logInput($modified_by, "edit", $type, $input, $ip);
						header('Location: '.$successLink);
					}
					else
						echo "Error! ";
				}
		}
	}

   
	else if($type == 'rpanelcontent')
	{
		$editLink = Link::editRpanelContent($_GET['value']);
		$failLink = $editLink."&s=0";
		$successLink = $editLink."&s=1";
		if(!isset($_POST['edit_rpanelcontent']))
		{
				$cFetcher = new ContentFetcher($type, $value);
				$result = $cFetcher -> fetchData();
				$r = mysql_fetch_array($result);
				if(isset($_GET['s']) && $_GET['s'] == 0) {
					echo "Title and Link Fields cannot be left Blank!";
				} else if (isset($_GET['s']) && $_GET['s'] == 1) {
					echo "Edit Successful!</br>";
				}
				echo "<br/><br/><div class='span10 navHolder'>
						<br/>
						<div class='span4'>
						<form action='{$editLink}' method='post'>
								<label> Title</label>
								<input type='text' maxlength='50' value='{$r['title']}' name='title'/> <br/>
								<label> Priority</label>
								<input type='text' value='{$r['priority']}' name='priority'/> <br/>
								<label> Link</label>
								<input type='text' value='{$r['link']}' name='link'/> <br/>
								<input type='submit' class='bnt btn-success' value='submit' name='edit_rpanelcontent' />
								<input type='submit' class='bnt' value='Cancel' name='cancel' />
						</form>
						<br/>
					</div>
					<div class='span4 help-msg'>
					<ul>
					<h4><u>Note:</u></h4>
					<li>1. Maximum length for 'title' is 50 characters.</li>
					<li>2. For 'Link' field you can either paste an external link OR first SAVE this and UPLOAD a file to get the LINK. </li>
					</ul>
				
					</div>
					</div>
					";
		}
		else
		{
				$title = $san -> cleanString($_POST['title']);
				$priority = $san -> cleanString($_POST['priority']);
				$link = $san -> cleanString($_POST['link']);
				$modified_by = $_SESSION['_iiita_cms_username_'];
				
				if (!$title || !$link) {
					header('Location:' .$failLink);
				} else {
					$result = 0;
					$sql = 'UPDATE right_panel_content SET title = :title, priority = :priority, link = :link, modified_on = NOW(), modified_by = :modified_by WHERE id = :id';
					$params = array(':title' => $title, ':priority' => $priority, ':link' => $link,':id' => $value, ':modified_by' => $modified_by);
					$result = DatabaseHandler::Execute($sql, $params);
			
					if($result) {
						$ip = $_SERVER['REMOTE_ADDR'];
						$input = $title.' $$ '.$priority.' $$ '.$link.' $$ '.$value.' $$ '.$modified_by.' $$ '; 
						Logger::logInput($modified_by, "edit", $type, $input, $ip);
						header('Location: '.$successLink);
					}
					else
						echo "Error! ";
				}
		}
	}
	
	else if($type == 'events')
	{
		$editLink = Link::editEvent($_GET['value']);
		$failLink = $editLink."&s=0";
		$successLink = $editLink."&s=1";
		if(!isset($_POST['edit_event']))
		{
		   
			$result = get_eventbyid($value);
			$r = mysql_fetch_array($result);
			if(isset($_GET['s']) && $_GET['s'] == 0) {
				echo "Title and Content Fields cannot be left Blank!";
			} else if (isset($_GET['s']) && $_GET['s'] == 1) {
				echo "Edit Successful!";
			}
			echo " <div class='span10 navHolder'>
					<form action='{$editLink}' method='post'>
						<label value='front_title'> Front Title: </label><input  class='span4'  type='text' value='{$r['front_title']}' name='front_title'/><br/>
						<label value='inner_title'> Inner Title: </label> <input class='span4' type='text' value='{$r['inner_title']}' name='inner_title'/> <br/>
						<label value='front_content'> Front Content : </label> <textarea class='span9' type='text' name='front_content' >{$r['front_content']}</textarea> <br/>
						<label value='inner_content'>Inner Content:</label><textarea class='span9' rows='6' cols='10' name='inner_content'>{$r['inner_content']}</textarea> <br/>
						<label value='event_date'> Event Date:</label><input type='text' class='span4 jdpicker' rows='6' cols='10' name='event_date'  value='{$r['event_date']}' /> <br/>
						<label value='image_caption'> Image Caption:</label><textarea class='span4' rows='2' cols='10' maxlength='50' name='image_caption'>{$r['image_caption']}</textarea> <br/>
						<input type='submit' class='btn  btn-success' value='  Save  ' name='edit_event' />
						<input type='submit' class='btn' value='Cancel' name='cancel' />
					</form>
				</div>
			";
		}
		else
		{
			$front_title = $san -> cleanString($_POST['front_title']);
			$inner_title = $san -> cleanString($_POST['inner_title']);
			$front_content = $san -> cleanString($_POST['front_content']);
			$inner_content = $san -> cleanHTML($_POST['inner_content']);
			$image_caption = $san -> cleanString($_POST['image_caption']);
			$event_date = $san -> cleanString($_POST['event_date']);
			$modified_by = $_SESSION['_iiita_cms_username_'];
			
			if (!$front_title || !$inner_title || !$front_content || !$inner_content) {
				header('Location:'.$failLink);
			} else {
				$result = 0;
				$sql = 'UPDATE events SET front_title = :front_title, inner_title = :inner_title, front_content = :front_content, inner_content = :inner_content, event_date = :event_date,	image_caption = :image_caption, modified_on = NOW(), modified_by = :modified_by WHERE id = :id';
				$params = array(':front_title' => $front_title, ':inner_title' => $inner_title, ':front_content' => $front_content, 'inner_content' => $inner_content, ':event_date' => $event_date, ':image_caption' => $image_caption, ':id' => $value, ':modified_by' => $modified_by);
				$result = DatabaseHandler::Execute($sql, $params);
			
				if($result) {
					Subscription::Delete($type, $value);
					Subscription::Add($type, $value, $modified_by);
					$ip = $_SERVER['REMOTE_ADDR'];
					$input = $front_title.' $$ '.$inner_title.' $$ '.$front_content.' $$ '.$inner_content.' $$ '.$event_date.' $$ '.$image_caption.' $$ '.$value.' $$ '.$modified_by.' $$ '; 
					Logger::logInput($modified_by, "edit", $type, $input, $ip);
					header('Location: '.$successLink);
				}
				else
					echo "Error! ";
			}
		}
		
	}
	
	else if($type == 'news')
	{
		$editLink = Link::editNews($_GET['value']);
		$failLink = $editLink."&s=0";
		$successLink = $editLink."&s=1";
		if(!isset($_POST['edit_news']))
		{
		   
			$result = get_newsbyid($value);
			$r = mysql_fetch_array($result);
			if(isset($_GET['s']) && $_GET['s'] == 0) {
				echo "Title and Content Fields cannot be left Blank!";
			} else if (isset($_GET['s']) && $_GET['s'] == 1) {
				echo "Edit Successful!";
			}
			echo " <div class='span10 navHolder'>
					<form action='{$editLink}' method='post'>
						<label value='front_title'> Front Title: </label><input  class='span4' type='text' maxlength='50' value='{$r['front_title']}' name='front_title'/><br/>
						<label value='inner_title'> Inner Title: </label> <input class='span4' type='text' maxlength='50' value='{$r['inner_title']}' name='inner_title'/> <br/>
						<label value='front_content'> Front Content: : </label> <textarea rows='3' class='span9' maxlength='300' type='text' name='front_content' >{$r['front_content']}</textarea> <br/>
						<label value='inner_content'> Inner Content:</label><textarea class='span9' rows='6' cols='10' name='inner_content'>{$r['inner_content']}</textarea> <br/>
						<label value='link'> Link: </label> <input class='span4' type='text' name='link' value='{$r['link']}'></textarea> <br/>
						<input type='submit' class='btn btn-success' value='  Save  ' name='edit_news' />
						<input type='submit' class='btn' value='Cancel' name='cancel' />
					</form>
				</div>
			";
		}
		else
		{
			$front_title = $san -> cleanString($_POST['front_title']);
			$inner_title = $san -> cleanString($_POST['inner_title']);
			$front_content = $san -> cleanString($_POST['front_content']);
			$inner_content = $san -> cleanString($_POST['inner_content']);
			$link = $san -> cleanString($_POST['link']);
			$modified_by = $_SESSION['_iiita_cms_username_'];
			
			if (!$front_title || !$inner_title || !$front_content || !$inner_content) {
				header('Location: '.$failLink);
			} else {
				$result = 0;
				$sql = 'UPDATE news SET front_title =:front_title, inner_title =:inner_title, front_content =:front_content, inner_content =:inner_content, link =:link, modified_on = NOW(), modified_by =:modified_by WHERE id = :id';
				$params = array(':front_title' => $front_title, ':inner_title' => $inner_title, ':front_content' => $front_content, ':inner_content' => $inner_content, ':link' => $link, ':id' => $value, ':modified_by' => $modified_by);
				$result = DatabaseHandler::Execute($sql, $params);
			
				if($result) {
					Subscription::Delete($type, $value);
					Subscription::Add($type, $value, $modified_by);
					$ip = $_SERVER['REMOTE_ADDR'];
					$input = $front_title.' $$ '.$inner_title.' $$ '.$front_content.' $$ '.$inner_content.' $$ '.$link.' $$ '.$value.' $$ '.$modified_by.' $$ '; 
					Logger::logInput($modified_by, "edit", $type, $input, $ip);	
					header('Location: '.$successLink);
				}
				else
					echo "Error! ";
			}
		}
		
	}
   
	else if($type == 'announcements')
        {
                $editLink = Link::editAnnouncement($_GET['value']);
                $failLink = $editLink."&s=0";
				$successLink = $editLink."&s=1";
                if(!isset($_POST['edit_announcement']))
                {
                   
                        $result = get_announcementbyid($value);
                        $r = mysql_fetch_array($result);
						if(isset($_GET['s']) && $_GET['s'] == 0) {
							echo "Title, Link or Content Field cannot be left Blank!";
						}  else if (isset($_GET['s']) && $_GET['s'] == 1) {
							echo "Edit Successful!";
						}
                        echo " <div class='span10 navHolder'>
					<div class='span5'>
                                        <form action='{$editLink}' method='post'>
                                                <label value='title'> Title: </label><input  class='span4' type='text' maxlength='100' value='{$r['title']}'name='title'/><br/>
                                                <label value='link'> Link: </label> <input class='span4' type='text' value='{$r['link']}' name='link'/> <br/>
                                                <label value='content'> Content: : </label> <textarea class='span8' rows='5' type='text' name='content'>{$r['content']}</textarea> <br/>
                                                <label value='expiry_date'> Expiry Date:</label><input class='span4 jdpicker' rows='6' cols='10' name='expiry_date'  value='{$r['expiry_date']}' /> <br/>
				<input type='checkbox' name='emphasize' "; if($r['emphasize']) echo "checked";  echo " /> Emphasize <br/>
                                                <input type='submit' class='btn  btn-success' value='  Save  ' name='edit_announcement' />
                                                <input type='submit' class='btn' value='Cancel' name='cancel' />
                                        </form>
					</div>
					<div class='span4 help-msg'>
					<ul>
					<h4><u>Note:</u></h4>
					<li>1. Maximum length for 'title' is 100 characters.</li>
					<li>2. For 'Link' field you can either paste an external link OR first SAVE this and UPLOAD a file to get the LINK. </li>
					</ul>
				
					</div>
                                </div>
                        ";
                }
                else
                {
                        $title = $san -> cleanString($_POST['title']);
                        $link = $san -> cleanString($_POST['link']);
                        $content = $san -> cleanString($_POST['content']);
                        $expiry_date = $san -> cleanString($_POST['expiry_date']);
                        $modified_by = $_SESSION['_iiita_cms_username_'];
			
			if(isset($_POST['emphasize']))
				$emphasize = 1;
			else
				$emphasize = 0;
                       
                        if (!$title || !$link || !$content) {
                            header('Location: '.$failLink);
                        } else {
                                $result = 0;
                                $sql = 'UPDATE announcements SET title = :title, link = :link, content = :content, emphasize= :emphasize,  expiry_date = :expiry_date, modified_on = NOW(), modified_by = :modified_by WHERE id = :id';
                                $params = array(':title' => $title, ':link' => $link, ':content' => $content, ':emphasize' => $emphasize, ':expiry_date' => $expiry_date,':id' => $value, ':modified_by' => $modified_by);
                                $result = DatabaseHandler::Execute($sql, $params);
                       
                                if($result) {
									Subscription::Delete($type, $value);
									Subscription::Add($type, $value, $modified_by);
									$ip = $_SERVER['REMOTE_ADDR'];
									$input = $title.' $$ '.$link.' $$ '.$content.' $$ '.$expiry_date.' $$ '.$value.' $$ '; 
									Logger::logInput($modified_by, "edit", $type, $input, $ip);	
									header('Location: '.$successLink);
								}
                                else
                                        echo "Error! ";
                        }
                }
               
        }
       
        else if($type == 'tenders')
        {
                $editLink = Link::editTender($_GET['value']);
                $failLink = $editLink."&s=0";
				$successLink = $editLink."&s=1";
                if(!isset($_POST['edit_tender']))
                {
                        $result = get_tenderbyid($value);
                        $r = mysql_fetch_array($result);
						if(isset($_GET['s']) && $_GET['s'] == 0) {
							echo "Title or Content Field cannot be left Blank!";
						} else if (isset($_GET['s']) && $_GET['s'] == 1) {
							echo "Edit Successful!";
						}
                        echo " <div class='span10 navHolder'>
					<div class='span5'>
                                        <form action='{$editLink}' method='post'>
                                                <label value='title'> Title: </label><input  class='span4' maxlength='100' type='text' value='{$r['title']}' name='title'/><br/>
                                                <label value='link'> Link: </label> <input class='span4' type='text' value='{$r['link']}' name='link'/> <br/>
                                                <label value='content'> Content: </label> <textarea class='span4' rows='4' type='text' name='content'>{$r['content']}</textarea> <br/>
                                                <label value='expiry_date'> Expiry Date:</label><input type='text' class='span4 jdpicker' rows='6' cols='10' name='expiry_date' value='{$r['expiry_date']}' /> <br/>
                                                <input type='submit' class='btn btn-success' value='  Save  ' name='edit_tender' />
                                                <input type='submit' class='btn' value='Cancel' name='cancel' />
                                        </form>
					</div>
					<div class='span4 help-msg'>
					<ul>
					<h4><u>Note:</u></h4>
					<li>1. Maximum length for 'title' is 100 characters.</li>
					<li>2. For 'Link' field you can either paste an external link OR first SAVE this and UPLOAD a file to get the LINK. </li>
					</ul>
				
					</div>
                                </div>
                        ";
                }
                else
                {
                        $title = $san -> cleanString($_POST['title']);
                        $link = $san -> cleanString($_POST['link']);
                        $content = $san -> cleanString($_POST['content']);
                        $expiry_date = $san -> cleanString($_POST['expiry_date']);
                        $modified_by = $_SESSION['_iiita_cms_username_'];
                       
                        if (!$title || !$content) {
                                header('Location: '.$failLink);
                        } else {
                                $result = 0;
                                $sql = 'UPDATE tenders SET title = :title, link = :link, content = :content, expiry_date = :expiry_date, modified_on = NOW(), modified_by = :modified_by WHERE id = :id';
                                $params = array(':title' => $title, ':link' => $link, ':content' => $content, ':expiry_date' => $expiry_date,':id' => $value, ':modified_by' => $modified_by);
                                $result = DatabaseHandler::Execute($sql, $params);
                       
                                if($result) {
									Subscription::Delete($type, $value);
									Subscription::Add($type, $value, $modified_by);
									$ip = $_SERVER['REMOTE_ADDR'];
									$input = $title.' $$ '.$link.' $$ '.$content.' $$ '.$expiry_date.' $$ '.$value.' $$ '; 
									Logger::logInput($modified_by, "edit", $type, $input, $ip);	
									header('Location: '.$successLink);
								}
                                else
                                        echo "Error! ";
                        }
                }
               
        }
	
	else if($type == 'carousel')
	{
		$editLink = Link::editCarousel($_GET['value']);
		$failLink = $editLink."&s=0";
		$successLink = $editLink."&s=1";
		if(!isset($_POST['edit_carousel']))
		{
		   
			$result = get_carouselbyid($value);
			$r = mysql_fetch_array($result);
			if(isset($_GET['s']) && $_GET['s'] == 0) {
				echo "Overview or Link Field cannot be left Blank!";
			} else if (isset($_GET['s']) && $_GET['s'] == 1) {
				echo "Edit Successful!</br>";
			}
			
			echo " <div class='span10 navHolder'>
					<div class='span5'>
					<form action='{$editLink}' method='post'>
						<label value='overview'> Overview: </label><textarea class='span4' rows='8' type='text' maxlength='250'  name='overview'>{$r['overview']}</textarea><br/>
						<label value='link'> Link: </label> <input class='span4' type='text' name='link' value='{$r['link']}'></textarea> <br/>
						<label value='weight'> Weight: </label> <input class='span4' type='text' name='weight' value='{$r['weight']}'></textarea> <br/>
						<input type='submit' class='btn btn-success' value='  Save  ' name='edit_carousel' />
						<input type='submit' class='btn' value='Cancel' name='cancel'>
					</form>
					<br/>
					</div>
					<div class='span4 help-msg'>
					<ul>
					<h4><u>Note:</u></h4>
					<li>1. Maximum length for 'overview' is 250 characters.</li>
					<li>2. Set 'Link' is bound on Read More button in carousel. </li>
					<li>3. Use weight to set priority in descending order. </li>
					</ul>
				
					</div>
				</div>
			";
		}
		else
		{
			$overview = $san -> cleanString($_POST['overview']);
			$link = $san -> cleanString($_POST['link']);
			$weight = $san -> cleanString($_POST['weight']);
			$modified_by = $_SESSION['_iiita_cms_username_'];
			
			if (!$overview || !$link) {
				header('Location:'.$failLink);
			} else {
				$result = 0;
				$sql = 'UPDATE carousel SET overview =:overview, link =:link, weight = :weight,  modified_on = NOW(), modified_by =:modified_by WHERE id = :id';
				$params = array(':overview' => $overview, ':link' => $link, ':weight' => $weight, ':id' => $value, ':modified_by' => $modified_by);
				$result = DatabaseHandler::Execute($sql, $params);
			
				if($result) {
					$ip = $_SERVER['REMOTE_ADDR'];
					$input = $overview.' $$ '.$link.' $$ '.$value.' $$ '.$modified_by.' $$ '; 
					Logger::logInput($modified_by, "edit", $type, $input, $ip);
					header('Location: '.$successLink);
				}
				else
					echo "Error! ";
			}
		}
		
	} 
	else if($type == 'subscriptions')
	{
					$editlink = Link::editSubTopic($_GET['value']);
					$failLink = $editlink."&s=0";
					$successLink = $editlink."&s=1";
					if(!isset($_POST['edit_sub_topic']))
					{
							
							$result = get_sub_topicsbyid($value);
							$r = mysql_fetch_array($result);
							if(isset($_GET['s']) && $_GET['s'] == 0) {
								echo "Title Field cannot be left Blank!";
							} else if (isset($_GET['s']) && $_GET['s'] == 1) {
								echo "Edit Successful!";
							}
							echo "
									<form action='{$editlink}' method='post'>
											<input type='text' maxlength='30' value={$r['title']} name='title'/> <br/>
											<input type='submit' value='submit' name='edit_sub_topic' />
									</form>";
					}
					else
					{
							$title = $san -> cleanString($_POST['title']);
							$modified_by = $_SESSION['_iiita_cms_username_'];
							
							if (!$title) {
								header('Location:'.$failLink);
							} else {
								$result = 0;
								$sql = 'UPDATE sub_topics SET title = :title, modified_on = NOW(), modified_by = :modified_by WHERE id = :id';
								$params = array(':title' => $title, ':id' => $value, ':modified_by' => $modified_by);
								$result = DatabaseHandler::Execute($sql, $params);
								
								if($result) {
									$ip = $_SERVER['REMOTE_ADDR'];
									$input = $title.' $$ '; 
									Logger::logInput($modified_by, "edit", $type, $input, $ip);
									header('Location: '.$successLink);
								}
								else
									echo "Error! ";
							}
					}
	}
	else if($type == 'sub_content')
	{
		$result = get_sub_contentbyid($value);
		$r = mysql_fetch_array($result);
				   
		echo "
			<textarea class='span4' rows='6' cols='10' name='inner_content'>{$r['data']}</textarea> <br/>";
				
	}
				
	
	else if($type == 'settings')
	{
		
		$editLink = Link::editSettings($_GET['value']);
		$failLink = $editLink."&s=0";
		$modified_by = $_SESSION['_iiita_cms_username_'];
		if(isset($_POST['restore_css'])) {
			$successLink = Link::editSettings()."&css=1";
			$css = file_get_contents('../css/style.back.css');
			$path = '/css/';
			$filename = 'style.css';
			writeContent($path, $filename, $css);
			$ip = $_SERVER['REMOTE_ADDR'];
			$input = $css. ' $$ '; 
			Logger::logInput($modified_by, "restore_css", $type, $input, $ip);
			header("Location:".$successLink);
		} 
		if(isset($_POST['restore_js'])) {
			$successLink = Link::editSettings()."&js=1";
			$js = file_get_contents('../js/custom.back.js');
			$path = '/js/';
			$filename = 'custom.js';
			writeContent($path, $filename, $js);
			
			$ip = $_SERVER['REMOTE_ADDR'];
			$input = $js. ' $$ '; 
			Logger::logInput($modified_by, "restore_js", $type, $input, $ip);
			header("Location:".$successLink);
		}
		if(!isset($_POST['edit_settings']))
		{
			$css_content = file_get_contents('../css/style.css');
			$js_content = file_get_contents('../js/custom.js');
			if(isset($_GET['s']))
				echo "<font color='green'>Successfully Saved CSS and JS.</font>";
			if(isset($_GET['css']))
				echo "<font color='green'>Successfully Restored CSS.</font>";
			if(isset($_GET['js']))
				echo "<font color='green'>Successfully Restored JS.</font>";
				
			echo "<div class='span10'>";
			echo "<form method='post' action={$editLink}>";
			echo "<div class='row'>";
			echo "<div class='span5'>";
			echo "<label style='color: rgb(35, 73, 93);'><h3> CSS:</h3></label>";
			echo "<textarea name='css' rows='30' class='span5' style='font-family:sans-serif; font-weight:bold;'> {$css_content}</textarea>";
			echo "</div>";
			echo "<div class='span5'>";
			echo "<label style='color: rgb(35, 73, 93);'><h3> JS:</h3></label>";
			echo "<textarea name='js' rows='30' class='span5' style='font-family:sans-serif; font-weight:bold;'> {$js_content}</textarea>";
			echo "</div>";
			echo "</div>";
			echo "<input type='submit' class='btn btn-success' value='  Save  ' name='edit_settings' />
					<input type='submit' class='btn' value='Cancel' name='cancel' />";
			echo "<div style='float:right'>";
			echo "<input type='submit' class='btn btn-info' value='Restore CSS' name='restore_css' />&nbsp;&nbsp;&nbsp;";
			echo "<input type='submit' class='btn btn-info' value='Restore JS' name='restore_js' />";
			echo "</div>";
			echo "</form>
				<br/>
				<br/>
				</div>
			";
			
		}
		
		else
		{	
			$successLink = Link::editSettings()."&s=1";
			$css = $_POST['css'];
			$js = $_POST['js'];
			
			$path = '/js/';
			$filename = 'custom.js';
			writeContent($path, $filename, $js);
			$ip = $_SERVER['REMOTE_ADDR'];
			$input = $js. ' $$ '; 
			Logger::logInput($modified_by, "edit_js", $type, $input, $ip);
			
			$path = '/css/';
			$filename = 'style.css';
			writeContent($path, $filename, $css);
			$ip = $_SERVER['REMOTE_ADDR'];
			$input = $css. ' $$ '; 
			Logger::logInput($modified_by, "edit_css", $type, $input, $ip);
			
			header("Location:".$successLink);
		}
		
	}
	
	else if($type == 'editor')
	{
		$editLink = Link::editEditor();
		if(isset($_POST['edit_editor']))
		{
			$complete_path = $_SERVER['DOCUMENT_ROOT'].'/'. $san -> cleanHTML($_POST['path']);
			$path = $_POST['path'];	
			$p = explode('/', $path);
			if(file_exists($complete_path) && !is_dir($complete_path) && !in_array('cms', $p) && !in_array('constants.php', $p)) {
				$content = $san -> parseTextareaToShow(file_get_contents($complete_path));
						
				echo "<br/><br/><div class='navHolder'>	<label> <h4>Editing for:<i> {$complete_path} </i> </h4></label>
					<form method='post' action={$editLink} >
						<textarea name='content' class='span10' rows='20' >{$content} </textarea><br/>
						<input name='path' value='{$path}' type='hidden' />
						<input type='submit' class='span2 btn btn-success' name='edit_editor_save' value='Save the file'>
						<input type='submit' class='span2 btn btn-danger' name='cancel' value='Cancel'>
						<br/><br/>
					</form>
					</div>";
			} else if(in_array('cms', $p) || in_array('constants.php', $p)) {
				echo "<br/><h1>403 Forbidden  From Editing CMS :X </h1><br/>";
				echo "<a href='?type=editor&action=generate'><button class='span2 btn' > Go Back</button></a>";
		 	}  else {
				echo "<h1>404 File Not Found :(</h1><br/>";
				echo "<a href='?type=editor&action=generate'><button class='span2 btn' > Go Back</button></a>";
			}
		}

		if(isset($_POST['edit_editor_save']))
		{
			$content = $san -> parseTextareaToSave($_POST['content']);
			$path = $san -> cleanHTML($_POST['path']);
			$complete_path = $_SERVER['DOCUMENT_ROOT'].$path;
			if(file_exists($complete_path)) {
				$content = file_get_contents($complete_path);
			}

			echo "<br/><br/><div class='navHolder'>";
			if(saveFile($path, $content))
				echo "<h5 style='color:green;'>Successfully saved the file \"$path\"</h5>";
			else 
				echo "<h5 style='color:red;'>There was an error in saving the file \"$path\"<h5>";
			
		
			$content = $_POST['content'];
				
			echo "	<label> <h4>Editing for:<i> {$complete_path} </i> </h4></label>
					<form method='post' action={$editLink} >
						<textarea name='content' class='span10' rows='20' >{$content} </textarea><br/>
						<input name='path' value='{$path}' type='hidden' />
						<input type='submit' class='span2 btn btn-success' name='edit_editor_save' value='Save the file'>
						<button class='btn btn-danger'>Cancel</button>
						
						<br/><br/>
					</form>
					</div>";

		}
	}
	else if($type == 'user_accounts')
	{
		$editLink = Link::editUser($value);
		
		$user = mysql_fetch_array(get_user($value));
		if(isset($_POST['edit_user'])) {
			$username = $san -> cleanString($_POST['username']);
			$pass = $san -> cleanString($_POST['pass']);
			$confirm_pass = $san -> cleanString($_POST['confirm_pass']);
			$remarks = $san -> cleanString($_POST['remarks']);
			$modified_by = $_SESSION['_iiita_cms_username_'];
			if($u = user_exists($username)) {
					$error = 'user already exists.';
			}
			if($pass != $confirm_pass || $username == '' ) {
					$error = 'Password is not correct or username is empty';
			} else if($pass == '') {
				$hash_pass = $user['pass'];
			} else if(strlen($pass) < 6 ){
				$error = 'Password is short';
			} else {
				$hash_pass = sha1($pass);
			}
							
			if(!isset($error)){
				$result = 0;
				$sql = 'INSERT INTO users (username, pass, added_on, remarks) VALUES (:username, :pass, NOW(), :remarks)';
				$params = array(':username' => $username, ':pass' => $hash_pass, ':remarks' => $remarks);
				$result = DatabaseHandler::Execute($sql, $params);
				
				if($result) {
					$uid = get_userid($username);
					echo count($_POST['cms_topics']);
					
					for($i = 0; $i<count($_POST['cms_topics']); $i++) {
						$rid = $_POST['cms_topics'][$i];
						$sql = 'INSERT INTO users_roles (uid, rid) VALUES (:uid, :rid)';
						$params = array(':uid' => $uid, ':rid' => $rid);
						$result = DatabaseHandler::Execute($sql, $params);
					}
					$ip = $_SERVER['REMOTE_ADDR'];
					$input = $username.' $$ '.$hash_pass.' $$ '.$remarks.' $$ '; 
					Logger::logInput($modified_by, "edit", $type, $input, $ip);
					header('Location:'.Link::viewUsers());
				}
				else
					header('Location:'.Link::editUser($value));
			}
		}
		else { 
			echo"<div class='span10 navHolder'>
			
				<form action = '{$editLink}' method='post'>
					<div class='span6'>
					<label value='Username'> Username</label> <input readonly='readonly' type='text' maxlength='60' name='username' value='{$user['username']}'/> <br/>
					<label value='Username'> Status </label> <input readonly='readonly' type='text' name='username'  "; if($user['isactive']) echo "style='color:green;' value='active'"; else echo "style='color:red;' value='inactive' "; echo "/> <br/>
					<label value='remarks'> Remarks </label> <textarea rows='5' class='span4' name='remarks'>{$user['remarks']} </textarea> <br/>
					</div>
					<div class='span3'>
						<h4><u>Allow Privilages for:</u></h4><br/>
					";
				$topics = get_all_cms_topics();	
				$i = 0;
				while($t = mysql_fetch_array($topics)) {
					$i++;
					if(chk_role($user['id'], $t['id'])) echo $i.".".strtoupper($t['topic'])."<br/>";
				}	
				echo "<br/>
				</div>
				<div class='span6'>
				<input type='submit' name='cancel' class='btn' value='Go Back' /><br/>
				</div>	
				</form><br/>
				<div class='span10'><br/><br/></div>
				</div><br/>
				";
		
		}
		 
		
	}
	}
?>
<script type='text/javascript' src='/js/jquery.lightbox_me.js'></script>

<script>
function preview(s_url, n_url) {
	var content = document.getElementById("subContent").value;
	var display = document.getElementById("hidden");
	var cssC = document.getElementById("cssContent").value;
	var jsC = document.getElementById("jsContent").value;
	var cssNjs = document.getElementById("cssNjs");
	cssNjs.innerHTML = "<style>"+cssC+"</style>"+"<script>"+jsC;
	display.innerHTML = content;
	
	var imgNodes = display.getElementsByTagName("img");

	for(var idx = 0; idx < imgNodes.length;idx++)
	{
		
		var n =  imgNodes[idx].src.split("/");
		var src = "/" + n_url + "/" + s_url + "/" + n[4] + "/" + n[5];
		imgNodes[idx].src = src;
		 
	}	
	
	var iframeNodes = display.getElementsByTagName("iframe");

	for(var idx = 0; idx < iframeNodes.length;idx++)
	{
		
		var n =  iframeNodes[idx].src.split("/");
		var src = "/" + n_url + "/" + s_url + "/" + n[4] + "/" + n[5];
		iframeNodes[idx].src = src;
		 
	}	
	return false;
}

function lightBoxMe(id) {

        id = "#" + id.toString();

  $(id).lightbox_me({

       centered: false,



  });

}
function hideMe(id) {
	

        id = id.toString();
        var  hide = document.getElementById(id);
        hide.style.display = "none";
        $(function(){

    $(".lb_overlay").hide();

  });

}

function saveMe(id, msg, s_url, n_url) {
	
	var display = document.getElementById("hidden");
	
	var imgNodes = display.getElementsByTagName("img");

	for(var idx = 0; idx < imgNodes.length;idx++)
	{
		var src = "";
		var n =  imgNodes[idx].src.split("/");
		src =  n[5] + "/" + n[6];
		imgNodes[idx].src = src;
		 
	}
		
	var iframeNodes = display.getElementsByTagName("iframe");

	for(var idx = 0; idx < iframeNodes.length;idx++)
	{
		
		var src = "";
		var n =  iframeNodes[idx].src.split("/");
		src =  n[5] + "/" + n[6];
		iframeNodes[idx].src = src;
		 
	}	
	document.getElementById("subContent").value = document.getElementById("hidden").innerHTML;
	
	var imgNodes = display.getElementsByTagName("img");

	for(var idx = 0; idx < imgNodes.length;idx++)
	{
		
		var n =  imgNodes[idx].src.split("/");
		var src = "/" + n_url + "/" + s_url + "/" + n[4] + "/" + n[5];
		imgNodes[idx].src = src;
		 
	}	
	
	var iframeNodes = display.getElementsByTagName("iframe");

	for(var idx = 0; idx < iframeNodes.length;idx++)
	{
		
		var n =  iframeNodes[idx].src.split("/");
		var src = "/" + n_url + "/" + s_url + "/" + n[4] + "/" + n[5];
		iframeNodes[idx].src = src;
	}		 
	$("html, body").animate({ scrollTop: 00 }, 600);	
	var box = document.getElementById(id);
	box.innerHTML = msg;

	id = "#" + id.toString();
	setTimeout(function(){$(id).fadeIn("slow")},500);
	
	setTimeout(function(){$(id).fadeOut("slow")},5000);
}

</script>
