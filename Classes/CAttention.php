<?
	//==========================================================================
	/*
		Table for Attention
		
		---Tim, I don't understand. What is an Attention notice? What is it used for?
		Why would a developer add an Attention notice?---
		
		CAttention - It's an internal system notice to a business regarding a conflict. 
		For example, you have two products put up live in a store, and the products both 
		have the same SEO pagename (myproduct.php). Or, another example, you have a 
		store put up live, and accepting transactions, but no payment gateway has been setup.
		
		
		@ItemID - This is used on the Attention module so you can click an icon to bring 
			up the Add/Edit form, and fix it without having to go to each module to fix 
			the issues raised.

		4/10/2009
	*/
	//==========================================================================
	class CAttention extends CTable {
		function __construct() {
			$this->Table = "Attention";
		}

		/**
		 *	Load an individual Attention entry by ID
		 *
		 *	@param integer $ID
		 *	@return boolean
		 */
		function OnLoad($ID) {
			if(parent::OnLoadByID($ID) === false) {
				trigger_error("Unable to load (".$this->Table.") : $ID", E_USER_NOTICE);
				return false;
			}

			return true;
		}

		//----------------------------------------------------------------------
		// Static Functions
		//----------------------------------------------------------------------
		/**
		 *	Send a list of attention notices by email to their intended recipients (called via cronjob?) - Yes, cronjob
		 *	
		 *	@static
		 *	@return boolean
		 */
		public static function SendReport() {
			if(!CAttention::HasNotices()) return false;

			$Content = "";

			$AttTable = new CAttention();
			$AttTable->OnLoadAll();

			$Content .= "You have ".count($AttTable->Rows)." item(s) that require your attention.<br/><br/>";

			$i = 1;
			foreach($AttTable->Rows as $Row) {
				$Content .= "<b>$i.</b> ".$Row->Content."<br/><br/>";

				++$i;
			}

			$Content .= "<a href='".Config::$Options["System"]["Domain"].".Login'>Click here to login.</a>";

			$Email = new CEmailNotice();

			$Email->FromName	= Config::$Options["Attention"]["Notices"]["FromName"];
			$Email->FromEmail	= Config::$Options["Attention"]["Notices"]["FromEmail"];

			$Email->Subject		= Config::$Options["Attention"]["Notices"]["Subject"];

			$Email->Content		= $Content;

			foreach(CSystem::GetNoticeList() as $ToEmail) {
				$Email->ToEmail = $ToEmail;
				$Email->Send();
			}

			return true;
		}

		/**
		 *	Add an Attention notice to the queue? based on a given set of parameters
		 *	
		 *	@static
		 *	@param integer $BusinessID
		 *	@param string $Table - database table name that a conflict was found in
		 *	@param integer $ItemID - the ItemID is the row in that table, @see @ItemID
		 *	@param string $Content - body of the Attention notice
		 *	@return integer | boolean
		 */
		public static function PushNotice($BusinessesID, $Table, $ItemID, $Content) {
			if(CAttention::NoticeExists($BusinessesID, $Table, $ItemID, $Content)) return false;

			$Data = Array(
				"BusinessesID"	=> intval($BusinessesID),
				"Timestamp"		=> time(),
				"Table"			=> htmlspecialchars($Table),
				"ItemID"		=> intval($ItemID),
				"Content"		=> htmlspecialchars($Content)
			);

			return CTable::Add("Attention", $Data);
		}

		/**
		 *	Check if an Attention has already been added to the queue?
		 *	
		 *	@static
		 *	@param integer $BusinessID
		 *	@param string $Table - database table name that a conflict was found in
		 *	@param integer $ItemID - the ItemID is the row in that table, @see @ItemID
		 *	@param string $Content - body of the Attention notice
		 *	@return boolean
		 */
		public static function NoticeExists($BusinessesID, $Table, $ItemID, $Content) {
			$Table		= mysql_real_escape_string(htmlspecialchars($Table));
			$ItemID		= intval($ItemID);
			$Content	= mysql_real_escape_string(htmlspecialchars($Content));

			$AttClass = new CAttention();
			if($AttClass->OnLoadAll("WHERE `BusinessesID` = ".intval($BusinessesID)." && `Table` = '$Table' && `ItemID` = $ItemID && `Content` = '$Content'") === false) {
				return false;
			}

			return true;
		}

		/**
		 *	Check if a given Business has Attention notices in the queue
		 *	
		 *	@static
		 *	@param integer $BusinessID
		 *	@return boolean
		 */
		public static function HasNotices($BusinessesID) {
			$NumRows = CTable::NumRows("Attention", "WHERE `BusinessesID` = ".intval($BusinessesID));

			if($NumRows !== false && $NumRows > 0) {
				return true;
			}

			return false;
		}

		/**
		 *	Check if a Business has notices based on a given table and item id? No lo entiendo!
		 *	
		 *	@static
		 *	@param integer $BusinessID
		 *	@param string $Table - database table name that a conflict was found in
		 *	@param integer $ItemID - the ItemID is the row in that table, @see @ItemID
		 *	@return boolean
		 */
		public static function HasNoticesByItem($BusinessesID, $Table, $ItemID) {
			$Table	= mysql_real_escape_string($Table);
			$ItemID = intval($ItemID);

			$NumRows = CTable::NumRows("Attention", "WHERE `BusinessesID` = ".intval($BusinessesID)." && `Table` = '".$Table."' && `ItemID` = ".$ItemID);

			if($NumRows !== false && $NumRows > 0) {
				return true;
			}

			return false;
		}
	};

	//==========================================================================
?>
