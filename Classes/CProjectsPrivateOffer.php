<?php

/**
 * Description of CProjectsPrivateOffer
 *
 * @author nbuttarelli
 */
class CProjectsPrivateOffer extends CTable {

	const TABLE_NAME = "ProjectsPrivateOffer";
	
    function __construct() {
        $this->Table = self::TABLE_NAME;
    }
	
	public static function GetConnectionTypes() {
        return Array("Connect", "Connect Plus", "Both", "ConnectPlus");
    }
	
	public static function GetPriceTypes() {
        return Array("Connect", "Connect Plus", "ConnectPlus");
    }
	
    public function Save($ProjectsID, $Data, $Extra) {
        if ($ProjectsID > 0) {
			$Data["Modified"] = time();
            $Data["ModifiedIPAddress"] = $Extra["REMOTE_ADDR"];
			
            return CTable::Update($this->Table, $ProjectsID, $Data);
        } else {
            $Data["Created"] = time();
            $Data["CreatedIPAddress"] = $Extra["REMOTE_ADDR"];
            
            return CTable::Add($this->Table, $Data);
        }
        return false;
    }
	
	public static function ExistsWithProjectNumber($ProjectNumber)
	{
		$Projects = CTable::Select(self::TABLE_NAME,"WHERE ProjectNumber = $ProjectNumber");
		return ($Projects != null) && $Projects->count() > 0;
	}
	
	public static function ExistsWithISBN($ISBN)
	{
		$Projects = CTable::Select(self::TABLE_NAME,"WHERE ISBN = $ISBN");
		
		return ($Projects != null) && $Projects->count() > 0;
	}
	
	public static function ExistsWithConnectPlusISBN($ConnectPlusISBN)
	{
		if ($ConnectPlusISBN != null)
		{
			$Projects = CTable::Select(self::TABLE_NAME,"WHERE ConnectPlusISBN = $ConnectPlusISBN");
			
			return ($Projects != null) && $Projects->count() > 0;
		}
		else
			return false;
	}
}

?>
