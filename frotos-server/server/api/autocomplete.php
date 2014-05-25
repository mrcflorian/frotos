<?php
session_start();

$root = "../";
include_once($root."models/User.php");
include_once($root."models/Intervention.php");
include_once($root.'controllers/Session.php');

$user_id = addslashes($_REQUEST['user_id']);

switch ($_REQUEST['action']) {
	case 'friends':
	{
		if (isset($_REQUEST['query'])) {
			$query = strtolower($_REQUEST['query']);
			$db = Database::getInstance();
			$res = $db->runQueryAsArray("SELECT u.user_id, concat(firstname, ' ', lastname) as 'name', profile_picture FROM users u, friendships f WHERE (lower(u.firstname) LIKE '%{$query}%' or lower(u.lastname) LIKE '%{$query}%') and f.user_id1='{$user_id}' and f.user_id2=u.user_id union SELECT n.user_id, n.name, profile_picture FROM nonusers n, friendships f WHERE lower(name) LIKE '%{$query}%' and f.user_id1='{$user_id}' and f.user_id2=n.user_id limit 10");
			$array = array();
			foreach ($res as $row) {
				$array[] = $row;
			}
			echo json_encode ($array); //Return the JSON Array
		}
		break;
	}
	default:
	{
	}
}
?>