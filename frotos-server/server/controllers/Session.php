<?php
include_once($root.'controllers/FacebookApi.php');

class Session {

	public static $instance = null;
	private $logged_in = false;
	public $id;

	private function __construct() {
        if (isset($_SERVER['REQUEST_URI'])) {
		    if (!session_id())
                session_start();
        }
        if (isset($_SERVER['REMOTE_ADDR']))
			$_SESSION['IP'] = $_SERVER['REMOTE_ADDR'];
		if (isset($_SERVER['HTTP_USER_AGENT']))
			$_SESSION['USER_AGENT'] = $_SERVER['HTTP_USER_AGENT'];

		$this->check_login();
		if ($this->logged_in) {
			// actions to take right away if user is logged in
		} else {
			// actions to take right away if user is not logged in
		}
	}

	public static function getInstance ()
	// returns the only instance of the class
	{
		if (!self::$instance)
			self::$instance = new self ();
		return self::$instance;
	}

	public function is_logged_in() {
		return $this->logged_in;
	}

	public function login($user, $pass) {
		// need a function in User class to verify authentification
		// database should find user based on username/password
		if(!$user) return false; // bad call
		if ($user->login($pass) === false)
			return false;
		 
		$this->id = $_SESSION['user_id'] = $user->getUserId();
		$this->logged_in = true;
		return true;
	}

	public function loginWithFacebook($fbid)
	{
		$id = '99'.$fbid;
        $user = User::getUserById($id);
		if ($user) {
			$this->id = $_SESSION['user_id'] = $user->getUserId();
			$this->logged_in = true;
            
            //prepare to get a long-lived access token
            //$fb = FacebookApi::getInstance();
            //request a long-lived token
            //if (($long_token = $fb->facebook->getExtendedAccessToken()) != false)
            //{
              //  AccessToken::setAccessTokenForHkId($this->hkid, $long_token);
            //}
            
			return true;
		}
		else
		{
			$facebook = FacebookApi::getInstance();
			$fbuser = $facebook -> getFacebookUserInfo();
			if(!$fbuser)
			{
				return false;
			}
			
			// Register
			$data['fb_id'] = $fbid;
			$data['email'] = $fbuser['email'];
			$data['firstname'] = addslashes($fbuser['first_name']);
			$data['lastname'] = addslashes($fbuser['last_name']);
			$date = new DateTime($fbuser['birthday']);
			$data['birthday'] = $date->format("Y-m-d H:i:s");
			$data['sex'] = $fbuser['gender'] === "male" ? 1 : 0;
			$data['friends'] = $facebook -> getFacebookFriends();
			$data['friends'] = $data['friends']['data'];
			
			if (isset ($fbuser['location']['name']))
			    $data['location'] = $fbuser['location']['name'];
			if(User::createUserWithFbId($data) == true)
			{
				$this->id = $_SESSION['user_id'] = '99'.$data['fb_id'];
				$this->logged_in = true;				
				//prepare to get a long-lived access token
				//$fb = FacebookApi::getInstance();
				//request a long-lived token
				//if (($long_token = $fb->facebook->getExtendedAccessToken()) != false)
				//{
				  //  AccessToken::setAccessTokenForHkId($this->hkid, $long_token);
				//}
				return true;
			}
		}
		return true;
	}

	public function logout() {
		unset($_SESSION['user_id']);
		unset($this->id);
		$this->logged_in = false;
	}

	private function check_login() {
		if (isset($_SESSION['user_id'])) {
			$this->id = $_SESSION['user_id'];
			$this->logged_in = true;
		} else {
			unset($this->id);
			$this->logged_in = false;
		}
	}

}
$session = Session::getInstance();
?>
