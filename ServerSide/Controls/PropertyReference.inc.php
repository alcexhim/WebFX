<?php
	namespace WebFX\Controls;
	
	use WebFX\WebControl;
	use WebFX\System;
		
	class PropertyReference extends WebControl
	{
		public $DefaultValue;
		
		protected function RenderContent()
		{
			echo(System::GetConfigurationValue($this->ID, $this->DefaultValue));
		}	
	}
?>