<?php
//header('Content-Type: application/json');
$root = "../";
include_once($root."models/User.php");
include_once($root."models/Intervention.php");
include_once($root."controllers/Feed.php");

$action = addslashes($_POST['action']);
$res = array();
$res[] = array();

switch ($action)
{
	case 'getFeed':
	{
		$res = Feed::getFeed(addslashes($_POST['user_id']));
		break;
	}
	default:
	{
		$res[0]['error'] = true;
		$res[0]['message'] = 'Invalid action';
	}
}
echo json_encode($res);
?>