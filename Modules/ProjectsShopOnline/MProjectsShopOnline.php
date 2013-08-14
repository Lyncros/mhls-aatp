<?php

/**
 * Module for display the list of ShopOnline Projects.
 */
class MProjectsShopOnline extends CTemplateModule {

    public function __construct() {
        parent::__construct("./Modules/ProjectsShopOnline/Views");

        $this->JSFile = "MProjectsShopOnline.js";

        $this->Twig->addFunction(new Twig_SimpleFunction("GetStatusName", function($Status) {
                    return CProjectsShopOnline::GetStatusNameById($Status);
                }));
    }

    function ProjectsShopOnlineParams() {
        $Params = Array();
        $CProjectsShop = new CProjectsShopOnline();

        if ($CProjectsShop->OnLoadAllActive()) {
            $Projects = $CProjectsShop->Rows->RowsToArrayAllColumns();
            foreach ($Projects as $Project) {
                $Params["projects"][$Project["ID"]] = $Project;
                $Params["projects"][$Project["ID"]]["milestones"] = $CProjectsShop->LoadMilestonesByProjectID($Project["ID"]);
            }
        }


        return $Params;
    }

    function ShowProjectDetails() {
        $ProjectID = intval($_POST["ID"]);
        return Array(1, $this->RenderProjectDetails($ProjectID));
    }

    function RenderProjectDetails($ProjectID) {
        $ProjectShop = new CProjectsShopOnline();
        if ($ProjectShop->OnLoadByID($ProjectID)) {
            $ProjectDataArray = $ProjectShop->Rows->RowsToArrayAllColumns();

            //Deleting is not allowed in this phase
            $Params = Array(
                "canEdit" => CSecurity::$User->CanAccess("ProjectDetails", "Edit"),
                "canDelete" => FALSE, //CSecurity::$User->CanAccess("ProjectDetails", "Delete"),
                "project" => $ProjectDataArray[$ProjectID],
            );

            $Params["project"]["milestones"] = $ProjectShop->Milestones;
            $Params["project"]["milestoneCompletion"] = $this->CalculateProjectMilestonesCompletion($ProjectShop->Milestones);
        }

        return $this->LoadTemplate("ProjectShopOnlineDetails")->render($Params);
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

        $CMilestones = new CProjectsShopOnlineMilestones();
        if ($CMilestones->Save($MilestoneID, $Data, $Extra) === FALSE) {
            return Array(0, "Error " . ($IsNew ? "adding" : "updating") . " milestone.");
        }

        return Array(1, Array("Milestone " . ($IsNew ? "added" : "saved") . " successfully.", $this->RenderProjectDetails($ProjectID)));
    }

    /*
     * TODO: Reuse this copy & paste code from MMilestoneList.php
     */

    private function CalculateProjectMilestonesCompletion($Milestones) {
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
