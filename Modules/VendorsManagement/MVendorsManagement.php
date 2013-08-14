<?php

/**
 * Module for display the list of Vendors.
 */
class MVendorsManagement extends CTemplateModule {
	function __construct() {
		parent::__construct("./Modules/VendorsManagement/Views");
		
		$this->JSFile		= "MVendorsManagement.js";		
		
	}
    
    function IndexParams() {
        $Params = Array();
        $Params["Permissions"] = Array('Access');
        
        $VendorsInfo = Array();
        $Vendors = new CVendors();
        
        foreach ($Vendors->OnLoadAll() as $Vendor)
        {
            $VendorInfo = Array();
            $VendorInfo['vendor'] = $Vendor->Current;
            $VendorInfo['totalProjects'] = CVendors::TotalProjectsOf($Vendor->ID);
            $VendorInfo['specialityColumns'] = $this->GetVendorsProjectsBySpecialitiesInColumns($Vendor->ID, 3);
                    
            $VendorsInfo[] = $VendorInfo;
        }
            
        $Params["vendorsInfo"] = $VendorsInfo;
  
		return $Params;
	}
    
    private function GetVendorsProjectsBySpecialitiesInColumns($VendorID, $ColumnsNumber)
    {
        $Result = Array();
        for($i=0;$i<$ColumnsNumber;$i++)
            $Result[$i] = Array();
        
        $Counter = 0;
        $CurrentColumn = 0;
        
        $VendorsProjectsBySpecialities = CVendors::TotalProjectsBySpecialities($VendorID);
        $TotalSpecialitiesPerColumn = ceil($VendorsProjectsBySpecialities->count() / $ColumnsNumber);
                
        foreach ($VendorsProjectsBySpecialities as $Speciality)
        {            
            if($Counter < $TotalSpecialitiesPerColumn)
                $Counter++;
            else
            {
                $Counter = 1;
                $CurrentColumn++;            
            }
            
            $Result[$CurrentColumn][] = Array ( "Name" => $Speciality->Name, "Count" => $Speciality->Count);
        }        
        
        return $Result;
    }
}

?>
