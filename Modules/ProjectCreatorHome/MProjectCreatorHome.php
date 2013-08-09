<?php

class MProjectCreatorHome extends CUnauthorizedModule {

    const ISBN10_COL = 0;
    const AUTHOR_COL = 1;
    const REQUESTER_NAME_COL = 2;
    const REQUESTER_EMAIL_COL = 3;
    const DATE_NEEDED_COL = 4;
    const CREATIVE_USER_COL = 5;
    const COMMENTS_COL = 6;
    const CUSTOM_COVER_URL_COL = 7;
    const ISBN_TYPE_COL = 8;

    function __construct() {
        parent::__construct("./Modules/ProjectCreatorHome/Views");
    }

    function BuildDefaultParams() {
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
        $Filename = $_POST["Filename"];

        $FileContents = file(CData::$PathTemp . $Filename, FILE_IGNORE_NEW_LINES);
        $ErrorDetails = Array();
        $CreatedProjects = 0;

        foreach ($FileContents as $Line) {
            $Values = $this->ParseCSVLine($Line);

            $ISBN = $Values[self::ISBN10_COL];
            //Ignore the titles line
            if (strripos($ISBN, "ISBN") === FALSE) {

                $Errors = Array();

                $Email = $Values[self::REQUESTER_EMAIL_COL];
                if (!CValidate::Email($Email)) {
                    $Errors[] = "Invalid e-mail address: '$Email'.\n";
                }

                $DateNeededStr = $Values[self::DATE_NEEDED_COL];
                $DateNeeded = strtotime($DateNeededStr);
                if ($DateNeeded === FALSE) {
                    $Errors[] = "Invalid date needed: '$DateNeededStr'.\n";
                }

                $Fullname = $Values[self::CREATIVE_USER_COL];
                $UsersID = CUsers::GetIdUserWithName($Fullname);

                if (!is_int($UsersID) || (is_int($UsersID) && $UsersID < 1)) {
                    $Errors[] = "Creative Analyst not found for name: '$Fullname'.\n";
                }

                $ISBNType = $Values[self::ISBN_TYPE_COL];
                if (array_search($ISBNType, CProjectsShopOnline::GetISBNTypes()) === FALSE) {
                    $Errors[] = "Invalid ISBN Type: '$ISBNType'.\n";
                }

                if (empty($Errors)) {
                    $Data = Array(
                        "ISBN10" => $ISBN,
                        "Author" => $Values[self::AUTHOR_COL],
                        "RequesterName" => $Values[self::REQUESTER_NAME_COL],
                        "RequesterEmail" => $Email,
                        "DateNeeded" => $DateNeeded,
                        "UsersID" => $UsersID,
                        "Comments" => $Values[self::COMMENTS_COL],
                        "CustomCoverURL" => $Values[self::CUSTOM_COVER_URL_COL],
                        "ISBNType" => $ISBNType,
                    );

                    $Extra = Array(
                        "REMOTE_ADDR" => $_SERVER["REMOTE_ADDR"],
                    );

                    $ProjectShop = new CProjectsShopOnline();
                    if (true) {//$ProjectShop->Save(0, $Data, $Extra)) {
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
        $Params["created"] = $CreatedProjects;
        $Params["errors"] = $ErrorDetails;

        return Array(1, Array("", $Template->render($Params)));
    }

    public function ParseCSVLine($Line) {
        $Values = str_getcsv($Line);
        $Return = Array();

        foreach ($Values as $Element) {
            $Return[] = trim($Element);
        }

        return $Return;
    }

}

?>
