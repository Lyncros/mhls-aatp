<?
	$ShowHidden = (@$_GET["ShowHidden"] == 1 ? true : false);
?>

<table width="100%" style="padding-right: 20px; padding-left: 10px; padding-top: 10px;" cellspacing="0" cellpadding="0">
<tr>
	<td valign="top" align="left" style="line-height: 19px;">
		Show Hidden: <div class="ToggleOnOff <?=($ShowHidden ? "ToggleOn" : "ToggleOff");?>" onClick="CModule.Load('Alerts', {'ShowHidden' : '<?=((int)(!$ShowHidden));?>'});"></div>
	</td>
	<td valign="top" align="right">
		<?
			if(CSecurity::IsAdmin()) {
		?>
		<div class="ButtonAdd" onClick="MAlerts.Window_AddEdit(0);"></div>
		<?
			}
		?>
	</td>
</tr>
</table>

<table class="Alerts" cellspacing="0" cellpadding="0">
<tr>
	<th class="AlertsHeader AlertsHeaderFirst">Alerts</th>

	<th class="AlertsHeader">&nbsp;</th>
</tr>
<?
	$Alerts = new CAlerts();
	if($Alerts->OnLoadByUsersID(CSecurity::GetUsersID(), $ShowHidden) !== false) {
		foreach($Alerts->Rows as $Row) {
?>
<tr>
	<td valign="top" class="AlertsCellName">
	<h1><?=$Row->Title;?></h1>

	<?=date("n/d/Y", $Row->Timestamp);?>

	<?
		if(CSecurity::IsAdmin()) {
	?>
	<br/><br/>
	<div class="Icon_Delete" onClick="MAlerts.Window_Delete(<?=$Row->ID;?>);" style="float: right;"></div>
	<div class="Icon_Edit" onClick="MAlerts.Window_AddEdit(<?=$Row->ID;?>);" style="float: right; margin-right: 4px;"></div>
	<?
		}
	?>
	</td>

	<td valign="top" class="AlertsCell" style="background-color: <?=($Row->Read == 1 ? "#EEEEEE" : "#FFFFFF");?>" rel="AlertRow" id="Alert<?=$Row->ID;?>">
	<div class='AlertsCellHiddenTag' style="<?=($Row->Hidden == 1 ? "display: block" : "display: none");?>">Hidden</div>

	<?=$Row->Content;?>
	<br/><br/><br/>
	<a href="javascript:MAlerts.ToggleHide('Alert<?=$Row->ID;?>');" rel="Hide" style="float: right;"><?=($Row->Hidden == 1 ? "Unhide" : "Hide");?></a>
	<a href="javascript:MAlerts.SetRead('Alert<?=$Row->ID;?>', 0);" rel="Read" style="float: right; margin-right: 8px; <?=($Row->Read == 1 ? "display: block" : "display: none");?>">Mark Unread</a>
	</td>
</tr>
<?
		}
	}else{
?>
<tr>
	<td class="AlertsCellName CSearch_NoResults" colspan="2" style="font-size: 12px;">You have no Alerts.</td>
</tr>
<?
	}
?>
</table>

<input type="hidden" id="ShowHidden" value="<?=$ShowHidden;?>"/>
