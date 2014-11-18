<?php
	namespace WebFX;
	
	class HTMLControl extends WebControl
	{
		public $InnerHTML;
		
		protected function RenderContent()
		{
			echo($this->InnerHTML);
		}
	}
?>