<?
	//==========================================================================
	/*
		[Permissions]
		View
		Delete
		Window_Delete
		[-]
	*/
	//=========================================================================
	class MAttention extends CModuleGeneric {
		public $Table = "Attention";

		function __construct() {
			$this->Table		= "Attention";
			$this->Classname	= "CAttention";

			parent::__construct();
		}

		//---------------------------------------------------------------------
		function OnExecute() {
			return parent::OnExecute();
		}

		//---------------------------------------------------------------------
		function OnRenderJS() { 
			$this->FileControl->LoadFile("MAttention.js", CFILE_TYPE_JS);
		}

		//----------------------------------------------------------------------
		function OnRender() {
			parent::OnRender();
		}

		//----------------------------------------------------------------------
		function OnAJAX($Action) {
			if(parent::CanAccess($Action) == false) {
				return Array(0, "You do not have permission to perform this action");
			}

			return parent::OnAJAX($Action);
		}

		//======================================================================
		function Delete() {
			return parent::Delete();
		}
	};

	//==========================================================================
?>
