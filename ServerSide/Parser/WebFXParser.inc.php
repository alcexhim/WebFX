<?php
	namespace WebFX\Parser;
	
	use UniversalEditor\ObjectModels\Markup\MarkupObjectModel;
	
	use WebFX\System;
	
	use WebFX\WebNamespaceReference;
	use WebFX\WebScript;
	use WebFX\WebStyleSheet;
	use WebFX\WebVariable;
	
	use WebFX\WebControlAttribute;
	use WebFX\WebControlClientIDMode;
	
	use WebFX\HTMLControl;
	use WebFX\HTMLControls\HTMLControlLiteral;
	
	require("XMLParser.inc.php");
	
	class ControlLoader
	{
		public static $Namespaces;
		
		public static function ParseChildren($elem, &$obj)
		{
			// our parent is a WebControl and we should parse its children as properties
			if (is_array($elem->Elements))
			{
				foreach ($elem->Elements as $elem1)
				{
					if (get_class($elem1) != "UniversalEditor\\ObjectModels\\Markup\\MarkupTagElement") continue;
					
					foreach ($elem1->Elements as $elem2)
					{
						if (get_class($elem2) != "UniversalEditor\\ObjectModels\\Markup\\MarkupTagElement") continue;
					
						$i = stripos($elem->Name, ":");
						$prefix = substr($elem2->Name, 0, $i);
						$name = substr($elem2->Name, $i + 1);
						
						if (isset(ControlLoader::$Namespaces[$prefix]) && ControlLoader::$Namespaces[$prefix] != "")
						{
							$realname = ControlLoader::$Namespaces[$prefix] . "\\" . $name;
						}
						else
						{
							$realname = $name;
						}
						
						$obj1 = new $realname();
						ControlLoader::LoadAttributes($elem2, $obj1);
						
						if ($obj1->ParseChildElements)
						{
							ControlLoader::ParseChildren($elem2, $obj1);
						}
						else
						{
							if (is_array($elem2->Elements))
							{
								foreach ($elem2->Elements as $elem3)
								{
									ControlLoader::LoadControl($elem3, $obj1);
								}
							}
						}
						
						$obj->{$elem1->Name}[] = $obj1;
					}
				}
			}
		}
		public static function LoadAttributes($elem, &$obj)
		{
			if (is_array($elem->Attributes))
			{
				foreach ($elem->Attributes as $attr)
				{
					$obj->{$attr->Name} = $attr->Value;
				}
			}
		}
		public static function LoadControl($elem, $parent)
		{
			if (get_class($elem) == "UniversalEditor\\ObjectModels\\Markup\\MarkupTagElement")
			{
				$i = stripos($elem->Name, ":");
				if ($i !== false)
				{
					$prefix = substr($elem->Name, 0, $i);
					$name = substr($elem->Name, $i + 1);
					
					if (isset(ControlLoader::$Namespaces[$prefix]) && ControlLoader::$Namespaces[$prefix] != "")
					{
						$realname = ControlLoader::$Namespaces[$prefix] . "\\" . $name;
					}
					else
					{
						$realname = $name;
					}
					
					$obj = new $realname();
					ControlLoader::LoadAttributes($elem, $obj);
					
					if (is_subclass_of($obj, "WebFX\\WebControl") && $obj->ParseChildElements)
					{
						ControlLoader::ParseChildren($elem, $obj);
					}
					else
					{
						if (is_array($elem->Elements))
						{
							foreach ($elem->Elements as $elem1)
							{
								ControlLoader::LoadControl($elem1, $obj);
							}
						}
					}
					
					$obj->ParentObject = $parent;
					$parent->Controls[] = $obj;
				}
				else
				{
					$ctl = new HTMLControl();
					$ctl->TagName = $elem->Name;
					if (is_array($elem->Attributes))
					{
						foreach ($elem->Attributes as $attr)
						{
							$ctl->Attributes[] = new WebControlAttribute($attr->Name, $attr->Value);
						}
					}
					if (is_array($elem->Elements) && count($elem->Elements) > 0)
					{
						foreach ($elem->Elements as $elem1)
						{
							ControlLoader::LoadControl($elem1, $ctl);
						}
					}
					$ctl->ParentObject = $parent;
					$parent->Controls[] = $ctl;
				}
			}
			else if (get_class($elem) == "UniversalEditor\\ObjectModels\\Markup\\MarkupLiteralElement")
			{
				$parent->Controls[] = new HTMLControlLiteral($elem->Value);
			}
		}
	}
	class Page
	{
		public $Controls;
		
		public $FileName;
		
		public $MasterPage;
		
		public $References;
		public $Scripts;
		public $StyleSheets;
		
		public $Title;
		
		public function __construct()
		{
			$this->Controls = array();
			$this->References = array();
			$this->Scripts = array();
			$this->StyleSheets = array();
		}
		
		public function MergeMasterPageControls($controls)
		{
			$newControls = array();
			if ($this->MasterPage != null)
			{
				foreach ($controls as $control)
				{
					if (get_class($control) == "WebFX\\Controls\\SectionPlaceholder")
					{
						$pageControls = $this->Controls;
						foreach ($pageControls as $pageControl)
						{
							if (get_class($pageControl) != "WebFX\\Controls\\Section") continue;
							$newControls[] = $pageControl;
						}
					}
					else
					{
						$control->Controls = $this->MergeMasterPageControls($control->Controls);
						$newControls[] = $control;
					}
				}
			}
			return $newControls;
		}
		
		public function Render()
		{
			header('Content-Type: application/xhtml+xml;charset=UTF-8');
			
			$controls = $this->Controls;
			if ($this->MasterPage != null)
			{
				$controls = $this->MergeMasterPageControls($this->MasterPage->Controls);
			}
			
			foreach ($controls as $ctl)
			{
				$ctl->Initialize();
			}
			
			$scripts = $this->Scripts;
			if ($this->MasterPage != null)
			{
				$scripts = $this->MasterPage->Scripts;
				foreach ($this->Scripts as $script)
				{
					$scripts[] = $script;
				}
			}
			
			$stylesheets = $this->StyleSheets;
			if ($this->MasterPage != null)
			{
				$stylesheets = $this->MasterPage->StyleSheets;
				foreach ($this->StyleSheets as $stylesheet)
				{
					$stylesheets[] = $stylesheet;
				}
			}
			
			$references = $this->References;
			if ($this->MasterPage != null)
			{
				$references = $this->MasterPage->References;
				foreach ($this->References as $reference)
				{
					$references[] = $reference;
				}
			}
			
			$variables = $this->Variables;
			if ($this->MasterPage != null)
			{
				$variables = $this->MasterPage->Variables;
				foreach ($this->Variables as $variables)
				{
					$variables[] = $variables;
				}
			}
			System::$Variables = $variables;
			
			echo("<?xml version=\"1.0\" encoding=\"utf-8\"?>");
			echo("<!DOCTYPE html>");
			echo("<html xmlns=\"http://www.w3.org/1999/xhtml\"");
			
			$referenceAlreadyUsed = array();
			foreach ($references as $reference)
			{
				$referenceAlreadyUsed[$reference->TagPrefix] = false;
			}
			
			foreach ($references as $reference)
			{
				if ($referenceAlreadyUsed[$reference->TagPrefix]) continue;
				echo(" xmlns:" . $reference->TagPrefix . "=\"" . $reference->NamespaceURL . "\"");
				$referenceAlreadyUsed[$reference->TagPrefix] = true;
			}
			echo(">");
			echo("<head>");
			foreach ($scripts as $script)
			{
				$tagScript = new HTMLControl();
				$tagScript->ClientIDMode = WebControlClientIDMode::None;
				$tagScript->TagName = "script";
				
				if ($script->ContentType != "")
				{
					$tagScript->Attributes[] = new WebControlAttribute("type", $script->ContentType);
				}
				if ($script->FileName != "")
				{
					$tagScript->Attributes[] = new WebControlAttribute("src", System::ExpandRelativePath($script->FileName));
				}
				if ($script->Content != "")
				{
					$tagScript->InnerHTML = $script->Content;
				}
				$tagScript->Render();
			}
			foreach ($stylesheets as $stylesheet)
			{
				$tagStyleSheet = new HTMLControl();
				$tagStyleSheet->ClientIDMode = WebControlClientIDMode::None;
				$tagStyleSheet->HasContent = false;
				$tagStyleSheet->TagName = "link";
				$tagStyleSheet->Attributes[] = new WebControlAttribute("rel", "stylesheet");
				$tagStyleSheet->Attributes[] = new WebControlAttribute("type", "text/css");
				if ($stylesheet->FileName != "")
				{
					$tagStyleSheet->Attributes[] = new WebControlAttribute("href", System::ExpandRelativePath($stylesheet->FileName));
				}
				if ($stylesheet->Content != "")
				{
					$tagStyleSheet->InnerHTML = $stylesheet->Content;
				}
				$tagStyleSheet->Render();
			}
			echo("</head>");
			echo("<body>");
			foreach ($controls as $ctl)
			{
				$ctl->Render();
			}
			echo("</body>");
			echo("</html>");
		}
		
		public static function FromMarkup($element, $parser)
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
				$page->MasterPage = $parser->GetMasterPageByFileName($attrMasterPageFileName->Value);
			}
			$attTitle = $element->GetAttribute("Title");
			if ($attTitle != null)
			{
				$page->Title = $attTitle->Value;
			}
			
			$tagScripts = $element->GetElement("Scripts");
			if ($tagScripts != null)
			{
				foreach ($tagScripts->Elements as $elem)
				{
					if (get_class($elem) != "UniversalEditor\\ObjectModels\\Markup\\MarkupTagElement") continue;
					
					$attContentType = $elem->GetAttribute("ContentType");
					$contentType = "text/javascript";
					if ($attContentType != null) $contentType = $attContentType->Value;
					
					$page->Scripts[] = new WebScript($elem->GetAttribute("FileName")->Value, $contentType);
				}
			}
			$tagStyleSheets = $element->GetElement("StyleSheets");
			if ($tagStyleSheets != null)
			{
				foreach ($tagStyleSheets->Elements as $elem)
				{
					if (get_class($elem) != "UniversalEditor\\ObjectModels\\Markup\\MarkupTagElement") continue;
					
					$attFileName = $elem->GetAttribute("FileName");
					if ($attFileName == null) continue;
					
					$page->StyleSheets[] = new WebStyleSheet($attFileName->Value);
				}
			}
			$tagVariables = $element->GetElement("Variables");
			if ($tagVariables != null)
			{
				foreach ($tagVariables->Elements as $elem)
				{
					if (get_class($elem) != "UniversalEditor\\ObjectModels\\Markup\\MarkupTagElement") continue;
					
					$attName = $elem->GetAttribute("Name");
					if ($attName == null) continue;
					
					$value = "";
					$attValue = $elem->GetAttribute("Value");
					if ($attValue != null) $value = $attValue->Value;
					
					$page->Variables[] = new WebVariable($attName->Value, $value);
				}
			}
			
			$tagReferences = $element->GetElement("References");
			if ($tagReferences != null)
			{
				foreach ($tagReferences->Elements as $elem)
				{
					if (get_class($elem) != "UniversalEditor\\ObjectModels\\Markup\\MarkupTagElement") continue;
					
					$attTagPrefix = $elem->GetAttribute("TagPrefix");
					if ($attTagPrefix == null) continue;
					
					$attNamespacePath = $elem->GetAttribute("NamespacePath");
					if ($attNamespacePath == null) continue;
					
					$attNamespaceURL = $elem->GetAttribute("NamespaceURL");
					if ($attNamespaceURL == null) continue;
					
					$page->References[] = new WebNamespaceReference($attTagPrefix->Value, $attNamespacePath->Value, $attNamespaceURL->Value);
				}
			}
			
			$references = $page->References;
			if ($page->MasterPage != null)
			{
				$references = $page->MasterPage->References;
				foreach ($page->References as $reference)
				{
					$references[] = $reference;
				}
			}
			foreach ($references as $reference)
			{
				ControlLoader::$Namespaces[$reference->TagPrefix] = $reference->NamespacePath;
			}
			
			$tagContent = $element->GetElement("Content");
			if ($tagContent != null)
			{
				foreach ($tagContent->Elements as $elem)
				{
					ControlLoader::LoadControl($elem, $page);
				}
			}
			return $page;
		}
	}
	
	class WebFXParser
	{
		public $MasterPages;
		public $Pages;
		
		public function GetMasterPageByFileName($filename)
		{
			foreach ($this->MasterPages as $page)
			{
				if ($page->FileName == $filename) return $page;
			}
			return null;
		}
		public function GetPageByFileName($filename)
		{
			foreach ($this->Pages as $page)
			{
				if ($page->FileName == $filename) return $page;
			}
			return null;
		}
		
		public function __construct()
		{
			$this->Clear();
		}
		
		public function Clear()
		{
			$this->MasterPages = array();
			$this->Pages = array();
		}
		
		public function LoadFile($filename)
		{
			$markup = MarkupObjectModel::FromFile($filename);
			
			$tagWebsite = $markup->GetElement("Website");
			if ($tagWebsite == null) return;
			
			$tagMasterPages = $tagWebsite->GetElement("MasterPages");
			
			if ($tagMasterPages != null)
			{
				foreach ($tagMasterPages->Elements as $element)
				{
					if (get_class($element) != "UniversalEditor\\ObjectModels\\Markup\\MarkupTagElement") continue;
					if ($element->Name == "MasterPage")
					{
						$this->MasterPages[] = Page::FromMarkup($element, $this);
					}
				}
			}
			
			$tagPages = $tagWebsite->GetElement("Pages");
			if ($tagPages != null)
			{
				foreach ($tagPages->Elements as $element)
				{
					if (get_class($element) != "UniversalEditor\\ObjectModels\\Markup\\MarkupTagElement") continue;
					if ($element->Name == "Page")
					{
						$this->Pages[] = Page::FromMarkup($element, $this);
					}
				}
			}
		}
	}
?>