<?php
	namespace WebFX;
	
	\Enum::Create("WebFX\\Parser\\WebPageMessageSeverity", "None", "Information", "Warning", "Error");
	
	class WebPageMessage
	{
		public $Message;
		public $Severity;
		
		public function __construct($message, $severity = WebPageMessageSeverity::None)
		{
			$this->Message = $message;
			$this->Severity = $severity;
		}
	}
?>