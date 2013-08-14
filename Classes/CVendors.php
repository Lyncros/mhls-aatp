<?
	//==========================================================================
	/*
		Table for Product Types

		7/26/2012 9:00 AM
	*/
	//==========================================================================
	class CVendors extends CTable {		
        
        function __construct() {
			$this->Table = "Vendors";
		}
        
		public static function OnCron() {
			
		}

		function OnLoad($ID) {
			if(parent::OnLoadByID($ID) === false) {
				return false;
			}

			return true;
		}
        
		function OnInit() {
			
		}
        
        public static function TotalProjectsOf($VendorID) {
            return CTable::NumRows("ProjectsVendors", "WHERE `VendorsID` = $VendorID");
        }
        
        public static function TotalProjectsBySpecialities($VendorID) {
            return CTable::SelectFields("s.`Name`, count(s.`ID`) AS Count","ProjectsVendors","  JOIN `ProjectsSpecialities` ps ON `ProjectsVendors`.`ProjectsID` = ps.`ProjectsID`
                                                                                                    JOIN `Specialities` s ON ps.`SpecialitiesID` = s.`ID`
                                                                                                    WHERE `ProjectsVendors`.`VendorsID` = $VendorID
                                                                                                    GROUP BY s.`ID`");            
            /*if($Result->count()>0){
                return Array();}
            else
                return $Result;*/
        }
	};
?>
