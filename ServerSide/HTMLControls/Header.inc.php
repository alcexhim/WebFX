<?php 
	namespace WebFX\HTMLControls;
	
	use WebFX\HTMLControl;
	
	class Header extends HTMLControl
	{
		public function __construct()
		{
			parent::__construct();
			
			$this->TagName = "header";
		}
	}
?>