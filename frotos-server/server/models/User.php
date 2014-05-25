<?

include_once($root."models/Table.php");

class User extends Table {
	
	public function __construct( $userId = 0, $info = '') {
		parent::__construct("users", "user_id", $userId, $info);
	}
	
	public static function createUserWithFbId($info) {
		$user = new User();
		$user -> setEmail($info['email']);
		$user -> setUserId('99'.$info['fb_id']);
		$user -> setFbId($info['fb_id']);
		$user -> setFirstName($info['firstname']);
		$user -> setLastName($info['lastname']);
		$user -> setLocation($info['location']);
		$user -> setBirthday($info['birthday']);
		$user -> setSex($info['sex']);
		$user -> setCreatedAt(date("Y-m-d H:i:s"));
		$user -> setProfilePicture($info['profile_picture']);
		$user -> processFriends($info['friends'], $user -> getUserId());
		
		if (1 == $user -> insertDB()) {
			// Delete from nonusers
			$q = "delete from nonusers where user_id='{$user->getUserId()}'";
			$db = Database::getInstance();
			$db -> runQuery($q);
			return true;
		}
		return false;
	}
	
	private static function processFriends($friends, $my_id) {
		$db = Database::getInstance();
		foreach($friends as $friend) {
			$curr_id = '99'.$friend['id'];
			$u = User::getUserById($curr_id);
			User::addFriendship ($curr_id, $my_id);
			if ($u) {
				// The friend is already on Intervention
				// TODO: Notify the user $u that $this joined Intervention
			}
			else {
				$friend['name'] = addslashes($friend['name']);
				$q = "insert ignore into nonusers(user_id, name, fb_id, profile_picture) values('{$curr_id}', '{$friend['name']}', '{$friend['id']}', '{$friend['profile_picture']}')";
				$db -> runQuery($q);
			}
		}
	}

	public static function getUserByEmail( $email )
	{
		$db = Database::getInstance();
		$res = $db->runQueryAsArray( "select user_id from users where ".
				"email='$email'" );
		if (count($res) == 0)
			return null;
		return new User($res[0]['user_id']);
	}

	public static function getUserById( $id )
	{
		$db = Database::getInstance();
		$res = $db->runQueryAsArray( "select user_id from users where ".
				"user_id='$id'" );
		if (count($res) == 0)
			return null;
		return new User($res[0]['user_id']);
	}

	public function getUserId()
	{
		return $this->info['user_id'];
	}

	public function setUserId($userId)
	{
		$this->info['user_id'] = $userId;
	}

	public function getFbId()
	{
		return $this->info['fb_id'];
	}
	
	public function setFbId($fb_id)
	{
		$this->info['fb_id'] = $fb_id;
	}

	public function getFirstName()
	{
		return $this->info['firstname'];
	}

	public function setFirstName($firstName)
	{
		$this->info['firstname'] = $firstName;
	}
    
    public function getDate() {
        return $this->info['created_at'];
    }
   
	public function getLastName()
	{
		return $this->info['lastname'];
	}
	public function setLastName($lastName)
	{
		$this->info['lastname'] = $lastName;
	}
	public function getEmail()
	{
		return $this->info['email'];
	}
	public function setEmail($email)
	{
		$this->info['email'] = $email;
	}

	public function getSex()
	{
		return $this->info['sex'];
	}
	public function setSex($sex)
	{
		$this->info['sex'] = $sex;
	}

	public function getLocation()
	{
		return $this->info['location'];
	}
	
	public function setLocation($location)
	{
		$this->info['location'] = $location;
	}
	
	public function getBirthday()
	{
		return $this->info['birthday'];
	}
	
	public function setBirthday($date)
	{
		$this->info['birthday'] = $date;
	}
	
	public function setCreatedAt($date)
	{
		$this->info['created_at'] = $date;
	}
	
	public function getCreatedAt()
	{
		return $this->info['created_at'];
	}

	public function login($pass)
	{
		$pass = md5($pass);
		$r = $this->db->runQueryAsArray("SELECT user_id FROM users " .
				"WHERE email='".$this->getEmail()."' AND pass='$pass'");
		if (count($r) > 0)
			return true;
		return false;
	}

	public function getFriends()
	{
		$sql = "select u.user_id as user_id, u.firstname as firstname, u.lastname as lastname, u.profile_picture as profile_picture from friendships f, users u where f.user_id1 = u.user_id and f.user_id2 = '".$this->getUserId()."'";
		return $this->db->runQueryAsArray($sql);
	}
	public function countFriends()
	{
		$friends = array();
		$arr = $this->db->runQueryAsArray("select count(user_id2) from friendships, users u where user_id2=u.user_id and user_id1='".$this->getUserId()."'");
		return (int)$arr[0][0];
	}
	
	public function getFriendsFromNonUsers()
	{
		$sql = "select u.user_id as user_id, u.name as name, u.fb_id as fb_id, u.profile_picture as profile_picture from friendships f, nonusers u where f.user_id1 = u.user_id and f.user_id2 = '".$this->getUserId()."'";
		return $this->db->runQueryAsArray($sql);
	}
	
	public function setProfilePicture($url)
	{
		$this->info['profile_picture'] = $url;
	}
	public function getProfilePicture()
	{
		/* Returns a string with the path to the profile picture */
		return $this->info['profile_picture'];
	}
	
	public static function addFriendship($user_id1, $user_id2)
	{
		$sql = "insert ignore into friendships values({$user_id1}, {$user_id2})";
		$db = Database::getInstance();
		$r = $db->runQuery($sql);
		$sql = "insert ignore into friendships values({$user_id2}, {$user_id1})";
		$r = $db->runQuery($sql);
	}
}
?>
