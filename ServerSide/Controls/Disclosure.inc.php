<?php
	namespace WebFX\Controls;
	
	use WebFX\System;
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
			echo("<div class=\"Disclosure");
			if ($this->Expanded) echo(" Expanded");
			echo("\" id=\"Disclosure_" . $this->ID . "\">");
			echo("<div class=\"Title\"><a href=\"#\" onclick=\"" . $this->ID . ".ToggleExpanded();\"><span class=\"DisclosureButton\">&nbsp;</span> <span class=\"Title\">" . $this->Title . "</span></a></div>");
			echo("<div class=\"Content\">");
		}
		protected function AfterContent()
		{
			echo("</div>");
			echo("</div>");
			echo("<script type=\"text/javascript\">var " . $this->ID . " = new Disclosure(\"" . $this->ID . "\");</script>");
		}
	}
?>