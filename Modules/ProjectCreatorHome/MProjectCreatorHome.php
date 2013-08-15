<?php

class MProjectCreatorHome extends CUnauthorizedModule {
    // Shop Online Columns

    const SO_ISBN10_COL = 0;
    const SO_AUTHOR_COL = 1;
    const SO_REQUESTER_NAME_COL = 2;
    const SO_REQUESTER_EMAIL_COL = 3;
    const SO_DATE_NEEDED_COL = 4;
    const SO_CREATIVE_USER_COL = 5;
    const SO_COMMENTS_COL = 6;
    const SO_CUSTOM_COVER_URL_COL = 7;
    const SO_ISBN_TYPE_COL = 8;

    // Private Offer Columns
    const PO_PROJECT_NUMBER_COL = 0;
    const PO_ISBN_COL = 1;
    const PO_CONNECT_PLUS_ISBN_COL = 2;
    const PO_REQUESTER_NAME_COL = 3;
    const PO_REQUESTER_EMAIL_COL = 4;
    const PO_LSC_COL = 5;
    const PO_DATE_NEEDED_COL = 6;
    const PO_CREATIVE_CONTACT_COL = 7;
    const PO_CONNECTION_TYPE_COL = 8;
    const PO_SCHOOL_NAME_COL = 9;
    const PO_SCHOOL_CITY_COL = 10;
    const PO_CAMPUS_NAME_COL = 11;
    const PO_DURATION_COL = 12;
    const PO_PRICE_TYPE_COL = 13;
    const PO_PRICE_COL = 14;

    function __construct() {
        parent::__construct("./Modules/ProjectCreatorHome/Views");
    }

    private function BuildTemplateDefaultParams() {
        return Array(
            "menuItems" => CSidebarMenu::BuildProjectsFormsSideMenu(),
            "jsFiles" => Array("./Modules/ProjectCreatorHome/MProjectCreatorHome.js"),
            "cssFiles" => Array("./Modules/ProjectCreatorHome/style.css"),
        );
    }

    function IndexParams() {
        return $this->PrivateOfferParams();
    }
    
    function IndexAction() {
        return "PrivateOffer";
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

        return array_merge($data, $this->BuildTemplateDefaultParams());
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
            "ShopPageInstructions" => htmlspecialchars($_POST["ShopPageInstructions"]),
            "EmailInstructions" => htmlspecialchars($_POST["EmailInstructions"]),
        );
        
        $ProjectShop = new CProjectsShopOnline();
        $Extra = $this->BuildSaveParameters();
        $NewProjectID = $ProjectShop->Save(0, $Data, $Extra);

