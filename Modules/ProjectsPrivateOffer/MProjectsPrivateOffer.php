<?php

/**
 * Module for display the list of PrivateOffer Projects.
 */
class MProjectsPrivateOffer extends MProjectsBase {

    public function __construct() {
        parent::__construct("./Modules/ProjectsPrivateOffer/Views", "CProjectsPrivateOffer");

        $this->JSFile = "MProjectsPrivateOffer.js";
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

}

?>
