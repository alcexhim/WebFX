<?php
	namespace WebFX\HTMLControls;
	
	use WebFX\HTMLControl;
	use WebFX\WebControl;
	
	use WebFX\WebControlAttribute;
	
	\Enum::Create("WebFX\\HTMLControls\\HTMLControlFormMethod", "None", "Get", "Post");
	
	class HTMLControlForm extends HTMLControl
	{
		public function __construct($id = null, $method = HTMLControlFormMethod::None)
		{
			parent::__construct($id);
			
			$this->TagName = "form";
			$this->Method = $method;
		}
		
		public $Action;
		public $Method;
		
		protected function RenderBeginTag()
		{
			if ($this->Action != null)
			{
				$this->Attributes[] = new WebControlAttribute("action", $this->Action);
			}
			if ($this->Method != HTMLControlFormMethod::None)
			{
				$methodstr = "";
				switch ($this->Method)
				{
					case HTMLControlFormMethod::Get:
					{
						$methodstr = "GET";
						break;
					}
					case HTMLControlFormMethod::Post:
					{
						$methodstr = "POST";
						break;
					}
				}
				$this->Attributes[] = new WebControlAttribute("method", $methodstr);
			}
			parent::RenderBeginTag();
		}
	}
?>