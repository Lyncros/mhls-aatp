<?php

class MProjectCreatorHome extends CUnauthorizedModule {

	// Shop Online Columns
    const SO_ISBN10_COL 			= 0;
    const SO_AUTHOR_COL 			= 1;
    const SO_REQUESTER_NAME_COL 	= 2;
    const SO_REQUESTER_EMAIL_COL 	= 3;
    const SO_DATE_NEEDED_COL 		= 4;
    const SO_CREATIVE_USER_COL 		= 5;
    const SO_COMMENTS_COL 			= 6;
    const SO_CUSTOM_COVER_URL_COL 	= 7;
    const SO_ISBN_TYPE_COL 			= 8;

	// Private Offer Columns
	const PO_PROJECT_NUMBER_COL 	= 0;
	const PO_ISBN_COL 				= 1;
	const PO_CONNECT_PLUS_ISBN_COL 	= 2;
	const PO_REQUESTER_NAME_COL 	= 3;
    const PO_REQUESTER_EMAIL_COL 	= 4;
	const PO_LSC_COL				= 5;
	const PO_DATE_NEEDED_COL 		= 6;
	const PO_CREATIVE_CONTACT_COL 	= 7;
	const PO_CONNECTION_TYPE_COL	= 8;
	const PO_SCHOOL_NAME_COL		= 9;
	const PO_SCHOOL_CITY_COL		= 10;
	const PO_CAMPUS_NAME_COL		= 11;
	const PO_DURATION_COL			= 12;
	const PO_PRICE_TYPE_COL			= 13;
	const PO_PRICE_COL				= 14;
	
    function __construct() {
        parent::__construct("./Modules/ProjectCreatorHome/Views");
    }

    private function BuildDefaultParams() {
        return Array(
            "menuItems" => CSidebarMenu::BuildProjectsFormsSideMenu(),
            "jsFiles" => Array("./Modules/ProjectCreatorHome/MProjectCreatorHome.js"),
            "cssFiles" => Array("./Modules/ProjectCreatorHome/style.css"),
        );
    }

    function IndexParams() {
        $data = array();
        $data['activeSidebarNode'] = '';

        return array_merge($data, $this->BuildDefaultParams());
    }

	///////////////////////////////////////////////
	////////         SHOP ONLINE      /////////////
	///////////////////////////////////////////////
	
    function ShopOnlineParams() {
        $data = array();

        $Users = CUsers::GetAllAssignableToMilestone();
        $UsersArray = Array(0 => "Nobody") + $Users->RowsToAssociativeArrayWithMultipleColumns("LastName,FirstName");
        
        $data["Users"] = $UsersArray;
        $data["ISBNTypes"] = CProjectsShopOnline::GetISBNTypes();
        $data['activeSidebarNode'] = 'ShopOnline';

        return array_merge($data, $this->BuildDefaultParams());
    }

    function CreateShopOnline() {
        $Data = Array(
            "ISBN10" => htmlspecialchars($_POST["ISBN10"]),
            "Author" => htmlspecialchars($_POST["Author"]),
            "RequesterName" => htmlspecialchars($_POST["RequesterName"]),
            "RequesterEmail" => htmlspecialchars($_POST["RequesterEmail"]),
            "DateNeeded" => strtotime($_POST["DateNeeded"]),
            "UsersID" => intval($_POST["UsersID"]),
            "Comments" => htmlspecialchars($_POST["Comments"]),
            "CustomCoverURL" => htmlspecialchars($_POST["CustomerCoverURL"]),
            "ISBNType" => htmlspecialchars($_POST["ISBNType"]),
        );

        $Extra = Array(
            "REMOTE_ADDR" => $_SERVER["REMOTE_ADDR"],
        );

        $ProjectShop = new CProjectsShopOnline();
        if ($ProjectShop->Save(0, $Data, $Extra)) {
            return Array(1, "Project Shop Online created successfully.");
        } else {
            return Array(0, "Error creating Project Shop Online.");
        }
    }

