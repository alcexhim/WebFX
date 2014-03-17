<?php
	namespace WebFX;
	
    class WebStyleSheet
    {
        public $ContentType;
        public $FileName;
        
        public function __construct($FileName, $ContentType = "text/css")
        {
            $this->FileName = $FileName;
            $this->ContentType = $ContentType;
        }
    }
?>