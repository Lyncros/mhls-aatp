<?php

class MVendorsManagement extends CTemplateModule {
	function __construct() {
		parent::__construct("./Modules/VendorsManagement/Views");
		
		$this->JSFile		= "MVendorsManagement.js";		
		
	}

	function IndexParams() {
		return array();
	}
}

?>
