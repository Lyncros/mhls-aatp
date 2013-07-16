<?
	//==========================================================================
	/*
		A class for iterating through a mySQL resource

		12/22/2009 9:35 AM
	*/
	//==========================================================================
	class CTableIterator implements Countable, Iterator {
		private $Result;
		private $Key = 0;
		public	$Current = array();
		
		function __construct($Result) {
			if(!is_resource($Result)) {
				throw new Exception("Invalid result passed to __construct");
			}
			
			$this->Result = $Result;

			$this->rewind();
		}

		function __destruct() {
			if(is_resource($this->Result)) {
				mysql_free_result($this->Result);
			}
		}
		
		//Iterator methods
		public function rewind() {
			$this->Key = 0;

			if ($this->count() > 0) {
				mysql_data_seek($this->Result, 0);
			}

			$this->next();
		}
		
		public function valid() {
			return (bool)$this->Current;
		}

		public function current() {	
			return $this;
		}

		public function Key() {	
			return $this->Key;	
		}
		
		public function next() {
			if($this->count() > 0) {
				$this->Current = mysql_fetch_assoc($this->Result);

				++$this->Key;
			}
		}

		public function seek($Index) {
			if($Index < 0 || $Index >= $this->count()) return;

			mysql_data_seek($this->Result, $Index);

			$this->next();
		}
		
		//Countable
		public function count() {
			return mysql_num_rows($this->Result);
		}

		function __get($Variable) {
			if($this->Current) {
				return $this->Current[$Variable];
			}

			return false;
		}

		function __set($Variable, $Value) {
			if($this->Current) {
				return ($this->Current[$Variable] = $Value);
			}

			return false;
		}

		function __isset($Variable) {
			return ($this->Current && isset($this->Current[$Variable]));
		}

		//======================================================================
		// Other
		//======================================================================
		function ColumnExists($Name) {
			return array_key_exists($Name, $this->Current);
		}
		
		function RowsToAssociativeArray($Column, $IDColumn = "ID") {
			$Return = Array();

			foreach($this as $Row) {
				$Return[$Row->{$IDColumn}] = $Row->{$Column};
			}

			return $Return;
		}
		
		function RowsToArray($Column) {
			$Return = Array();

			foreach($this as $Row) {
				$Return[] = $Row->{$Column};
			}

			return $Return;
		}
		
		function MultipleColumnRowsToArray($Columns) {
			$Return = Array();
			$ColumnsArray = explode(",",$Columns);
			foreach($this as $Row) {
				$Return[] = $this->ConcatColumnValues($Row, $ColumnsArray);
			}

			return $Return;
		}
		
		function RowsToAssociativeArrayWithMultipleColumns($Columns, $IDColumn = "ID") {
			$Return = Array();

			$ColumnsArray = explode(",",$Columns);
			foreach($this as $Row) {
				$Return[$Row->{$IDColumn}] = $this->ConcatColumnValues($Row, $ColumnsArray);
			}

			return $Return;
		}
		
		function ConcatColumnValues($Row, $ColumnsArray) {
			$value = "";
			$isFirst = true;
			foreach ($ColumnsArray as $Column)
			{
				if(!$isFirst) $value .= ", ";
				$value .= $Row->{$Column};
				$isFirst = false;
			}
			
			return $value;
		}
	};
?>