    function UploadShopOnlineFile() {
        $FilenameOriginal = $_POST["FilenameOriginal"];
        $Filepath = CData::$PathTemp.$_POST["Filename"];

        $FileContents = file($Filepath, FILE_IGNORE_NEW_LINES);
        $ErrorDetails = Array();
        $CreatedProjects = 0;
        $RowsProcessed = 0;

        foreach ($FileContents as $Line) {
            $Values = $this->ParseCSVLine($Line);

            $ISBN = $Values[self::SO_ISBN10_COL];
            //Ignore the titles line
            if (strripos($ISBN, "ISBN") === FALSE) {
                
                $RowsProcessed++;

                $Errors = Array();

                $Email = $Values[self::SO_REQUESTER_EMAIL_COL];
                if (!CValidate::Email($Email)) {
                    $Errors[] = "Invalid e-mail address: '$Email'.\n";
                }

                $DateNeededStr = $Values[self::SO_DATE_NEEDED_COL];
                $DateNeeded = strtotime($DateNeededStr);
                if ($DateNeeded === FALSE) {
                    $Errors[] = "Invalid date needed: '$DateNeededStr'.\n";
                }

                $Fullname = $Values[self::SO_CREATIVE_USER_COL];
                $UsersID = CUsers::GetIdUserWithName($Fullname);

                if (!is_int($UsersID) || (is_int($UsersID) && $UsersID < 1)) {
                    $Errors[] = "Creative Analyst not found for name: '$Fullname'.\n";
                }

                $ISBNType = $Values[self::SO_ISBN_TYPE_COL];
                if (array_search($ISBNType, CProjectsShopOnline::GetISBNTypes()) === FALSE) {
                    $Errors[] = "Invalid ISBN Type: '$ISBNType'.\n";
                }

                if (empty($Errors)) {
                    $Data = Array(
                        "ISBN10" => $ISBN,
                        "Author" => $Values[self::SO_AUTHOR_COL],
                        "RequesterName" => $Values[self::SO_REQUESTER_NAME_COL],
                        "RequesterEmail" => $Email,
                        "DateNeeded" => $DateNeeded,
                        "UsersID" => $UsersID,
                        "Comments" => $Values[self::SO_COMMENTS_COL],
                        "CustomCoverURL" => $Values[self::SO_CUSTOM_COVER_URL_COL],
                        "ISBNType" => $ISBNType,
                    );

                    $Extra = Array(
                        "REMOTE_ADDR" => $_SERVER["REMOTE_ADDR"],
                    );

                    $ProjectShop = new CProjectsShopOnline();
                    if ($ProjectShop->Save(0, $Data, $Extra)) {
                        $CreatedProjects++;
                    } else {
                        $Errors[] = "Could not save project in database.";
                    }
                }

                if (!empty($Errors)) {
                    $ErrorDetails[] = Array(
                        "ISBN" => $ISBN,
                        "details" => $Errors,
                    );
                }
            }
        }

        $Template = $this->LoadTemplate("ProjectsUploadResult");

        $Params = Array();
        $Params["filename"] = $FilenameOriginal;
        $Params["filesize"] = filesize($Filepath);
        $Params["processed"] = $RowsProcessed;
        $Params["created"] = $CreatedProjects;
        $Params["errors"] = $ErrorDetails;

        return Array(1, Array("", $Template->render($Params)));
    }
	
	///////////////////////////////////////////////
	////////       PRIVATE OFFER      /////////////
	///////////////////////////////////////////////
	
	function PrivateOfferParams() {
        $data = array();
		
		$LSCs = new CUsers();
        $LSCs->LoadAllOfGroup('Learning Solutions Consultant');
        $data["LSCs"] = Array(0 => "Nobody") + $LSCs->Rows->RowsToAssociativeArrayWithMultipleColumns("LastName,FirstName");
        
		$CreativeContacts = new CUsers();
		$CreativeContacts->LoadAllOfGroup('Creative Contact');
        $data["CreativeContacts"] = Array(0 => "Nobody") + $CreativeContacts->Rows->RowsToAssociativeArrayWithMultipleColumns("LastName,FirstName");
		        
        $data['activeSidebarNode'] = 'PrivateOffer';

        return array_merge($data, $this->BuildDefaultParams());
    }

