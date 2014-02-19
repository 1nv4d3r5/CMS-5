<?php

class Subscription
{
	public static function Add($type, $value, $user)
	{
		$qry = "SELECT * FROM $type WHERE id = :id";
		$params = array(':id' => $value);
		$result = DatabaseHandler::GetAll($qry, $params);
		$data = "";
		if ($type == 'events' || $type == 'news') {
			$data = $result[0]['inner_content'];
			$front_title = $result[0]['front_title'];
			$inner_title = $result[0]['inner_title'];
			$sql = "INSERT INTO sub_content (type, front_title, inner_title, data, post_id, isdeleted, added_by, modified_by, modified_on) VALUES (:type, :front_title, :inner_title, :data, :post_id, 0, :added_by, :modified_by, NOW())";
			$params = array(':type' => $type, ':front_title' => $front_title, ':inner_title' => $inner_title, ':data' => $data, ':post_id' => $value, ':added_by' => $user, ':modified_by' => $user);
		} else {
			$data = $result[0]['content'];
			$title = $result[0]['title'];
			$sql = "INSERT INTO sub_content (type, front_title, inner_title, data, post_id, isdeleted, added_by, modified_by, modified_on) VALUES (:type, :title, :title, :data, :post_id, 0, :added_by, :modified_by, NOW())";
			$params = array(':type' => $type, ':title' => $title, ':data' => $data, ':post_id' => $value, ':added_by' => $user, ':modified_by' => $user);
		}
		
		$result = DatabaseHandler::Execute($sql, $params);			
	}
	
	public static function Delete($type, $value)
	{
		$sql = "UPDATE sub_content SET isdeleted = 1 WHERE type = :type AND post_id = :post_id AND isdeleted = 0";
		$params = array(':type' => $type, ':post_id' => $value);
		$result = DatabaseHandler::Execute($sql, $params);	
	}
};

?>