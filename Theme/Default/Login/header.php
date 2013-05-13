<?
	$FileControlTheme	= $this;
	$ThemePath			= $FileControlTheme->Path;
?>
<html>
<head><title>The Almighty App For All Things Project</title></head>
<?
	$FileControlTheme->LoadFile("style.css", CFILE_TYPE_CSS);
?>
<body>

<script language="JavaScript" type="text/javascript" src="./js/jquery/jquery.js"></script>
<script type="text/javascript" language="JavaScript">
function LostPassword() {
	$("#LoginForm").fadeOut("fast", function() {
		$("#LostPassword").fadeIn("fast");
	});
}

function BackToLogin() {
	$("#LostPassword").fadeOut("fast", function() {
		$("#LoginForm").fadeIn("fast");
	});
}
</script>
