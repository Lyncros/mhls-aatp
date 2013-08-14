<?php

/**
 * Base class for CProjectsShopOnlineMilestones and CProjectsPrivateOfferMilestones.
 * NOT for CProjectsMilestones.
 */
class CProjectsMilestonesBase extends CTable {

    public function __construct($Table) {
        parent::__construct($Table);
    }

    public function LoadByProjectID($ProjectID) {
        return $this->OnLoadAll("WHERE `ProjectsID` = $ProjectID AND `Deleted` IS NULL");
    }

}

?>
