<?php
session_start();
$interventionURL = "http://interventionapp.com";
$root = "";
?>
<head>
	<title>Intervention</title>
	<script src="scripts/json.js"></script>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.8/jquery.min.js"></script>
	<script src="scripts/bootstrap.js"></script>
    <link href="css/bootstrap.css" rel="stylesheet">
	<!--<link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.1.1/css/bootstrap-theme.min.css">-->
    <link href="css/bootstrap-responsive.css" rel="stylesheet">
	<script src="scripts/script.js"></script>
	<link href="css/style.css" media="screen" rel="stylesheet"/>
</head>
<body>

<nav class="navbar navbar-inverse navbar-default" role="navigation">
  <div class="container-fluid">

    <div class="navbar-header">
      <a class="navbar-brand" href="<?=$interventionURL?>">Intervention</a>
    </div>

    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
      <form class="navbar-form navbar-right" role="search">
        <div class="form-group">
          <input type="text" class="form-control" placeholder="Search">
        </div>
        <button type="submit" class="btn btn-default">Search</button>
      </form>
      <ul class="nav navbar-nav navbar-right">
        <li><a href="<?=$interventionURL?>">Home</a></li>
		<li><a href="profile.php">Profile</a></li>
        <!--<li class="dropdown">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown">Dropdown <b class="caret"></b></a>
          <ul class="dropdown-menu">
            <li><a href="#">Action</a></li>
            <li><a href="#">Another action</a></li>
            <li><a href="#">Something else here</a></li>
            <li class="divider"></li>
            <li><a href="#">Separated link</a></li>
          </ul>
        </li>-->
      </ul>
    </div><!-- /.navbar-collapse -->
  </div><!-- /.container-fluid -->
</nav>