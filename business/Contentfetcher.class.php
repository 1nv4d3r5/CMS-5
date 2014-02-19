<?php
class ContentFetcher
{
	private  $type = '';
	private  $value = 0;
	private $table;
	public function __construct($t, $v)
	{
		$this -> type = $t;
		$this -> value = (int)($v);
	}
	
	public function getTable($type)
	{
		if($type == 'nav')
			return "nav";
		if($type == 'subnav')
			return "subnav";
		if($type == 'rpanel')
			return "right_panel_topics";
		if($type == 'rpanelcontent')
			return "right_panel_content";
	}
	
	public function fetchData()
	{
		global $connection;
		$this -> table = $this -> getTable($this -> type);
		$query = "SELECT * FROM ".$this -> table ." WHERE id = " .$this -> value;
		$result = mysql_query($query, $connection);
		return $result;
	}
};

?>
