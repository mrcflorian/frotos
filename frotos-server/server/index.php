<?php
	$root = "";
	include_once($root.'controllers/Session.php');
	include_once($root.'controllers/FacebookApi.php');
	include_once($root.'models/User.php');
	$session = Session::getInstance();
	$fb = FacebookApi::getInstance();
	if (!($session->is_logged_in())) {
		if ($fb->isAuthentificated()) {
			$uid = $fb->getFacebookUserInfo();
			$uid = $uid["id"];
			$session->loginWithFacebook($uid);
		}
	}
	if ($session->is_logged_in()) {
		header("location:home.php");
		die();
	}
?>
<head>
	<title>Intervention</title>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.8/jquery.min.js"></script>
	<script src="scripts/bootstrap.js"></script> 	
    <link href="css/bootstrap.css" rel="stylesheet">
    <link href="css/bootstrap-responsive.css" rel="stylesheet">
	<script src="scripts/script.js"></script>
	<link href="css/style.css" media="screen" rel="stylesheet"/>
</head>
<body class="landing-body">
	<?php
    	echo $fb->getFacebookInit();
    ?>
	<div id="container" class="landing-page">
		<h1 class="brand">Intervention</h1>
		<h2 id="tagline">Get better by having fun with friends!</h2>
		
		<div onclick='FacebookLogin();' class="btn btn-large btn-block btn-primary btn-facebook-login" href=""><img src="images/facebook-icon.png" /><span>Login with Facebook</span></div>
	</div>
</body>
<script>
	function FacebookLogin()
	{
		FB.login(function(response) {
			if (response.status === 'connected')
				login();
		}, {scope: 'email,user_birthday,user_location,publish_actions'});
	}
</script>