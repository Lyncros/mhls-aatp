<?php

/**
 * Module for display the list of ShopOnline Projects.
 */
class MProjectsShopOnline extends MProjectsBase {

    public function __construct() {
        parent::__construct("./Modules/ProjectsShopOnline/Views", "CProjectsShopOnline", "ProjectsShopOnline");

        $this->JSFile = "MProjectsShopOnline.js";

        $this->Twig->addFunction(new Twig_SimpleFunction("GetStatusName", function($Status) {
                    return CProjectsShopOnline::GetStatusNameById($Status);
                }));
    }

    function ProjectsShopOnlineParams($SearchKeywords = "") {
        $Params = Array();
        $CProjectsShop = new CProjectsShopOnline();

        if ($CProjectsShop->OnLoadAllActive($SearchKeywords)) {
            $Projects = $CProjectsShop->Rows->RowsToArrayAllColumns();
            foreach ($Projects as $Project) {
                $Params["projects"][$Project["ID"]] = $Project;
                $Params["projects"][$Project["ID"]]["milestones"] = $CProjectsShop->LoadMilestonesByProjectID($Project["ID"]);
            }
        }


        return $Params;
    }

    public function BuildSaveProjectParameters() {
        return Array(
            "ISBN10" => htmlspecialchars($_POST["ISBN10"]),
            "Status" => intval($_POST["Status"]),
        );
    }
    
    public function Search() {
        $Keywords = htmlspecialchars($_POST["Keywords"]);
        
        $Params = $this->ProjectsShopOnlineParams($Keywords);
        $Params["Keywords"] = $Keywords;
        $template = $this->LoadTemplate("ProjectsShopOnline");
        return Array(1, $template->render($Params));
    }

}

?>
