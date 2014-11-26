<?php
	namespace WebFX\Controls;
	
	use WebFX\HTMLControls\HTMLControlInput;
	use WebFX\HTMLControls\HTMLControlInputType;

	class FormView extends \WebFX\WebControl
	{
		public $Items;
		
		public function __construct()
		{
			$this->ParseChildElements = true;
		}
		
		protected function RenderContent()
		{
			echo("<div class=\"FormView\">");
			foreach ($this->Items as $item)
			{
				echo("<div class=\"Field ");
				switch (get_class($item))
				{
					case 'WebFX\Controls\FormViewItemText':
					{
						echo("Text");
						break;
					}
					case 'WebFX\Controls\FormViewItemPassword':
					{
						echo("Password");
						break;
					}
					case 'WebFX\Controls\FormViewItemMemo':
					{
						echo("Memo");
						break;
					}
				}
				if ($item->Required) echo(" Required");
				echo("\">");
				echo("<label for=\"" . $item->ID . "\">" . $item->Title . "</label>");
				switch (get_class($item))
				{
					case 'WebFX\Controls\FormViewItemText':
					{
						$elem = new HTMLControlInput();
						$elem->ID = $item->ID;
						$elem->Type = HTMLControlInputType::Text;
						$elem->Name = $item->Name;
						$elem->Value = $item->DefaultValue;
						$elem->Render();
						break;
					}
					case 'WebFX\Controls\FormViewItemPassword':
					{
						$elem = new HTMLControlInput();
						$elem->ID = $item->ID;
						$elem->Type = HTMLControlInputType::Password;
						$elem->Name = $item->Name;
						$elem->Value = $item->DefaultValue;
						$elem->Render();
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
					default:
					{
						echo("<!-- class not implemented: " . get_class($item) . " -->");
					}
				}
				echo("</div>");
			}
			echo("</div>");
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
	class FormViewItemPassword extends FormViewItemText
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