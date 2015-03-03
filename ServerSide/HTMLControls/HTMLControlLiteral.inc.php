<?php
	namespace WebFX\HTMLControls;
	
	use WebFX\HTMLControl;
	use WebFX\WebControl;
	
	use WebFX\WebControlAttribute;
	use WebFX\System;
	
	class HTMLControlLiteral extends HTMLControl
	{
		public $Value;
		
		public function __construct($value = null)
		{
			parent::__construct();
			
			if ($value == null) $value = "";
			$this->Value = $value;
		}
		
		protected function RenderContent()
		{
			echo(System::ExpandRelativePath($this->Value));
		}
	}
?>