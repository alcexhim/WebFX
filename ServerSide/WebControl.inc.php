<?php
	namespace WebFX;
	
    class WebControl
    {
		public $ID;
		public $ClientID;
		public $ClientIDMode;
		
		public $Controls;
		public $HasContent;
		
		public $ParentObject;
		
		public $Top;
		public $Left;
		public $Width;
		public $Height;
		
		public $MaximumWidth;
		public $MaximumHeight;
		
		public $Visible;
		
		public $HorizontalAlignment;
		public $VerticalAlignment;
		
		public $CssClass;
		public $ClassList;
		public $TagName;
		public $Attributes;
		public $StyleRules;
		
		public $ToolTipTitle;
		public $ToolTipText;
		
		public function FindParentPage()
		{
			$parent = $this->ParentObject;
			while ($parent != null)
			{
				if (get_class($parent) == "WebFX\\Parser\\Page" || get_class($parent) == "WebFX\\Parser\\MasterPage")
				{
					return $parent;
				}
				$parent = $parent->ParentObject;
			}
			return null;
		}
		
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
		
		public function __construct()
		{
			$this->Visible = true;
			$this->HorizontalAlignment = HorizontalAlignment::Inherit;
			$this->VerticalAlignment = VerticalAlignment::Inherit;
			
			$this->Controls = array();
			$this->HasContent = true;
			
			$this->TagName = null;
			$this->ClassList = array();
			$this->Attributes = array();
			$this->StyleRules = array();
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
		
        public function Initialize()
        {
            $this->OnInitialize();
			foreach ($this->Controls as $control)
			{
				$control->Initialize();
			}
        }
		
		protected function OnInitialize()
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
		
		public static function BeginTag($tagName, $namedParameters)
		{
			echo("<" . $tagName);
			
			if (is_array($namedParameters))
			{
				$classNames = $namedParameters["ClassNames"];
				$attributes = $namedParameters["Attributes"];
				$styleRules = $namedParameters["StyleRules"];
			}
			
			if (!is_array($classNames)) $classNames = array();
			if (!is_array($attributes)) $attributes = array();
			if (!is_array($styleRules)) $styleRules = array();
			
			$count = count($classNames);
			if ($count > 0)
			{
				echo(" class=\"");
				for ($i = 0; $i < $count; $i++)
				{
					echo($classNames[$i]);
					if ($i < $count - 1) echo(" ");
				}
				echo("\"");
			}
			
			$count = count($styleRules);
			if ($count > 0)
			{
				echo(" style=\"");
				for ($i = 0; $i < $count; $i++)
				{
					$item = $styleRules[$i];
					echo($item->Name . ": " . $item->Value);
					if ($i < $count - 1) echo("; ");
				}
				echo("\"");
			}
			
			$count = count($attributes);
			if ($count > 0)
			{
				echo(" ");
				for ($i = 0; $i < $count; $i++)
				{
					$item = $attributes[$i];
					echo($item->Name . "=\"" . $item->Value . "\"");
					if ($i < $count - 1) echo(" ");
				}
			}
			echo(">");
		}
		public static function EndTag($tagName)
		{
			echo("</" . $tagName . ">");
		}
		
		protected function RenderBeginTag()
		{
			if ($this->TagName != "")
			{
				echo("<" . $this->TagName);
				
				$styleAttributeContent = "";
				$classAttributeContent = "";
				
				$count = count($this->Attributes);
				if ($count > 0)
				{
					$found = false;
					foreach ($this->Attributes as $attr)
					{
						if (!(strtolower($attr->Name) == "style" || strtolower($attr->Name) == "class"))
						{
							$found = true;
							break;
						}
					}
					if ($found) echo(" ");
					$i = 0;
					foreach ($this->Attributes as $attr)
					{
						if (strtolower($attr->Name) == "style")
						{
							if (!\StringMethods::EndsWith($attr->Value, ";"))
							{
								$styleAttributeContent .= $attr->Value . "; ";
							}
							else
							{
								$styleAttributeContent .= $attr->Value;
							}
						}
						else if (strtolower($attr->Name) == "class")
						{
							$classAttributeContent .= $attr->Value;
						}
						else
						{
							echo($attr->Name);
							echo("=\"");
							echo($attr->Value);
							echo("\"");
							if ($i < $count - 1) echo(" ");
						}
						$i++;
					}
				}
				
				$styleRules = $this->StyleRules;
				if (!$this->Visible)
				{
					$styleRules[] = new WebStyleSheetRule("display", "none");
				}
				if ($this->Width != null)
				{
					$styleRules[] = new WebStyleSheetRule("width", $this->Width);
				}
				if ($this->Height != null)
				{
					$styleRules[] = new WebStyleSheetRule("height", $this->Height);
				}
				if ($this->MaximumWidth != null)
				{
					$styleRules[] = new WebStyleSheetRule("max-width", $this->MaximumWidth);
				}
				if ($this->MaximumHeight != null)
				{
					$styleRules[] = new WebStyleSheetRule("max-height", $this->MaximumHeight);
				}
				
				if (count($styleRules) > 0 || $styleAttributeContent != "")
				{
					echo(" style=\"");
					echo($styleAttributeContent);
					$count = count($styleRules);
					$i = 0;
					foreach ($styleRules as $rule)
					{
						echo($rule->Name);
						echo(": ");
						echo($rule->Value);
						echo(";");
						if ($i < $count - 1) echo(" ");
						$i++;
					}
					echo("\"");
				}
				if ($this->CssClass != "")
				{
					if ($classAttributeContent != "") $classAttributeContent .= " ";
					$classAttributeContent .= $this->CssClass;
				}
				if (count($this->ClassList) > 0)
				{
					if ($classAttributeContent != "") $classAttributeContent .= " ";
					$count = count($this->ClassList);
					for ($i = 0; $i < $count; $i++)
					{
						$classAttributeContent .= $this->ClassList[$i];
						if ($i < $count - 1) $classAttributeContent .= " ";
					}
				}
				
				if ($classAttributeContent != "")
				{
					echo(" class=\"" . $classAttributeContent . "\"");
				}
				
				if ($id == null)
				{
					if ($this->ClientIDMode == WebControlClientIDMode::Automatic)
					{
						$id = "WFX" . WebControl::GenerateRandomString("ABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890", 10);
					}
				}
				$this->ID = $id;
				
				if ($this->ClientID != null)
				{
					echo(" id=\"" . $this->ClientID . "\"");
				}
				else if ($this->ID != null)
				{
					echo(" id=\"" . $this->ID . "\"");
				}
				
				if ($this->ToolTipTitle != null) echo(" data-tooltip-title=\"" . $this->ToolTipTitle . "\"");
				if ($this->ToolTipText != null) echo(" data-tooltip-content=\"" . $this->ToolTipText . "\"");
				
				if (!$this->HasContent) echo(" /");
				echo(">");
			}
		}
		protected function RenderEndTag()
		{
			if ($this->TagName != "" && $this->HasContent)
			{
				echo("</" . $this->TagName . ">");
			}
		}
		
		public function BeginContent()
		{
			$this->RenderBeginTag();
            $this->BeforeContent();
		}
		public function EndContent()
		{
            $this->AfterContent();
			$this->RenderEndTag();
		}
        
        public function Render()
        {
            $this->BeginContent();
			if (count($this->Controls) > 0)
			{
				foreach ($this->Controls as $control)
				{
					$control->Render();
				}
			}
			else
			{
				$this->RenderContent();
			}
            $this->EndContent();
        }
    }
?>