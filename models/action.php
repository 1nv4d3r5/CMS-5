<?php
	if(isset($_GET['action']))
		$action = htmlentities($_GET['action']);
	else
		$action = null;
	
	if(isset($_GET['type']))
		$type = htmlentities($_GET['type']);
	else
		$type = null;
		
	if(isset($_GET['value'])) 
	{
		$value = htmlentities($_GET['value']);
		if (!is_numeric($value))
			header('Location: /cms');
	}
	else
		$value = null;
	
	if(isset($_GET['page'])) 
	{
		$page = htmlentities($_GET['page']);
		if (!is_numeric($page))
			header('Location: /cms');
	}
	else 
		$page = 1;
	
	
	if(isset($_POST['cancel'])) 
	{
		$t = $type;
		if($t == 'subnav') 
			$t = 'nav'; 
		if($t == 'rpanelcontent')
			$t = 'rpanel';
		if($t=='sub_content')
			header('Location:?type='.$t."&action=generate"."&value=".$value);
		else
			header('Location:?type='.$t."&action=generate");
	}
	
	if($action == 'add')
		require_once('actions/add.php');
	
	elseif($action == 'upload')
		require_once('actions/fileUpload.php');
	
	elseif($action == 'generate')
		require_once('actions/generate.php');
	
	elseif($action == 'edit')
		require_once('actions/edit.php');
	
	elseif($action == 'del')
		require_once('actions/delete.php');
	
	elseif($action == 'activate')
		require_once('actions/activate.php');
	
	elseif($action == 'deactivate')
		require_once('actions/deactivate.php');
		
	elseif($action == 'preview')
		require_once('actions/preview.php');
		
	elseif($action == 'send')
		require_once('actions/send.php');
		
	else
	{
		
	}
	
?>