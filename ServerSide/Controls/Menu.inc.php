<?php
	namespace WebFX\Controls;
	use WebFX\System;
	use WebFX\WebControl;
	
	class Menu extends WebControl
	{
		public $Items;
		
		protected function RenderContent()
		{
			echo("<div class=\"Menu Popup\" id=\"Menu_" . $this->ID . "\" style=\"");
			if ($this->Top != null) echo("top: " . $this->Top . ";");
			if ($this->Left != null) echo("left: " . $this->Left . ";");
			if ($this->Width != null) echo("width: " . $this->Width . ";");
			if ($this->Height != null) echo("height: " . $this->Height . ";");
			if ($this->MaximumWidth != null) echo("max-width: " . $this->MaximumWidth . ";");
			if ($this->MaximumHeight != null) echo("max-height: " . $this->MaximumHeight . ";");
			
			echo("\">");
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
			echo("</div>");
			echo("<script type=\"text/javascript\">var " . $this->ID . " = new Menu('" . $this->ID . "');</script>");
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