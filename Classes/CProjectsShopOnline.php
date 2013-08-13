<?php

/**
 * Class for table ProjectsShopOnline
 */
class CProjectsShopOnline extends CTable {

    function __construct() {
        $this->Table = "ProjectsShopOnline";
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

    public function Save($ProjectsID, $Data, $Extra) {
        if ($ProjectsID > 0) {
            return CTable::Update($this->Table, $ProjectsID, $Data);
        } else {
            $Data["Created"] = time();
            $Data["CreatedIPAddress"] = $Extra["REMOTE_ADDR"];

            return CTable::Add($this->Table, $Data);
        }
        return false;
    }

    public function GetStatusName() {
        return self::GetStatusNameById($this->Status);
    }

    public function ISBNExists($ISBN) {
        $Count = CTable::NumRows($this->Table, "WHERE `ISBN10` = $ISBN");

        return intval($Count) > 0;
    }
    
    public function OnLoadByID($ID, $Extra = "") {
        return $this->OnLoadByQuery("
            SELECT P.*, CONCAT(U.`LastName`, ', ', U.`FirstName`) as `ContactName` 
            FROM `ProjectsShopOnline` as P 
                JOIN `Users` as U ON P.UsersID = U.ID 
            WHERE P.`ID` = $ID AND `Deleted` = 0");
    }

    public function OnLoadAllActive() {
        return $this->OnLoadByQuery("
            SELECT P.*, CONCAT(U.`LastName`, ', ', U.`FirstName`) as `ContactName` 
            FROM `ProjectsShopOnline` as P 
                JOIN `Users` as U ON P.UsersID = U.ID 
            WHERE `Deleted` = 0");
    }

}

?>
