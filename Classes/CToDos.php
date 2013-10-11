<?

//==========================================================================
/*
  Table for To Dos

  7/12/2012 7:54 AM
 */
//==========================================================================
class CToDos extends CTable {

    function __construct() {
        $this->Table = "ToDos";
    }

    //----------------------------------------------------------------------
    function OnInit() {
        
    }

    function OnLoad($ID) {
        if (parent::OnLoadByID($ID) === false) {
            return false;
        }

        return true;
    }

    /*     * *********************************************************************
     * 	Static Functions
     * ******************************************************************** */

    public static function GetStatusList() {
        return Array(
            "Active" => "Active",
            "Complete" => "Complete",
        );
    }

    //----------------------------------------------------------------------
    public static function OnCron() {
        
    }

}

;
?>
