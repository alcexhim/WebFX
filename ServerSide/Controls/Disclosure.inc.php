<?php
	namespace WebFX\Controls;
	use System;
	use WebFX\WebControl;
	
	class Disclosure extends WebControl
	{
		public $Expanded;
		public $Title;
		
		public function __construct($id, $title = "", $expanded = true)
		{
			parent::__construct($id);
			$this->Title = $title;
			$this->Expanded = $expanded;
		}
		
		protected function BeforeContent()
		{
			echo("<div class=\"Disclosure\">");
			echo("<div class=\"Title\"><a href=\"#\" onclick=\"" . $this->ID . ".ToggleExpanded();\"><span class=\"DisclosureButton\">&nbsp;</span> <span class=\"Title\">" . $this->Title . "</span></div>");
			echo("<div class=\"Content\">");
		}
		protected function AfterContent()
		{
			echo("</div>");
			echo("</div>");
		}
	}
?>