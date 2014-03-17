<?php
	namespace WebFX\Pages;
	
	class MessagePage extends \WebFX\WebPage
	{
		public $Message;
		
		protected function BeforeContent()
		{
			?><div class="Message"><?php
		}
		protected function RenderContent()
		{
			?><div class="Content"><?php echo($this->Message); ?></div><?php
		}
		protected function AfterContent()
		{
			?></div><?php
		}
	}
?>