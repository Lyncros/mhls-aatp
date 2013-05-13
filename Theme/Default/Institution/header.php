<?php // -*- php -*-
	$App = $this->Parent->Parent;
?>
<!DOCTYPE html>
<html>
<head><title>The Almighty App For All Things Project</title>
	<?
		$this->LoadFile("style.css", CFILE_TYPE_CSS);
	?>
	<script type="text/javascript" src="http://use.typekit.com/srq1ehx.js"></script>
	<script type="text/javascript">try{Typekit.load();}catch(e){}</script>
	
	<script type="text/javascript" src="./js/Include.php"></script>
	<? $App->OnRenderJS(); ?>
</head>
<body>

<div id="CPageNotice"></div>
<div id="CLoading">Loading</div>

<center>
<div class="HeaderWrapper">
	<div class="Header">
		<div class="HeaderLogo" onClick="CModule.Load('Dashboard');"></div>
		<div class="HeaderWelcome"></div>
	</div>
</div>
<div class="BodyWrapper">
	<div class="Body">
		<div class="Content">
