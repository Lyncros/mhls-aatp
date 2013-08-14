<?php

/**
 * Base clas for CProjectsShopOnline and CProjectsPrivateOffer.
 * NOT for generic CProjects.
 */
abstract class CProjectsBase extends CTable {

    private $MilestoneClass;
    private $MilestoneToDosClass;

    public function __construct($Table, $MilestoneClass, $MilestoneToDosClass) {
        parent::__construct($Table);
        $this->MilestoneClass = $MilestoneClass;
        $this->MilestoneToDosClass = $MilestoneToDosClass;
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
