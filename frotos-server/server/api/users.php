<?php
header('Content-Type: application/json');
$root = "../";
include_once($root."models/User.php");
$action = addslashes($_POST['action']);
$json = json_decode(stripslashes($_POST['json_data']), true);
$res = array();
$res[] = array();

switch ($action)
{
	case 'create':
	{
		$success = User::createUserWithFbId($json);
		if ($success) {
			$res[0]['success'] = true;
		} else {
			$res[0]['error'] =  true;
		}
		break;
	}
	case 'update_picture':
	{
		$user_id = addslashes($_POST['user_id']);
		$picture = addslashes($_POST['profile_picture']);
		$u2 = User::getUserById($user_id);
		if ($u2 == null) {
			$q2 = "update ignore nonusers set profile_picture='{$picture}' where fb_id='{$user_id}'";
		} else {
			$q2 = "update  users set profile_picture='{$picture}' where fb_id='{$user_id}'";
		}
		$db = Database::getInstance();
		$db->runQuery($q2);
	}
	default:
	{
		$res[0]['error'] = true;
	}
}

echo json_encode($res);

?>
