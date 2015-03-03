<?php
	namespace WebFX\HTMLControls;
	
	use WebFX\Enumeration;
	use WebFX\HTMLControl;
	use WebFX\WebControl;
	
	use WebFX\WebControlAttribute;
	
	/**
	 * Provides an enumeration of predefined values for method of form submission.
	 * @author Michael Becker
	 */
	abstract class HTMLControlFormMethod extends Enumeration
	{
		/**
		 * No method is specified
		 * @var int 0
		 */
		const None = 0;
		/**
		 * Will use the HTTP GET method
		 * @var int 1
		 */
		const Get = 1;
		/**
		 * Will use the HTTP POST method
		 * @var int 2
		 */
		const Post = 2;
	}
	
	class HTMLControlForm extends HTMLControl
	{
		public function __construct($id = null, $method = HTMLControlFormMethod::None)
		{
			parent::__construct($id);
			
			$this->TagName = "form";
			$this->Method = $method;
		}
		
		/**
		 * The URL to direct the user to upon form submission.
		 * @var string
		 */
		public $Action;
		/**
		 * The method of form submission.
		 * @var HTMLControlFormMethod
		 */
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