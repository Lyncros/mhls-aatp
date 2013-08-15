<?

//==========================================================================
/*
  Table for Product Types

  7/26/2012 9:00 AM
 */
//==========================================================================
class CProjectsShopOnlineStoreFrontItems extends CTable {

    function __construct() {
        parent::__construct("ProjectsShopOnlineStoreFrontItems");
    }

    public static function OnCron() {
        
    }

    function OnLoad($ID) {
        if (parent::OnLoadByID($ID) === false) {
            return false;
        }

        return true;
    }

    function OnInit() {
        
    }

    public function OnLoadByProjectID($ProjectsID) {
        return $this->OnLoadAll("WHERE `ProjectsID` = $ProjectsID");
    }
}

;
?>
