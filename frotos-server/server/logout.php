<?php
include_once($root.'controllers/Session.php');
include_once($root.'controllers/FacebookApi.php');

$session =  Session::getInstance();
$fb = FacebookApi::getInstance();
if ($session->is_logged_in() || $fb->isAuthentificated())
{
    $session->logout();
    unset($_SESSION);
    $fb->logout();
}
?>
<?php
echo $fb->getFacebookInit();
?>
<?php
$user = null;

if ($session->is_logged_in() || $fb->isAuthentificated())
{
	$fb->logout();
	$session->logout();
	unset($_SESSION);
}
?>

<script type="text/javascript">
FB.logout(function(response) {
    window.location = "http://interventionapp.com";
});
</script>
