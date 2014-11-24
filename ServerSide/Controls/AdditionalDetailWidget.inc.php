<?php
	namespace WebFX\Controls;
	
	use WebFX\System;
	use WebFX\WebControl;
	
	\Enum::Create("WebFX\\Controls\\AdditionalDetailWidgetDisplayStyle", "Magnify", "Ellipsis", "Arrow");
	
	class AdditionalDetailWidget extends WebControl
	{
		public $DisplayStyle; /* AdditionalDetailWidgetDisplayStyle */
		public $Text;
		
		public $ShowText; /* bool */
		
		public $TargetFrame;
		public $TargetURL;
		public $TargetScript;
		
		public $MenuItems;
		public $MenuItemHeaderText;
		
		public $PreviewContentURL;
		
		public $ClassTitle;
		
		public function __construct($id)
		{
			parent::__construct($id);
			$this->ClassTitle = "";
			$this->DisplayStyle = AdditionalDetailWidgetDisplayStyle::Ellipsis;
			$this->MenuItemHeaderText = "Available Actions";
			$this->MenuItems = array();
			$this->ShowText = true;
		}
		
		private function RenderMenuItem($mi)
		{
			if (get_class($mi) == "WebFX\\Controls\\MenuItemText")
			{
				echo("<a href=\"");
				if ($mi->PostBackUrl == "")
				{
					echo("#");
				}
				else
				{
					echo(System::ExpandRelativePath($mi->PostBackUrl));
				}
				echo("\"");
				if ($mi->OnClientClick != "")
				{
					echo(" onclick=\"" . $mi->OnClientClick . "\"");
				}
				echo(">");
				echo($mi->Text);
				echo("</a>");
			}
			else if (get_class($mi) == "WebFX\\Controls\\MenuItemSeparator")
			{
				echo("<br />");
			}
		}
		
		protected function OnInitialize()
		{
			$this->TagName = "div";
			$this->ClassList[] = "AdditionalDetailWidget";
			if ($this->ShowText)
			{
				$this->ClassList[] = "Text";
			}
			switch ($this->DisplayStyle)
			{
				case AdditionalDetailWidgetDisplayStyle::Magnify:
				{
					$this->ClassList[] = "Magnify";
					break;
				}
				case AdditionalDetailWidgetDisplayStyle::Arrow:
				{
					$this->ClassList[] = "Arrow";
					break;
				}
				case AdditionalDetailWidgetDisplayStyle::Ellipsis:
				{
					$this->ClassList[] = "Ellipsis";
					break;
				}
			}
		}
		
		protected function BeforeContent()
		{
			echo("<a class=\"AdditionalDetailText\" href=\"");
			if ($this->TargetURL != "")
			{
				echo(System::ExpandRelativePath($this->TargetURL));
			}
			else
			{
				echo("#");
			}
			echo("\"");
			
			if ($this->TargetFrame != "")
			{
				echo(" target=\"" . $this->TargetFrame . "\"");
			}

			echo(">");
			echo($this->Text);
			echo("</a>");

			echo("<a class=\"AdditionalDetailButton\">&nbsp;</a>");

			echo("<div class=\"Content\">");

			echo("<div class=\"MenuItems");
			if (count($this->MenuItems) <= 0)
			{
				echo(" Empty");
			}
			echo("\">");
			echo("<div class=\"Header\">" . $this->MenuItemHeaderText . "</div>");
			echo("<div class=\"Content\">");
			foreach ($this->MenuItems as $mi)
			{
				$this->RenderMenuItem($mi);
			}
			echo("</div>");
			echo("</div>");

			echo("<div class=\"PreviewContent\">");
			echo("<div class=\"Header\">");
			if ($this->ClassTitle != "") echo("<span class=\"ClassTitle\">" . $this->ClassTitle . "</span>");
			if ($this->Text != "")
			{
				echo("<span class=\"ObjectTitle\">");
				echo("<a href=\"");
				if ($this->PostBackURL != "")
				{
					echo($this->PostBackURL);
				}
				else
				{
					echo("#");
				}
				echo("\"");

				echo(">");
				echo($this->Text);
				echo("</a>");
				echo("</span>");
			}
			echo("</div>");
			echo("<div class=\"Content\">");
		}
		protected function AfterContent()
		{
			echo("</div>");
			echo("</div>");

			echo("</div>");
		}
	}
?>