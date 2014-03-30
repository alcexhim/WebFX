<?php
	namespace WebFX;
	
	use WebFX\Pages\ErrorPage;

	class ErrorEventArgs
	{
		public $Message;
		public $ParentError;
		
		public function __construct($message, $parentError = null)
		{
			$this->Message = $message;
			$this->ParentError = $parentError;
		}
	}
	class IncludeFile
	{
		public $FileName;
		public $IsRequired;
		
		public function __construct($filename, $isRequired = false)
		{
			$this->FileName = $filename;
			$this->IsRequired = $isRequired;
		}
	}
	class System
	{
		public static $Configuration;
		public static $IncludeFiles;
		public static $EnableTenantedHosting;
		
		public static function GetConfigurationValue($key, $defaultValue = null)
		{
			if (System::HasConfigurationValue($key))
			{
				return System::$Configuration[$key];
			}
			return $defaultValue;
		}
		public static function SetConfigurationValue($key, $value)
		{
			System::$Configuration[$key] = $value;
		}
		public static function ClearConfigurationValue($key)
		{
			unset(System::$Configuration[$key]);
		}
		public static function HasConfigurationValue($key)
		{
			return isset(System::$Configuration[$key]);
		}
		
		public static $Modules;
		
		public static $ErrorEventHandler;
		public static $BeforeLaunchEventHandler;
		public static $AfterLaunchEventHandler;
		
		public static function Redirect($path)
		{
			header("Location: " . System::ExpandRelativePath($path));
			return;
		}
		public static function ExpandRelativePath($path, $includeServerInfo = false)
		{
			$torepl = System::GetConfigurationValue("Application.BasePath");
			if (System::$EnableTenantedHosting)
			{
				$torepl .= "/" . System::GetConfigurationValue("Application.DefaultTenant");
			}
			
			$retval = str_replace("~", $torepl, $path);
			if ($includeServerInfo)
			{
				// from http://stackoverflow.com/questions/6768793/php-get-the-full-url
				$sp = strtolower($_SERVER["SERVER_PROTOCOL"]);
				$protocol = substr($sp, 0, strpos($sp, "/")) . $s;
				$port = ($_SERVER["SERVER_PORT"] == "80") ? "" : (":".$_SERVER["SERVER_PORT"]);
				$serverPath = $protocol . "://" . $_SERVER["SERVER_NAME"] . $port;
				$retval = $serverPath . $retval;
			}
			return $retval;
		}
		public static function RedirectToLoginPage()
		{
			System::Redirect("~/account/login");
			return;
		}
		public static function GetVirtualPath()
		{
			if (isset($_GET["virtualpath"]))
			{
				if ($_GET["virtualpath"] != null) return explode("/", $_GET["virtualpath"]);
			}
			return array();
		}
		public static function IncludeFile($filename, $isRequired)
		{
			global $RootPath;
			$filename = str_replace("~/", $RootPath . "/", $filename);
			if ($isRequired)
			{
				require_once($filename);
			}
			else
			{
				include_once($filename);
			}
		}
		
		public static function Launch()
		{
			if (!is_array(System::$Modules) || count(System::$Modules) == 0)
			{
				$retval = call_user_func(System::$ErrorEventHandler, new ErrorEventArgs("There are no modules configured for this WebFX application."));
				return false;
			}
			
			$path = System::GetVirtualPath();
			
			if (is_callable(System::$BeforeLaunchEventHandler))
			{
				$retval = call_user_func(System::$BeforeLaunchEventHandler, $path);
				if (!$retval) return false;
			}
			
			$success = false;
			foreach (System::$Modules as $module)
			{
				if (!$module->Enabled) continue;
				
				foreach ($module->Pages as $vpath)
				{
					$path0 = "";
					if (isset($path[0]))
					{
						$path0 = $path[0];
					}
					if ($vpath->PathName == $path0)
					{
						$xpath = $path;
						array_shift($xpath);
						if (!$vpath->Execute($xpath))
						{
							/*
							if (is_callable(System::$ErrorEventHandler))
							{
								$retval = call_user_func(System::$ErrorEventHandler, new ErrorEventArgs("The module '" . $path[0] . "' did not execute properly"));
								if (!$retval) return false;
							}
							*/
						}
						else
						{
							$success = true;
							break;
						}
					}
				}
			}
			
			if (is_callable(System::$AfterLaunchEventHandler))
			{
				$retval = call_user_func(System::$AfterLaunchEventHandler);
				if (!$retval) return false;
			}
			
			if (!$success)
			{
				header("HTTP/1.1 404 Not Found");
				return false;
			}
			return true;
		}
	}
	class Module
	{
		public $Name;
		public $Enabled;
		public $Pages;
		
		public function __construct($name, $pages)
		{
			$this->Name = $name;
			$this->Enabled = true;
			if (is_array($pages))
			{
				$this->Pages = $pages;
			}
			else
			{
				$this->Pages = array($pages);
			}
		}
	}
	class ModulePage
	{
		public $PathName;
		public $UserFunction;
		public $Pages;
		public $BeforeExecute;
		public $AfterExecute;
		
		public function __construct($pathName, $userFunctionOrPages, $beforeExecute = null, $afterExecute = null)
		{
			$this->PathName = $pathName;
			if (is_callable($userFunctionOrPages))
			{
				$this->UserFunction = $userFunctionOrPages;
			}
			else if (is_array($userFunctionOrPages))
			{
				$this->Pages = $userFunctionOrPages;
			}
			$this->BeforeExecute = $beforeExecute;
			$this->AfterExecute = $afterExecute;
		}
		
		public function Execute($path)
		{
			foreach (System::$IncludeFiles as $includefile)
			{
				if (get_class($includefile) != "WebFX\\IncludeFile") continue;
				System::IncludeFile($includefile->FileName, $includefile->IsRequired);
			}
			
			if (is_callable($this->BeforeExecute))
			{
				$retval = call_user_func($this->BeforeExecute, $path);
				if ($retval === false) return false;
			}
			if (is_array($this->Pages))
			{
				foreach ($this->Pages as $vpath)
				{
					if ($vpath->PathName == $path[0])
					{
						array_shift($path);
						if (!$vpath->Execute($path))
						{
							if (is_callable(System::$ErrorEventHandler))
							{
								// $retval = call_user_func(System::$ErrorEventHandler, new ErrorEventArgs("The module '" . $path[0] . "' did not execute properly"));
								return true;
							}
						}
						return true;
					}
				}
				return false;
			}
			
			$retval = false;
			if (is_callable($this->UserFunction))
			{
				$retval = call_user_func($this->UserFunction, $path);
			}
			if (is_callable($this->AfterExecute))
			{
				$retval2 = call_user_func($this->AfterExecute, $path);
				if ($retval2 === false) return false;
			}
			return $retval;
		}
	}
	
	require_once("Enum.inc.php");
	require_once("StringMethods.inc.php");
	require_once("JH.Utilities.inc.php");
	
	\Enum::Create("WebFX\\HorizontalAlignment", "Inherit", "Left", "Center", "Right");
	\Enum::Create("WebFX\\VerticalAlignment", "Inherit", "Top", "Middle", "Bottom");
	
    require("WebApplication.inc.php");
    require("WebOpenGraphSettings.inc.php");
    require("WebResourceLink.inc.php");
    require("WebScript.inc.php");
    require("WebStyleSheet.inc.php");
    
    require("WebControl.inc.php");
    
    require("WebPage.inc.php");
    require("WebPageCommand.inc.php");
    require("WebPageMetadata.inc.php");
	require("WebPageVariable.inc.php");
	
	System::$Configuration = array();
	System::$EnableTenantedHosting = false;
	
	System::$IncludeFiles = array();
	System::$Modules = array();
	System::$ErrorEventHandler = function($e)
	{
		echo($e->Message);
	};
	
	global $WebFXRootPath;
	$WebFXRootPath = dirname(__FILE__);
	
	global $RootPath;
	require_once($RootPath . "/Include/Configuration.inc.php");
	
	// Global Controls loader
	$a = glob($WebFXRootPath . "/Controls/*.inc.php");
	foreach ($a as $filename)
	{
		require_once($filename);
	}
	
	// Local Objects loader
	$a = glob($RootPath . "/Include/Objects/*.inc.php");
	foreach ($a as $filename)
	{
		require_once($filename);
	}
	
	// Local Controls loader
	$a = glob($RootPath . "/Include/Controls/*.inc.php");
	foreach ($a as $filename)
	{
		require_once($filename);
	}
	
	// Local MasterPages loader
	$a = glob($RootPath . "/Include/MasterPages/*.inc.php");
	foreach ($a as $filename)
	{
		require_once($filename);
	}
	
	// Local Pages loader
	$a = glob($RootPath . "/Include/Pages/*.inc.php");
	foreach ($a as $filename)
	{
		require_once($filename);
	}
	
	// Local Modules loader
	$a = glob($RootPath . "/Include/Modules/*/Main.inc.php");
	foreach ($a as $filename)
	{
		include_once($filename);
	}
	
	require_once("DataFX/DataFX.inc.php");
	
	session_start();
?>