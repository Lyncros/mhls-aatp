<?
	$SessionControl = CSession::SetSection("MLogin");
?>

<div id="PageNotice" class="PageNotice" style="display: <?=(strlen($this->Parent->PageNotice) > 0 ? "block" : "none");?>;"><?=$this->Parent->PageNotice;?></div>

<div class="LoginBox">
	<div class='Logo'></div>
	<form method="post" action="Login?Action=Login" name="LoginForm" id="LoginForm" style="display: <?=(@$_GET["Action"] == "LostPassword" ? "none" : "block");?>;">
		<input type="text" id="Username" name="Username" placeholder="Username or Email" value="<?=@CForm::MakeSafe($_POST["Username"]);?>" class="InputBox"/>
		<input type="password" id="Password" name="Password" placeholder="Password" class="InputBox"/>

		<div class="LoginButton" onClick="$(this).parent().submit();"></div>
		<br/><br/>
		<!-- <a href="javascript:LostPassword();" style="float: right; padding-right: 50px;">I lost my Password</a>	 -->
	</form>

	<!-- <form method="post" action="Login?Action=LostPassword" id="LostPassword" style="display: <?=(@$_GET["Action"] == "LostPassword" ? "block" : "none");?>;">
		<table width="100%" class="LoginBox_Content">
			<tr>
				<td align="right" width="40%"><b>Enter your Username:</b></td>
				<td width="60%" align="left"><input type="text" id="Username" name="Username" value="<?=@CForm::MakeSafe($_POST["Username"]);?>" class="InputBox"/></td>
			</tr>
		</table>
		<br/><br/><br/>

		<input type="submit" name="Submit" value="" class="SubmitButton"/>
		<br/><br/>
		<a href="javascript:BackToLogin();" style="float: right; padding-right: 50px;">Back to Login</a>	
	</form>-->
</div>

<script language="Javascript">
$(function() {
	<? 
		if(strlen(CCookie::GetValue("MLogin", "Username")) > 0) {
	?>
	$("#Password").focus();
	<?
	 	}else{
	?>
	$("#Username").focus();
	<?
	 	}
	?>
	
	$("#Username").bind("keydown", function(e) {
		if(e.keyCode == 13) $("#LoginForm").submit();
	});
	
	$("#Password").bind("keydown", function(e) {
		if(e.keyCode == 13) $("#LoginForm").submit();
	});	
});
</script>
