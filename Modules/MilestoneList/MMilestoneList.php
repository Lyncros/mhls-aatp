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

        $Milestones = new CProjectsMilestones();
        if ($Milestones->OnLoadByAssignedTo($UserID)) {

            $Milestones = $Milestones->Rows->RowsToArrayAllColumns();

            foreach ($Milestones as $M) {
                $ProjectID = $M["ProjectsID"];
                if (!array_key_exists($ProjectID, $Params["Projects"])) {
                    $Params["Projects"][$ProjectID] = $this->BuildProjectData($M);
                }
                
                $Params["Projects"][$ProjectID]["Milestones"][$M["ID"]] = $this->BuildMilestoneData($M);
            }

            $Params["TotalMilestones"] = count($Milestones);
        }

        
        $MilestonesTODOs = new CProjectsMilestonesToDos();
        if ($MilestonesTODOs->OnloadByAssignedTo($UserID)) {
            
            $MilestonesTODOs = $MilestonesTODOs->Rows->RowsToArrayAllColumns();

            foreach ($MilestonesTODOs as $MT) {
                $ProjectID = $MT["ProjectsID"];
                if (!array_key_exists($ProjectID, $Params["Projects"])) {
                    $Params["Projects"][$ProjectID] = $this->BuildProjectData($MT);
                }
                
                $MilestoneID = $MT["MilestoneID"];
                if (!array_key_exists($MilestoneID, $Params["Projects"][$ProjectID]["Milestones"])) {
                    $Milestones = new CProjectsMilestones();
                    $Milestones->OnLoadByID($MilestoneID);
                    $MDataArray = $Milestones->Rows->RowsToArrayAllColumns();
                    $MData = $MDataArray[$MilestoneID];
                    
                    $Params["Projects"][$ProjectID]["Milestones"][$MilestoneID] = $this->BuildMilestoneData($MData);
                }
                
                $Params["Projects"][$MT["ProjectsID"]]["Milestones"][$MT["MilestoneID"]]["ToDos"][$MT["ID"]] = $MT;
            }
            $Params["TotalToDos"] = count($MilestonesTODOs);
        }
        
        $Params["Security"]["CanEditMilestone"] = CSecurity::$User->CanAccess("Milestones", "Edit");
        $Params["Security"]["CanEditMilestoneToDo"] = CSecurity::$User->CanAccess("MilestonesToDos", "Edit");
        
        return $Params;
    }
    
    function BuildProjectData($Data) {
        return Array(
            "DirectProjectLink" => "http://".CURL::GetDomain()."/Projects?ID=".$Data["ProjectsID"],
            "ProductNumber" => $Data["ProductNumber"],
            "School" => $Data["School"],
            );
    }
    
    function BuildMilestoneData($MData) {
        $Extra = Array(
            "ToDosCompletion" => $this->CalculateMilestoneTODOsCompletion($MData["ID"]),
            "Complete" => $this->IsComplete($MData["Status"]),
        );
        
        return array_merge($MData, $Extra);
    }
    
    /**
     * Action for start editing a Milestone.
     * @return html for milestone edit form
     */
    function EditMilestone() {
        $Milestone = new CProjectsMilestones();
        $MilestoneID = $_POST["MilestoneID"];

        if ($Milestone->OnLoadByID($MilestoneID)) {
			$MilestonsArray = $Milestone->Rows->RowsToArrayAllColumns();
			$Milestone = $MilestonsArray[$MilestoneID];
            $Milestone["Complete"] = $this->IsComplete($Milestone["Status"]);

            $Users = CUsers::GetAllAssignableToMilestone();

            $Params["M"] = $Milestone;
            $Params["Users"] = Array(0 => "Nobody") + $Users->RowsToAssociativeArrayWithMultipleColumns("LastName,FirstName");
            $Params["Security"]["CanDeleteMilestone"] = CSecurity::$User->CanAccess("Milestones", "Delete");

            $Template = $this->LoadTemplate("EditMilestone");
			
            return Array(1, $Template->render($Params));
        } else {
            return Array(0, "Error loading milestone.");
        }
    }
    
    /**
     * Action for start editing a Milestone ToDo.
     * @return html for milestone ToDo edit form
     */
    function EditMilestoneToDo() {
        $ToDo = new CProjectsMilestonesToDos();
        $ToDoID = $_POST["MilestoneToDoID"];
        
        if ($ToDo->OnLoadByID($ToDoID)) {
			$ToDosArray = $ToDo->Rows->RowsToArrayAllColumns(); 
            $ToDo = $ToDosArray[$ToDoID];

            $Users = CUsers::GetAllAssignableToMilestoneTodos();

            $Params["ToDo"] = $ToDo;
            $Params["Users"] = Array(0 => "Nobody") + $Users->RowsToAssociativeArrayWithMultipleColumns("LastName,FirstName");
            $Params["Security"]["CanDeleteMilestoneToDo"] = CSecurity::$User->CanAccess("MilestonesToDos", "Delete");

            $Template = $this->LoadTemplate("EditToDo");
            return Array(1, $Template->render($Params));
        } else {
            return Array(0, "Error loading milestone ToDo.");
        }
    }

    function SaveMilestone() {
        $MilestoneID = intval($_POST["MilestoneID"]);
        $IsNew = $MilestoneID <= 0;
        
        $Data = Array(
            "ProjectsID"			=> intval($_POST["ProjectsID"]),
            "Name"					=> htmlspecialchars($_POST["Name"]),
            "CustomerApproval"		=> intval($_POST["CustomerApproval"]),
            "Summary"				=> htmlspecialchars($_POST["Summary"]),
            "EstimatedStartDate"	=> strtotime($_POST["EstimatedStartDate"]),
            "ExpectedDeliveryDate"	=> strtotime($_POST["ExpectedDeliveryDate"]),
            "ActualDeliveryDate"	=> strtotime($_POST["ActualDeliveryDate"]),
            "PlantAllocated"		=> htmlspecialchars($_POST["PlantAllocated"]),
            "AssignedTo"			=> intval($_POST["AssignedTo"]),
            "Status"				=> (intval($_POST["Status"]) == 0) ? "Active" : "Complete",
        );
        
        $Extra = Array(
            "REMOTE_ADDR"           => $_SERVER["REMOTE_ADDR"],
        );            

        $CMilestones = new CProjectsMilestones();
        if (!$CMilestones->Save($MilestoneID, $Data, $Extra)) {
            return Array(0, "Error " . ($IsNew ? "adding" : "updating") . " milestone.");
        }

        $Params["M"] = $Data;
        $Params["M"]["ID"] = $MilestoneID;
        $Params["M"]["ToDosCompletion"] = $this->CalculateMilestoneTODOsCompletion($MilestoneID);
        $Params["M"]["Complete"] = ($Data["Status"] == 1);
        $Params["Security"]["CanEditMilestone"] = CSecurity::$User->CanAccess("Milestones", "Edit");

        $Template = $this->LoadTemplate("MilestonesImAssignedToRow");

        return Array(1, Array("Milestone " . ($IsNew ? "added" : "saved") . " successfully.", $Template->render($Params)));
    }
    
    function SaveMilestoneToDo() {
        $ToDoID = intval($_POST["ToDoID"]);
        $IsNew = $ToDoID <= 0;

        $Data = Array(
            "MilestoneID"       => intval($_POST["MilestoneID"]),
            "Name"              => htmlspecialchars($_POST["Name"]),
            "Complete"          => intval($_POST["Complete"]),
            "Comment"           => htmlspecialchars($_POST["Comment"]),
            "CommentRequired"   => intval($_POST["CommentRequired"]),
            "AssignedTo"        => intval($_POST["AssignedTo"]),
        );
        
        $Extra = Array (
            "REMOTE_ADDR"       => $_SERVER["REMOTE_ADDR"],
        );

        $CMilestones = new CProjectsMilestonesToDos();
        if (!$CMilestones->Save($ToDoID, $Data, $Extra)) {
            return Array(0, "Error " . ($IsNew ? "adding" : "updating") . " milestone.");
        }

        $Params["ToDo"] = $Data;
        $Params["ToDo"]["ID"] = $ToDoID;
        $Params["Security"]["CanEditMilestoneToDo"] = CSecurity::$User->CanAccess("MilestonesToDos", "Edit");

        $Template = $this->LoadTemplate("MilestonesImAssignedToToDoRow");

        return Array(1, Array("Milestone " . ($IsNew ? "added" : "saved") . " successfully.", $Template->render($Params)));
    }

    function DeleteMilestone() {
        $CProjectsMilestones = new CProjectsMilestones();
        $MilestoneID = intval($_POST["MilestoneID"]);

        if ($CProjectsMilestones->OnDeleteLogic($MilestoneID, CSecurity::GetUsersID(), $_SERVER["REMOTE_ADDR"])) {
            return Array(1, "Milestone deleted successfully");
        } else {
            return Array(0, "Error deleting Milestone");
        }
    }
    
     function DeleteMilestoneToDo() {
        $CProjectsMilestonesToDos = new CProjectsMilestonesToDos();
        $MilestoneToDoID = intval($_POST["MilestoneToDoID"]);

        if ($CProjectsMilestonesToDos->OnDeleteLogic($MilestoneToDoID, CSecurity::GetUsersID(), $_SERVER["REMOTE_ADDR"])) {
            return Array(1, "Milestone ToDo deleted successfully");
        } else {
            return Array(0, "Error deleting Milestone ToDo");
        }
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
		else
		{
			$Completion = 0;
		}			

        return $Completion;
    }

    public function IsComplete($Status) {
        return strcasecmp($Status, "Complete") == 0;
    }
}

?>
