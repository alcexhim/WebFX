<?php
	namespace WebFX\Controls;
	use WebFX\System;
	use WebFX\WebControl;
	
	use WebFX\WebControlAttribute;
	use WebFX\WebStyleSheetRule;
	
	class Menu extends WebControl
	{
		public $Items;
		
		public function __construct($id = null)
		{
			parent::__construct($id);
			
			$this->TagName = "div";
			$this->ClassList[] = "Menu";
			$this->ClassList[] = "Popup";
			
			if ($this->Top != null) $this->StyleRules[] = new WebStyleSheetRule("top", $this->Top);
			if ($this->Left != null) $this->StyleRules[] = new WebStyleSheetRule("left", $this->Left);
			if ($this->Width != null) $this->StyleRules[] = new WebStyleSheetRule("width", $this->Width);
			if ($this->Height != null) $this->StyleRules[] = new WebStyleSheetRule("height", $this->Height);
			if ($this->MaximumWidth != null) $this->StyleRules[] = new WebStyleSheetRule("max-width", $this->MaximumWidth);
			if ($this->MaximumHeight != null) $this->StyleRules[] = new WebStyleSheetRule("max-height", $this->MaximumHeight);
		}
		
		protected function RenderContent()
		{
			foreach ($this->Items as $menuItem)
			{
				if (get_class($menuItem) == "WebFX\\Controls\\MenuItemCommand")
				{
					echo("<a class=\"MenuItem\" href=\"" . System::ExpandRelativePath($menuItem->NavigateUrl) . "\" onclick=\"");
					if ($menuItem->OnClientClick != null)
					{
						echo($menuItem->OnClientClick . "; ");
					}
					echo($this->ID . ".PopupMenu.Hide(); return false;\">");
					if ($menuItem->Description != null)
					{
						echo("<span class=\"Title\">" . $menuItem->Title . "</span> ");
						echo("<span class=\"Description\">" . $menuItem->Description . "</span>");
					}
					else
					{
						echo("<span class=\"TitleWithoutDescription\">" . $menuItem->Title . "</span>");
					}
					echo("</a>");
				}
				else if (get_class($menuItem) == "WebFX\\Controls\\MenuItemHeader")
				{
					echo("<span class=\"MenuItem\"><span class=\"MenuItemTitle\">" . $menuItem->Title . "</span> <span class=\"MenuItemSubtitle\">" . $menuItem->Subtitle . "</span></span>");
				}
				else if (get_class($menuItem) == "WebFX\\Controls\\MenuItemSeparator")
				{
					echo("<hr class=\"MenuItem\" />");
				}
				else
				{
					echo("<!-- Unknown MenuItem class: " . get_class($menuItem) . " -->");
				}
			}
		}
	}
	
	class MenuItem
	{
	}
	class MenuItemHeader extends MenuItem
	{
		public $Title;
		public $Subtitle;
		public function __construct($title, $subtitle = null)
		{
			$this->Title = $title;
			$this->Subtitle = $subtitle;
		}
	}
	class MenuItemCommand extends MenuItem
	{
		public $Title;
		public $NavigateUrl;
		public $OnClientClick;
		public $Description;
		
		public function __construct($title, $navigateUrl = "#", $onClientClick = null, $description = null)
		{
			$this->Title = $title;
			$this->NavigateUrl = $navigateUrl;
			$this->OnClientClick = $onClientClick;
			$this->Description = $description;
		}
	}
	class MenuItemSeparator extends MenuItem
	{
	}
	class MenuItemMenu extends MenuItem
	{
		public $Title;
		public $Items;
		
		public function __construct($title, $menuItems = array())
		{
			$this->Title = $title;
			$this->Items = $menuItems;
		}
	}
?>