    function CreatePrivateOffer() {
        $Data = Array(
            "ProjectNumber" => intval($_POST["ProjectNumber"]),
			"ISBN" => htmlspecialchars($_POST["ISBN"]),
			"ConnectPlusISBN" => htmlspecialchars($_POST["ConnectPlusISBN"]),            
            "RequesterName" => htmlspecialchars($_POST["RequesterName"]),
            "RequesterEmail" => htmlspecialchars($_POST["RequesterEmail"]),
			"LscID" => intval($_POST["LscID"]),
            "DateNeeded" => strtotime($_POST["DateNeeded"]),
            "CreativeContactID" => intval($_POST["CreativeContactID"]),
			"ConnectionType" => htmlspecialchars($_POST["ConnectionType"]),
            "SchoolName" => htmlspecialchars($_POST["SchoolName"]),
			"SchoolCity" => htmlspecialchars($_POST["SchoolCity"]),
			"CampusName" => htmlspecialchars($_POST["CampusName"]),
			"Duration" => intval($_POST["Duration"]),
            "PriceType" => htmlspecialchars($_POST["PriceType"]),
			"Price" => floatval($_POST["Price"]),			
        );

        $Extra = Array(
            "REMOTE_ADDR" => $_SERVER["REMOTE_ADDR"],			
        );

        $ProjectPrivateOffer = new CProjectsPrivateOffer();
        if ($ProjectPrivateOffer->Save(0, $Data, $Extra)) {
            return Array(1, "Project Private Offer created successfully.");
        } else {
            return Array(0, "Error creating Project Private Offer.");
        }
    }

    function UploadPrivateOfferFile() {
        $Filename = $_POST["Filename"];

        $FileContents = file(CData::$PathTemp . $Filename, FILE_IGNORE_NEW_LINES);
        $ErrorDetails = Array();
        $CreatedProjects = 0;
		
        foreach ($FileContents as $Line) {
            $Values = $this->ParseCSVLine($Line);

            $ProjectNumber = $Values[self::PO_PROJECT_NUMBER_COL];
            //Ignore the titles line
            if (is_numeric($ProjectNumber)) {

                $Errors = $this->ProjectPrivateOfferCheckMandatoryFields($Values);
				
				if(CProjectsPrivateOffer::ExistsWithProjectNumber($ProjectNumber)){
				    $Errors[] = "Duplicated project Number: '$ProjectNumber'.\n";
				}
					
				$ISBN = $Values[self::PO_ISBN_COL];
				if(CProjectsPrivateOffer::ExistsWithISBN($ISBN)){
				    $Errors[] = "Duplicated ISBN: '$ISBN'.\n";
				}
				
				$ConnectPlusISBN = $Values[self::PO_CONNECT_PLUS_ISBN_COL];
				if(CProjectsPrivateOffer::ExistsWithConnectPlusISBN($ConnectPlusISBN)){
				    $Errors[] = "Duplicated Connect Plus ISBN: '$ConnectPlusISBN'.\n";
				}
				
				$Email = $Values[self::PO_REQUESTER_EMAIL_COL];
                if (!CValidate::Email($Email)) {
                    $Errors[] = "Invalid e-mail address: '$Email'.\n";
                }

				$LSCFullname = $Values[self::PO_LSC_COL];
                $LscID = CUsers::GetIdUserWithName($LSCFullname);

                if (!is_int($LscID) || (is_int($LscID) && $LscID < 1)) {
                    $Errors[] = "User not found for name: '$LSCFullname'.\n";
                }
				
                $DateNeededStr = $Values[self::PO_DATE_NEEDED_COL];
                $DateNeeded = strtotime($DateNeededStr);
                if ($DateNeeded === FALSE) {
                    $Errors[] = "Invalid date needed: '$DateNeededStr'.\n";
                }

                $CreativeContactFullname = $Values[self::PO_CREATIVE_CONTACT_COL];
                $CreativeContactID = CUsers::GetIdUserWithName($CreativeContactFullname);

                if (!is_int($CreativeContactID) || (is_int($CreativeContactID) && $CreativeContactID < 1)) {
                    $Errors[] = "User not found for name: '$CreativeContactFullname'.\n";
                }

                $ConnectionType = $Values[self::PO_CONNECTION_TYPE_COL];
                if (array_search($ConnectionType, CProjectsPrivateOffer::GetConnectionTypes()) === FALSE) {
                    $Errors[] = "Invalid Connection Type: '$ConnectionType'.\n";
                }
				
				$Duration = intval($Values[self::PO_DURATION_COL]);
				if (!is_int($Duration) || (is_int($Duration) && $Duration < 0)) {
                    $Errors[] = "Duration must be expressed in day numbers, not as: '$Duration'.\n";
                }

				$PriceType = $Values[self::PO_PRICE_TYPE_COL];
                if (array_search($PriceType, CProjectsPrivateOffer::GetPriceTypes()) === FALSE) {
                    $Errors[] = "Invalid Price Type: '$PriceType'.\n";
                }
				
				$Price = floatval($Values[self::PO_PRICE_COL]);
				if (!is_float($Price) || (is_float($Price) && $Price < 0)) {
                    $Errors[] = "Price must be a number equal or greater than 0, can't parse: '$Duration'.\n";
                }
				
                if (empty($Errors)) {
                    $Data = Array(
						"ProjectNumber" => $ProjectNumber,
                        "ISBN" => $ISBN,
						"ConnectPlusISBN" => $ConnectPlusISBN,
						"RequesterName" => $Values[self::PO_REQUESTER_NAME_COL],
                        "RequesterEmail" => $Email,
						"LscID" => $LscID,
                        "DateNeeded" => $DateNeeded,
						"CreativeContactID" => $CreativeContactID,
						"ConnectionType" => $ConnectionType,
						"SchoolName" => $Values[self::PO_SCHOOL_NAME_COL],
						"SchoolCity" => $Values[self::PO_SCHOOL_CITY_COL],
						"CampusName" => $Values[self::PO_CAMPUS_NAME_COL],
                        "Duration" => $Duration,
                        "PriceType" => $PriceType,
                        "Price" => $Price                        
                    );

                    $Extra = Array(
                        "REMOTE_ADDR" => $_SERVER["REMOTE_ADDR"],
                    );

                    $ProjectPrivateOffer = new CProjectsPrivateOffer();
                    if ($ProjectPrivateOffer->Save(0, $Data, $Extra)) {
                        $CreatedProjects++;
                    } else {
                        $Errors[] = "Could not save project with project number: ".$ProjectNumber." in database.";
                    }
                }

                if (!empty($Errors)) {
                    $ErrorDetails[] = Array(
                        "Project Number" => $ProjectNumber,
                        "details" => $Errors,
                    );
                }
            }
        }

        $Template = $this->LoadTemplate("ProjectsUploadResult");

        $Params = Array();
        $Params["created"] = $CreatedProjects;
        $Params["errors"] = $ErrorDetails;

        return Array(1, Array("", $Template->render($Params)));
    }
	
