<?php
	namespace WebFX\Engines\Marvel\MenuItems;
	class MenuItemCommandReference extends MenuItem
	{
		public $CommandID;
		
		public function __construct($commandID)
		{
			$this->CommandID = $commandID;
		}
	}
?>