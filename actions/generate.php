<?php
	
	if($type == 'nav')
	{
		$nav = get_allnav();
		$addLink = Link::addNav();
		
		echo "<a href='{$addLink}'><button class='btn btn-success' value='abc'> Add a Nav </button></a>";
		echo "<br/><p> <i>Click to edit </i></p>";

		echo "<div class='span9'>";
		while($n = mysql_fetch_array($nav))
		{
			$subnav = get_allsubnav($n['id']);
			$addLink = Link::addSubnav($n['id']);
			$editLink = Link::editNav($n['id']);
			$deletelink = Link::delNav($n['id']);
			$activatelink = Link::actNav($n['id']);
			$uploadLink = Link::uploadNav($n['id']);
			
			echo"	
					<table  class='span7 navHolder table table-bordered'>
					<tr>
						<td><a href='{$editLink}'>".ucFirst($n['title'])."</a></td>
						<td "; if($n['isactive']) echo "class='active'>Active"; else echo "class='inactive'>Inactive"; echo "</td>
						<td><a href='{$addLink}'><button class='btn'>Add Subcategory</button></a></td>
						<td><a href='{$uploadLink}'><button class='btn btn-primary'>Uploads</button></a></td>
						<td><a href='{$deletelink}' ><button class='btn btn-danger'>Delete</button></a>	</td>";
						if(!$n['isactive']) echo "<td><a href='{$activatelink}'><button class='btn btn-success'>Activate</button></a></td>";
					echo "</tr>
				
				
					";
				
			while($s = mysql_fetch_array($subnav))
			{
				$editLink = Link::editSubnav($s['id']);
				$deletelink = Link::delSubnav($s['id']);
				$uploadLink = Link::uploadSubNav($s['id']);
				$activatelink = Link::actSubNav($s['id']);
				echo "<div class='subnavHolder'>
			
			<tr style='font-size:12px;'>
				<td>&nbsp;&nbsp;--><a href='{$editLink}'>".ucFirst($s['title'])."</a></td>
				<td ";if($s['isactive']) echo "class='active'>Active"; else echo "class='inactive'>Inactive"; echo "</td>
				<td><a href='{$uploadLink}'><button class='btn btn-primary'>Uploads</button></a></td>
				<td><a href='{$deletelink}' ><button class='btn btn-danger'>Delete</button></a> </td>";
				if(!$s['isactive']) echo "<td><a href='{$activatelink}'><button class='btn btn-success'>Activate</button></a></td>";
			echo "</tr>
		
		</div>";
			}
		
		}
		echo "</table>";
		echo "</div><br/>";
	}
	
	else if($type == 'rpanel')
	{
		$topics = get_rpanel_topics();
		
		$addLink = Link::addRpanelTopic();
		$uploadLink = Link::uploadRpanel();
		
		echo "<a href='{$addLink}'><button class='btn btn-success' value='abc'> Add a Topic </button></a>";
		echo "<br/><br/><p> <i>Click to edit </i></p>";
		echo "<div class='span9 '>";
		echo "<br/><a href='{$uploadLink}'><button class='btn btn-primary' value='abc'> Upload Files</button></a></br/>";
		echo "<table class='span7 navHolder table table-bordered'>";
		while($n = mysql_fetch_array($topics))
		{
			$content= get_rpanel_content($n['id']);
			$addLink = Link::addRpanelContent($n['id']);
			$editLink = Link::editRpanelTopic($n['id']);
			$deletelink = Link::delRpanelTopic($n['id']);
			$activatelink = Link::actRpanelTopic($n['id']);
			$deactivatelink = Link::deactRpanelTopic($n['id']);
			$uploadlink = Link::uploadRpanel($n['id']);
			
			echo "<tr>";
			echo "<td><a href='{$editLink}'>".ucFirst($n['title'])."</a></td>";
			echo "<td><a href='{$deletelink}'><button class='btn btn-danger' value='abc' > Delete </button></a></td>";
			echo "<td><a href='{$addLink}'><button class='btn' value='abc'> Add a subcategory </button> </a></td>";
			if($n['isactive'] != 1)
					echo "<td><a href='{$activatelink}'><button class='btn btn-success' value='abc'> Activate </button></a></br></td>";
				else
					echo "<td><a href='{$deactivatelink}'><button class='btn btn-warning' value='abc'> DeActivate </button></a></br></td>";
			echo "<td><a href='{$uploadlink}'><button class='btn btn-primary' value='abc'> Upload Icon </button> </a></td>";
			 echo "</tr>";
			 echo "<tr>";
			while($c = mysql_fetch_array($content))
			{
				
				$editLink = Link::editRpanelContent($c['id']);
				$deletelink = Link::delRpanelContent($c['id']);
				$activatelink = Link::actRpanelContent($c['id']);
				$deactivatelink = Link::deactRpanelContent($c['id']);
				
				echo "<tr>";
				echo "<td><a href='{$editLink}'>&nbsp;&nbsp;&nbsp;->".ucFirst($c['title'])."</a></td> ";
				echo "<td><a href='{$deletelink}'><button class='btn btn-danger' value='abc'> Delete </button></a></br></td>";
				if($c['isactive'] != 1)
					echo "<td><a href='{$activatelink}'><button class='btn btn-success' value='abc'> Activate </button></a></br></td>";
				else
					echo "<td><a href='{$deactivatelink}'><button class='btn btn-warning' value='abc'> DeActivate </button></a></br></td>";
				echo "</tr>";
			}
			echo "</tr>";
		}
		echo "</table>";
		echo "</div>";
	}
	
	else if($type == 'events')
	{
		$count = get_page_count("events");
		if($count > 1)
		{
			echo " <b >Pages: &nbsp &nbsp </b> ";
		
			for( $i = 1; $i <= $count; $i++) {
				echo "<a href='?type=events&action=generate&page={$i}'>"; if($i==$page) echo "<b>"; echo $i; if($i==$page) echo " </b>"; echo "</a> &nbsp;&nbsp;&nbsp;";
			}
		}
		
		$addLink = Link::addEvent();
		
		echo "<a href='{$addLink}'> <button class='btn btn-success' value='abc'> Add a Event </button></a>";
		echo "<br/><br/><p> <i>Click to edit </i></p>";
		
		$events = get_events($page);
		echo "<div class='span9'>";
		echo "<table class='span7 navHolder table table-striped '>";
		while($n = mysql_fetch_array($events))
		{
			$editLink = Link::editEvent($n['id']);
			$deletelink = Link::delEvent($n['id']);
			$activatelink = Link::actEvent($n['id']);
			$deactivatelink = Link::deactEvent($n['id']);
			$uploadLink = Link::uploadEvent($n['id']);
			
			echo "<tr>";
			echo " <td><a href='{$editLink}'>".ucFirst($n['front_title'])."</a></td>";
			echo "<td><a href='{$deletelink}'><button class='btn btn-danger' value='abc'> Delete </button></a> </td>";		
			if($n['isactive'] != 1)
				echo "<td><a href='{$activatelink}'><button class='btn btn-success' value='abc'> Activate </button></a></td>";
			else
				echo "<td><a href='{$deactivatelink}'><button class='btn btn-warning' value='abc'> DeActivate </button></a></td>";
			echo "<td><a href='{$uploadLink}'><button class='btn btn-primary' value='abc'> Upload </button></a></td> ";		
			echo "</tr>";
		}
		
		echo "</table>";
		echo "</div>";
		
	}
	
	
	else if($type == 'news')
	{
		$count = get_page_count("news");
		if($count > 1)
		{
			echo " <b style = 'color:#0044DD'>Pages: &nbsp &nbsp </b> ";
		
			for( $i = 1; $i <= $count; $i++) {
				echo "<a href='?type=news&action=generate&page={$i}'>"; if($i==$page) echo "<b>"; echo $i; if($i==$page) echo " </b>"; echo "</a> &nbsp;&nbsp;&nbsp;";
			}
		}
		
		$addLink = Link::addNews();
		
		echo "<a href='{$addLink}'> <button class='btn btn-success' value='abc'> Add a News </button></a>";
		echo "<br/><br/><p> <i>Click to edit </i></p>";
		
		$news = get_news($page);
		echo "<div class='span9'>";
		echo "<table class='span7 navHolder table table-striped'>";
		while($n = mysql_fetch_array($news))
		{
			$editLink = Link::editNews($n['id']);
			$deletelink = Link::delNews($n['id']);
			$activatelink = Link::actNews($n['id']);
			$deactivatelink = Link::deactNews($n['id']);
			echo "<tr>";
			echo "<td> <a href='{$editLink}'>".ucFirst($n['front_title'])."</a></td>";
			echo "<td><a href='{$deletelink}'><button  class='btn btn-danger' value='abc'> Delete </button></a></td>";	
			if($n['isactive'] != 1)
				echo "<td><a href='{$activatelink}'><button class='btn btn-success' value='abc'> Activate </button></a></td>";
			else
				echo "<td><a href='{$deactivatelink}'><button class='btn btn-warning' value='abc'> DeActivate </button></a></br></td>";	
			echo "</tr>";
		}
		echo "</table>";
		echo "</div>";
	}
	else if($type == 'announcements')
        {
                $count = get_page_count("announcements");
                if($count > 1)
                {
                        echo " <b style = 'color:#0044DD'>Pages: &nbsp &nbsp </b> ";
               
                        for( $i = 1; $i <= $count; $i++) {
                                echo "<a href='?type=announcements&action=generate&page={$i}'>"; if($i==$page) echo "<b>"; echo $i; if($i==$page) echo " </b>"; echo "</a> &nbsp;&nbsp;&nbsp;";
                        }
                }
               
                $addLink = Link::addAnnouncement();
               
                echo "<a href='{$addLink}'> <button class='btn btn-success' value='abc'> Add an Announcement </button></a>";
                echo "<br/><br/><p> <i>Click to edit </i></p>";
               
               
                $announcements = get_announcements($page);
               
                echo "<div class='span9'>";
                echo "<table class='span7 navHolder table table-striped'>";
                while($n = mysql_fetch_array($announcements))
                {
                        $editLink = Link::editAnnouncement($n['id']);
                        $deletelink = Link::delAnnouncement($n['id']);
                        $activatelink = Link::actAnnouncement($n['id']);
                        $deactivatelink = Link::deactAnnouncement($n['id']);
                        $uploadlink = Link::uploadAnnouncement($n['id']);
                       
                        echo "<tr>";
                        echo " <td><a href='{$editLink}'>".ucFirst($n['title'])."</a></td>";
                        echo "<td><a href='{$deletelink}'><button class='btn btn-danger' value='abc'> Delete </button></a></td>";              
                        if($n['isactive'] != 1)
                                echo "<td><a href='{$activatelink}'><button class='btn btn-success' value='abc'> Activate </button></a></td>";
                        else
                                echo "<td><a href='{$deactivatelink}'><button class='btn btn-warning' value='abc'> DeActivate </button></a></td>";
                        echo "<td><a href='{$uploadlink}'><button class='btn btn-primary' value='abc'> Upload </button></a></td>";
                        echo"</tr>";
                }
        }
       
        else if($type == 'tenders')
        {
                $count = get_page_count("tenders");
                if($count > 1)
                {
                        echo " <b style = 'color:#0044DD'>Pages: &nbsp &nbsp </b> ";
               
                        for( $i = 1; $i <= $count; $i++) {
                                echo "<a href='?type=tenders&action=generate&page={$i}'>"; if($i==$page) echo "<b>"; echo $i; if($i==$page) echo " </b>"; echo "</a> &nbsp;&nbsp;&nbsp;";
                        }
                }
               
                $addLink = Link::addTender();
               
                echo "<a href='{$addLink}'> <button class='btn btn-success' value='abc'> Add a Tender </button></a>";
                echo "<br/><br/><p> <i>Click to edit </i></p>";
               
                $tenders = get_tender($page);
               
                echo "<div class='span9'>";
                echo "<table class='span7 navHolder table table-striped'>";
                while($n = mysql_fetch_array($tenders))
                {
                        $editLink = Link::editTender($n['id']);
                        $deletelink = Link::delTender($n['id']);
                        $activatelink = Link::actTender($n['id']);
                        $deactivatelink = Link::deactTender($n['id']);
                        $uploadlink = Link::uploadTender($n['id']);
                        echo"<tr>";
                        echo " <td><a href='{$editLink}'>".ucFirst($n['title'])."</a></td>";
                        echo "<td><a href='{$deletelink}'><button class='btn btn-danger' value='abc'> Delete </button></a></td>";              
                        if($n['isactive'] != 1)
                                echo "<td><a href='{$activatelink}'><button class='btn btn-success' value='abc'> Activate </button></a></br></td>";
                        else
                                echo "<td><a href='{$deactivatelink}'><button class='btn btn-warning' value='abc'> DeActivate </button></a></br></td>";
                        echo "<td><a href='{$uploadlink}'><button class='btn btn-primary' value='abc'> Upload </button></a></td>";
                        echo "</tr>";
                }
                echo "</table>";
                echo "</div>";
        }
	
	else if($type == 'carousel')
	{	
		$addLink = Link::addCarousel();
		echo "<a href='{$addLink}'> <button class='btn btn-success' value='abc'> Add a new Carousel </button></a>";
		echo "<br/><br/><p> <i>Click to edit </i></p>";
		
		$carousel = get_carousel();
		echo "<div class='span9'>";
		echo "<table class='span7 navHolder table table-striped'>";
		while($n = mysql_fetch_array($carousel))
		{
		
			$editLink = Link::editCarousel($n['id']);
			$deletelink = Link::delCarousel($n['id']);
			$activatelink = Link::actCarousel($n['id']);
			$deactivatelink = Link::deactCarousel($n['id']);
			$uploadLink = Link::uploadCarousel($n['id']);
			
			echo "<tr>";
			echo "<td><a href='{$editLink}'>".ucFirst($n['overview'])."</a></td>";
			echo "<td>". ($n['weight'])."</td>";
			echo "<td><a href='{$deletelink}'><button class='btn btn-danger' value='abc'> Delete </button></a></td>";	
			if($n['isactive'] != 1)
				echo "<td><a href='{$activatelink}'><button class='btn btn-success' value='abc'> Activate </button></a></td>";
			else
				echo "<td><a href='{$deactivatelink}'><button class='btn btn-warning' value='abc'> DeActivate </button></a></td>";
			echo "<td><a href='{$uploadLink}'><button class='btn btn-primary' value='abc'> Upload </button></a> </td>";		
		}
		echo "</table>";
		echo "</div>";
		
	}
	
	else if ($type=='user_accounts')
	{
		$addLink = Link::addUser();
		
		echo "<a href='{$addLink}'> <button class='btn btn-success' value='abc'> New User </button></a>";
		echo "<br/><br/><p> <i>Click to View </i></p>";
		echo "<div class='span9' ><br/>";
		$users = get_all_users();
		echo "<table class='span6 navHolder table table-striped'>";
		while( $u = mysql_fetch_array($users)) {
			if($u['username'] != $_SESSION['_iiita_cms_username_'] && $u['username'] != 'admin') {
				$editLink = Link::editUser($u['id']);
				$deletelink = Link::delUser($u['id']);
				$actlink = Link::actUser($u['id']);
				$deactlink = Link::deactUser($u['id']);
				$changePass = 'change_pass.php?user='.$u['username'];
				
				echo "<tr>";
				echo "<td><a href='{$editLink}'>".ucFirst($u['username'])."</a></td>";
				echo "<td><a href='{$deletelink}'><button class='btn btn-danger' value='abc'> Delete </button></a> </td>";	
				if(!$u['isactive']) 
					echo "<td><a href='{$actlink}'><button class='btn btn-success' value='abc'> Activate </button></a> </td>";		
				else 
					echo "<td><a href='{$deactlink}'><button class='btn btn-warning' value='abc'> DeActivate </button></a> </td>";
				if($_SESSION['_iiita_cms_username_'] == 'admin')
					echo "<td><a href='{$changePass}'><button class='btn btn-info' value='abc'> Change Password </button></a> </td>";		
				echo "</tr>";
			}
			
		}
		echo "</table>";
		echo "</div>";
	}
	
	else if ($type=='feeds')
	{
		echo "<br/><br/>";
		echo "<div class='span9 '>";
		echo "<table class='span7 navHolder table table-bordered'>";
		$content= get_sub_content();
		$sendLink = Link::sendSubContent();
		echo "<tr>";
		while($c = mysql_fetch_array($content))
		{
			$deletelink = Link::deleteFeed($c['id']);
			echo "<tr>";
			echo "<td>&nbsp;&nbsp;&nbsp;-> ".ucFirst($c['front_title'])."</a></td><td> ".ucFirst($c['type'])."</td><td><a href='{$deletelink}'><button class='btn btn-danger'> Delete</button></a>";
			echo "</tr>";
		}
		echo "</table>";
		echo "&nbsp;&nbsp;&nbsp;&nbsp;<a href='{$sendLink}'><button class='btn btn-primary' value='abc'> Send Feeds</button></a>";
		echo "</div>";
	}
	
	else if ($type=='query')
	{
		echo "<div class='span9 '>";
		echo "<table class='span7 navHolder table table-bordered'>";
		$query= get_query();
		echo "<tr>";
		while($c = mysql_fetch_array($query))
		{
			$deletelink = Link::delQuery($c['id']);
			echo "<tr>";
			echo "<td><p>&nbsp;&nbsp;&nbsp;-> ".ucFirst($c['query'])."</p></td> ";
			echo "<td><p>&nbsp;&nbsp;&nbsp;{$c['email']}</p></td> ";
			echo "<td><a href='{$deletelink}'><button class='btn btn-danger' value='abc'> Delete </button></a></br></td>";
			echo "</tr>";
		}
		echo "</tr>";
		echo "</table>";
		echo "</div>";
	}
	
	else if($type == 'settings') {
		$editLink = Link::editSettings();
		echo "<a href='{$editLink}'><button class='btn btn-success'>Edit CSS and JS</button></a>";
	}
	
	else if ($type=='gfiles')
	{
		$uploadlink = Link::uploadGfiles();
		echo "<a href='{$uploadlink}'> <button class='btn btn-success' value='abc'>Upload Global Files </button></a> <br/>";
		echo "Currently Uploaded files <br/>";
	}

	else if ($type == 'editor')
	{
		$addLink = Link::addEditor();
		$editLink = Link::editEditor();
		echo "<br/>";
		echo "<a href='?type=editor&action=upload&value=0'><h4><button class='btn btn-primary'>Upload files</button></h4></a>";
		
		echo "<div class='span10 navHolder' >";
		echo "<div class='span6 '>";
		echo "	<form action='{$editLink}' method='post' >
				<label><h3>Edit an existing file: </h3></label>
				<input name='path' class='span4' type='text' id='edit_path' placeholder='Path of the file' /> &nbsp; &nbsp;
			
				<input name='edit_editor' class='span2 btn btn-primary' style='margin-top:-7px;' type='submit' value='Edit'/>
			</form> <br/> ";
	
		echo "	<form action='{$addLink}' method='post' >
			<label><h3> Add a new File</h3> </label>
				<input name='path' class='span4' type='text' readonly='readonly'  placeholder='Path of the file' /> &nbsp; &nbsp;
				<input name='add_editor' class='span2 btn btn-primary ' style='margin-top:-7px;' type='submit' value='Add'/>
			</form> ";
		echo "</div><br/><br/>";
		echo "<div class='span3' style='padding:20px; padding-top:0px;'>  ";
		echo "<h4> Here is the path of some important files:</h4>(click to get the path)<br/><br/>";
		echo "<p  onclick='copy_text(\"p1\")'><b>1. Footer</b></p>";
		echo "<p id='p1' style='display:none;'>includes/site_footer.php</p>";
		echo "<p  onclick='copy_text(\"p2\")'><b>2. Rolling Message</b></p>";
		echo "<p id='p2' style='display:none;'>includes/rolling_msg.php</p>";
		echo "<p  onclick='copy_text(\"p3\")'><b>3. Announcements Inner</b></p>";
		echo "<p id='p3' style='display:none;'>announcements.php</p>";
		echo "<p  onclick='copy_text(\"p4\")'><b>4. Events Inner</b></p>";
		echo "<p id='p4' style='display:none;'>events.php</p>";
		echo "<p  onclick='copy_text(\"p5\")'><b>5. News Inner</b></p>";
		echo "<p id='p5' style='display:none;'>news.php</p>";
		echo "<p  onclick='copy_text(\"p6\")'><b>6. Tenders Inner</b></p>";
		echo "<p id='p6' style='display:none;'>tenders.php</p>";
		echo "</div>";
		echo "</div>";

		
	}
?>

<script>
function copy_text(id)
{
	document.getElementById("edit_path").value = document.getElementById(id).innerHTML;

}
</script>
