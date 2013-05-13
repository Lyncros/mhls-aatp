<?
	$Group = $this->Parent->TableObject;
?>
<h1 class="PageTitle">User Group Information</h1>

<table width="100%" cellspacing="0" cellpadding="0">
<tr>
	<td width="50%" valign="top">

	<table class="CForm_Table">
	<?
		echo CForm::AddTextbox("Name", "Name", $Group->Name, "Please enter a Name.");
		echo CForm::AddYesNo("Active", "Active", ($Group ? $Group->Active : 1));
	?>
	</table>

	</td>
	<td width="50%" valign="top">

	<div style="padding: 10px; background-color: #F5F5F5; width: 95%; margin-top: 20px;" id="UsersTable">
	<?
		$Users = new CUsers();
		if($Users->OnLoadAll("ORDER BY `LastName`, `FirstName`") !== false) {
			echo "<b>Add User:</b> <select name='Users' id='Users'>";
				echo "<option value=''>-- Select One --</option>";

			foreach($Users->Rows as $Row) {
				echo "<option value='".$Row->ID."'>".$Row->LastName.", ".$Row->FirstName."</option>";
			}

			echo "</select> <input type='button' value='Add' onClick=\"MUsersGroups.AddUser($('#Users').val(), $('#Users').selectedTexts()[0]);\"/>";
		}
	?>
	<div id="UserListBox" style="margin-top: 10px;"></div>

	<input type="hidden" id="UserList"></div>
	</div>

	</td>
</tr>
</table>

<br/><br/>
<center><input type="button" onClick="MUsersGroups.Save();" value="Save"/></center>
<br/><br/>


<input type="hidden" name="ID" id="ID" value="<?=intval($_GET["ID"]);?>"/>

<script type="text/javascript">
<?
	if($Group) {
		$UGC = new CTable("UsersGroupsConnections");
		if($UGC->OnLoadAll("WHERE `UsersGroupsID` = ".$Group->ID) !== false) {
			foreach($UGC->Rows as $Row) {
				$User = new CUsers();
				if($User->OnLoadByID($Row->UsersID) === false) continue;

				$Name = str_replace("'", "\\'", $User->LastName.", ".$User->FirstName);

				echo "MUsersGroups.AddUser(".$Row->UsersID.", '".$Name."');\n";
			}
		}
	}
?>
</script>
