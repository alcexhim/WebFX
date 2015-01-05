<?php
	namespace WebFX\Engines\Marvel\MenuItems;
	/**
	 * Represents a MenuItem that renders a separator.
	 * @author Michael Becker
	 */
	class MenuItemSeparator extends MenuItem
	{
		public $Text;
	
		public function __construct($text = null)
		{
			$this->Text = $text;
		}
	}
?>