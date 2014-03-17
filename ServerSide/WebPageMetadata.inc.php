<?php
	namespace WebFX;
	
    class WebPageMetadata
    {
        public $Name;
        public $Content;
        public $IsHTTPEquivalent;
        
        public function __construct($name, $content = "", $isHTTPEquivalent = false)
        {
            $this->Name = $name;
            $this->Content = $content;
            $this->IsHTTPEquivalent = $isHTTPEquivalent;
        }
    }
?>