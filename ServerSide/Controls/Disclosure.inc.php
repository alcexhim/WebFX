<?php
	namespace WebFX\Controls;
	
	use WebFX\System;
	use WebFX\WebControl;
	use WebFX\WebControlAttribute;
	
	class Disclosure extends WebControl
	{
		public $Expanded;
		public $Title;
		
		public function __construct($id, $title = "", $expanded = true)
		{
			parent::__construct($id);
			$this->Title = $title;
			$this->Expanded = $expanded;
			
			$this->TagName = "div";
			$this->ClassList[] = "Disclosure";
		}
		
		protected function RenderBeginTag()
		{
			if ($this->Expanded)
			{
				$this->ClassList[] = "Expanded";
				$this->Attributes[] = new WebControlAttribute("data-expanded", "true");
			}
			parent::RenderBeginTag();
		}
		
		protected function BeforeContent()
		{
			echo("<div class=\"Title\"><a href=\"#\"><span class=\"DisclosureButton\">&nbsp;</span> <span class=\"Title\">" . $this->Title . "</span></a></div>");
			echo("<div class=\"Content\">");
		}
		protected function AfterContent()
		{
			echo("</div>");
		}
	}
?>