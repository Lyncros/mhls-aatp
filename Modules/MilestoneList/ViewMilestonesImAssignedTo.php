<?php

$Milestones = new CProjectsMilestones();
$UserID = CSecurity::GetUsersID();
	
//FIXME: Remove this hack!
$UserID = 62;
//END FIXME 
	
if ($Milestones->OnLoadAll('WHERE `AssignedTo` = '.$UserID.' AND `Deleted` = 0')) {
	
	foreach($Milestones->Rows as $Row) {
		$Milestone = new CProjectsMilestones();
		if($Milestone->OnLoad($Row->ID)) {
			echo $Milestone->Name;
			echo "<br>";
		}
	}
}

$template = $twig->loadTemplate('MilestonesImAssignedTo.phtml');
$params = array(
    'name' => 'Krzysztof',
    'friends' => array(
        array(
            'firstname' => 'John',
            'lastname' => 'Smith'
        ),
        array(
            'firstname' => 'Britney',
            'lastname' => 'Spears'
        ),
        array(
            'firstname' => 'Brad',
            'lastname' => 'Pitt'
        )
    )
);
$template->display($params);
?>