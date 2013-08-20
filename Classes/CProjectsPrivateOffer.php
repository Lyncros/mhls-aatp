<?php

/**
 * Description of CProjectsPrivateOffer
 *
 * @author nbuttarelli
 */
class CProjectsPrivateOffer extends CProjectsBase {

    const TABLE_NAME = "ProjectsPrivateOffer";

    public $LSCUserName;
    public $CreativeContactName;

    function __construct() {
        parent::__construct(self::TABLE_NAME, "CProjectsPrivateOfferMilestones", "CProjectsPrivateOfferMilestonesToDos");
    }

    public function OnInit() {
        parent::OnInit();

        //Load internal vars.
        $this->LSCUserName = $this->LoadUserFullName($this->LscID);
        $this->CreativeContactName = $this->LoadUserFullName($this->CreativeContactID);

        return true;
    }
    
    public function OnLoadAllActive($SearchKeywords) {
        $AndClause = "";
        if (!empty($SearchKeywords)) {
            $AndClause = "AND (P.ISBN LIKE '%$SearchKeywords%' OR P.ProjectNumber LIKE '%$SearchKeywords%') ";
        }
        
        return $this->OnLoadByQuery("
            SELECT P.*, CONCAT(U.`LastName`, ', ', U.`FirstName`) as `CreativeContactName` 
            FROM `" . $this->Table . "` as P 
                JOIN `Users` as U ON P.CreativeContactID = U.ID 
            WHERE `Deleted` IS NULL $AndClause");
    }

    public static function GetConnectionTypes() {
        return Array("Connect", "Connect Plus", "Both", "ConnectPlus");
    }

    public static function GetPriceTypes() {
        return Array("Connect", "Connect Plus", "ConnectPlus");
    }
    
    public static function GetAllStatus() {
        return array("1" => "In Progress", "4" => "Completed");
    }
    
    public static function GetStatusNameById($StatusId) {
        $StatusList = self::GetAllStatus();
        return (array_key_exists($StatusId, $StatusList)) ? $StatusList[$StatusId] : "";
    }

    public static function ExistsWithProjectNumber($ProjectNumber) {
        $Projects = CTable::Select(self::TABLE_NAME, "WHERE ProjectNumber = $ProjectNumber");
        return ($Projects != null) && $Projects->count() > 0;
    }

    public static function ExistsWithISBN($ISBN) {
        $Projects = CTable::Select(self::TABLE_NAME, "WHERE ISBN = $ISBN");

        return ($Projects != null) && $Projects->count() > 0;
    }

    public static function ExistsWithConnectPlusISBN($ConnectPlusISBN) {
        if ($ConnectPlusISBN != null) {
            $Projects = CTable::Select(self::TABLE_NAME, "WHERE ConnectPlusISBN = $ConnectPlusISBN");

            return ($Projects != null) && $Projects->count() > 0;
        } else {
            return false;
        }
    }
}

?>