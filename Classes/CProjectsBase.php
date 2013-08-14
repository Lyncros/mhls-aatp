<?php

/**
 * Base clas for CProjectsShopOnline and CProjectsPrivateOffer.
 * NOT for generic CProjects.
 */
abstract class CProjectsBase extends CTable {

    private $MilestoneClass;
    private $MilestoneToDosClass;
    public $Milestones;

    public function __construct($Table, $MilestoneClass, $MilestoneToDosClass) {
        parent::__construct($Table);
        $this->MilestoneClass = $MilestoneClass;
        $this->MilestoneToDosClass = $MilestoneToDosClass;
    }

    function OnLoadByID($ID, $Extra) {
        if (parent::OnLoadByID($ID, $Extra) === false) {
            return false;
        }

        return $this->OnInit();
    }

    public function OnInit() {
        $this->Milestones = $this->LoadMilestonesByProjectID($this->ID);
        
        return true;
    }

    public function LoadMilestonesByProjectID($ProjectID) {
        $CProjectMilestones = new $this->MilestoneClass();
        return $CProjectMilestones->LoadByProjectID($ProjectID);
    }
    
    public function LoadUserFullName($UserID) {
        $CUser = new CUsers();
        if ($CUser->OnLoadByID($UserID)) {
            return $CUser->LastName . ", " . $CUser->FirstName;
        }
    }

    public function AddMilestonesAndTodoListsToProject($ProjectID, $MilestonesNames, $Extra) {
        $success = true;

        $CMilestones = new CMilestones();
        if ($CMilestones->OnLoadAll("WHERE Name IN ('" . implode("','", $MilestonesNames) . "')") === FALSE) {
            return true;
        }

        foreach ($CMilestones->Rows as $Milestone) {
            if (!$this->AddMilestone($ProjectID, $Milestone, $Extra)) {
                $success = false;
            }
        }

        return $success;
    }

    private function AddMilestone($ProjectID, $Milestone, $Extra) {
        $Data = array(
            "ProjectsID" => $ProjectID,
            "Name" => $Milestone->Name,
            "CustomerApproval" => $Milestone->CustomerApproval,
            "Summary" => $Milestone->Summary,
            "PlantAllocated" => $Milestone->PlantAllocated,
            "Status" => $Milestone->Status,
        );

        $CMilestones = new $this->MilestoneClass();
        $NewMilestoneID = $CMilestones->Save(0, $Data, $Extra);
        if ($NewMilestoneID === false) {
            return false;
        }

        $ToDoLists = unserialize($Milestone->ToDosLists);

        foreach ($ToDoLists as $ToDoListID) {
            $ToDosList = new CToDosLists();
            if ($ToDosList->OnLoad($ToDoListID) === false) {
                return Array(0, "Error loading To Do List info");
            }

            $ToDoIDs = unserialize($ToDosList->Members);
            foreach ($ToDoIDs as $ToDoID) {
                $CToDo = new CToDos();
                if ($CToDo->OnLoadByID($ToDoID) === false) {
                    continue;
                }

                if ($CToDo->Active == 0) {
                    continue;
                }

                $Data = Array(
                    "MilestoneID" => $NewMilestoneID,
                    "Name" => $CToDo->Name,
                    "Comment" => $CToDo->Comment,
                    "CommentRequired" => $CToDo->CommentRequired,
                );

                $CMilestoneToDos = new $this->MilestoneToDosClass();
                if ($CMilestoneToDos->Save(0, $Data, $Extra) === false) {
                    return false;
                }
            }
        }

        return true;
    }

}

?>
