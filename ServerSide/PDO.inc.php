<?php
	namespace WebFX;
	class PDO extends \PDO
	{
		public function __construct()
		{
			$engine = 'mysql';
			$host = 'localhost';
			$database = '';
			$user = 'root';
			$pass = '';
			
			$dns = $engine . ':dbname=' . $database . ";host=" . $host;
			
			parent::__construct($dns, $user, $pass);
		}
	}
	
	global $pdo;
	$pdo = new PDO();
?>