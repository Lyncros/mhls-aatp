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

    public function OnLoadAllActive() {
        return $this->OnLoadByQuery("
            SELECT P.*, CONCAT(U.`LastName`, ', ', U.`FirstName`) as `ContactName` 
            FROM `" . self::TABLE_NAME . "` as P 
                JOIN `Users` as U ON P.UsersID = U.ID 
            WHERE `Deleted` IS NULL");
    }

    public function GetStatusName() {
        return self::GetStatusNameById($this->Status);
    }

    public function ISBNExists($ISBN) {
        $Count = CTable::NumRows($this->Table, "WHERE `ISBN10` = $ISBN");

        return intval($Count) > 0;
    }

    public static function ExistsWithISBN10($ISBN10) {
        $Projects = CTable::Select(self::TABLE_NAME, "WHERE ISBN10 = ISBN10");

        return ($Projects != null) && $Projects->count() > 0;
    }

    public static function GetISBNTypes() {
        return Array("PPK", "Physical", "COMBO", "Virtual/ECOM");
    }

    public static function GetAllStatus() {
        return array("1" => "Live", "2" => "Dead", "3" => "Delayed", "4" => "Completed");
    }

    public static function GetStatusNameById($StatusId) {
        $StatusList = CProjectsShopOnline::GetAllStatus();
        return (array_key_exists($StatusId, $StatusList)) ? $StatusList[$StatusId] : "";
    }

}

?>
