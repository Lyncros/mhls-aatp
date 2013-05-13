<?
	//==========================================================================
	/*
		Class for pushing Notices to JH Data (JHNotice program)

		*** NOTICE ***
		Deprecated (replaced by CNotifier)
		 - Remove references, and then delete this file

		4/10/2009
	*/
	//==========================================================================
	class CNotices {
		function __construct() {
		}

		//======================================================================
		// Static Functions
		//======================================================================
		public static function AddNotice($UsersID, $Title, $Content, $URL) {
			if($UsersID <= 0) return false;

			$UsersID	= htmlspecialchars($UsersID);
			$Title		= htmlspecialchars($Title);
			$Content	= htmlspecialchars($Content);
			$URL		= htmlspecialchars($URL);

			$JHData = new CJHData();

			$JHData->Serialize = true;

			$Data = "
			<UsersID>".$UsersID."</UsersID>
			<Title>".$Title."</Title>
			<Content>".$Content."</Content>
			<URL>".$URL."</URL>
			";

			$Return = $JHData->Send("Notices", "AddNotice", $Data);

			return $Return["JHDATARESPONSE"][0]["RETURN"][0]["SUCCESS"][0]["VALUE"] ? true : false;
		}
	};

	//==========================================================================
?>
