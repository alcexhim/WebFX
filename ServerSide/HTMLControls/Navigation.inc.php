<?php 
	namespace WebFX\HTMLControls;
	
	use WebFX\HTMLControl;
	
	class Navigation extends HTMLControl
	{
		public function __construct()
		{
			parent::__construct();
			
			$this->TagName = "nav";
		}
	}
?>