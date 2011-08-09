<?php
	ob_start();
	if(!isset($_SESSION)){
		session_start();
	}
?>
<!DOCTYPE HTML>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="keywords" content="embr, open source, php, twitter, oauth" />
<meta name="description" content="Vivid Interface for Twitter" />
<meta name="author" content="disinfeqt, JLHwung" />
<link rel="icon" href="img/favicon.ico" />
<link id="css" href="css/main.css" rel="stylesheet" />
<title>Embr / <?php echo $title ?></title>
<?php 
	$myCSS = getDefCookie("myCSS","");
	$old_css = "ul.sidebar-menu li.active a";
	$new_css = "ul.sidebar-menu a.active";
	$myCSS = str_replace($old_css,$new_css,$myCSS);
	$fontsize = getDefCookie("fontsize","13px");
	$Bgcolor = getDefCookie("Bgcolor");
	$Bgimage = getDefCookie("Bgimage",'/img/bg-clouds.png');
	
	if ($title != 'Error' ){
		setcookie('loginPage',$_SERVER['PHP_SELF'],$_SERVER['REQUEST_TIME']+3600*24);
	}
?>
<style type="text/css">
<?php echo $myCSS ?>
a:active, a:focus {outline:none}
body {font-size:<?php echo $fontsize ?> !important;background-color:<?php echo $Bgcolor ?>;background-image: url('<?php echo $Bgimage?>')}
</style>
<script src="js/jquery.js"></script>
<script src="js/mediaPreview.js"></script>
<script src="js/public.js"></script>
</head>
<body>
<div id="shortcutTip" style="display:none"></div>
	<div id="header">
		<div class="wrapper">
			<a href="index.php"><img id="logo" style="float:left" width="155" height="49" src="img/logo.png" /></a>
			<ul id="nav" class="round">
				<?php $scheme=(!isset($_SERVER['HTTPS']) || $_SERVER['HTTPS'] != "on") ? 'http://' : 'https://';
				$base_url=str_replace('http://',$scheme,BASE_URL);
				?> 
				<li><a class="share" title="Drag me to share!" href="javascript:var%20d=document,w=window,f='<?php echo $base_url."/share.php" ?>',l=d.location,e=encodeURIComponent,p='?u='+e(l.href)+'&t='+e(d.title)+'&d='+e(w.getSelection?w.getSelection().toString():d.getSelection?d.getSelection():d.selection.createRange().text)+'&s=bm';a=function(){if(!w.open(f+p,'sharer','toolbar=0,status=0,resizable=0,width=600,height=300,left=175,top=150'))l.href=f+'.new'+p};if(/Firefox/.test(navigator.userAgent))setTimeout(a,0);else{a()}void(0);">Share</a></li>
				<li><a href="index.php">Home</a></li>
				<li><a href="profile.php">Profile</a></li>
				<li><a href="browse.php">Public</a></li>
				<li><a href="setting.php">Settings</a></li>
				<li><a href="logout.php">Logout</a></li>			
			</ul>
		</div>
	</div>
	<div id="content">
		<div class="wrapper">
			<div class="content-bubble-arrow"></div>
				<table cellspacing="0" class="columns">
					<tbody>
						<tr>
							<td id="left" class="column round-left">