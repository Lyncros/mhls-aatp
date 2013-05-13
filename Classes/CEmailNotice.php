<?
	//=========================================================================
	/*
		A class for sending out Email Notices

		4/10/2009
	*/
	//=========================================================================
	class CEmailNotice {
		public $TemplateFile	= "";

		public $Content			= "";

		public $FromName		= "";
		public $FromEmail		= "";

		public $ReplyTo			= "";

		public $ToEmail			= "";

		public $Subject			= "";

		/*
			Whatever key names assigned here will be replaced
			with the set value inside of the template file.
		*/
		public $Data = Array();

		/*
			Send an Email
		*/
		function Send() {
			//Does the template exist?
			if(strlen($this->TemplateFile) > 0 && ($TemplateContents = file_get_contents($this->TemplateFile)) !== false) {
				foreach($this->Data as $Key => $Value) {
					$TemplateContents = str_replace("\$".$Key, $Value, $TemplateContents);
				}
			}else{
				$TemplateContents = $this->Content;
			}

			if(strlen($this->FromName) <= 0) {
				$this->FromName = $this->FromEmail;
			}

			$Headers  = "MIME-Version: 1.0\n";
			$Headers .= "Content-type: text/html; charset=iso-8859-1\n";
			$Headers .= "From: ".$this->FromName." <".$this->FromEmail.">\n";

			if(strlen($this->ReplyTo) > 0) {
				$Headers .= "Reply-To: ".$this->ReplyTo."\n";
			}

			$Headers .= "X-Sender: <".$this->FromEmail.">\n";
			$Headers .= "X-Mailer: PHP\n"; 
			$Headers .= "X-Priority: 1\n"; 
			$Headers .= "Return-Path: <".$this->FromEmail.">\n";  

			if(mail($this->ToEmail, $this->Subject, $TemplateContents, $Headers) == false) {
				trigger_error("Unable to send email notice", E_WARNING);
				return false;
			}

			return true;
		}
	};

	//==========================================================================
?>
