<?
	//==========================================================================
	/*
		Class for managing sessions

		6/24/2009 7:31 AM
	*/
	//==========================================================================
	class CSession extends ArrayObject {
		private $Name = "";

		function __construct($Name = "") {
			$this->SetSection($Name);

			ArrayObject::__construct(array(), ArrayObject::ARRAY_AS_PROPS);
		}

		public static function OnInit($SessionID = "") {
			//$Path = session_save_path("d:/phptemp");

			if(strlen($SessionID) > 0) {
				session_id($SessionID);
			}

			session_id();
			session_start();
		}

		public static function OnDone() {
		}

		function Load($Session) {
			return true;
		}

		function Clear() {
			$_SESSION[$this->Name] = array();
		}

		function SetSection($Name) {
			$this->Name = $Name;

			if(!isset($_SESSION[$this->Name])) {
				$_SESSION[$this->Name] = Array();
			}

			return $this;
		}

		//======================================================================
		// Object Access
		//======================================================================
		public function &__get($VarName) {
			if(!isset($_SESSION[$this->Name][$VarName])) return NULL; 

			return $_SESSION[$this->Name][$VarName];
		}

		public function &__set($Name, $Value) {
			$_SESSION[$this->Name][$Name] = $Value;

			return $_SESSION[$this->Name][$Name];
		}

		public function __isset($Name) {
			if(!isset($_SESSION[$this->Name][$Name])) {
				return false; 
			}

			return true;
		}

		public function __unset($Name) {
			if(!isset($_SESSION[$this->Name][$Name])) return false; 

			unset($_SESSION[$this->Name][$Name]);

			return true;
		}

		//======================================================================
		// Random Access (Array)
		//======================================================================
		function offsetExists($Offset) {
			return $this->__isset($Offset);
		}

		function offsetGet($Offset) {
			return $this->__get($Offset);
		}

		function offsetSet($Offset, $Value) {
			return $this->__set($Offset, $Value);
		}

		function offsetUnset($Offset) {
			return $this->__unset($Offset);
		}
	};

	//==========================================================================
?>