        if ($NewProjectID === FALSE) {
            return Array(0, "Error saving Project Shop Online in database.");
        } else {
            $MilestonesNames = $this->GetConfig("ShopOnlineMilestones");

            if (!empty($MilestonesNames)) {
                if ($ProjectShop->AddMilestonesAndTodoListsToProject($NewProjectID, $MilestonesNames, $Extra) === FALSE) {
                    return Array(0, "Error adding automatic milestones to project.");
                }
            }
            
            $StoreFrontInfoItems = $this->GetStoreFrontInfoItems($_POST);
            
            if (!empty($StoreFrontInfoItems)) {
                 if ($ProjectShop->AddStoreFrontInfoItems($NewProjectID, $StoreFrontInfoItems) === FALSE) {
                    return Array(0, "Error adding store front info item to project.");
                }                
            }
            
            $template = $this->LoadTemplate('PrivateOfferConfirmation');
			
            return Array(1, $template->display());            
        }
    }

    function UploadShopOnlineFile() {
        $FilenameOriginal = $_POST["FilenameOriginal"];
        $Filepath = CData::$PathTemp . $_POST["Filename"];

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

                $this->ValidateISBN($ISBN, $Errors);
                $Email = $this->ValidateEmail($Values, $Errors);
                $DateNeeded = $this->ValidateDateNeeded($Values[self::SO_DATE_NEEDED_COL], $Errors);
                $UsersID = $this->ValidateUsersID($Values[self::SO_CREATIVE_USER_COL], $Errors);
                $ISBNType = $this->ValidateISBNType($Values, $Errors);

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

                    $ProjectShop = new CProjectsShopOnline();
                    $Extra = $this->BuildSaveParameters();
                    $NewProjectID = $ProjectShop->Save(0, $Data, $Extra);

                    if ($NewProjectID === FALSE) {
                        $Errors[] = "Could not save project in database.";
                    } else {
                        $MilestonesNames = $this->GetConfig("ShopOnlineMilestones");

                        if (empty($MilestonesNames)) {
                            $CreatedProjects++;
                        } else {
                            if ($ProjectShop->AddMilestonesAndTodoListsToProject($NewProjectID, $MilestonesNames, $Extra)) {
                                $CreatedProjects++;
                            } else {
                                $Errors[] = "Could not add automatic milestones to project.";
                            }
                        }
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

    public function ShopOnlineISBN10Exists() {
        $ISBN10 = htmlspecialchars($_POST["ISBN10"]);

        return Array(CProjectsShopOnline::ExistsWithISBN10($ISBN10), '');
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

        return array_merge($data, $this->BuildTemplateDefaultParams());
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

        $ProjectPrivateOffer = new CProjectsPrivateOffer();
        $Extra = $this->BuildSaveParameters();
        $NewProjectID = $ProjectPrivateOffer->Save(0, $Data, $Extra);
            
        if ($NewProjectID === FALSE) {
            return Array(0, "Error creating Project Private Offer.");
        } else {
            $MilestonesNames = $this->GetConfig("PrivateOfferMilestones");

            if (!empty($MilestonesNames)) {
                if ($ProjectPrivateOffer->AddMilestonesAndTodoListsToProject($NewProjectID, $MilestonesNames, $Extra) === FALSE) {
                    return Array(0, "Error adding automatic milestones to project.");
                }
            }
            
            $template = $this->LoadTemplate('PrivateOfferConfirmation');
			
            return Array(1, $template->display());
        }
    }

    function UploadPrivateOfferFile() {
        $FilenameOriginal = $_POST["FilenameOriginal"];
        $Filepath = CData::$PathTemp . $_POST["Filename"];

        $FileContents = file($Filepath, FILE_IGNORE_NEW_LINES);

        $ErrorDetails = Array();
        $CreatedProjects = 0;
        $RowsProcessed = 0;

        foreach ($FileContents as $Line) {
            $Values = $this->ParseCSVLine($Line);

            $ProjectNumber = $Values[self::PO_PROJECT_NUMBER_COL];
            //Ignore the titles line
            if (is_numeric($ProjectNumber)) {
                $RowsProcessed++;

                $Errors = $this->ProjectPrivateOfferCheckMandatoryFields($Values);

                if (CProjectsPrivateOffer::ExistsWithProjectNumber($ProjectNumber)) {
                    $Errors[] = "Duplicated project Number: '$ProjectNumber'.";
                }

                $ISBN = $Values[self::PO_ISBN_COL];
                if (CProjectsPrivateOffer::ExistsWithISBN($ISBN)) {
                    $Errors[] = "Duplicated ISBN: '$ISBN'.";
                }

                $ConnectPlusISBN = $Values[self::PO_CONNECT_PLUS_ISBN_COL];
                if (CProjectsPrivateOffer::ExistsWithConnectPlusISBN($ConnectPlusISBN)) {
                    $Errors[] = "Duplicated Connect Plus ISBN: '$ConnectPlusISBN'.";
                }

                $Email = $Values[self::PO_REQUESTER_EMAIL_COL];
                if (!CValidate::Email($Email)) {
                    $Errors[] = "Invalid e-mail address: '$Email'.";
                }

                $LscID = $this->ValidateUsersID($Values[self::PO_LSC_COL], $Errors);
                $DateNeeded = $this->ValidateDateNeeded($Values[self::PO_DATE_NEEDED_COL], $Errors);
                $CreativeContactID = $this->ValidateUsersID($Values[self::PO_CREATIVE_CONTACT_COL], $Errors);

                $ConnectionType = $Values[self::PO_CONNECTION_TYPE_COL];
                if (array_search($ConnectionType, CProjectsPrivateOffer::GetConnectionTypes()) === FALSE) {
                    $Errors[] = "Invalid Connection Type: '$ConnectionType'.";
                }

                $Duration = intval($Values[self::PO_DURATION_COL]);
                if (!is_int($Duration) || (is_int($Duration) && $Duration < 0)) {
                    $Errors[] = "Duration must be expressed in day numbers, not as: '$Duration'.";
                }

                $PriceType = $Values[self::PO_PRICE_TYPE_COL];
                if (array_search($PriceType, CProjectsPrivateOffer::GetPriceTypes()) === FALSE) {
                    $Errors[] = "Invalid Price Type: '$PriceType'.";
                }

                $Price = floatval($Values[self::PO_PRICE_COL]);
                if (!is_float($Price) || (is_float($Price) && $Price < 0)) {
                    $Errors[] = "Price must be a number equal or greater than 0, can't parse: '$Duration'.";
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

                    $ProjectPrivateOffer = new CProjectsPrivateOffer();
                    $Extra = $this->BuildSaveParameters();
                    $NewProjectID = $ProjectPrivateOffer->Save(0, $Data, $Extra);

                    if ($NewProjectID === FALSE) {
                        $Errors[] = "Error saving project in database.";
                    } else {
                        $MilestonesNames = $this->GetConfig("PrivateOfferMilestones");

                        if (empty($MilestonesNames)) {
                            $CreatedProjects++;
                        } else {
                            if ($ProjectPrivateOffer->AddMilestonesAndTodoListsToProject($NewProjectID, $MilestonesNames, $Extra)) {
                                $CreatedProjects++;
                            } else {
                                $Errors[] = "Error adding automatic milestones to project.";
                            }
                        }
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

    public function PrivateOfferProjectNumberExists() {
        $ProjectNumber = intval($_POST["ProjectNumber"]);

        return Array(CProjectsPrivateOffer::ExistsWithProjectNumber($ProjectNumber), '');
    }

    public function PrivateOfferISBNExists() {
        $ISBN = htmlspecialchars($_POST["ISBN"]);

        return Array(CProjectsPrivateOffer::ExistsWithISBN($ISBN), '');
    }

    public function PrivateOfferConnectPlusISBNExists() {
        $ConnectPlusISBN = htmlspecialchars($_POST["ConnectPlusISBN"]);

        return Array(CProjectsPrivateOffer::ExistsWithConnectPlusISBN($ConnectPlusISBN), '');
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

    private function ProjectPrivateOfferCheckMandatoryFields($Values) {
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

    private function ValidateISBN($ISBN, &$Errors) {
        $CProjectsShop = new CProjectsShopOnline();

        if ($CProjectsShop->ISBNExists($ISBN)) {
            $Errors[] = "ISBN already exists in database.";
        }
    }

    private function ValidateEmail($Values, &$Errors) {
        $Email = $Values[self::SO_REQUESTER_EMAIL_COL];
        if (CValidate::Email($Email)) {
            return $Email;
        } else {
            $Errors[] = "Invalid e-mail address: '$Email'.";
            return null;
        }
    }

    private function ValidateDateNeeded($DateNeededStr, &$Errors) {
        $DateNeeded = strtotime($DateNeededStr);
        if ($DateNeeded === FALSE) {
            $Errors[] = "Invalid date needed: '$DateNeededStr'.";
            return null;
        } else {
            return $DateNeeded;
        }
    }

    private function ValidateUsersID($UserFullname, &$Errors) {
        $FullnameArray = explode(" ", $UserFullname);
        $CUsers = new CUsers();
        $UsersID = $CUsers->GetIdUserWithName($FullnameArray[1], $FullnameArray[0]);

        if ($UsersID === 0) {
            $Errors[] = "Creative Analyst not found for name: '$UserFullname'.";
        } else if ($UsersID === -1) {
            $Errors[] = "Multiple Creative Analyst found for name: '$UserFullname'.";
        } else {
            return $UsersID;
        }

        return null;
    }

    private function ValidateISBNType($Values, &$Errors) {
        $ISBNType = $Values[self::SO_ISBN_TYPE_COL];
        if (array_search($ISBNType, CProjectsShopOnline::GetISBNTypes()) === FALSE) {
            $Errors[] = "Invalid ISBN Type: '$ISBNType'.";
            return null;
        } else {
            return $ISBNType;
        }
    }

    private function GetStoreFrontInfoItems($Post)
    {
        $StoreFrontInfoItems = Array();
        $index = 1;
        while(true){
            $key = "StoreFrontISBN".$index;
            if(array_key_exists($key,$Post)) {
                
                $StoreFrontInfoItems[] = Array( 'ISBN'    => $Post["StoreFrontISBN".$index], 
                                                'Author'  => $Post["StoreFrontAuthor".$index], 
                                                'Virtual' => $Post["StoreFrontVirtual".$index]);
                $index++;
            }
            else         
                break;
        }

        return $StoreFrontInfoItems;            
    }
}

?>
