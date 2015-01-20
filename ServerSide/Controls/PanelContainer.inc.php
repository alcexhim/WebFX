<?php
	namespace WebFX\Controls;
	
	use WebFX\WebControl;
	
	class PanelContainer extends WebControl
	{
		public function __construct()
		{
			parent::__construct();
			
			$this->TagName = "div";
			$this->ClassList[] = "PanelContainer";
		}
	}
?>