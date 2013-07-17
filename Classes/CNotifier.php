<?
	//==========================================================================
	/*
		A service class for sending/retrieving notifications

		4/10/2009
	*/
	//==========================================================================
	/**
	 *	Used for adding different message types
	 *	to a queue, then triggering the send mechanism
	 *	using a cron
	 *
	 */
	class CNotifier extends CAJAX {
		/**
		 *	Used by the CNotifier::GenerateUID method
		 *	for creating a unique identifier
		 *
		 *	@access private
		 *	@static
		 *	@staticvar string
		 */
		private static $Salt = "T!\g=B8CE1?UPd&=dJ'LhZ!oqSaGxT%#";

		//----------------------------------------------------------------------
		/**
		 *	Called by the cron script, not for in-site use
		 *
		 *	@static
		 *
		 */
		public static function OnCron() {
			//Remove old Notices never Retrieved
			$Expire = time() - (60 * 60 * 24 * 7); //7 Days Old

			CTable::RunQuery("DELETE FROM `NotifierQueue` WHERE `Timestamp` < ".$Expire);

			//Logout automatically
			$Expire = time() - (60 * 15); //15 minutes

			CTable::RunQuery("DELETE FROM `NotifierSessions` WHERE `Timestamp` < ".$Expire);

			$SMSQueue = new CNotifierSMSQueue();
			if($SMSQueue->OnLoadAll("ORDER BY `ID` ASC LIMIT 0, 5") !== false) {
				foreach($SMSQueue->Rows as $Row) {
					CGoogleVoice::SendSMS($Row->Number, $Row->Content);

					CTable::Delete("NotifierSMSQueue", $Row->ID);
				}
			}
		}

		//----------------------------------------------------------------------
		/**
		 *	A hook for the Ajax Controller
		 *
		 *
		 *	@param string $Action
		 *	@return array - code, message
		 */
		function OnAJAX($Action) {
			if($Action == "Login") {
				return $this->Login();
			}else
			if($Action == "Logout") {
				return $this->Logout();
			}else
			if($Action == "PollNotices") {
				return $this->PollNotices();
			}

			return Array(1, "");
		}

		//----------------------------------------------------------------------
		/**
		 *	Performs an ajax login on a set of expected parameters
		 *	Used by YPP Notifier Application only
		 *
		 *	@return array - code, message
		 */
		function Login() {
			$Username		= $_POST["Username"];
			$Password		= $_POST["Password"];
			
			//ValidLogin will escape variables passed automatically
			if(($UsersID = CUsers::ValidLogin($Username, $Password)) == false) {
				return Array(0, "The username / password entered is invalid");
			}

			$UID = self::GenerateUID();

			$Data = Array(
				"UsersID"	=> $UsersID,
				"UID"		=> $UID,
				"Timestamp"	=> time()
			);

			if(CTable::Add("NotifierSessions", $Data) == false) {
				return Array(0, "Unable to login, please try again");
			}

			return Array(1, json_encode($Data));
		}

		//----------------------------------------------------------------------
		/**
		 *	Perform a logout via ajax using expected parameters
		 *
		 *
		 *	@return void
		 */
		function Logout() {
			$UsersID	= intval($_POST["UsersID"]);
			$UID		= mysql_real_escape_string($_POST["UID"]);

			CTable::RunQuery("DELETE FROM `NotifierSessions` WHERE `UsersID` = ".$UsersID." && `UID` = '".$UID."'");
		}

		//----------------------------------------------------------------------
		/**
		 *	Retrieve queue notices for a given user (using expected parameters)
		 *
		 *
		 *	@return array - code, message
		 */
		function PollNotices() {
			$UsersID	= $_POST["UsersID"];
			$UID		= $_POST["UID"];

			if(self::IsLoggedIn($UsersID, $UID) == false) {
				return Array(2, "Not logged in");
			}

			$Data = Array();

			$QueueTable = new CNotifierQueue();
			if($QueueTable->OnLoadAll("WHERE `UsersID` = ".intval($UsersID)) !== false) {
				foreach($QueueTable->Rows as $Row) {
					$Data[] = $Row;

					CTable::Delete("NotifierQueue", $Row->ID);
				}
			}

			return Array(1, json_encode($Data));
		}

		//----------------------------------------------------------------------
		/**
		 *	Generate a unique identifier
		 *
		 *	@access private
		 *	@return string
		 */
		private function GenerateUID() {
			return sha1(md5(microtime().self::$Salt));
		}		

		//----------------------------------------------------------------------
		/**
		 *	Determine if a given user is already logged in to the system
		 *
		 *	@access private
		 *	@param int $UsersID
		 *	@param string $UID
		 *	@return boolean
		 */
		private function IsLoggedIn($UsersID, $UID) {
			$UsersID	= intval($UsersID);
			$UID		= mysql_real_escape_string($UID);

			$SesTable = new CNotifierSessions();
			if($SesTable->OnLoadAll("WHERE `UsersID` = ".$UsersID." && `UID` = '".$UID."'") === false || count($SesTable->Rows) <= 0) {
				return false;
			}

			//Update Timeout
			CTable::Update("NotifierSessions", $SesTable->ID, Array("Timestamp" => time()));

			return true;
		}

		//======================================================================
		/**
		 *	Add popup notification to the queue
		 *
		 *	@static
		 *	@param int $UsersID
		 *	@param string $Title
		 *	@param string $Content
		 *	@param string $URL
		 *	@return int | boolean
		 */
		public static function PushPopup($UsersID, $Title, $Content, $URL) {
			$Data = Array(
				"UsersID"	=> $UsersID,
				"Timestamp"	=> time(),
				"Title"		=> $Title,
				"Content"	=> $Content,
				"URL"		=> $URL
			);

			return CTable::Add("NotifierQueue", $Data);
		}

		/**
		 *	Used to send a single email using a template that already exists
		 *	in the system
		 *
		 *	@static
		 *	@param string $ToEmail - the email address of the recipient
		 *	@param string $Type - the template type? Yes
		 *	@param string $Name - the template name? Yes
		 *	@param string $SubName - the template subname? Yes
		 *	@param array $EmailParams - a series of key/value pairs that contain the data needed for the template
		 *	@return boolean
		 */
		public static function PushEmail($ToEmail, $Type, $Name, $SubName, Array $EmailParms) {
			CDataParser::ClearPublicData();

			foreach($EmailParms as $Key => $Value) {
				CDataParser::SetPublicData($Key, $Value);
			}

			$EmailTemplate = new CEmailTemplates();
			if($EmailTemplate->OnLoadByName($Type, $Name, $SubName) != false) {
				$EmailTemplate->Parse();
			}else{
				$EmailTemplate = null;
			}

			$EmailHandle = new CEmailNotice();

			$EmailContent = "";

			//Ideally a Template is there for us to Use
			if($EmailTemplate) {
				$EmailHandle->FromName	= $EmailTemplate->FromName;
				$EmailHandle->FromEmail	= $EmailTemplate->FromEmail;
				$EmailHandle->ReplyTo	= $EmailTemplate->ReplyTo;
				$EmailHandle->Subject	= $EmailTemplate->Subject;

				$EmailContent			= $EmailTemplate->Content;

			//Fallback
			}else{
				if(!isset(Config::$Options[$Type]) || 
					!isset(Config::$Options[$Type][$Name]) || 
					!isset(Config::$Options[$Type][$Name][$SubName]) || 
					!isset(Config::$Options[$Type][$Name][$SubName]["FromEmail"])) {
					trigger_error("No Fallback Email Template Found : [$Type][$Name][$SubName]", E_USER_WARNING);
					return false;
				}

				$EmailHandle->FromName	= CDataParser::ParseString(@Config::$Options[$Type][$Name][$SubName]["FromName"]);
				$EmailHandle->FromEmail	= CDataParser::ParseString(@Config::$Options[$Type][$Name][$SubName]["FromEmail"]);
				$EmailHandle->ReplyTo	= CDataParser::ParseString(@Config::$Options[$Type][$Name][$SubName]["ReplyTo"]);
				$EmailHandle->Subject	= CDataParser::ParseString(@Config::$Options[$Type][$Name][$SubName]["Subject"]);

				$EmailContent			= CDataParser::ParseString(@Config::$Options[$Type][$Name][$SubName]["Content"]);
			}

			$EmailHandle->ToEmail = $ToEmail;

			$EmailHandle->Content = $EmailContent;
			
			return $EmailHandle->Send();
		}

		/**
		 *	Add an SMS message to the queue
		 *
		 *	@static
		 *	@param string $Number
		 *	@param string $Content
		 *	@return int | boolean
		 */
		public static function PushSMS($Number, $Content) {
			if(strlen($Number) <= 0)	continue;
			if(strlen($Content) <= 0)	continue;

			$Content = strip_tags($Content);

			$Data = Array(
				"Number"	=> $Number,
				"Content"	=> $Content
			);

			return CTable::Add("NotifierSMSQueue", $Data);
		}

		//======================================================================
		/**
		 *	Push a message to a user through the queue based on an options? 
		 *	possible parameters using templates specified by the given option.
		 *	For each possible option might send an email, sms, and/or provide a
		 *	popup upon logging in to the system
		 *
		 *	@static
		 *	@param string $Type - A type of option @see Config.php
		 *	@param string $Name - the name of the particular option
		 *	@param string $SubName - the subname of a particular option
		 *	@param array $EmailParams - optional, the parameters of an email, ie: Title, Content, URL
		 *	@return boolean
		 */
		public static function Push($Type, $Name, $SubName, array $EmailParms = Array(), $ProjectsID) {
			$result = true;
			
			$SubClass = new CNotifierSubscriptions();
			if($SubClass->OnLoadAllByType($Type, $Name, $SubName, $ProjectsID) === false) {
				// If no subscription for the Project, attempt the default
				if($SubClass->OnLoadAllByType($Type, $Name, $SubName, 0) === false) return false;
			}
			
			$Title		= CDataParser::ParseString(@Config::$Options[$Type][$Name][$SubName]["PopupTitle"]);
			$Content	= CDataParser::ParseString(@Config::$Options[$Type][$Name][$SubName]["PopupContent"]);
			$SMSContent	= CDataParser::ParseString(@Config::$Options[$Type][$Name][$SubName]["SMSContent"]);
			$URL		= CDataParser::ParseString(@Config::$Options[$Type][$Name][$SubName]["URL"]);

			foreach($SubClass->Rows as $Row) {
				$User = new CUsers();
				if($User->OnLoad($Row->UsersID) === false) {
					$User = null;
				}

				if($Row->Popup) {
					//Tim, you should move $Title, $Content, and $URL down here
					//no need to waste time parsing something if it isn't used
					$result = $result && self::PushPopup($User->ID, $Title, $Content, $URL);
				}

				if($Row->Email && $User) {
					if(count($EmailParms) <= 0) {
						$EmailParms = Array(
							"Title"		=> $Title,
							"Content"	=> $Content,
							"URL"		=> $URL
						);
					}
					
					$result = $result && self::PushEmail($User->Email, $Type, $Name, $SubName, $EmailParms);
				}

				if($Row->SMS && $User) {
					//Likewise, you should move $SMSContent down here
					$result = $result && self::PushSMS($User->PhoneSMS, $SMSContent);
				}
			}
			
			return $result;
		}

		/**
		 *	Push a message to all SuperAdmin users through the queue based on an options? 
		 *	possible parameters using templates specified by the given option.
		 *	For each possible option might send an email, sms, and/or provide a
		 *	popup upon logging in to the system
		 *
		 *	@static
		 *	@param string $Type - A type of option @see Config.php
		 *	@param string $Name - the name of the particular option
		 *	@param string $SubName - the subname of a particular option
		 *	@param array $EmailParams - optional, the parameters of an email, ie: Title, Content, URL
		 *	@return boolean
		 */
		public static function PushToSupers($Type, $Name, $SubName, array $EmailParms = Array()) {		
			$Title		= CDataParser::ParseString(@Config::$Options[$Type][$Name][$SubName]["PopupTitle"]);
			$Content	= CDataParser::ParseString(@Config::$Options[$Type][$Name][$SubName]["PopupContent"]);
			$SMSContent	= CDataParser::ParseString(@Config::$Options[$Type][$Name][$SubName]["SMSContent"]);
			$URL		= CDataParser::ParseString(@Config::$Options[$Type][$Name][$SubName]["URL"]);

			$UserGroups = new CUsersGroups();
			if($UserGroups->OnLoadAll("WHERE `SuperAdmin` = 1") !== false) {
				foreach($UserGroups->Rows as $gRow) {
					$Users = new CUsers();
					if($Users->OnLoadAll("WHERE `UsersGroupsID` = ".intval($gRow->ID)) !== false) {
						foreach($Users->Rows as $Row) {
							$User = new CUsers();
							if($User->OnLoad($Row->ID) === false) {
								continue;
							}

							if($Row->Popup) {
								self::PushPopup($User->ID, $Title, $Content, $URL);
							}

							if($Row->Email && $User) {
								if(count($EmailParms) <= 0) {
									$EmailParms = Array(
										"Title"		=> $Title,
										"Content"	=> $Content,
										"URL"		=> $URL
									);
								}

								self::PushEmail($User->Email, $Type, $Name, $SubName, $EmailParms);
							}

							if($Row->SMS && $User) {
								if(strlen($SMSContent) > 0) {
									self::PushSMS($User->PhoneSMS, $SMSContent);
								}else{
									self::PushSMS($User->PhoneSMS, $Content);
								}
							}
						}
					}
				}
			}

			return true;
		}


		/**
		 *	Sends a single email, using a template that already exists in the system, to the
		 *  user whom ID is received.
		 *
		 *	@static
		 *	@param string $UserID - the email address of the recipient
		 *	@param string $Type - the template type
		 *	@param string $Name - the template name
		 *	@param string $SubName - the template subname
		 *	@param array $EmailParams - a series of key/value pairs that contain the data needed for the template
		 *	@return boolean
		 */
		public static function PushEmailToUserID($UserID, $Type, $Name, $SubName, Array $EmailParms) {
			$User = new CUsers();
			if($User->OnLoad($UserID)) {
				return self::PushEmail($User->Email, $Type, $Name, $SubName, $EmailParms);			
			}
			
			return false;			
		}

	};

	//==========================================================================
?>
