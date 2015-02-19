<? 
	if(function_exists('scriptTimer')){
		scriptTimer("header");
	}
?><!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js"> <!--<![endif]-->
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <title>Path Liquid Planner Time Tracker</title>
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1"> 
		<script src="//ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
		<link href="css/bootstrap.min.css" rel="stylesheet" type="text/css" />
		<script src="js/bootstrap.min.js"></script>
		<link href='http://fonts.googleapis.com/css?family=Cabin:400,700' rel='stylesheet' type='text/css'>
	    <link rel="stylesheet" href="css/bootstrap-theme.css">
        <link rel="stylesheet" href="css/normalize.css">
        <link rel="stylesheet" href="css/main.css">
		<link href="//netdna.bootstrapcdn.com/bootstrap/3.0.0/css/bootstrap-glyphicons.css" rel="stylesheet">
		<script src="js/jquery.runner.js"></script>
		<link href="css/bootstrap-dialog.min.css" rel="stylesheet" type="text/css" />
		<script src="js/bootstrap-dialog.min.js"></script>
		<script src="js/jquery-dateFormat.min.js"></script>
		<link rel="stylesheet" href="js/chosen_v1/chosen.css">
        <script src="js/vendor/modernizr-2.6.2-respond-1.1.0.min.js"></script>

		<script src="https://cdn.flurry.com/js/flurry.js"></script><script>FlurryAgent.startSession("GYHWWJR6STJDM87896TW");</script>
    </head>
    <body>
        <!--[if lt IE 7]>
            <p class="browsehappy">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> to improve your experience.</p>
        <![endif]-->

        <div class="header-container">
            <header class="wrapper clearfix">
                <h1 class="title">
	                <a href="time.php" style="color:white; text-decoration:none"><div style="width:34px;height:34px;display:inline"><img src="images/path-logo-small.png"></div>
	                
	                <span class="hidden-xs"><img src="images/lp-logo.png" alt="lp-logo" width="" height="" id="lplogo" /></span> Time Tracker <span class="hidden-xs"></a>&nbsp;&nbsp;&nbsp;&nbsp;<a href="/liquidplanner/time.php" title="Time Tracker" id="ttpopup" class="glyphicon glyphicon-new-window glyphiconStyleSmall" onclick="location.href='index.php'"></a></span> <img src="images/ajax-loader.gif" width="30" id="loading-indicator" style="display:none" /></h1> 
<div class="status-area"> <span class="hidden-xs"><span style="color: white; font-size: 1em"><a href="lpLogin.php?action=logout" style="color:white"><?=$_SESSION['lpusername']?></a></span></span> <span style="color: white; font-size: 1em"><a href="lpLogin.php?action=logout" style="color:white">Logout <span class="glyphicon glyphicon-log-out" style="color:white;"></span></a></span></div>



            </header>
        </div>

        <div class="main-container">
            <div class="main wrapper clearfix">
	            <div id="timer_feedback"></div>
