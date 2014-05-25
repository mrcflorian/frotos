<?php

include_once($root."facebook/facebook.php");

/*
 * Facebook API
* Need to be initialized before use
* $fb = FacebookApi::getInstance ();
* $fb->initApi ();
*
* Also need to include this library
* <script src="http://connect.facebook.net/en_US/all.js"></script>
*     <script>
*    FB.init({appId: '<?= YOUR_APP_ID ?>', status: true,
		*              cookie: true, xfbml: true});
*     FB.Event.subscribe('auth.login', function(response) {
		*       window.location.reload();
		*     });
*/

class FacebookApi
{
	public static $instance = null;
	private $app_id = "717726291580043";
	private $app_secret = "b34d532700ce4e78115945253c31a6bb";
	public $facebook = null;
	//private $cookie;
	//private $initialized = false;

	private function __construct()
	{
		//constructor Singleton
		$this->facebook = new Facebook(array(
				'appId'  => $this->app_id,
				'secret' => $this->app_secret,
		));
	}

	public static function getInstance ()
	// returns the only instance of the class
	{
		if (!self::$instance)
			self::$instance = new self ();
		return self::$instance;
	}
	
	public function logout()
	{
		//$this->facebook->destroySession();
		//$this->facebook->getLogoutUrl (array('next' => $hickeryURL));
        $fb_session = $this->facebook->getUser();
		$logoutUrl = $this->facebook->getLogoutUrl(array('next' => 'http://interventionapp.com'));
		//die($logoutUrl);
        $this->facebook->destroySession();
        header('Location:' . $logoutUrl);
	}

	public function getFacebookInit()
	{
		$val = "";
		$val = "<script>";
		$val .= "window.fbAsyncInit = function() {";
		$val .= "FB.init({";
		$appId = $this->app_id;
		$val .=	"appId      : '{$appId}',"; // App ID
		$val .= "frictionlessRequests: true,";
		$val .=	"channelUrl : 'http://interventionapp.com/channel.html',"; // Channel File
		$val .=	"status     : true,"; // check login status
		$val .=	"cookie     : true,"; // enable cookies to allow the server to access the session
		$val .=	"xfbml      : true";  // parse XFBML
		$val .=	"});";
			
		// Additional initialization code here
		$val .=	"FB.Event.subscribe('auth.login', function () {";
		$val .=	"window.location.reload()";
		$val .=	"});";
		if ($this->isAuthentificated() == false) {
			$val .= "FB.Event.subscribe('auth.statusChange', function(response) {".
					"if (response.status == 'connected')".
					"	window.location.reload();".
					"});";
		}
		$val .=	"};";
			
		// Load the SDK Asynchronously
		$val .=	"(function(d){";
		$val .=	"var js, id = 'facebook-jssdk', ref = d.getElementsByTagName('script')[0];";
		$val .=	"if (d.getElementById(id)) {return;}";
		$val .=	"js = d.createElement('script'); js.id = id; js.async = true;";
		$val .=	"js.src = \"//connect.facebook.net/en_US/all.js\";";
		$val .=	"ref.parentNode.insertBefore(js, ref);";
		$val .=	"}(document));";
		$val .=	"</script>";
		$val .=	"<div id=\"content\"></div>";
		return $val;
	}

	public function getFacebookLoginButton()
	{
		$val = "<fb:login-button data-scope=\"email,user_birthday,user_location,
		user_likes,friends_likes,user_relationships,user_work_history,user_checkins,
		friends_checkins,read_stream,publish_actions\" size=\"large\">Log in with Facebook!</fb:login-button>";
		return $val;
	}

	public function getFacebookUserInfo()
	{
		$user = $this->facebook->getUser();
		$me = null;
		if ($user)
		{
			try
			{
				$me = $this->facebook->api('/me');
			}
			catch (FacebookApiException $e)
			{
			    $this->facebook->destroySession();
			}
		}
	    return $me;
	}
    
    public function isValid() {
        if ($this->getFacebookUserInfo() == null)
            return false;
        return true;
    }
	public function getFacebookFriends()
	{
	    $user = $this->facebook->getUser();
	    $friends = null;
	    if ($user)
	    {
	        try
	        {
	            $friends = $this->facebook->api('/me/friends');
	        }
	        catch (FacebookApiException $e)
	        {
	            $this->facebook->destroySession();
	        }
	    }
	    
	    return $friends;
	}
    
    public function getFacebookLikes($hkid = 'me')
    {   
		$limit = 10000;
        $fbid = $hkid;
        if ($fbid !== 'me')
            $fbid= getFacebookId($hkid);

        $user = $this->facebook->getUser();
        $likes = null;
        if ($user)
        {
            try
            {
                $likes = $this->facebook->api("/{$fbid}/likes?limit={$limit}");
            } catch (FacebookApiException $e)
            {
                $this->facebook->destroySession();
            }
        }
        return $likes;
    }
    
    public function getFacebookLikesForFbId($id)
    {
        try
        {
            $likes = $this->facebook->api('/{$id}/likes');
        } catch (FacebookApiException $e)
        {
            return array();
        }
        return $likes;
    }
    
    public function getFacebookCheckins()
    {
        $user = $this->facebook->getUser();
        $checkins = null;
        if ($user)
        {
            try
            {
                $checkins = $this->facebook->api('/me/checkins');
            } catch (FacebookApiException $e)
            {
                $this->facebook->destroySession();
            }
        }
        return $checkins;
    }

    public function runQuery($query)
    {
        $ret = null;
        try
        {
            $ret = $this->facebook->api( array( 'method' => 'fql.query',
                    'query' => $query));
        } catch (FacebookApiException $e)
        {
            $this->facebook->destroySession();
        }
        return $ret;
    }
	
	public function runMultiQuery($queries) {
		$ret = null;
		try {
			$q = '{';
			foreach ($queries as $key => $value)
				$q .= '"' . $key . '":"' . $value . '",';
			$q = substr($q, 0, strlen($q) - 1);
			$q .= '}';
			$param = array(
				'method' => 'fql.multiquery',
				'queries' => $q,
				'callback' => ''
			);
			$ret = $this->facebook->api($param);
		} catch(FacebookApiException $e) {
			$this->facebook->destroySession();
		}
		return $ret;
	}
	public function isAuthentificated()
	{
		$user = $this->facebook->getUser();
		if ($user != null)
			return true;
		else
			return false;
	}
}
?>