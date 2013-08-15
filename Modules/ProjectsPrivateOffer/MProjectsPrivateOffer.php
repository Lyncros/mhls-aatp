<?php

/**
 * Module for display the list of PrivateOffer Projects.
 */
class MProjectsPrivateOffer extends MProjectsBase {

    public function __construct() {
        parent::__construct("./Modules/ProjectsPrivateOffer/Views", "CProjectsPrivateOffer", "ProjectsPrivateOffer");

        $this->JSFile = "MProjectsPrivateOffer.js";

        $this->Twig->addFunction(new Twig_SimpleFunction("GetStatusName", function($Status) {
                    return CProjectsPrivateOffer::GetStatusNameById($Status);
                }));
    }

    function ProjectsPrivateOfferParams() {
        $Params = Array();
        $CProjectsPrivateOffer = new CProjectsPrivateOffer();

        if ($CProjectsPrivateOffer->OnLoadAllActive()) {
            $Projects = $CProjectsPrivateOffer->Rows->RowsToArrayAllColumns();
            foreach ($Projects as $Project) {
                $Params["projects"][$Project["ID"]] = $Project;
                $Params["projects"][$Project["ID"]]["milestones"] = $CProjectsPrivateOffer->LoadMilestonesByProjectID($Project["ID"]);
            }
        }


        return $Params;
    }

    public function BuildSaveProjectParameters() {
        return Array(
            "ISBN" => htmlspecialchars($_POST["ISBN"]),
            "ScreenshotLink" => htmlspecialchars($_POST["ScreenshotLink"]),
            "Status" => intval($_POST["Status"]),
        );
    }

}

?>
