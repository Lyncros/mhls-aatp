<?php

class MMilestoneList extends CTemplateModule {

    function __construct() {
        parent::__construct("./Modules/MilestoneList/Views");

        $this->JSFile = "MMilestoneList.js";
    }

    /**
     * Build array parameters to pass to MilestoneImAssignedTo template.
     */
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
        
        $Params["security"]["canEdit"] = CSecurity::$User->CanAccess("Milestones", "Edit");

        return $Params;
    }

    /**
     * Action for start editing a Milestone.
     * @return html for milestone edit form
     */
    function EditMilestone() {
        $Milestone = new CProjectsMilestones();
        if ($Milestone->OnLoadByID($_POST["MilestoneID"])) {

            $Template = $this->LoadTemplate("EditMilestone");

            $Users = CUsers::GetAllAssignableToMilestone();
            
            $Params["m"] = $this->BuildMilestoneParams($Milestone);
            $Params["users"] = Array(0 => "Nobody") + $Users->RowsToAssociativeArrayWithMultipleColumns("LastName,FirstName");
            $Params["security"]["canDelete"] = CSecurity::$User->CanAccess("Milestones", "Delete");

            return Array(1, $Template->render($Params));
        } else {
            return Array(0, "Error loading milestone.");
        }
    }
    
    function SaveMilestone() {
        $MilestoneID = intval($_POST["MilestoneID"]);
        $IsNew = $MilestoneID <= 0;
            
        $CMilestones = new CMilestones();
        $Values = $_POST;
        $Values["REMOTE_ADDR"] = $_SERVER["REMOTE_ADDR"];

        if (!$CMilestones->Save($Values)) {
            return Array(0, "Error ".($IsNew ? "adding" : "updating")." milestone.");
        }
     
        $Params["m"] = array(
            "id"                    => $MilestoneID,
            "name"                  => htmlspecialchars($_POST["Name"]),
            "customerApproval"      => intval($_POST["CustomerApproval"]),
            "summary"               => htmlspecialchars($_POST["Summary"]),
            "estimatedStartDate"    => $this->FormatDate(strtotime($_POST["EstimatedStartDate"])),
            "expectedDeliveryDate"  => $this->FormatDate(strtotime($_POST["ExpectedDeliveryDate"])),
            "actualDeliveryDate"    => $this->FormatDate(strtotime($_POST["ActualDeliveryDate"])),
            "estimatedStartDateTS"  => strtotime($_POST["EstimatedStartDate"]),
            "expectedDeliveryDateTS"=> strtotime($_POST["ExpectedDeliveryDate"]),
            "actualDeliveryDateTS"  => strtotime($_POST["ActualDeliveryDate"]),
            "plantAllocated"        => htmlspecialchars($_POST["PlantAllocated"]),
            "complete"              => intval($_POST["Status"]),
            "todosCompletion"       => $this->CalculateMilestoneTODOsCompletion($MilestoneID),
            "assignedTo"            => intval($_POST["AssignedTo"]),
        );
        $Params["security"]["canEdit"] = CSecurity::$User->CanAccess("Milestones", "Edit");
        
        $Template = $this->LoadTemplate("MilestonesImAssignedToRow");

        return Array(1, Array("Milestone ".($IsNew ? "added" : "saved")." successfully.", $Template->render($Params)));
    }
    
    function DeleteMilestone() {
        $CProjectsMilestones = new CProjectsMilestones();
        $MilestoneID = intval($_POST["MilestoneID"]);
        
        if ($CProjectsMilestones->DeleteMilestone($MilestoneID, CSecurity::GetUsersID(), $_SERVER["REMOTE_ADDR"])) {
            return Array(1, "Milestone deleted successfully");
        }else {
            return Array(0, "Error deleting Milestone");
        }
    }
    
    public function BuildMilestoneParams($M) {
        return array(
            "id"                    => $M->ID,
            "projectsID"            => $M->ProjectsID,
            "name"                  => $M->Name,
            "customerApproval"      => $M->CustomerApproval,
            "summary"               => $M->Summary,
            "estimatedStartDate"    => $this->FormatDate($M->EstimatedStartDate),
            "expectedDeliveryDate"  => $this->FormatDate($M->ExpectedDeliveryDate),
            "actualDeliveryDate"    => $this->FormatDate($M->ActualDeliveryDate),
            "estimatedStartDateTS"  => $M->EstimatedStartDate,
            "expectedDeliveryDateTS"=> $M->ExpectedDeliveryDate,
            "actualDeliveryDateTS"  => $M->ActualDeliveryDate,
            "plantAllocated"        => $M->PlantAllocated,
            "complete"              => $this->IsComplete($M->Status),
            "todosCompletion"       => $this->CalculateMilestoneTODOsCompletion($M->ID),
            "assignedTo"            => $M->AssignedTo,
        );
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

}

?>
