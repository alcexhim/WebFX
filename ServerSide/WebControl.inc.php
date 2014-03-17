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
		
		public function __construct($id)
		{
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