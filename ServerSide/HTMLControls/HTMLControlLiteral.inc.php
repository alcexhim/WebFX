<?php
	namespace WebFX\HTMLControls;
	
	use WebFX\HTMLControl;
	use WebFX\WebControl;
	
	use WebFX\WebControlAttribute;
	
	class HTMLControlLiteral extends HTMLControl
	{
		public $Value;
		
		public function __construct($value)
		{
			parent::__construct();
			
			$this->Value = $value;
		}
		
		protected function RenderContent()
		{
			echo($this->Value);
		}
	}
?>