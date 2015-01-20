<?php
	namespace WebFX\Controls;
	
	use System;
	
	use WebFX\HorizontalAlignment;
	use WebFX\WebControl;
		
	class Panel extends WebControl
	{
		public $FooterContent;
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
			echo("<div class=\"Header\">" . $this->Title . "</div>");
			echo("<div class=\"Content\">");
		}
		
		public function BeginFooter()
		{
			echo("<div class=\"Footer\">");
		}
		public function EndFooter()
		{
			echo("</div>");
		}
		
		protected function AfterContent()
		{
			echo("</div>");
			
			if (is_callable($this->FooterContent))
			{
				$this->BeginFooter();
				call_user_func($this->FooterContent);
				$this->EndFooter();
			}
			
			echo("</div>");
		}
	}
?>