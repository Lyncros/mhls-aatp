<?php

/**
 * Description of CProjectsShopOnline
 *
 * @author jarias
 */
class CProjectsShopOnline extends CTable {

    function __construct() {
        $this->Table = "ProjectsShopOnline";
    }

    public static function GetISBNTypes() {
        return Array("PPK", "Physical", "COMBO", "Virtual/ECOM");
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

}

?>
