<?
include_once("../models/User.php");

class Intervention extends Table {
	
	public function __construct( $userId = 0, $info = '') {
		parent::__construct("interventions", "intervention_id", $userId, $info);
	}
	
	public static function createIntervention($info) {
		$intv = new Intervention();
		$intv -> setTitle($info['title']);
		$intv -> setUserId($info['user_id']);
		$intv -> setAuthorId($info['author_id']);
		$intv -> setDescription($info['description']);
		$intv -> setPhotoSrc($info['photo_src']);
		$intv -> setPrivacy($info['privacy']);
		$intv -> setCreatedAt(date("Y-m-d H:i:s"));
		$users = $info['users'];

		/*if (count($users)) {
			foreach ($users as $user) {
				$u = new User ($user['user_id']);
				if (!$u) {
					return false;
				}
			}
		}*/
		
		if (1 == $intv -> insertDB()) {
			$db = Database::getInstance();
			$q = "insert into interventions_users(intervention_id, user_id, accepted, joined_at) values('{$intv->getId()}', '{$intv->getAuthorIdl()}', '1', '{$intv->getCreatedAt()}')";
			$db -> runQuery($q);
			if (count($users)) {
				foreach ($users as $user) {
					$q = "insert into interventions_users(intervention_id, user_id, accepted) values('{$intv->getId()}', '{$user['user_id']}', '0')";
					$db -> runQuery($q);
				}
			}
			return true;
		}
		return false;
	}
	
	public static function getInterventionById( $id )
	{
		$db = Database::getInstance();
		$res = $db->runQueryAsArray( "select intervention_id from interventions where ".
				"intervention_id='$id'" );
		if (count($res) == 0)
			return null;
		return new Intervention($res[0]['intervention_id']);
	}

	public function getId()
	{
		return $this->info['intervention_id'];
	}

	public function setId($intvId)
	{
		$this->info['intervention_id'] = $intvId;
	}
	
	public function setUserId($user_id)
	{
		$this->info['user_id'] = $user_id;
	}
	
	public function getUserId()
	{
		return $this->info['user_id'];
	}

	public function getPrivacy()
	{
		return $this->info['privacy'];
	}
	
	public function setPrivacy($privacy)
	{
		$this->info['privacy'] = $privacy;
	}

	public function getTitle()
	{
		return $this->info['title'];
	}

	public function setTitle($title)
	{
		$this->info['title'] = $title;
	}
    
    public function getDate() {
        return $this->info['created_at'];
    }
   
	public function getDescription()
	{
		return $this->info['description'];
	}
	public function setDescription($description)
	{
		$this->info['description'] = $description;
	}
	public function getAuthorIdl()
	{
		return $this->info['author_id'];
	}
	public function setAuthorId($author_id)
	{
		$this->info['author_id'] = $author_id;
	}

	public function setCreatedAt($date)
	{
		$this->info['created_at'] = $date;
	}
	
	public function getCreatedAt()
	{
		return $this->info['created_at'];
	}

	public function setPhotoSrc($url)
	{
		$this->info['photo_src'] = $url;
	}
	
	public function getPhotoSrc()
	{
		return $this->info['photo_src'];
	}
	
	public static function getInterventions($user_id)
	{
		$db = Database::getInstance();
		$q = "select * from interventions";// where user_id='{$user_id}' order by created_at desc";
		return $db -> runQueryAsArray($q);
	}
	
	public static function getInterventionsCreatedByUser($user_id)
	{
		$db = Database::getInstance();
		$q = "select * from interventions";// where author_id='{$user_id}' order by created_at desc";
		return $db -> runQueryAsArray($q);
	}
}
?>
