<?php
	namespace WebFX\HTMLControls;
	
	use WebFX\System;
	
	use WebFX\HTMLControl;
	use WebFX\WebControl;
	
	use WebFX\WebControlAttribute;
	
	/**
	 * Provides an HTMLControl for the <A> HTML tag.
	 * @author Michael Becker
	 */
	class Anchor extends HTMLControl
	{
		public function __construct()
		{
			parent::__construct();
			
			$this->TagName = "a";
		}
		
		/**
		 * The URL to navigate to when this anchor is activated.
		 * @var string
		 */
		public $TargetURL;
		/**
		 * The script to execute when this anchor is activated.
		 * @var string
		 */
		public $TargetScript;
		
		protected function RenderBeginTag()
		{
			if ($this->TargetURL != null)
			{
				$this->Attributes[] = new WebControlAttribute("href", System::ExpandRelativePath($this->TargetURL));
			}
			else
			{
				$this->Attributes[] = new WebControlAttribute("href", "#");
			}
			if ($this->TargetScript != null)
			{
				$this->Attributes[] = new WebControlAttribute("onclick", $this->TargetScript);
			}
			parent::RenderBeginTag();
		}
	}
?>