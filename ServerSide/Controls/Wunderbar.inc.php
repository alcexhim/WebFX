<?php
	namespace WebFX\Controls;
	
	class Wunderbar extends \WebFX\WebControl
	{
		protected function RenderContent()
		{
			echo("<div class=\"Wunderbar\">");
			echo("<div class=\"WunderbarContents\">");
			foreach ($this->Items as $pane)
			{
				echo("<div class=\"WunderbarContent\">");
				if (is_callable($pane->Content))
				{
					call_user_func($pane->Content);
				}
				else
				{
					echo($pane->Content);
				}
				echo("</div>");
			}
			echo("</div>");
			echo("<div class=\"WunderbarButtons\">");
			foreach ($this->Items as $pane)
			{
				echo("<a href=\"#\" onclick=\"" . $this->ID . ".SetSelectedPane('" . $pane->ID . "');\">");
				if ($pane->ImageURL != null) echo("<img class=\"Icon\" src=\"" . $pane->ImageURL . "\" />");
				echo("<span class=\"Text\">" . $pane->Title . "</span>");
				echo("</a>");
			}
			echo("</div>");
			echo("</div>");
		}
	}
	class WunderbarPanel
	{
		public $ID;
		public $Title;
		public $Content;
		public $ImageURL;
		
		public function __construct($id, $title, $contentOrContentFunction, $imageURL = null)
		{
			$this->ID = $id;
			$this->Title = $title;
			$this->Content = $contentOrContentFunction;
			$this->ImageURL = $imageURL;
		}
	}
?>