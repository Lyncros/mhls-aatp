<?php

/**
 * Base class for MProjectsPrivateOffer and MrojectsShopOnline.
 */
abstract class MProjectsBase extends CTemplateModule {

    private $ProjectsClass;
    private $MilestonesClass;
    private $MilestoneToDosClass;
    private $SecName;

    public function __construct($ViewsFolder, $ProjectsClassPrefix, $SecName) {
        parent::__construct($ViewsFolder);

        $this->ProjectsClass = $ProjectsClassPrefix;
        $this->MilestonesClass = $ProjectsClassPrefix . "Milestones";
        $this->MilestoneToDosClass = $ProjectsClassPrefix . "MilestonesToDos";
        $this->SecName = $SecName;
    }

    function ShowProjectDetails() {
        $ProjectID = intval($_POST["ID"]);
        return Array(1, $this->RenderProjectDetails($ProjectID));
    }

    function RenderProjectDetails($ProjectID) {
        $CProject = new $this->ProjectsClass();
        if ($CProject->OnLoadByID($ProjectID)) {
            $Params = Array(
                "project" => $CProject,
                "statusList" => call_user_func(array($this->ProjectsClass, 'GetAllStatus')),
                "milestoneCompletion" => $this->CalculateProjectMilestonesCompletion($CProject->Milestones),
                "canEdit" => CSecurity::$User->CanAccess($this->SecName, "Edit"),
                "canDelete" => CSecurity::$User->CanAccess($this->SecName, "Delete"),
            );
            
        }

        return $this->LoadTemplate("ProjectDetails")->render($Params);
    }

    function SaveMilestone() {
        $MilestoneID = intval($_POST["MilestoneID"]);
        $ProjectID = intval($_POST["ProjectID"]);
        $IsNew = $MilestoneID <= 0; //right now, always update an existent milestone. This must be false.
        //right now only save Status
        $Data = Array(
            "Status" => (intval($_POST["Status"]) == 0) ? "Active" : "Complete",
        );

        $Extra = Array(
            "UsersID" => CSecurity::$User->ID,
            "IPAddress" => $_SERVER["REMOTE_ADDR"],
        );

        $CMilestones = new $this->MilestonesClass();
        if ($CMilestones->Save($MilestoneID, $Data, $Extra) === FALSE) {
            return Array(0, "Error " . ($IsNew ? "adding" : "updating") . " milestone.");
        }

        return Array(1, Array("Milestone " . ($IsNew ? "added" : "saved") . " successfully.", $this->RenderProjectDetails($ProjectID)));
    }
    
    abstract function BuildSaveProjectParameters();


    public function SaveProject() {
        $ProjectID = intval($_POST["ProjectID"]);
        $Data = $this->BuildSaveProjectParameters();
        $Extra = $this->BuildSaveParameters();

        $CProject = new $this->ProjectsClass();
        if ($CProject->Save($ProjectID, $Data, $Extra) === FALSE) {
            return Array(0, "Error updating project.");
        }

        return Array(1, Array("Project saved successfully.", $this->RenderProjectDetails($ProjectID)));
    }
    
    

    protected function CalculateProjectMilestonesCompletion($Milestones) {
        $Completion = 0;
        $TotalMilestones = count($Milestones);

        if ($TotalMilestones > 0) {
            $Completes = 0;

            foreach ($Milestones as $Milestone) {
                if (strcasecmp($Milestone->Status, "Complete") === 0) {
                    $Completes++;
                }
            }

            $Completion = round($Completes * 100 / $TotalMilestones);
        }

        return $Completion;
    }

}

?>
