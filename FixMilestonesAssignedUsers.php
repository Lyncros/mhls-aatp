<?php

require_once("Config.php");
require_once("Auto.php");

function PrintUsers($Users, $Title) {
    echo "<h2>" . count($Users) . " Users " . $Title . "</h2>";
    echo "<table>";

    foreach ($Users as $Id => $Username) {
        echo '<tr>';
        echo '<td>' . $Id . "</td><td>" . $Username . "</td>";
        echo '</tr>';
    }
    echo "</table>";
}

new CApp();

$Users = CUsers::GetAllAssignableToMilestone();

$UsersByOrder = Array(0 => "Nobody") + $Users->MultipleColumnRowsToArray("LastName,FirstName");
$UsersById = $Users->RowsToAssociativeArrayWithMultipleColumns("ID", "LastName,FirstName");

echo "<table border='1'><tr><td>";
PrintUsers($UsersByOrder, "By Order");
echo "</td><td>";
PrintUsers($UsersById, "By Id");
echo "</td></tr></table>";

$Milestones = new CProjectsMilestones();

if ($Milestones->OnLoadAll("WHERE AssignedTo IS NOT NULL AND AssignedTo != 0")) {
    echo "<h2>" . count($Milestones->Rows) . " Milestones Fixed</h2>";

    echo "<table border=1>
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Current (maybe wrong) UsersID</th>
            <th>Expected delivery date</th>
            <th>New User</th>
            <th>Action</th>
        <tr>";
    foreach ($Milestones->Rows as $M) {
        $IdAssignedToOK = $UsersById[$UsersByOrder[$M->AssignedTo]];
        echo "<tr>";
        echo "
            <td>" . $M->ID . "</td>
            <td>" . $M->Name . "</td>
            <td>" . $M->AssignedTo . "</td>
            <td>" . date('F j, Y', $M->ExpectedDeliveryDate) . "</td>
            <td>" . $UsersByOrder[$M->AssignedTo] . "</td>";

        if (isset($IdAssignedToOK) && is_numeric($IdAssignedToOK) && $IdAssignedToOK > 0) {
            $Data = Array(
				"ProjectsID"			=> $M->ProjectsID,
				"Name"					=> $M->Name,
				"CustomerApproval"		=> $M->CustomerApproval,
				"Summary"				=> $M->Summary,
				"EstimatedStartDate"	=> $M->EstimatedStartDate,
				"ExpectedDeliveryDate"	=> $M->ExpectedDeliveryDate,
				"ActualDeliveryDate"	=> $M->ActualDeliveryDate,
				"PlantAllocated"		=> $M->PlantAllocated,
				"AssignedTo"			=> $IdAssignedToOK,
				"Status"				=> $M->Status,
			);

            $Extra = Array(
                "REMOTE_ADDR" => $_SERVER["REMOTE_ADDR"],
            );

            $Result = $Milestones->Save($M->ID, $Data, $Extra);
            echo "<td>Assigned " . $IdAssignedToOK . " " . ($Result ? "OK" : "ERROR") . "</td>";
        } else {
            echo "<td>Nothing to do</td>";
        }
        echo "</tr>";
    }
    echo "</table>";
}
?>
