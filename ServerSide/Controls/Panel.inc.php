<?php
	namespace WebFX\Controls;
	
	use System;
	
	use WebFX\HorizontalAlignment;
	use WebFX\WebControl;
		
	class Panel extends WebControl
	{
		public $Width;
		public $Title;
		
		public function __construct($id, $title = "")
		{
			parent::__construct($id);
			$this->Title = $title;
		}
		
		protected function BeforeContent()
		{
			echo("<div class=\"Panel\" style=\"");
			if ($this->Width != null)
			{
				echo("width: " . $this->Width . "; ");
			}
			switch ($this->HorizontalAlignment)
			{
				case HorizontalAlignment::Center:
				{
					echo("margin-left: auto; ");
					echo("margin-right: auto; ");
					break;
				}
			}
			echo("\">");
			echo("<div class=\"PanelTitle\">" . $this->Title . "</div>");
			echo("<div class=\"PanelContent\">");
		}
		protected function AfterContent()
		{
			echo("</div>");
			echo("</div>");
		}
	}
?>