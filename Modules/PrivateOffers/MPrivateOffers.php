<?php

	class MPrivateOffers extends CUnauthorizedModule {
		
		function __construct() {
			parent::__construct("./Modules/PrivateOffers/Views");
		}
		
		function FormParams() {
			$milestones = new CProjectsMilestones();
			$userID = CSecurity::GetUsersID();
			$params = array();
			
			//FIXME
			$userID = 62;
	
			if ($milestones->OnLoadAll('WHERE `AssignedTo` = '.$userID.' AND `Deleted` = 0')) {
				$params["total"] = $milestones->Rows->count();
				
				foreach($milestones->Rows as $m) {
					$params["milestones"][] = array(
						"id"					=> $m->ID,
						"projectsId"			=> $m->ProjectsID,
						"name"					=> $m->Name, 
						"customerApproval"		=> $m->CustomerApproval,
						"summary"				=> $m->Summary,
						"estimatedStartDate"	=> $this->FormatDate($milestone->EstimatedStartDate),
						"expectedDeliveryDate"	=> $this->FormatDate($milestone->ExpectedDeliveryDate),
						"actualDeliveryDate"	=> $this->FormatDate($milestone->ActualDeliveryDate),
						"plantAllocated"		=> $m->PlantAllocated,
						"status"				=> $m->Status,
						"completion"			=> 50,
					);
				}
			}
				
			return $params;
		}

	};

	//==========================================================================
?>
