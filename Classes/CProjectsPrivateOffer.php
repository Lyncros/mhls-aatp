<?php

/**
 * Description of CProjectsPrivateOffer
 *
 * @author nbuttarelli
 */
class CProjectsPrivateOffer extends CProjectsBase {

    const TABLE_NAME = "ProjectsPrivateOffer";

    function __construct() {
        parent::__construct(self::TABLE_NAME, "CProjectsPrivateOfferMilestones", "CProjectsPrivateOfferMilestonesToDos");
    }

    public static function GetConnectionTypes() {
        return Array("Connect", "Connect Plus", "Both", "ConnectPlus");
    }

    public static function GetPriceTypes() {
        return Array("Connect", "Connect Plus", "ConnectPlus");
    }

    public static function ExistsWithProjectNumber($ProjectNumber) {
        $Projects = CTable::Select(self::TABLE_NAME, "WHERE ProjectNumber = $ProjectNumber");
        return ($Projects != null) && $Projects->count() > 0;
    }

    public static function ExistsWithISBN($ISBN) {
        $Projects = CTable::Select(self::TABLE_NAME, "WHERE ISBN = $ISBN");

        return ($Projects != null) && $Projects->count() > 0;
    }

    public static function ExistsWithConnectPlusISBN($ConnectPlusISBN) {
        if ($ConnectPlusISBN != null) {
            $Projects = CTable::Select(self::TABLE_NAME, "WHERE ConnectPlusISBN = $ConnectPlusISBN");

            return ($Projects != null) && $Projects->count() > 0;
        } else {
            return false;
        }
    }

}

?>
