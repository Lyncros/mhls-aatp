<?
	$App = $this->Parent->Parent;

	$FileControlTheme	= $this;
	$ThemePath			= $FileControlTheme->Path;
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
<head><title>YourPromoPeople Administration System</title>
<?
	$FileControlTheme->LoadFile("style.css", CFILE_TYPE_CSS);
	$FileControlTheme->LoadFile("style-full.css", CFILE_TYPE_CSS);
	$FileControlTheme->LoadFile("jqueryui/ui.all.css", CFILE_TYPE_CSS);

	$App->OnRenderCSS();
?>
</head>
<body>

<script type="text/javascript" src="./js/Include.php"></script>
<?
	$App->OnRenderJS();
?>

<center>
<div class="Content_Wrapper_Full">
	<div class="Content_Body_Full">
