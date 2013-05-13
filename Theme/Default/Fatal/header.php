<?	
	$FileControlTheme	= $this;
	$ThemePath			= $FileControlTheme->Path;
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
<head><title>Fatal Error</title>
<link rel="shortcut icon" type="image/ico" href="<?=$ThemePath;?>/fatal.ico"/>
<link rel="icon" type="image/ico" href="<?=$ThemePath;?>/fatal.ico"/>
<?
	$FileControlTheme->LoadFile("style.css", CFILE_TYPE_CSS);
?>
</head>
<body>

<center>
<div class="Content">
<img src="<?=$ThemePath;?>/header.jpg"/>

We're sorry, but either the page you are looking for does not exist, or the system has encountered an error. If you feel you reached this page in error, please click back and try again.<br/><br/>

<b>The Technical Details:</b><br/>
