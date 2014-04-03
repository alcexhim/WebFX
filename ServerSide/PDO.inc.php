<?php
	namespace WebFX;
	class PDO extends \PDO
	{
		public function __construct()
		{
			$engine = 'mysql';
			$host = System::GetConfigurationValue("Database.ServerName");
			$database = System::GetConfigurationValue("Database.DatabaseName");
			$user = System::GetConfigurationValue("Database.UserName");
			$pass = System::GetConfigurationValue("Database.Password");
			
			$dns = $engine . ':dbname=' . $database . ";host=" . $host;
			
			parent::__construct($dns, $user, $pass);
		}
	}
	
	global $pdo;
	$pdo = new PDO();
?>