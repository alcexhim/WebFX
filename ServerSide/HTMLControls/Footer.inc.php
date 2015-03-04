<?php 
	namespace WebFX\HTMLControls;
	
	use WebFX\HTMLControl;
	
	class Footer extends HTMLControl
	{
		public function __construct()
		{
			parent::__construct();
			
			$this->TagName = "footer";
		}
	}
?>