	///////////////////////////////////////////////////
	////////         PRIVATE FUNCTIONS      ///////////
	///////////////////////////////////////////////////
    private function ParseCSVLine($Line) {
        $Values = str_getcsv($Line);
        $Return = Array();

        foreach ($Values as $Element) {
            $Return[] = trim($Element);
        }

        return $Return;
    }
	
	private function ProjectPrivateOfferCheckMandatoryFields($Values)
	{
		$Errors = Array();
		
		if ($Values[self::PO_PROJECT_NUMBER_COL] == null)
			$Errors[] = "Product Number is required.";
		
		if ($Values[self::PO_ISBN_COL] == null)
			$Errors[] = "ISBN is required.";
		
		if ($Values[self::PO_REQUESTER_NAME_COL] == null)
			$Errors[] = "Requester name is required.";
		
		if ($Values[self::PO_REQUESTER_EMAIL_COL] == null)
			$Errors[] = "Requester email is required.";
		
		if ($Values[self::PO_LSC_COL] == null)
			$Errors[] = "LSC is required.";
		
		if ($Values[self::PO_DATE_NEEDED_COL] == null)
			$Errors[] = "Date needed is required.";
		
		if ($Values[self::PO_CREATIVE_CONTACT_COL] == null)
			$Errors[] = "Creative Contact is required.";
		
		if ($Values[self::PO_PRICE_TYPE_COL] == null)
			$Errors[] = "Price type is required.";
		
		if ($Values[self::PO_PRICE_COL] == null)
			$Errors[] = "Price is required.";
		
		return $Errors;
	}

}

?>
