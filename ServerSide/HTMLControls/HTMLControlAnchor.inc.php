<?php
	namespace WebFX\HTMLControls;
	
	use WebFX\System;
	
	use WebFX\HTMLControl;
	use WebFX\WebControl;
	
	use WebFX\WebControlAttribute;
	
	class HTMLControlAnchor extends HTMLControl
	{
		public function __construct()
		{
			parent::__construct();
			
			$this->TagName = "a";
		}
		
		public $TargetURL;
		public $TargetScript;
		
		protected function RenderBeginTag()
		{
			if ($this->TargetURL != null)
			{
				$this->Attributes[] = new WebControlAttribute("href", System::ExpandRelativePath($this->TargetURL));
			}
			else
			{
				$this->Attributes[] = new WebControlAttribute("href", "#");
			}
			if ($this->TargetScript != null)
			{
				$this->Attributes[] = new WebControlAttribute("onclick", $this->TargetScript);
			}
			parent::RenderBeginTag();
		}
	}
?>