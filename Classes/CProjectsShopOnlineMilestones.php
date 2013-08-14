<?php

/**
 * Class for table ProjectsShopOnlineMilestones
 */
class CProjectsShopOnlineMilestones extends CTable {
    
    public function __construct() {
        parent::__construct("ProjectsShopOnlineMilestones");
    }
    
    public function LoadByProjectID($ProjectID) {
        return $this->OnLoadAll("WHERE `ProjectsID` = $ProjectID AND `Deleted` IS NULL");
    }
    
}

?>
