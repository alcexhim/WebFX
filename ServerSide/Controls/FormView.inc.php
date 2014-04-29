<?php
	namespace WebFX\Controls;

	class FormView extends \WebFX\WebControl
	{
		public $Items;
		
		protected function RenderContent()
		{
			echo("<table class=\"FormView\">");
			foreach ($this->Items as $item)
			{
				echo("<tr");
				if ($item->Required) echo(" class=\"Required\"");
				echo(">");
				echo("<td>");
				echo("<label for=\"" . $item->ID . "\">" . $item->Title . "</label>");
				echo("</td>");
				echo("<td>");
				switch (get_class($item))
				{
					case 'WebFX\Controls\FormViewItemText':
					{
						echo("<input type=\"text\"");
						echo(" id=\"" . $item->ID . "\"");
						echo(" name=\"" . $item->Name . "\"");
						echo(" value=\"" . $item->DefaultValue . "\"");
						echo(" />");
						break;
					}
					case 'WebFX\Controls\FormViewItemMemo':
					{
						echo("<textarea");
						echo(" id=\"" . $item->ID . "\"");
						echo(" name=\"" . $item->Name . "\"");
						if ($item->Rows != null)
						{
							echo(" rows=\"" . $item->Rows . "\"");
						}
						if ($item->Columns != null)
						{
							echo(" cols=\"" . $item->Columns . "\"");
						}
						echo(">");
						echo($item->DefaultValue);
						echo("</textarea>");
						break;
					}
				}
				echo("</td>");
				echo("</tr>");
			}
			echo("</table>");
		}
	}
	
	abstract class FormViewItem
	{
		public $ID;
		public $Name;
		public $Title;
		public $DefaultValue;
		public $Required;
		
		public function __construct($id, $name = null, $title = null, $defaultValue = null)
		{
			$this->ID = $id;
			
			if ($name == null) $name = $id;
			$this->Name = $name;
			
			if ($title == null) $title = $name;
			$this->Title = $title;
			
			$this->DefaultValue = $defaultValue;
			$this->Required = false;
		}
	}
	class FormViewItemText extends FormViewItem
	{
		public function __construct($id, $name = null, $title = null, $defaultValue = null)
		{
			parent::__construct($id, $name, $title, $defaultValue);
		}
	}
	class FormViewItemMemo extends FormViewItemText
	{
		public $Rows;
		public $Columns;
		
		public function __construct($id, $name = null, $title = null, $defaultValue = null)
		{
			parent::__construct($id, $name, $title, $defaultValue);
		}
	}
?>