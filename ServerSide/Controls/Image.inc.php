<?php
	namespace WebFX\Controls;
	
	use WebFX\System;
	use WebFX\WebControl;
	use WebFX\WebControlAttribute;
	use WebFX\WebScript;
	
	class Image extends WebControl
	{
		public $AlternateText;
		public $ImageUrl;
		
		public function __construct()
		{
			parent::__construct();
			$this->TagName = "img";
			$this->HasContent = false;
		}
		
		protected function OnInitialize()
		{
			$this->Attributes = array();
			if ($this->AlternateText != "")
			{
				$this->Attributes[] = new WebControlAttribute("alt", $this->AlternateText);
			}
			if ($this->ImageUrl != "")
			{
				$this->Attributes[] = new WebControlAttribute("src", System::ExpandRelativePath($this->ImageUrl));
			}
		}
	}
?>