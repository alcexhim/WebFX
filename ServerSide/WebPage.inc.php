<?php
	namespace WebFX;
	
    class WebPage
    {
		public $BreadcrumbItems;
        public $Title;
		public $CssClass;
        public $Metadata;
        public $ResourceLinks;
        public $Scripts;
        public $StyleSheets;
		public $Styles;
		public $ContextMenu;
		public $Variables;
		public $OpenGraph;
		public $UseCompatibleRenderingMode;
		
		public function __construct()
		{
			$this->BreadcrumbItems = array();
			$this->Metadata = array();
			$this->OpenGraph = new WebOpenGraphSettings();
			$this->ResourceLinks = array();
			$this->Scripts = array();
			$this->StyleSheets = array();
			$this->Styles = array();
			$this->Variables = array();
			$this->UseCompatibleRenderingMode = false;
			
			$this->BeforeConstruct();
			if (is_array($this->Variables))
			{
				foreach ($this->Variables as $variable)
				{
					if (isset($_POST["WebPageVariable_" . $variable->Name . "_Value"]))
					{
						$variable->Value = $_POST["WebPageVariable_" . $variable->Name . "_Value"];
					}
					if (isset($_POST["WebPageVariable_" . $variable->Name . "_IsSet"]))
					{
						$variable->IsSet = $_POST["WebPageVariable_" . $variable->Name . "_IsSet"];
					}
				}
			}
			$this->AfterConstruct();
		}
        
        private $isInitialized;
        protected function Initialize()
        {
            
        }
		protected function BeforeConstruct()
		{
		}
		protected function AfterConstruct()
		{
		}
        protected function BeforeHeader()
        {
            
        }
        protected function AfterHeader()
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
		
		// WebPage Variables
		public function GetVariable($name)
		{
			foreach ($this->Variables as $variable)
			{
				if ($variable->Name == $name) return $variable;
			}
			return null;
		}
		public function GetVariableValue($name)
		{
			$variable = $this->GetVariable($name);
			if ($variable == null) return null;
			return $variable->Value;
		}
		public function SetVariableValue($name, $value, $autoDeclare = false)
		{
			$variable = $this->GetVariable($name);
			if ($variable == null)
			{
				if (!$autoDeclare) return false;
				
				$variable = new WebPageVariable($name, $value, true);
				$this->Variables[] = $variable;
				return true;
			}
			$variable->Value = $value;
			return true;
		}
		public function IsVariableDefined($name)
		{
			$variable = $this->GetVariable($name);
			if ($variable == null) return false;
			return true;
		}
		public function IsVariableSet($name)
		{
			$variable = $this->GetVariable($name);
			if ($variable == null) return false;
			return ($variable->IsSet == "true");
		}
        
        public function BeginContent()
        {
            if (!$this->isInitialized)
            {
                $this->Initialize();
                $this->isInitialized = true;
            }
            
            if (!$this->UseCompatibleRenderingMode)
            {
                echo("<!DOCTYPE html>\r\n");
            }
            echo("<html>\r\n");
            echo("\t<head>\r\n");
            $this->BeforeHeader();
            echo("\t\t<title>" . $this->Title . "</title>\r\n");
            echo("\t\t<meta http-equiv=\"Content-Type\" content=\"text/html; charset=UTF-8\" />\r\n");
            echo("\t\t<meta name=\"viewport\" content=\"width=device-width,minimum-scale=1.0\" />\r\n");
			if (is_array($this->Metadata))
			{
				foreach ($this->Metadata as $metadata)
				{
					echo("\t\t<meta ");
					if ($metadata->IsHTTPEquivalent)
					{
						echo("http-equiv=\"");
					}
					else
					{
						echo("name=\"");
					}
					echo($metadata->Name);
					echo("\" content=\"");
					echo($metadata->Content);
					echo("\" />\r\n");
				}
			}
			
			if (is_array($this->ResourceLinks))
			{
				foreach ($this->ResourceLinks as $link)
				{
					$this->OutputHeaderResourceLink($link);
				}
			}
			
			$this->OutputHeaderStyleSheet(new WebStyleSheet(System::$Configuration["WebFramework.StaticPath"] . "/dropins/CodeMirror/StyleSheets/CodeMirror.css"));
			$this->OutputHeaderStyleSheet(new WebStyleSheet(System::$Configuration["WebFramework.StaticPath"] . "/dropins/WebFramework/StyleSheets/Main.css"));
			if (is_array($this->StyleSheets))
			{
				foreach ($this->StyleSheets as $stylesheet)
				{
					$this->OutputHeaderStyleSheet($stylesheet);
				}
			}
			
			$this->OutputHeaderScript(new WebScript(System::$Configuration["WebFramework.StaticPath"] . "/dropins/JH.Utilities/Scripts/JH.Utilities.js"));
			$this->OutputHeaderScript(new WebScript(System::$Configuration["WebFramework.StaticPath"] . "/dropins/XMLHttpRequest/Scripts/XMLHttpRequest.js"));
			$this->OutputHeaderScript(new WebScript(System::$Configuration["WebFramework.StaticPath"] . "/dropins/CodeMirror/Scripts/CodeMirror.js"));
			$this->OutputHeaderScript(new WebScript(System::$Configuration["WebFramework.StaticPath"] . "/dropins/CodeMirror/Scripts/Modes/xml/xml.js"));
			$this->OutputHeaderScript(new WebScript(System::$Configuration["WebFramework.StaticPath"] . "/dropins/CodeMirror/Scripts/Modes/javascript/javascript.js"));
			$this->OutputHeaderScript(new WebScript(System::$Configuration["WebFramework.StaticPath"] . "/dropins/CodeMirror/Scripts/Modes/css/css.js"));
			$this->OutputHeaderScript(new WebScript(System::$Configuration["WebFramework.StaticPath"] . "/dropins/CodeMirror/Scripts/Modes/htmlmixed/htmlmixed.js"));
			
			$this->OutputHeaderScript(new WebScript(System::$Configuration["WebFramework.StaticPath"] . "/dropins/WebFramework/Scripts/json2.min.js"));
			$this->OutputHeaderScript(new WebScript(System::$Configuration["WebFramework.StaticPath"] . "/dropins/WebFramework/Scripts/WebFramework.js"));
			$this->OutputHeaderScript(new WebScript(System::$Configuration["WebFramework.StaticPath"] . "/dropins/WebFramework/Scripts/MousePosition.js"));
			$this->OutputHeaderScript(new WebScript(System::$Configuration["WebFramework.StaticPath"] . "/dropins/WebFramework/Scripts/PrependArgument.js"));
			$this->OutputHeaderScript(new WebScript(System::$Configuration["WebFramework.StaticPath"] . "/dropins/WebFramework/Scripts/WindowDimensions.js"));
			
			$this->OutputHeaderScript(new WebScript(System::$Configuration["WebFramework.StaticPath"] . "/dropins/WebFramework/Scripts/Controls/CodeEditor.js"));
			$this->OutputHeaderScript(new WebScript(System::$Configuration["WebFramework.StaticPath"] . "/dropins/WebFramework/Scripts/Controls/DropDown.js"));
			$this->OutputHeaderScript(new WebScript(System::$Configuration["WebFramework.StaticPath"] . "/dropins/WebFramework/Scripts/Controls/FlyoutTabContainer.js"));
			$this->OutputHeaderScript(new WebScript(System::$Configuration["WebFramework.StaticPath"] . "/dropins/WebFramework/Scripts/Controls/Menu.js"));
			$this->OutputHeaderScript(new WebScript(System::$Configuration["WebFramework.StaticPath"] . "/dropins/WebFramework/Scripts/Controls/Popup.js"));
			$this->OutputHeaderScript(new WebScript(System::$Configuration["WebFramework.StaticPath"] . "/dropins/WebFramework/Scripts/Controls/ProgressBar.js"));
			$this->OutputHeaderScript(new WebScript(System::$Configuration["WebFramework.StaticPath"] . "/dropins/WebFramework/Scripts/Controls/Ribbon.js"));
			$this->OutputHeaderScript(new WebScript(System::$Configuration["WebFramework.StaticPath"] . "/dropins/WebFramework/Scripts/Controls/TextBox.js"));
			$this->OutputHeaderScript(new WebScript(System::$Configuration["WebFramework.StaticPath"] . "/dropins/WebFramework/Scripts/Controls/ToolTip.js"));
			$this->OutputHeaderScript(new WebScript(System::$Configuration["WebFramework.StaticPath"] . "/dropins/WebFramework/Scripts/Controls/Window.js"));
			
			if (is_array($this->Scripts))
			{
				foreach ($this->Scripts as $script)
				{
					$this->OutputHeaderScript($script);
				}
			}
			
			// BEGIN: OpenGraph support
			if ($this->OpenGraph->Enabled)
			{
				$og_title = $this->OpenGraph->Title;
				$og_type = "other";
				$og_url = $this->OpenGraph->URL;
				$og_site_name = $this->OpenGraph->Title;
				$og_image = $this->OpenGraph->ImageURL;
				$og_description = $this->OpenGraph->Description;
				
				if ($og_title != null) $og_title = $this->Title;
				
				echo("\r\n\t\t<!-- Open Graph Specification -->\r\n");
				echo("\t\t<meta property=\"og:title\" content=\"" . $og_title . "\" />\r\n");
				echo("\t\t<meta property=\"og:type\" content=\"" . $og_type . "\" />\r\n");
				echo("\t\t<meta property=\"og:url\" content=\"" . $og_url . "\" />\r\n");
				echo("\t\t<meta property=\"og:site_name\" content=\"" . $og_site_name . "\" />\r\n");
				echo("\t\t<meta property=\"og:image\" content=\"" . $og_image . "\" />\r\n");
				echo("\t\t<meta property=\"og:description\" content=\"" . $og_description . "\" />\r\n\r\n");
			}
			// END: OpenGraph support
			
            $this->AfterHeader();
            echo("\t</head>\r\n");
            echo("\t<body");
			if ($this->CssClass != null)
			{
				echo(" class=\"" . $this->CssClass . "\"");
			}
			if (is_array($this->Styles) && count($this->Styles) > 0)
			{
				echo(" style=\"");
				foreach ($this->Styles as $key => $value)
				{
					echo($key . ": " . $value . ";");
				}
				echo("\"");
			}
			echo(">\r\n");
			
			echo("<div class=\"WindowModalBackground\" id=\"smwbKageModal__33661E2DD4B44AC39AD7EA460DF79355\">&nbsp;</div>");
			
			$this->BeforeVariablesInitialize();
			if (is_array($this->Variables))
			{
				if (count($this->Variables) > 0)
				{
					echo("<form id=\"WebPageForm\" method=\"POST\">");
					foreach ($this->Variables as $variable)
					{
						echo("<input type=\"hidden\" id=\"WebPageVariable_" . $variable->Name . "_Value\" name=\"WebPageVariable_" . $variable->Name . "_Value\" ");
						if (isset($_POST["WebPageVariable_" . $variable->Name . "_Value"]))
						{
							$variable->Value = $_POST["WebPageVariable_" . $variable->Name . "_Value"];
						}
						echo("value=\"" . $variable->Value . "\" />");
						
						echo("<input type=\"hidden\" id=\"WebPageVariable_" . $variable->Name . "_IsSet\" name=\"WebPageVariable_" . $variable->Name . "_IsSet\" ");
						if (isset($_POST["WebPageVariable_" . $variable->Name . "_IsSet"]))
						{
							$variable->IsSet = $_POST["WebPageVariable_" . $variable->Name . "_IsSet"];
						}
						echo("value=\"" . (($variable->IsSet == "true") ? "true" : "false") . "\" />");
					}
				}
			}
			$this->AfterVariablesInitialize();
			
            $this->BeforeContent();
        }
		
		protected function BeforeVariablesInitialize()
		{
		}
		protected function AfterVariablesInitialize()
		{
		}
		
		private function OutputHeaderStyleSheet($stylesheet)
		{
			$this->OutputHeaderResourceLink(new WebResourceLink($stylesheet->FileName, "stylesheet", $stylesheet->ContentType));
		}
		private function OutputHeaderResourceLink($link)
		{
			echo("\t\t<link rel=\"" . $link->Relationship . "\" type=\"");
			echo($link->ContentType);
			echo("\" href=\"");
			echo(System::ExpandRelativePath($link->URL));
			echo("\" />\r\n");
		}
		private function OutputHeaderScript($script)
		{
			echo("\t\t<script type=\"");
			echo($script->ContentType);
			echo("\"");
			if ($script->FileName != null)
			{
				echo(" src=\"");
				echo(System::ExpandRelativePath($script->FileName));
				echo("\"");
			}
			echo(">");
			if ($script->Content != null)
			{
				echo($script->Content);
			}
			echo("</script>\r\n");
		}
		
        public function Render()
        {
            $this->BeginContent();
            $this->RenderContent();
            $this->EndContent();
        }
        public function EndContent()
        {
            $this->AfterContent();
			
			if (is_array($this->Variables))
			{
				if (count($this->Variables) > 0)
				{
					echo("</form>");
				}
			}
			
			if ($this->ContextMenu != null)
			{
				if (get_class($this->ContextMenu) == "WebMenuControl")
				{
					$this->ContextMenu->ID = "ContextMenu";
					$this->ContextMenu->Render();
					echo("<script type=\"text/javascript\">document.addEventListener('mousedown', function(e) { ContextMenu.Show(); });</script>");
				}
			}
			
            echo("\t</body>\r\n");
            echo("</html>");
        }
    }
?>