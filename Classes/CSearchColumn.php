<?
	//==========================================================================
	/*
		Class used by CSearch.php for each column

		4/10/2009
	*/
	//=========================================================================
	class CSearchColumn {
		public $Name				= "";
		public $Column				= "";

		public $Width				= "100%";
		public $SearchType			= 0;

		public $Hidden				= false;

		//Foreign Table Link
		public $FTableBusinessesID	= 0;
		public $FTable				= "";
		public $FLink				= "";
		public $FColumn 			= "";

		public $Callback			= "";
	};

	//=========================================================================
?>
