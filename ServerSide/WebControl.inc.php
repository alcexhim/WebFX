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