<?php
	namespace WebFX;
	
	class WebApplication
	{
		public $Configuration;
		public $Modules;
		
		public function __construct()
		{
			
			$this->Configuration = array();
			$this->Modules = array();
		}
		public function Run()
		{
			echo(ROOT_PATH);
		}
	}
?>