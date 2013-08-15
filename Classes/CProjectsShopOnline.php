<?php

/**
 * Class for table ProjectsShopOnline
 */
class CProjectsShopOnline extends CProjectsBase {

    const TABLE_NAME = "ProjectsShopOnline";

    public $ContactName;

    function __construct() {
        parent::__construct(self::TABLE_NAME, "CProjectsShopOnlineMilestones", "CProjectsShopOnlineMilestonesToDos");
    }

    public function OnInit() {
        parent::OnInit();
        $this->ContactName = $this->LoadUserFullName($this->UsersID);

        return true;
    }
    
    public function AllValues() {
        $temp = $this->Current;
        $temp["ContactName"] = $this->ContactName;
        
        return $temp;
    }

    public function OnLoadAllActive($SearchKeywords) {
        $AndClause = "";
        if (!empty($SearchKeywords)) {
            $AndClause = "AND (P.ISBN10 LIKE '%$SearchKeywords%') ";
        }
        
        return $this->OnLoadByQuery("
            SELECT P.*, CONCAT(U.`LastName`, ', ', U.`FirstName`) as `ContactName` 
            FROM `" . self::TABLE_NAME . "` as P 
                JOIN `Users` as U ON P.UsersID = U.ID 
            WHERE `Deleted` IS NULL $AndClause");
    }

    public function GetStatusName() {
        return self::GetStatusNameById($this->Status);
    }

    public function ISBNExists($ISBN) {
        $Count = CTable::NumRows($this->Table, "WHERE `ISBN10` = $ISBN");

        return intval($Count) > 0;
    }
    
    public function AddStoreFrontInfoItems($ProjectID, $StoreFrontInfoItems) {
        $success = true;
        
        foreach ($StoreFrontInfoItems as $StoreFrontInfoItem) {
            if (!$this->AddStoreFrontInfoItem($ProjectID, $StoreFrontInfoItem)) {
                $success = false;
            }
        }

        return $success;
    }
    
    private function AddStoreFrontInfoItem($ProjectID, $StoreFrontInfoItem) {
        $Data = array(
            "ProjectsID" => $ProjectID,
            "ISBN"       => $StoreFrontInfoItem['ISBN'],
            "Author"     => $StoreFrontInfoItem['Author'],
            "Virtual"    => $StoreFrontInfoItem['Virtual']
        );

        $CStoreFrontInfoItems = new CProjectsShopOnlineStoreFrontItems();
        
        $NewStoreFrontInfoItem = $CStoreFrontInfoItems->Save(0, $Data);
        
        return !($NewStoreFrontInfoItem === false);
    }
    
    public static function ExistsWithISBN10($ISBN10) {
        $Projects = CTable::Select(self::TABLE_NAME, "WHERE ISBN10 = $ISBN10");

        return ($Projects != null) && $Projects->count() > 0;
    }

    public static function GetISBNTypes() {
        return Array("1" => "PPK", "2" => "Physical", "3" => "COMBO", "4" => "Virtual/ECOM");        
    }

    public static function GetAllStatus() {
        return array("1" => "Live", "2" => "Dead", "3" => "Delayed", "4" => "Completed");
    }

    public static function GetStatusNameById($StatusId) {
        $StatusList = self::GetAllStatus();
        return (array_key_exists($StatusId, $StatusList)) ? $StatusList[$StatusId] : "";
    }
}

?>