<?
	//==========================================================================
	/*
		Bare bones interface used by classes that incorporate AJAX methods

		4/10/2009
	*/
	//==========================================================================
	class CAJAX {
		function OnAJAX($Action) {
			return Array(1, "");
		}

		//======================================================================
		// Static Functions
		//======================================================================
		/**
		 *	Format a URL for an AJAX request using given parameters
		 *
		 *	@static
		 *	@param string $Request - usage various based on $Type
		 *	@param string $Type - must be one of the case parameters from AJAX.php switch
		 *	@param string $Action - used as an argument to a OnAjax method for a request
		 *	@return string
		 */
		public static function FormatURL($Request, $Type, $Action) {
			return "AJAX.php?AJAX_Request=".urlencode($Request)."&AJAX_Type=".urlencode($Type)."&AJAX_Action=".urlencode($Action);
		}

		public static function Call($Request, $RequestType, $RequestAction, Array $Parms) {
			@CHook::Call($this, CHOOK_CAJAX_CALL_START);

			//This is a stupid hack for my stupidity in not simply sending $Parms
			//to every OnAJAX call in the first place, and to fix now would
			//take forever
			$OldPost = $_POST;

			$_POST = $Parms;

			$theApp = CApp::GetInstance();

			switch($RequestType) {
				/*
					Requesting for access to System functions (root directory classes)
				*/
				case "System": {
					if(class_exists($Request) == false) {
						$ReturnCode	= 0;
						$ReturnText	= "The requested System file cannot be found : ".$Request;
					}else{
						$SysClass = new $Request();

						if(($SysClass instanceof CAJAX) == false) {
							$ReturnCode	= 0;
							$ReturnText	= "The System Class does not have an AJAX interface";
						}else{
							list($ReturnCode, $ReturnText) = $SysClass->OnAJAX($RequestAction);
						}
					}

					break;
				}

				/*
					Requesting for access to Module functions (Modules folder, CMain classes)
				*/
				case "Module": {
					if(CModule::Exists($Request) == false) {
						$ReturnCode	= 0;
						$ReturnText	= "The requested Module file cannot be found : ".$Request;
					}else{
						$ModClass = CModule::LoadObject($Request, $theApp);

						$ModClass->OnInit($ModClass, $theApp->GetTheme());

						if(($ModClass instanceof CAJAX) == false) {
							$ReturnCode	= 0;
							$ReturnText	= "The Module does not have an AJAX interface";
						}else{
							list($ReturnCode, $ReturnText) = $ModClass->OnAJAX($RequestAction);
						}
					}

					break;
				}

				/*
					Requesting for access to Plugin functions (Plugin folder)
				*/
				case "Plugin": {
					if(CPlugin::Exists($Request) == false) {
						$ReturnCode	= 0;
						$ReturnText	= "The requested Plugin file cannot be found : ".$Request;
					}else{
						$PlugClass = CPlugin::LoadObject($Request, $theApp);

						$PlugClass->OnInit($PlugClass);

						if(($PlugClass instanceof CAJAX) == false) {
							$ReturnCode	= 0;
							$ReturnText	= "The Module does not have an AJAX interface";
						}else{
							list($ReturnCode, $ReturnText) = $PlugClass->OnAJAX($RequestAction);
						}
					}

					break;
				}

				/*
					Requesting for access to Payment Gateway functions (PaymentGateways folder)

					AJAX functions for Payment Gateways are only allowed for Administrators
				*/
				case "PaymentGateway": {
					if(CSecurity::IsAdmin("BusinessesPaymentGateways") == false) break;

					if(CPaymentGateway::Exists($Request) == false) {
						$ReturnCode	= 0;
						$ReturnText	= "The requested Payment Gateway file cannot be found : ".$Request;
					}else{
						$PayClass = CPaymentGateway::LoadObject($Request);

						if(($PayClass instanceof CAJAX) == false) {
							$ReturnCode	= 0;
							$ReturnText	= "The Module does not have an AJAX interface";
						}else{
							list($ReturnCode, $ReturnText) = $PayClass->OnAJAX($RequestAction);
						}
					}

					break;
				}


				/*
					Requesting for access to Template functions (Templates folder)
				*/
				case "Template": {
					if(CTemplate::Exists($Request) == false) {
						$ReturnCode	= 0;
						$ReturnText	= "The requested Template file cannot be found : ".$Request;
					}else{
						$TempClass = CTemplate::LoadObject($Request, $theApp);

						if(($TempClass instanceof CAJAX) == false) {
							$ReturnCode	= 0;
							$ReturnText	= "The Module does not have an AJAX interface";
						}else{
							list($ReturnCode, $ReturnText) = $TempClass->OnAJAX($RequestAction);
						}
					}

					break;
				}

				default: {
					$ReturnCode	= 0;
					$ReturnText	= "The Request Type is invalid : ".$RequestType;

					break;
				}
			}

			$_POST = $OldPost;

			@CHook::Call($this, CHOOK_CAJAX_CALL_END);

			return Array($ReturnCode, $ReturnText);
		}
	};

	//==========================================================================
?>
