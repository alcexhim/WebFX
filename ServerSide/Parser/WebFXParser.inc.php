<?php
	namespace WebFX\Parser;
	
	use UniversalEditor\ObjectModels\Markup\MarkupObjectModel;
	
	use WebFX\WebScript;
	
	require("XMLParser.inc.php");
	
	class Page extends \WebFX\WebPage
	{
		public $CodeFileName;
		public $Controls;
		
		public $FileName;
		
		public $MasterPage;
		
		public static function FromMarkup($element)
		{
			$page = new Page();
			
			$attFileName = $element->GetAttribute("FileName");
			if ($attFileName != null)
			{
				$page->FileName = $attFileName->Value;
			}
			
			$attrMasterPageFileName = $element->GetAttribute("MasterPageFileName");
			if ($attrMasterPageFileName != null)
			{
				$filename = getcwd() . "/" . $attrMasterPageFileName->Value;
				$page->MasterPage = MasterPage::FromFile($filename);
			}
			return $page;
		}
	}
	class MasterPage extends Page
	{
		public static function FromFile($filename)
		{
			$page = new MasterPage();
			$markup = MarkupObjectModel::FromFile($filename);
			
			$tagMasterPage = $markup->GetElement("wfx:MasterPage");
			$tagScripts = $tagMasterPage->GetElement("Scripts");
			foreach ($tagScripts->Elements as $elem)
			{
				$attContentType = $elem->GetAttribute("ContentType");
				$contentType = "text/javascript";
				if ($attContentType != null) $contentType = $attContentType->Value;
				
				$page->Scripts = new WebScript($elem->GetAttribute("FileName")->Value, $contentType);
			}
			return $page;
		}
	}
	
	class WebFXParser
	{
		public $Pages;
		
		public function __construct()
		{
			$this->Clear();
		}
		
		public function Clear()
		{
			$this->Pages = array();
		}
		
		public function LoadFile($filename)
		{
			$markup = MarkupObjectModel::FromFile($filename);
			
			foreach ($markup->Elements as $element)
			{
				if ($element->Name == "wfx:Page")
				{
					$this->Pages[] = Page::FromMarkup($element);
				}
				else if ($element->Name == "wfx:MasterPage")
				{
					$this->Pages[] = MasterPage::FromMarkup($element);
				}
			}
		}
	}
?>