<?php
	namespace WebFX;
	
    class WebControl
    {
		public $ID;
		public $Top;
		public $Left;
		public $Width;
		public $Height;
		
		public $MaximumWidth;
		public $MaximumHeight;
		
		public $Visible;
		
		public $HorizontalAlignment;
		public $VerticalAlignment;
		
		private static function GenerateRandomString($valid_chars, $length)
		{
			// start with an empty random string
			$random_string = "";

			// count the number of chars in the valid chars string so we know how many choices we have
			$num_valid_chars = strlen($valid_chars);

			// repeat the steps until we've created a string of the right length
			for ($i = 0; $i < $length; $i++)
			{
				// pick a random number from 1 up to the number of valid chars
				$random_pick = mt_rand(1, $num_valid_chars);

				// take the random character out of the string of valid chars
				// subtract 1 from $random_pick because strings are indexed starting at 0, and we started picking at 1
				$random_char = $valid_chars[$random_pick-1];

				// add the randomly-chosen char onto the end of our string so far
				$random_string .= $random_char;
			}

			// return our finished random string
			return $random_string;
		}
		
		public function __construct($id)
		{
			if ($id == null) $id = "WFX" . WebControl::GenerateRandomString("ABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890", 10);
			
			$this->ID = $id;
			$this->Visible = true;
			$this->HorizontalAlignment = HorizontalAlignment::Inherit;
			$this->VerticalAlignment = VerticalAlignment::Inherit;
		}
		
		public function GetClientProperty($name, $defaultValue = null)
		{
			if (!isset($_COOKIE[$this->ID . "__ClientProperty_" . $name])) return $defaultValue;
			return $_COOKIE[$this->ID . "__ClientProperty_" . $name];
		}
		public function SetClientProperty($name, $value, $expires = null)
		{
			setcookie($this->ID . "__ClientProperty_" . $name, $value, $expires);
		}
		
        private $isInitialized;
        protected function Initialize()
        {
            
        }
        protected function BeforeContent()
        {
            
        }
        protected function RenderContent()
        {
            
        }
        protected function AfterContent()
        {
            
        }
		
		public function BeginContent()
		{
            if (!$this->isInitialized)
            {
                $this->Initialize();
                $this->isInitialized = true;
            }
            $this->BeforeContent();
		}
		public function EndContent()
		{
            $this->AfterContent();
		}
        
        public function Render()
        {
            $this->BeginContent();
            $this->RenderContent();
            $this->EndContent();
        }
    }
?>