<?php
include_once($root.'models/User.php');
include_once($root."models/Intervention.php");

class Feed {

	/*
		This method returns the feed (which appears on Home) for a given user_id.
	*/
	public static function getFeed($user_id)
	{
		$u = new User($user_id);
		$db = Database::getInstance();
		$friends = $u -> getFriends();
		$interventions = array();
		foreach ($friends as $friend)
		{
			$interventions = $interventions + Intervention::getInterventions($friend['user_id']);
			$interventions = $interventions + Intervention::getInterventionsCreatedByUser($friend['user_id']);
		}
		$non_friends = $u -> getFriendsFromNonUsers();
		foreach ($non_friends as $friend)
		{
			$interventions = $interventions + Intervention::getInterventions($friend['user_id']);
			$interventions = $interventions + Intervention::getInterventionsCreatedByUser($friend['user_id']);
		}
		
		return $interventions;
		usort($interventions, function($a, $b) {
			return strtotime($b['created_at']) - strtotime($a['created_at']);
		});
		return $interventions;
	}
}
?>
