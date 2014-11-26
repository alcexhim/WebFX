<?php
	namespace WebFX\HTMLControls;
	
	use WebFX\HTMLControl;
	use WebFX\WebControl;
	
	use WebFX\WebControlAttribute;
	
	\Enum::Create("WebFX\\HTMLControls\\HTMLControlInputType", "None", "Text", "Password");
	
	class HTMLControlInput extends HTMLControl
	{
		public function __construct()
		{
			parent::__construct();
			$this->TagName = "input";
		}
		
		public $Name;
		public $Type;
		public $Value;
		public $PlaceholderText;
		
		protected function RenderBeginTag()
		{
			switch ($this->Type)
			{
				case HTMLControlInputType::Text:
				{
					$this->Attributes[] = new WebControlAttribute("type", "text");
					break;
				}
				case HTMLControlInputType::Password:
				{
					$this->Attributes[] = new WebControlAttribute("type", "password");
					break;
				}
			}
			if (isset($this->ID)) $this->Attributes[] = new WebControlAttribute("id", $this->ID);
			if (isset($this->Name)) $this->Attributes[] = new WebControlAttribute("name", $this->Name);
			if (isset($this->Value)) $this->Attributes[] = new WebControlAttribute("value", $this->Value);
			if (isset($this->PlaceholderText)) $this->Attributes[] = new WebControlAttribute("placeholder", $this->PlaceholderText);
			parent::RenderBeginTag();
		}
	}
?>