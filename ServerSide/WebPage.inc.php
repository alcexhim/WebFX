<?php
	namespace WebFX;
	
	/**
	 * Contains functionality common to all WebFX Web pages. 
	 * @author Michael Becker
	 */
    class WebPage
    {
		public $BreadcrumbItems;
		
		/**
		 * The title of this Web page.
		 * @var string
		 */
        public $Title;
		public $CssClass;
		public $ClassList;
        public $Metadata;
        public $ResourceLinks;
        public $Scripts;
        public $StyleSheets;
		public $Styles;
		public $ContextMenu;
		public $Variables;
		public $OpenGraph;
		
		/**
		 * When true, omits the DOCTYPE HTML declaration at the beginning of the document to be compatible with
		 * older Web browsers.
		 * @var boolean
		 */
		public $UseCompatibleRenderingMode;
		
		/**
		 * Specifies whether the request for this page is for partial content only.
		 * @var boolean
		 */
		public $IsPartial;
		
		public function __construct()
		{
			$this->BreadcrumbItems = array();
			$this->Metadata = array();
			$this->OpenGraph = new WebOpenGraphSettings();
			$this->ResourceLinks = array();
			$this->ClassList = array();
			$this->Scripts = array();
			$this->StyleSheets = array();
			$this->Styles = array();
			$this->Variables = array();
			$this->UseCompatibleRenderingMode = false;
			
			$this->IsPartial = isset($_GET["partial"]);
			
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
        /**
         * Performs any necessary processing before the main content of the Web page. Designed for use by page developers.
         */
        protected function BeforeContent()
        {
            
        }
        /**
         * Renders the main content of the Web page. Designed for use by page developers.
         */
        protected function RenderContent()
        {
            
        }
        /**
         * Performs any necessary processing after the main content of the Web page. Designed for use by page developers.
         */
        protected function AfterContent()
        {
            
        }
		
        /**
         * This function is called before the content for a full page is generated. To generate a partial page, pass
         * "partial" in the query string.
         */
		protected function BeforeFullContent()
		{
			
		}
		/**
		 * This function is called after the content for a full page is generated. To generate a partial page, pass
		 * "partial" in the query string.
		 */
		protected function AfterFullContent()
		{
			
		}
		
		/**
		 * Retrieves the WebPageVariable with the given name associated with this WebPage.
		 * @param string $name The name of the WebPageVariable to return. 
		 * @return WebPageVariable|NULL The WebPageVariable with the given name, or NULL if no WebPageVariable with the given name is defined for this WebPage.
		 */
		public function GetVariable($name)
		{
			foreach ($this->Variables as $variable)
			{
				if ($variable->Name == $name) return $variable;
			}
			return null;
		}
		/**
		 * Retrieves the string value for the WebPageVariable with the given name associated with this WebPage.
		 * @param string $name The name of the WebPageVariable whose value is to be returned. 
		 * @return string The value of the WebPageVariable with the given name, or the empty string ("") if no WebPageVariable with the given name is defined for this WebPage.
		 */
		public function GetVariableValue($name)
		{
			$variable = $this->GetVariable($name);
			if ($variable == null) return null;
			return $variable->Value;
		}
		/**
		 * Updates the WebPageVariable with the given name associated with this WebPage.
		 * @param string $name The name of the WebPageVariable to update.
		 * @param string $value The value to set for the specified WebPageVariable.
		 * @param boolean $autoDeclare True if the variable should be created if it doesn't exist; false if the function should fail.
		 * @return boolean True if the variable was updated successfully; false otherwise.
		 */
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
		/**
		 * Determines if a WebPageVariable with the given name is defined on this WebPage.
		 * @param string $name The name of the WebPageVariable to search for.
		 * @return boolean True if a WebPageVariable with the given name is defined on this WebPage; false if not.
		 */
		public function IsVariableDefined($name)
		{
			$variable = $this->GetVariable($name);
			if ($variable == null) return false;
			return true;
		}
		/**
		 * Determines if a WebPageVariable with the given name has a value (is not null) on this WebPage.
		 * @param string $name The name of the WebPageVariable to search for.
		 * @return boolean True if a WebPageVariable with the given name is defined and not null on this WebPage; false if either the variable is not defined or the variable is defined but does not have a value.
		 */
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
            
			if (!$this->IsPartial)
			{
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

				$this->OutputHeaderScript(new WebScript(System::$Configuration["WebFramework.StaticPath"] . "/dropins/WebFramework/Scripts/WebFramework.js.php"));
				$script = new WebScript();
				$script->Content = "WebFramework.BasePath = \"" . System::GetConfigurationValue("Application.BasePath") . "\"";
				$this->OutputHeaderScript($script);
				
				/*
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
				
				$this->OutputHeaderScript(new WebScript(System::$Configuration["WebFramework.StaticPath"] . "/dropins/WebFramework/Scripts/Controls/AdditionalDetailWidget.js"));
				$this->OutputHeaderScript(new WebScript(System::$Configuration["WebFramework.StaticPath"] . "/dropins/WebFramework/Scripts/Controls/CheckBox.js"));
				$this->OutputHeaderScript(new WebScript(System::$Configuration["WebFramework.StaticPath"] . "/dropins/WebFramework/Scripts/Controls/CodeEditor.js"));
				$this->OutputHeaderScript(new WebScript(System::$Configuration["WebFramework.StaticPath"] . "/dropins/WebFramework/Scripts/Controls/Disclosure.js"));
				$this->OutputHeaderScript(new WebScript(System::$Configuration["WebFramework.StaticPath"] . "/dropins/WebFramework/Scripts/Controls/DropDown.js"));
				$this->OutputHeaderScript(new WebScript(System::$Configuration["WebFramework.StaticPath"] . "/dropins/WebFramework/Scripts/Controls/FlyoutTabContainer.js"));
				$this->OutputHeaderScript(new WebScript(System::$Configuration["WebFramework.StaticPath"] . "/dropins/WebFramework/Scripts/Controls/ListView.js"));
				$this->OutputHeaderScript(new WebScript(System::$Configuration["WebFramework.StaticPath"] . "/dropins/WebFramework/Scripts/Controls/Menu.js"));
				$this->OutputHeaderScript(new WebScript(System::$Configuration["WebFramework.StaticPath"] . "/dropins/WebFramework/Scripts/Controls/Notification.js"));
				$this->OutputHeaderScript(new WebScript(System::$Configuration["WebFramework.StaticPath"] . "/dropins/WebFramework/Scripts/Controls/Popup.js"));
				$this->OutputHeaderScript(new WebScript(System::$Configuration["WebFramework.StaticPath"] . "/dropins/WebFramework/Scripts/Controls/ProgressBar.js"));
				$this->OutputHeaderScript(new WebScript(System::$Configuration["WebFramework.StaticPath"] . "/dropins/WebFramework/Scripts/Controls/Ribbon.js"));
				$this->OutputHeaderScript(new WebScript(System::$Configuration["WebFramework.StaticPath"] . "/dropins/WebFramework/Scripts/Controls/SplitContainer.js"));
				$this->OutputHeaderScript(new WebScript(System::$Configuration["WebFramework.StaticPath"] . "/dropins/WebFramework/Scripts/Controls/TabContainer.js"));
				$this->OutputHeaderScript(new WebScript(System::$Configuration["WebFramework.StaticPath"] . "/dropins/WebFramework/Scripts/Controls/TextBox.js"));
				$this->OutputHeaderScript(new WebScript(System::$Configuration["WebFramework.StaticPath"] . "/dropins/WebFramework/Scripts/Controls/ToolTip.js"));
				$this->OutputHeaderScript(new WebScript(System::$Configuration["WebFramework.StaticPath"] . "/dropins/WebFramework/Scripts/Controls/Window.js"));
				*/
				
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
				$classList = array();
				if ($this->CssClass != null)
				{
					$classList[] = $this->CssClass;
				}
				if (is_array($this->ClassList))
				{
					foreach ($this->ClassList as $item)
					{
						$classList[] = $item;
					}
				}

				$count = count($classList);
				if ($count > 0)
				{
					echo(" class=\"");
					$i = 0;
					foreach ($classList as $item)
					{
						echo($item);
						$i++;
						if ($i > $count - 1)
						{
							echo(" ");
						}
					}
					echo("\"");
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
				
				$this->BeforeFullContent();
			}
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
		
		/**
		 * Renders the complete Web page, including beginning and ending content. Designed for use by end-users.
		 */
        public function Render()
        {
            $this->BeginContent();
            $this->RenderContent();
            $this->EndContent();
        }
        
        /**
         * Renders the ending content of the Web page. Designed for use by end-users.
         */
        public function EndContent()
        {
			$this->AfterContent();
			if (!$this->IsPartial)
			{
				$this->AfterFullContent();
				
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
    }
?>