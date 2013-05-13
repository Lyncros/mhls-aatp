<?
	//==========================================================================
	/*
		A class for iterating through Parsed Data from CDataParser

		12/22/2009 9:35 AM
	*/
	//==========================================================================
	class CDataParserIterator implements Countable, Iterator {
		private $Data = Array();
		
		function __construct($Data) {
			if(!is_array($Data)) {
				throw new Exception("Invalid array passed to __construct");
			}
			
			$this->Data = $Data;

			$this->rewind();
		}
		
		//Iterator methods
		public function rewind() {			
			reset($this->Data);
		}
		
		public function valid() {
			return key($this->Data) !== null && key($this->Data) !== false;
		}

		public function current() {	
			return current($this->Data);
		}

		public function key() {	
			return key($this->Data);
		}
		
		public function next() {
			return next($this->Data);
		}
		
		//Countable
		public function count() {
			return count($this->Data);
		}

		function __get($Key) {
			if($this->Data) {
				return $this->Data[$this->key()][$Key];
			}

			return false;
		}

		function __set($Key, $Value) {
			if($this->Data) {
				return ($this->Data[$this->key()][$Key] = $Value);
			}

			return false;
		}

		function __isset($Key) {
			return ($this->Data && isset($this->Data[$this->key()][$Key]));
		}
	};
?>
