<?php 
	namespace WebFX\HTMLControls;
	
	use WebFX\HTMLControl;
	
	class Paragraph extends HTMLControl
	{
		public function __construct()
		{
			parent::__construct();
			
			$this->TagName = "p";
		}
	}
?>