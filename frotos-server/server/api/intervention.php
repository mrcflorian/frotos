<?php
//header('Content-Type: application/json');
$root = "../";
include_once($root."models/User.php");
include_once($root."models/Intervention.php");

$action = addslashes($_POST['action']);
$json = json_decode(stripslashes($_POST['json_data']), true);
$res = array();
$res[] = array();

switch ($action)
{
	case 'create':
	{
		$success = Intervention::createIntervention($json);
		if ($success) {
			$res[0]['success'] = true;
			$res[0]['message'] = 'The intervention was created successfully!';
		} else {
			$res[0]['error'] =  true;
			$res[0]['message'] = 'Sorry, the intervention couldn\'t be created. Please check all the fields!';
		}
		break;
	}
	case 'statuses':
	{
		$res = Intervention::getInterventions(addslashes($_POST['user_id']));
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