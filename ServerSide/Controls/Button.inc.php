<?php
	namespace WebFX\Controls;
	
	use WebFX\System;
	
	use WebFX\WebControl;
	use WebFX\WebControlAttribute;
	
	use WebFX\HTMLControl;
			
	class Button extends WebControl
	{
		public $Text;
		
		protected function RenderContent()
		{
			if ($this->UseSubmitBehavior)
			{
				$tag = new HTMLControl();
				$tag->TagName = "input";
				
				$tag->ClassList = $this->ClassList;
				$tag->ClassList[] = $this->CssClass;
				$tag->Attributes[] = new WebControlAttribute("id", $this->ClientID);
				$tag->Attributes[] = new WebControlAttribute("type", "submit");
				$tag->Attributes[] = new WebControlAttribute("value", $this->Text);
				$tag->Render();
			}
			else
			{
				$tag = new HTMLControl();
				$tag->TagName = "a";
				
				$tag->ClassList = $this->ClassList;
				$tag->ClassList[] = "Button";
				$tag->ClassList[] = $this->CssClass;
				$tag->Attributes[] = new WebControlAttribute("id", $this->ClientID);
				$tag->Attributes[] = new WebControlAttribute("href", "#");
				
				$tag->InnerHTML = $this->Text;
				
				$tag->Render();
			}
		}
	}
?>