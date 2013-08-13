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

        $Params["projects"] = $CProjectsShop->OnLoadAllActive();

        return $Params;
    }
    
    function ShowProjectDetails() {
        $ProjectShop = new CProjectsShopOnline();
        $ProjectID = intval($_POST["ID"]);
        
        //Editing is not allowed in this phase
        $Params = Array(
            "canEdit"   => false, //CSecurity::$User->CanAccess("ProjectDetails", "Edit"),
            "canDelete" => false, //CSecurity::$User->CanAccess("ProjectDetails", "Delete"),
            "project"   => $ProjectShop->OnLoadByID($ProjectID),
        );
        
        return Array(1, $this->LoadTemplate("ProjectShopOnlineDetails")->render($Params));
    }

}

?>
