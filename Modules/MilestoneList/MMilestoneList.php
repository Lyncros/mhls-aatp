<?php

class MMilestoneList extends CTemplateModule {

    function __construct() {
        parent::__construct("./Modules/MilestoneList/Views");
    }

    function OnAJAX($Action) {
        if (parent::CanAccess($Action) == false) {
            return Array(0, "You do not have permission to perform this action");
        }

        return parent::OnAJAX($Action);
    }

    function MilestonesImAssignedToParams() {
        $UserID = CSecurity::GetUsersID();
        $Params = array();
        $Params["total"] = 0;

        //FIXME
        $UserID = 62;
        
        $Milestones = new CProjectsMilestones();
        if ($Milestones->OnLoadByAssignedTo($UserID)) {

            foreach ($Milestones->Rows as $M) {
                if (!array_key_exists($M->ProjectsID, $Params["projects"])) {
                    $Projects = new CProjects();
                    $Projects->OnLoad($M->ProjectsID);

                    $Params["projects"][$M->ProjectsID]["productNumber"] = $Projects->ProductNumber;
                    $Params["projects"][$M->ProjectsID]["school"] = $Projects->School;
                }

                $Params["projects"][$M->ProjectsID]["milestones"][] = $this->BuildMilestoneParams($M);
            }

            $Params["total"] += $Milestones->Rows->count();
        }

        $MilestonesTODOs = new CProjectsMilestonesToDos();
        if ($MilestonesTODOs->OnloadByAssignedTo($UserID)) {

            foreach ($MilestonesTODOs->Rows as $MT) {
                //TODO: Set TODOs data to params array.
            }
            $Params["total"] += $MilestonesTODOs->Rows->count();
        }


        return $Params;
    }

    function CalculateMilestoneTODOsCompletion($MilestoneID) {
        $Completion = -1;
        $MilestonesTODOs = new CProjectsMilestonesToDos();

        if ($MilestonesTODOs->OnLoadByMilestone($MilestoneID)) {
            $TotalToDos = $MilestonesTODOs->Rows->count();

            if ($TotalToDos > 0) {
                $Completes = 0;

                foreach ($MilestonesTODOs->Rows as $ToDo) {
                    if ($ToDo->Complete) {
                        $Completes++;
                    }
                }

                $Completion = round($Completes * 100 / $TotalToDos);
            }
        }

        return $Completion;
    }

    public function IsComplete($Status) {
        return strcasecmp($Status, "Complete") == 0;
    }

    public function BuildMilestoneParams($M) {
        return array(
            "id" => $M->ID,
            "name" => $M->Name,
            "customerApproval" => $M->CustomerApproval,
            "summary" => $M->Summary,
            "estimatedStartDate" => $this->FormatDate($M->EstimatedStartDate),
            "expectedDeliveryDate" => $this->FormatDate($M->ExpectedDeliveryDate),
            "actualDeliveryDate" => $this->FormatDate($M->ActualDeliveryDate),
            "plantAllocated" => $M->PlantAllocated,
            "complete" => $this->IsComplete($M->Status),
            "todosCompletion" => $this->CalculateMilestoneTODOsCompletion($M->ID),
        );
    }

}

?>
