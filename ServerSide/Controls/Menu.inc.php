<?php
	namespace WebFX\Controls;

	use WebFX\Enumeration;
	use WebFX\System;
	use WebFX\WebControl;
	
	use WebFX\WebControlAttribute;
	use WebFX\WebStyleSheetRule;
	
	use WebFX\HTMLControl;
	use WebFX\HTMLControls\HTMLControlAnchor;
			
	/**
	 * Provides an enumeration of predefined values for orientation of a menu.
	 * @author Michael Becker
	 */
	abstract class MenuOrientation extends Enumeration
	{
		/**
		 * The menu is displayed horizontally.
		 * @var int 1
		 */
		const Horizontal = 1;
		/**
		 * The menu is displayed vertically.
		 * @var int 2
		 */
		const Vertical = 2;
	}
	
	class Menu extends WebControl
	{
		/**
		 * Determines whether the menu displays horizontally or vertically.
		 * @var MenuOrientation
		 */
		public $Orientation;
		/**
		 * A collection of MenuItems on this Menu.
		 * @var MenuItem
		 */
		public $Items;
		
		public function __construct()
		{
			parent::__construct();
			
			$this->ParseChildElements = true;
			
			$this->TagName = "ul";
			$this->ClassList[] = "Menu";
			
			if ($this->Top != null) $this->StyleRules[] = new WebStyleSheetRule("top", $this->Top);
			if ($this->Left != null) $this->StyleRules[] = new WebStyleSheetRule("left", $this->Left);
			if ($this->Width != null) $this->StyleRules[] = new WebStyleSheetRule("width", $this->Width);
			if ($this->Height != null) $this->StyleRules[] = new WebStyleSheetRule("height", $this->Height);
			if ($this->MaximumWidth != null) $this->StyleRules[] = new WebStyleSheetRule("max-width", $this->MaximumWidth);
			if ($this->MaximumHeight != null) $this->StyleRules[] = new WebStyleSheetRule("max-height", $this->MaximumHeight);
		}
		
		protected function RenderBeginTag()
		{
			if ($this->Orientation == "Horizontal" || $this->Orientation == MenuOrientation::Horizontal)
			{
				$this->ClassList[] = "Horizontal";
			}
			else if ($this->Orientation == "Vertical" || $this->Orientation == MenuOrientation::Vertical)
			{
				$this->ClassList[] = "Vertical";
			}
			
			$this->Controls = array();
			foreach ($this->Items as $menuItem)
			{
				$this->Controls[] = Menu::CreateMenuItemControl($menuItem);
			}
			parent::RenderBeginTag();
		}
		
		public static function CreateMenuItemControl($menuItem)
		{
			if (get_class($menuItem) == "WebFX\\Controls\\MenuItemCommand")
			{
				$li = new HTMLControl();
				$li->TagName = "li";
				
				if ($menuItem->Selected)
				{
					$li->ClassList[] = "Selected";
				}
				
				$a = new HTMLControlAnchor();
				$a->TargetURL = $menuItem->NavigateUrl;
				if ($menuItem->OnClientClick != null)
				{
					$a->Attributes[] = new WebControlAttribute("onclick", $menuItem->OnClientClick);
				}
				
				if ($menuItem->IconName != "")
				{
					$iIcon = new HTMLControl();
					$iIcon->TagName = "i";
					$iIcon->ClassList[] = "fa";
					$iIcon->ClassList[] = "fa-" . $menuItem->IconName;
					$a->Controls[] = $iIcon;
				}
					
				if ($menuItem->Description != null)
				{
					$spanTitle = new HTMLControl();
					$spanTitle->TagName = "span";
					$spanTitle->ClassList[] = "Title";
					$spanTitle->InnerHTML = $menuItem->Title;
					$a->Controls[] = $spanTitle;
					
					$spanDescription = new HTMLControl();
					$spanDescription->TagName = "span";
					$spanDescription->ClassList[] = "Description";
					$spanDescription->InnerHTML = $menuItem->Description;
					$a->Controls[] = $spanDescription;
				}
				else
				{
					$spanTitle = new HTMLControl();
					$spanTitle->TagName = "span";
					$spanTitle->ClassList[] = "Title NoDescription";
					$spanTitle->InnerHTML = $menuItem->Title;
					$a->Controls[] = $spanTitle;
				}
					
				$li->Controls[] = $a;
					
				if (count($menuItem->Items) > 0)
				{
					$ul = new HTMLControl();
					$ul->TagName = "ul";
					$ul->ClassList[] = "Menu";
					
					foreach ($menuItem->Items as $item1)
					{
						$ul->Controls[] = Menu::CreateMenuItemControl($item1);
					}
					
					$li->Controls[] = $ul;
				}
				return $li;
			}
			else if (get_class($menuItem) == "WebFX\\Controls\\MenuItemHeader")
			{
				$span = new HTMLControl();
				$span->TagName = "span";
				$span->ClassList[] = "MenuItem";
				
				$spanHeader = new HTMLControl();
				$spanHeader->TagName = "span";
				$spanHeader->ClassList[] = "MenuItemTitle";
				$spanHeader->InnerHTML = $menuItem->Title;
				$span->Controls[] = $spanHeader;
				
				$spanHeader = new HTMLControl();
				$spanHeader->TagName = "span";
				$spanHeader->ClassList[] = "MenuItemSubtitle";
				$spanHeader->InnerHTML = $menuItem->Subtitle;
				$span->Controls[] = $spanHeader;
				return $span;
			}
			else if (get_class($menuItem) == "WebFX\\Controls\\MenuItemSeparator")
			{
				$hr = new HTMLControl();
				$hr->TagName = "hr";
				return $hr;
			}
			else
			{
				System::WriteErrorLog("Unknown MenuItem class: " . get_class($menuItem));
			}
		}
	}
	
	class MenuItem extends WebControl
	{
		public function __construct()
		{
			$this->ParseChildElements = true;
		}
	}
	class MenuItemHeader extends MenuItem
	{
		public $Title;
		public $Subtitle;
		public function __construct($title = null, $subtitle = null)
		{
			parent::__construct();
			$this->Title = $title;
			$this->Subtitle = $subtitle;
		}
	}
	class MenuItemCommand extends MenuItem
	{
		public $Items;
		public $IconName;
		public $Title;
		public $NavigateUrl;
		public $OnClientClick;
		public $Selected;
		public $Description;
		
		public function __construct($title = null, $navigateUrl = "#", $onClientClick = null, $description = null)
		{
			parent::__construct();
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