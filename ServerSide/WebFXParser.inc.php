<?php
	namespace WebFX;
	
	class WebFXParser
	{
		private $reader;
		
		public static function Create($filename)
		{
			$this->reader = new XMLReader();
			$this->reader->open($filename, null, LIBXML_HTML_NOIMPLIED);
			
			// see if we are on a WFX node
			echo($this->reader->readString());
			// $this->reader->getAttribute("ID");
		}
	}
?>