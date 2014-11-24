<?php
	namespace UniversalEditor\ObjectModels\Markup;
	
	class MarkupAttribute
	{
		public $Prefix;
		public $Name;
		public $Value;
		
		public function GetFullName()
		{
			if ($this->Prefix != "")
			{
				return $this->Prefix . ":" . $this->Name;
			}
			else
			{
				return $this->Name;
			}
		}
		public function SetFullName($name)
		{
			$names = explode(":", $name);
			if (count($names) > 1)
			{
				$this->Prefix = $names[0];
				$this->Name = $names[1];
			}
			else if (count($names) == 1)
			{
				$this->Prefix = "";
				$this->Name = $names[0];
			}
		}
		
		public function __construct($name, $value, $prefix = null)
		{
			if ($prefix == null)
			{
				$this->SetFullName($name);
			}
			else
			{
				$this->Prefix = $prefix;
				$this->Name = $name;
			}
			$this->Value = $value;
		}
	}
	class MarkupElement
	{
		public static function FromArray($array)
		{
			$element = new MarkupTagElement();
			$element->Name = $array["Name"];
			
			if (isset($array["Attributes"]))
			{
				$attributes = $array["Attributes"];
				foreach ($attributes as $name => $value)
				{
					$element->Attributes[] = new MarkupAttribute($name, $value);
				}
			}
			if (isset($array["Elements"]))
			{
				$elements = $array["Elements"];
				foreach ($elements as $elem)
				{
					$element->Elements[] = MarkupElement::FromArray($elem);
				}
			}
			
			return $element;
		}
	}
	class MarkupTagElement extends MarkupElement
	{
		public $Name;
		
		public $Attributes;
		public $Elements;
		
		public $Value;
		
		public function GetAttribute($name, $index = 0)
		{
			$i = 0;
			$last = null;
			foreach ($this->Attributes as $attribute)
			{
				if ($attribute->Name == $name)
				{
					$last = $attribute;
					$i++;
					if ($i == $index) return $attribute;
				}
			}
			return $last;
		}
		public function GetAttributes()
		{
			return $this->Attributes;
		}
		
		public function HasAttribute($name)
		{
			return ($this->GetAttribute($name) != null);
		}
		
		public function GetElement($name, $index = 0)
		{
			$i = 0;
			$last = null;
			foreach ($this->Elements as $element)
			{
				if ($element->Name == $name)
				{
					$last = $element;
					$i++;
					if ($i == $index) return $element;
				}
			}
			return $last;
		}
		public function GetElements()
		{
			return $this->Elements;
		}
	}
	
	class MarkupObjectModel
	{
		public $Elements;
		
		public function GetElement($name, $index = 0)
		{
			$i = 0;
			$last = null;
			foreach ($this->Elements as $element)
			{
				if ($element->Name == $name)
				{
					$last = $element;
					$i++;
					if ($i == $index) return $element;
				}
			}
			return $last;
		}
		public function GetElements()
		{
			return $this->Elements;
		}
		
		public static function FromFile($filename)
		{
			$parser = new XMLParser();
			$markup = $parser->LoadFile($filename);
			return $markup;
		}
		
		public function __construct()
		{
			$this->Elements = array();
		}
		public static function FromArray($array)
		{
			$markup = new MarkupObjectModel();
			$markup->LoadArray($array);
			return $markup;
		}
		public function LoadArray($array)
		{
			$count = count($array);
			for ($i = 0; $i < $count; $i++)
			{
				$this->Elements[] = MarkupElement::FromArray($array[$i]);
			}
		}
	}
	
	class XMLParser
	{
		
		private $mvarOutput;
		var $resParser;
		var $strXmlData;
		
		public function LoadFile($filename)
		{
			$file = fopen($filename, "r");
			$input = fread($file, filesize($filename));
			return $this->Load($input);
		}
		
		public function Load($input)
		{
			$this->mvarOutput = array();
			
			$this->resParser = xml_parser_create ();
			xml_parser_set_option($this->resParser, XML_OPTION_CASE_FOLDING, 0);
			
			xml_set_object($this->resParser, $this);
			xml_set_element_handler($this->resParser, "tagOpen", "tagClosed");
		   
			xml_set_character_data_handler($this->resParser, "tagData");
	   
			$this->strXmlData = xml_parse($this->resParser, $input);
			if(!$this->strXmlData)
			{
				$message = xml_error_string(xml_get_error_code($this->resParser));
				$lineNumber = xml_get_current_line_number($this->resParser);
				die(sprintf("XML error: %s at line %d", $message, $lineNumber));
			}
						   
			xml_parser_free($this->resParser);
		   
			return MarkupObjectModel::FromArray($this->mvarOutput);
		}
			
		private function tagOpen($parser, $name, $attrs)
		{
			$tag = array("Name" => $name, "Attributes" => $attrs);
			array_push($this->mvarOutput,$tag);
		}
	   
		private function tagData($parser, $value)
		{
			if(trim($value))
			{
				if (isset($this->mvarOutput[count($this->mvarOutput) - 1]['Value']))
				{
					$this->mvarOutput[count($this->mvarOutput)-1]['Value'] .= $tagData;
				}
				else
				{
					$this->mvarOutput[count($this->mvarOutput)-1]['Value'] = $value;
				}
			}
		}
	   
		function tagClosed($parser, $name)
		{
			$this->mvarOutput[count($this->mvarOutput) - 2]['Elements'][] = $this->mvarOutput[count($this->mvarOutput) - 1];
			array_pop($this->mvarOutput);
		}
	}
?>