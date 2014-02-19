<?php
	require_once('business/DirCreator.class.php');
	require_once('business/ContentSanitize.class.php');
		
	$san = new Sanitize();
	
	if($type == 'nav')
        {
                $addLink = Link::addNav();
                $failLink = $addLink."&s=0";
               
                if(isset($_POST['nav_adder']))
                {              
                        $title = $san -> cleanString($_POST['title']);
                        $tagline = $san -> cleanString($_POST['tagline']);
                        $overview = $san -> cleanString($_POST['overview']);
                        $inner_tagline = $san -> cleanString($_POST['inner_tagline']);
                        $links = $san -> cleanString($_POST['links']);
                        $added_by = $_SESSION['_iiita_cms_username_'];
                        $url = strtolower(str_replace(" ", "_", $title));
						
                        if(!$title)
                                header('Location:'.$failLink);
                        else
                        {
                                $result = 0;
                                $sql = 'INSERT INTO nav (title, url, tagline, overview, inner_tagline, links, added_by, added_on, modified_by, modified_on) VALUES (:title, :url, :tagline, :overview, :inner_tagline, :links,  :added_by, NOW(), :modified_by, NOW())';
                                $params = array(':title' => $title, ':url' => $url, ':tagline' => $tagline, ':overview' => $overview, ':inner_tagline' => $inner_tagline, ':links' => $links,':added_by' => $added_by, ':modified_by' => $added_by);
								$result = DatabaseHandler::Execute($sql, $params);
                                echo "<script>alert('Nav in Database entered !!');</script>";
                                if($result) {
										$ip = $_SERVER['REMOTE_ADDR'];
										$input = $title.' $$ '.$url.' $$ '.$tagline.' $$ '.$overview.' $$ '.$inner_tagline.' $$ '.$links.' $$ '; 
                                        Logger::logInput($added_by, "add", $type, $input, $ip);
                                        $nav = get_navbytitle($title);
                                        $curr_nav = mysql_fetch_array($nav);
                                        $dirCreate = new DirCreator('nav', $curr_nav['id']);
                                        $path = "../temp/".$url."/";
                                        $dirCreate -> generateStructure($path);
                                        $successLink = Link::uploadNav($curr_nav['id'])."&s=1";
                                        header('Location:'.$successLink);
                                }
                                else
                                        header('Location:'.$failLink);
                        }
                }
                else
                {
                        if(isset($_GET['s']))
                        {
                                if($_GET['s']) {
                                        echo "<p style='color:green;'>Successfully Added</p>";
                                }
                                else
                                        echo "<p style='color:red;'>Failure in Addition. Title cannot be left blank!</p>";
                        }
                       
                        echo "<div class='span10 navHolder'>";
                        echo" <div class='span5'>
                                <form  action = '{$addLink}' method='post'>
                                <label value='title'> Title </label> <input class='span3' type='text' name='title' maxlength='25' value=''/> <br/>
                                <label value='tagline'> Tagline </label> <input class='span4' type='text' maxlength='50' name='tagline'/> <br/>
                                <label value='inner_tagline'> Inner Tagline </label> <input class='span4' type='text' maxlength='50' name='inner_tagline'/> <br/>
				<label value='overview'> Overview </label> <textarea class='span4' rows='6'  maxlength='300' name='overview'> </textarea> <br/>
                                <label value='links'> Links </label> <textarea class='span4' rows='4' name='links'> </textarea> <br/>
                                <input type='submit' class='btn-success' name='nav_adder' value='Add a Nav' />
                                <input type='submit' class='btn-danger' name='cancel' value='Cancel' />
                               
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
                                ";
                        echo "</div>";
                       
                }
        }
	
	else if($type == 'subnav')
	{
		$addLink = Link::addSubnav($_GET['value']);
		$failLink = $addLink."&s=0";
		
		if(isset($_POST['nav_adder'])) {
			$title = $san -> cleanString($_POST['title']);
			$tagline = $san -> cleanString($_POST['tagline']);
			$overview = $san -> cleanString($_POST['overview']);
			$css = $san -> cleanHtml($_POST['css']);
			$js = $san -> cleanJs($_POST['js']);
			$content = $san -> cleanHtml($_POST['content']);
			$nav_id = $san -> cleanString($_GET['value']);
			$added_by = $_SESSION['_iiita_cms_username_'];
			$url = strtolower(str_replace(" ", "_", $title));
			
		//	if(!check_subnav($url, $nav_id))
		//		header('Location: '.$failLink);

			if (!$title || !check_subnav($url, $nav_id)) {
				header('Location: '.$failLink);
			} else {				
				$result = 0;
				$sql = 'INSERT INTO subnav (nav_id, title, url, tagline, overview, css, js, content, added_by, added_on, modified_by, modified_on) VALUES (:nav_id, :title, :url, :tagline, :overview, :css, :js, :content, :added_by, NOW(), :modified_by, NOW())';
				$params = array(':title' => $title, ':url' => $url, ':tagline' => $tagline, ':overview' => $overview, ':content' => $content, ':nav_id' => $nav_id, ':css' => $css, ':js' => $js, ':added_by' => $added_by, ':modified_by' => $added_by);
				$result = DatabaseHandler::Execute($sql, $params);			
				if($result) {
					$ip = $_SERVER['REMOTE_ADDR'];
					$input = $title.' $$ '.$url.' $$ '.$tagline.' $$ '.$overview.' $$ '.$content.' $$ '.$nav_id.' $$ '.$css.' $$ '.$js.' $$ '; 
                			Logger::logInput($added_by, "add", $type, $input, $ip);
					$subnav = get_subnavbytitle($title, $nav_id);
					$curr_subnav = mysql_fetch_array($subnav);
					$dirCreate = new DirCreator('subnav', $curr_subnav['id']);
					$path = "../temp/".$url."/";
					$dirCreate -> generateStructure($path);
					$successLink = Link::uploadSubNav($curr_subnav['id'])."&s=1";
					header('Location:'.$successLink);
				}
				else
					header('Location:'.$failLink);
			}
		}
		else {
			if(isset($_GET['s'])) 
				if($_GET['s'])
					echo "<p style='color:green;'>Successfully Added</p>";
				else
					echo "<p style='color:red;'>Failure in Addition. Title cannot be left blank or Cannot have two subnav with same title!</p>";
				echo"<div class='span10 navHolder'>
						<form action = '{$addLink}' method='post'>
							<label value='title'> Title </label> <input class='span4' type='text' maxlength='21' name='title'/> <br/>
							<label value='tagline'> Tagline </label> <input class='span4' type='text' maxlength='50' name='tagline'/> <br/>
							<label value='overview'> Overview </label> <textarea class='span4' type='text' rows='6' maxlength='300' name='overview'></textarea> <br/>
							<label value='css'> CSS </label> <textarea class='span9' rows='5' cols='10' name='css'> </textarea> <br/>
							<label value='js'> JS </label> <textarea class='span9' rows='5' cols='10' name='js'> </textarea><br/>
							<label value='content'> Content </label> <textarea class='span9' rows='15'  name='content'> </textarea><br/>
							<input type='submit' class='btn-success' name='nav_adder' value='Add a Sub Nav' />
							<input type='submit' class='btn-danger' name='cancel' value='Cancel' />
						</form>
					</div>
				";
			}
		}
	
	
	else if($type == 'rpanel')
	{
		$addLink = Link::addRpanelTopic();
		$successLink = $addLink."&s=1";
		$failLink = $addLink."&s=0";
		
		if(isset($_POST['rpanel_adder'])) 
		{
			
			$title = $san -> cleanString($_POST['title']);
			$priority = $san -> cleanString($_POST['priority']);
			$added_by = $_SESSION['_iiita_cms_username_'];
			
			if (!$title) {
				header('Location:'.$failLink);
			} else {
				$result = 0;
				$sql = 'INSERT INTO right_panel_topics (title, priority, added_by, added_on, modified_by, modified_on) VALUES (:title, :priority, :added_by, NOW(), :modified_by, NOW())';
				$params = array(':title' => $title, ':priority' => $priority, ':added_by' => $added_by, ':modified_by' => $added_by);
				$result = DatabaseHandler::Execute($sql, $params);
				if($result) {
					$ip = $_SERVER['REMOTE_ADDR'];
					$input = $title.' $$ '.$priority.' $$ '; 
                    Logger::logInput($added_by, "add", $type, $input, $ip);
					header('Location:'.$successLink);
				}
				else
					header('Location:'.$failLink);
			}
		}
		else 
		{
			if(isset($_GET['s']))
			{
				if($_GET['s'])
					echo "<p style='color:green;'>Successfully Added</p>";
				else
					echo "<p style='color:red;'>Failure in Addition. Title cannot be left blank!</p>";
			}
			
			echo"
				<div class='span4 navHolder'>
				<form action = '{$addLink}' method='post'>
					<label value='title'> Title </label> <input type='text' maxlength='20' name='title'/> <br/>
					<label value='priority'> Priority </label> <input type='text' name='priority'/> </br>
					<input type='submit' class='btn btn-success' name='rpanel_adder' value='Add a Topic' />
					<input type='submit' class='btn' name='cancel' value='Cancel' />
				</form>
				<br/>
				</div>
				";
		}
	}

	
	else if($type == 'rpanelcontent')
	{
		$addLink = Link::addRpanelContent($_GET['value']);
		$successLink = $addLink."&s=1";
		$failLink = $addLink."&s=0";
		
		if(isset($_POST['rpanelcontent_adder'])) 
		{
			
			$topic_id= $san -> cleanString($_GET['value']);
			$title = $san -> cleanString($_POST['title']);
			$priority = $san -> cleanString($_POST['priority']);
			$link = $san -> cleanString($_POST['link']);
			$added_by = $_SESSION['_iiita_cms_username_'];
			
			if (!$title || !$link) {
				header('Location:'.$failLink);
			} else {
				$result = 0;
				$sql = 'INSERT INTO right_panel_content (topic_id, title, link, priority, added_by, added_on, modified_by, modified_on) VALUES (:topic_id, :title, :link, :priority, :added_by, NOW(), :modified_by, NOW())';
				$params = array(':title' => $title, ':topic_id' => $topic_id, ':link' => $link, ':priority' => $priority, ':added_by' => $added_by, ':modified_by' => $modified_by);
				$result = DatabaseHandler::Execute($sql, $params);
			
				if($result) {
					$ip = $_SERVER['REMOTE_ADDR'];
					$input = $title.' $$ '.$topic_id.' $$ '.$link.' $$ '; 
                    Logger::logInput($added_by, "add", $type, $input, $ip);
					header('Location:'.$successLink);
				}
				else
					header('Location:'.$failLink);
			}
		}
		else {
			if(isset($_GET['s'])) 
				if($_GET['s'])
					echo "<p style='color:green;'>Successfully Added</p>";
				else
					echo "<p style='color:red;'>Failure in Addition. Title and Link fields cannot be left blank!</p>";
			echo"<br/><br/><div class='span10 navHolder' >
					<div class='span4'>
					<br/>
					<form action = '{$addLink}' method='post'>
					<label value='title'> Title </label> <input type='text'  maxlength='30' name='title'/> <br/>
					<label value='link'> Link </label> <input type='text' class='span4' name='link'/> <br/>
					<label value='priority'> Priority </label> <input type='text' name='priority'/> </br>
					<input type='submit' class='btn btn-success' name='rpanelcontent_adder' value='Add Content' />
					<input type='submit' class='btn' name='cancel' value='Cancel' />
					</form>
					<br/>
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
	}
	
	else if($type == 'events')
	{
		$addLink = Link::addEvent();
		$successLink = $addLink."&s=1";
		$failLink = $addLink."&s=0";
		
		if(isset($_POST['event_adder'])) {
			
			$front_title = $san -> cleanString($_POST['front_title']);
			$inner_title = $san -> cleanString($_POST['inner_title']);
			$front_content = $san -> cleanString($_POST['front_content']);
			$inner_content = $san -> cleanHTML($_POST['inner_content']);
			$image_caption = $san -> cleanString($_POST['image_caption']);
			$event_date = $san -> cleanString($_POST['event_date']);
			$link = $san -> cleanString($_POST['link']);
			$added_by = $_SESSION['_iiita_cms_username_'];
			if (!$front_title || !$inner_title || !$front_content || !$inner_content) {
				header('Location: '.$failLink);
			} else {
				$result = 0;
				$sql = 'INSERT INTO events (front_title, inner_title, front_content, inner_content, event_date, image_caption, added_by, added_on, link) VALUES (:front_title, :inner_title, :front_content, :inner_content, :event_date, :image_caption, :added_by, NOW(), :link)';
				$params = array(':front_title' => $front_title, ':inner_title' => $inner_title, ':front_content' => $front_content, 'inner_content' => $inner_content, ':event_date' => $event_date, ':image_caption' => $image_caption, ':added_by' => $added_by, ':link' => $link);
				$result = DatabaseHandler::Execute($sql, $params);
			
				if($result) {
					$ip = $_SERVER['REMOTE_ADDR'];
					$input = $front_title.' $$ '.$inner_title.' $$ '.$front_content.' $$ '.$inner_content.' $$ '.$event_date.' $$ '.$image_caption.' $$ '; 
                    Logger::logInput($added_by, "add", $type, $input, $ip);
					header('Location:'.$successLink);
				}
				else
					header('Location:'.$failLink);
			}
		}
		else 
		{
			if(isset($_GET['s']))
			{
				if($_GET['s'])
					echo "<p style='color:green;'>Successfully Added</p>";
				else
					echo "<p style='color:red;'>Failure in Addition. Title and Content fields cannot be left blank.</p>";
			}
			
			echo"<div class='span10 navHolder' ><br/>
				<form action = '{$addLink}' method='post'>
					<label value='front_title'> Front Title </label> <input class='span4' type='text'  name='front_title'/> <br/>
					<label value='inner_title'> Inner Title </label> <input class='span4 type='text' name='inner_title'/> <br/>
					<label value='event_date'> Event Date </label> <input class='span3 jdpicker type='text' name='event_date' /> <br/>
					<label value='image_caption'> Image caption </label> <textarea class='span4' rows=2' maxlength='50' name='image_caption'></textarea> <br/>
					<label value='front_content'> Front Content </label> <textarea class='span4' rows='2' cols='10' name='front_content'> </textarea><br/>
					<label value='inner_content'> Inner Content </label> <textarea class='span8' rows='8' cols='10' name='inner_content'> </textarea><br/>
					<label value='link'> Link </label> <input class='span4 type='text' name='link'/> <br/>
					<input type='submit' class='btn btn-success' name='event_adder' value='Add an Event' />
					<input type='submit' class='btn ' name='cancel' value='Cancel' />
				</form>
				<br/>
				</div>
				";
		}
	}
		
	else if($type == 'news')
	{
		$addLink = Link::addNews();
		$successLink = $addLink."&s=1";
		$failLink = $addLink."&s=0";
		
		if(isset($_POST['news_adder'])) 
		{
			
			$front_title = $san -> cleanString($_POST['front_title']);
			$inner_title = $san -> cleanString($_POST['inner_title']);
			$front_content = $san -> cleanString($_POST['front_content']);
			$inner_content = $san -> cleanString($_POST['inner_content']);
			$link = $san -> cleanString($_POST['link']);
			$added_by = $_SESSION['_iiita_cms_username_'];
			
			if (!$front_title || !$inner_title || !$front_content || !$inner_content) {
				header('Location: '.$failLink);
			} else {
				$result = 0;
				$sql = 'INSERT INTO news (front_title, inner_title, front_content, inner_content, link, added_by, added_on) VALUES (:front_title, :inner_title, :front_content, :inner_content, :link, :added_by, NOW())';
				$params = array(':front_title' => $front_title, ':inner_title' => $inner_title, ':front_content' => $front_content, 'inner_content' => $inner_content, ':link' => $link, ':added_by' => $added_by);
				$result = DatabaseHandler::Execute($sql, $params);
			
				if($result) {
					$ip = $_SERVER['REMOTE_ADDR'];
					$input = $front_title.' $$ '.$inner_title.' $$ '.$front_content.' $$ '.$inner_content.' $$ '.$link.' $$ '; 
                    Logger::logInput($added_by, "add", $type, $input, $ip);
					header('Location:'.$successLink);
				}
				else
					header('Location:'.$failLink);
			}
		}
		else {
			if(isset($_GET['s'])) 
				if($_GET['s'])
					echo "<p style='color:green;'>Successfully Added</p>";
				else
					echo "<p style='color:red;'>Failure in Addition. Title and Content fields cannot be left blank.</p>";
			echo"<div class='span10 navHolder'>
				<form action = '{$addLink}' method='post'>
				<label value='front_title'> Front Title </label> <input class='span4' type='text' maxlength='30' name='front_title'/> <br/>
				<label value='inner_title'> Inner Title </label> <input class='span4' type='text' maxlength='50' name='inner_title'/> <br/>
				<label value='link'> Link </label> <input class='span4' type='text' name='link'/> <br/>
				<label value='front_content'> Front Content </label> <textarea class='span9' rows='3' cols='10' maxlength='300' name='front_content'> </textarea><br/>
				<label value='inner_content'> Inner Content </label> <textarea class='span9' rows='7' cols='10' name='inner_content'> </textarea><br/>
				<input type='submit' class='btn btn-success' name='news_adder' value='Add News' />
				<input type='submit' class='btn' name='cancel' value='Cancel' />
				</form>
				<br/>
				</div>
				";
		}
	}
	
	else if($type == 'announcements')
	{
		$addLink = Link::addAnnouncement();
		$successLink = $addLink."&s=1";
		$failLink = $addLink."&s=0";
		$viewAnnouncement = Link::viewAnnouncement();
		
		if(isset($_POST['announcement_adder'])) 
		{
			
			$title = $san -> cleanString($_POST['title']);
			$content = $san -> cleanString($_POST['content']);
			$link = $san -> cleanString($_POST['link']);
			$expiry_date = $san -> cleanString($_POST['expiry_date']);
			$added_by = $_SESSION['_iiita_cms_username_'];
			if(isset($_POST['emphasize']))
				$emphasize = 1;
			else
				$emphasize = 0;
		
			if (!$title || !$content) {
				header('Location: '.$failLink);
			} else {
				$result = 0;
				$sql = 'INSERT INTO announcements (title, link, content, expiry_date, added_by, added_on, emphasize) VALUES (:title, :link, :content, :expiry_date, :added_by, NOW(), :emphasize)';
				$params = array(':title' => $title, ':content' => $content, ':link' => $link, ':expiry_date' => $expiry_date, ':added_by' => $added_by, ':emphasize' => $emphasize);
				$result = DatabaseHandler::Execute($sql, $params);
			
				if($result) {
					$ip = $_SERVER['REMOTE_ADDR'];
					$input = $title.' $$ '.$content.' $$ '.$link.' $$ '.$expiry_date.' $$ '; 
                    Logger::logInput($added_by, "add", $type, $input, $ip);
					header('Location:'.$viewAnnouncement);
				}
				else
					header('Location:'.$failLink);
			}
		}
		else {
			if(isset($_GET['s'])) 
				if($_GET['s']) {
					echo "<p style='color:green;'>Successfully Added</p>";
				}
				else
					echo "<p style='color:red;'>Failure in Addition. Title and Content field cannot be left blank.</p>";
			echo"<div class='span10 navHolder'>
				<div class='span5'>
				<form action = '{$addLink}' method='post'>
				<label value='title'> Title </label> <input class='span4' type='text' maxlength='100' name='title'/> <br/>
				<label value='content'> Content </label> <textarea  class='span4' rows='4' name='content'></textarea> <br/>
				<label value='expiry_date'> Expiry Date </label> <input class='span4 jdpicker' type='text' name='expiry_date'/> <br/>
				<label value='link'> Link </label> <input class='span4' type='text' name='link'/> <br/>
				<input type='checkbox' name='emphasize'/> Emphasize <br/>
				<br/>
				<input type='submit' class='btn btn-success' name='announcement_adder' value='Add Announcement' />
				<input type='submit' class='btn' name='cancel' value='Cancel' />
				</form>
				<br/>
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
	}
	
	else if($type == 'tenders')
	{
		$addLink = Link::addTender();
		$successLink = $addLink."&s=1";
		$failLink = $addLink."&s=0";
		
		if(isset($_POST['tender_adder'])) 
		{
			
			$title = $san -> cleanString($_POST['title']);
			$content = $san -> cleanString($_POST['content']);
			$link = $san -> cleanString($_POST['link']);
			$expiry_date = $san -> cleanString($_POST['expiry_date']);
			$added_by = $_SESSION['_iiita_cms_username_'];
			
			if (!$title || !$content) {
				header('Location: '.$failLink);
			} else {
				$result = 0;
				$sql = 'INSERT INTO tenders (title, link, content, expiry_date, added_by, added_on) VALUES (:title, :link, :content, :expiry_date, :added_by, NOW())';
				$params = array(':title' => $title, ':content' => $content, ':link' => $link, ':expiry_date' => $expiry_date, ':added_by' => $added_by);
				$result = DatabaseHandler::Execute($sql, $params);
			
				if($result) {
					$ip = $_SERVER['REMOTE_ADDR'];
					$input = $title.' $$ '.$content.' $$ '.$link.' $$ '.$expiry_date.' $$ '; 
                    Logger::logInput($added_by, "add", $type, $input, $ip);
					header('Location:'.$successLink);
				}
				else
					header('Location:'.$failLink);
			}
		}
		else {
			if(isset($_GET['s'])) 
				if($_GET['s'])
					echo "<p style='color:green;'>Successfully Added</p>";
				else
					echo "<p style='color:red;'>Failure in Addition. Title and Content field cannot be left blank.</p>";
			echo"<div class='span10 navHolder'>
				<div class='span5'>
				<form action = '{$addLink}' method='post'>
				<label value='title'> Title </label> <input class='span4' type='text' maxlength='100' name='title'/> <br/>
				<label value='content'> Content </label> <textarea class='span4' rows='4' name='content'/> </textarea><br/>
				<label value='expiry_date'> Expiry Date </label> <input class='span4 jdpicker' type='text' name='expiry_date'/> <br/>
				<label value='link'> Link </label> <input class='span4' type='text' name='link'/> <br/>				
				<input type='submit' class='btn btn-success' name='tender_adder' value='Add Tender' />
				<input type='submit' class='btn' name='cancel' value='Cancel' />
				</form>
				<br/>
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
	}
	
	else if($type == 'carousel')
	{
		$addLink = Link::addCarousel();
		$successLink = $addLink."&s=1";
		$failLink = $addLink."&s=0";
		
		if(isset($_POST['carousel_adder'])) 
		{
			$overview = $san -> cleanString($_POST['overview']);
			$link = $san -> cleanString($_POST['link']);
			$weight = $san -> cleanString($_POST['weight']);
			$added_by = $_SESSION['_iiita_cms_username_'];
			
			if (!$overview || !$link) {
				header('Location: '.$failLink);
			} else {
				$result = 0;
				$sql = 'INSERT INTO carousel (overview, link, weight,  added_by, added_on) VALUES (:overview, :link, :weight, :added_by, NOW())';
				$params = array(':overview' => $overview, ':link' => $link, ':weight' => $weight, ':added_by' => $added_by);
				$result = DatabaseHandler::Execute($sql, $params);
			
				if($result) {
					$ip = $_SERVER['REMOTE_ADDR'];
					$input = $overview.' $$ '.$link.' $$ '; 
                    Logger::logInput($added_by, "add", $type, $input, $ip);
					header('Location:'.$successLink);
				}
				else
					header('Location:'.$failLink);
			}
		}
		else {
			if(isset($_GET['s'])) 
				if($_GET['s'])
					echo "<p style='color:green;'>Successfully Added</p>";
				else
					echo "<p style='color:red;'>Failure in Addition. Overview and Link field cannot be left blank.</p>";
			echo"<div class='span10 navHolder'>
					<div class='span5'>
					<form action = '{$addLink}' method='post'>
					<label value='Overview'> Overview </label> <textarea class='span4' rows='3' maxlength='250' name='overview'></textarea> <br/>
					<label value='link'> Link </label> <input class='span4' type='text' name='link'/> <br/>
					<label value='weight'> Weight </label> <input class='span4' type='text' name='weight'/> <br/>
					<input type='submit' class='btn btn-success' name='carousel_adder' value='Add Carousel' />
					<input type='submit' class='btn' name='cancel' value='Cancel' />
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
	}
	else if ($type == 'user_accounts')
	{
		$addLink = Link::addUser();
		$successLink  = Link::viewUsers();
		$failLink  = $addLink."&s=0";
		if(isset($_POST['add_user'])) 
		{
			$username = $san -> cleanString($_POST['username']);
			$pass = $san -> cleanString($_POST['pass']);
			$confirm_pass = $san -> cleanString($_POST['confirm_pass']);
			$remarks = $san -> cleanString($_POST['remarks']);
			$added_by = $_SESSION['_iiita_cms_username_'];
			
			if($u = user_exists($username)) {
					$error = 'User Already Exists. Try a different Username.';
			} else if($username == '') {
				$error = 'Username can\'t be empty.';
			} else if($pass != $confirm_pass  || strlen($pass) < 6) {
					$error = 'Password do not match or may be length is less than 6 characters';
			}
			else {
				$hash_pass = sha1($pass);
				$result = 0;
				$sql = 'INSERT INTO users (username, pass, added_by, added_on, modified_by, modified_on, remarks) VALUES (:username, :pass, :added_by, NOW(), :modified_by, NOW(), :remarks)';
				$params = array(':username' => $username, ':pass' => $hash_pass, ':added_by' => $added_by, ':modified_by' => $added_by, ':remarks' => $remarks);
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
                    Logger::logInput($added_by, "add", $type, $input, $ip);
					header('Location:'.$successLink);
				}
				else
					header('Location:'.$failLink);
			}
		}
		
		if(isset($_GET['s'])) {
				if($_GET['s'])
					echo "<p style='color:green;'>Successfully Added</p>";
				else
					echo "<p style='color:red;'>Failure in Addition</p>";
		}
					
				if(isset($error)) {
					echo "<p style='color:red;'> ".$error."</p>";
				}
				echo"<div class='span10 navHolder'>";
			
				echo "<form action = '{$addLink}' method='post'>
					<div class='span6'>
					<label value='Username'> Username <font color='red'>*</font></label> <input type='text' maxlength='60' name='username'/> <br/>
					<label value='pass'> Password <font color='red'>*</font>(atleast 6 characters long and alphanumeric) </label> <input type='text' maxlength='128' name='pass'/> <br/>
					<label value='confirm_pass'> Confirm Password <font color='red'>*</font></label> <input type='text' name='confirm_pass'/> <br/>
					<label value='remarks'> Remarks </label> <textarea rows='5' class='span4' name='remarks'> </textarea> <br/>
					</div>
					<div class='span3'>
						<p>Allow Privilages for:</p>
					";
				$topics = get_all_cms_topics();
				while($t = mysql_fetch_array($topics)) {
					echo "<input type='checkbox' name='cms_topics[]' value='{$t['id']}'/> ".strtoupper($t['topic'])."<br/>";
				}	
				echo "<br/>
				</div>
				<div class='span6'>
				<input type='submit' name='add_user' class='btn btn-success' value='Add User' />
				<input type='submit' name='cancel' class='btn btn-danger' value='Cancel' /><br/>
				</div>	
				</form><br/>
				</div>
				";
		
	}
	else if($type == 'editor')
	{
		$x = 0;
		if(isset($_POST['add_editor']))
		{
			$path = $san->cleanHTML($_POST['path']);
			$x = add_file($_SERVER['DOCUMENT_ROOT'].'/'.$path);
			if(!$x)
				echo "File creation failed";
			else
				echo "File successfull";
		}
		else
		{
		}
	}
	else if($type == 'subscriptions')
	{
		$addLink = Link::addSubTopic();
		$successLink = $addLink."&s=1";
		$failLink = $addLink."&s=0";
		
		if(isset($_POST['sub_topic_add'])) 
		{
			
			$title = $san -> cleanString($_POST['title']);
			$added_by = $_SESSION['_iiita_cms_username_'];
			
			if (!$title) {
				header('Location:'.$failLink);
			} else {
				$result = 0;
				$sql = 'INSERT INTO sub_topics (title, added_by, added_on, modified_by, modified_on) VALUES (:title, :added_by, NOW(), :modified_by, NOW())';
				$params = array(':title' => $title, ':added_by' => $added_by, ':modified_by' => $added_by);
				$result = DatabaseHandler::Execute($sql, $params);
				if($result) {
					$ip = $_SERVER['REMOTE_ADDR'];
					$input = $title.' $$ '; 
                    Logger::logInput($added_by, "add", $type, $input, $ip);
					header('Location:'.$successLink);
				}
				else
					header('Location:'.$failLink);
			}
		}
		else 
		{
			if(isset($_GET['s']))
			{
				if($_GET['s'])
					echo "<p style='color:green;'>Successfully Added</p>";
				else
					echo "<p style='color:red;'>Failure in Addition. Title field cannot be left blank.</p>";
			}
			
			echo"
				<div class='span4 navHolder'>
				<form action = '{$addLink}' method='post'>
					<label value='title'> Title </label> <input type='text' maxlength='50' name='title'/> <br/>
					<input type='submit' class='btn btn-success' name='sub_topic_add' value='Add a Topic' />
					<input type='submit' class='btn' name='cancel' value='Cancel' />
				</form>
				<br/>
				</div>
				";
		}
	}

	
	else if($type == 'sub_content')
	{
		$addLink = Link::addSubContent($_GET['value']);
		$successLink = $addLink."&s=1";
		$failLink = $addLink."&s=0";
		
		if(isset($_POST['sub_content_add'])) 
		{
			
			$id= $san -> cleanString($_GET['value']);
			$data = $san -> cleanString($_POST['data']);
			$added_by = $_SESSION['_iiita_cms_username_'];
			
			if (!$data) {
				header('Location:'.$failLink);
			} else {
				$result = 0;
				$sql = 'INSERT INTO sub_content (data, topic_id, added_by, added_on, modified_by, modified_on) VALUES (:data, :id, :added_by, NOW(), :modified_by, NOW())';
				$params = array(':data' => $data, ':id' => $id, ':added_by' => $added_by, ':modified_by' => $added_by);
				$result = DatabaseHandler::Execute($sql, $params);
			
				if($result) {
					$ip = $_SERVER['REMOTE_ADDR'];
					$input = $topic_id.' $$ '.$data.' $$ '; 
                    Logger::logInput($added_by, "add", $type, $input, $ip);
					header('Location:'.$successLink);
				}
				else
					header('Location:'.$failLink);
			}
		}
		else {
			if(isset($_GET['s'])) 
				if($_GET['s'])
					echo "<p style='color:green;'>Successfully Added</p>";
				else
					echo "<p style='color:red;'>Failure in Addition</p>";
			echo"<div class='span5 navHolder' >
					<br/>
					<form action = '{$addLink}' method='post'>
					<label value='link'> Content </label> <textarea rows='10' cols='55' name='data'> </textarea><br/>
					
					<input type='submit' class='btn btn-success' name='sub_content_add' value='Add Content' />
					<input type='submit' class='btn' name='cancel' value='Cancel' />
					</form>
					<br/>
				</div>
				";
		}
	}
	echo"<script src='pikaday.js'></script>
	<script>
		var picker = new Pikaday({ field: document.getElementById('datepicker') });
	</script>";
?>
