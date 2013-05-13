<?
	$SessionControl = CSession::SetSection("MLogin");
?>

<div id="PageNotice" class="PageNotice" style="display: <?=(strlen($this->Parent->PageNotice) > 0 ? "block" : "none");?>;"><?=$this->Parent->PageNotice;?></div>

<div class="LoginBox" style="height: 190px;">
	<b style='color: green;'>Please enter your new password below.</b><br/><br/>

	<form method="post" action="/Login?Action=ResetPassword&Reset=1" name="LoginForm" id="LoginForm" style="display: <?=(@$_GET["Action"] == "LostPassword" ? "none" : "block");?>;">
		<table width="100%" class="LoginBox_Content">
			<tr>
				<td align="right" width="40%"><b>New Password:</b></td>
				<td width="60%" align="left"><input type="password" id="Password1" name="Password1" class="InputBox"/></td>
			</tr>
			<tr>
				<td align="right"><b>New Password (again):</b></td>
				<td align="left"><input type="password" id="Password2" name="Password2" class="InputBox"/></td>
			</tr>
			<!--<tr>
				<td align="right" width="47%"><b>Remember Me:</b></td>
				<td width="53%" align="left"><input type="checkbox" id="RememberMe" name="RememberMe" class="InputBox" <? if(strlen(CCookie::GetValue("MLogin", "Username")) > 0) echo "checked='checked';";?>/></td>
			</tr>-->
		</table>
		<br/>

		<input type="hidden" name="PassCode" value="<?=htmlspecialchars((isset($_GET["PassCode"]) ? $_GET["PassCode"] : $_POST["PassCode"]), ENT_QUOTES);?>"/>

		<input type="submit" name="Submit" value="" class="SubmitButton"/><br/>
		<a href="/Login" style="float: right; padding-right: 50px;">Click Here to Login</a>	
	</form>
</div>

<script language="Javascript">
$(function() {
	$("#Password1").focus();
});
</script>
