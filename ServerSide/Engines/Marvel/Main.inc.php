<?php
	$items = glob("MasterPages/*.inc.php");
	foreach ($items as $item)
	{
		require_once($item);
	}
	
	$items = glob("Items/*.inc.php");
	foreach ($items as $item)
	{
		require_once($item);
	}
?>