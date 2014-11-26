<?php
	namespace WebFX\Controls;
	
	use WebFX\HTMLControls\HTMLControlInput;
	use WebFX\HTMLControls\HTMLControlInputType;
	
	use WebFX\HTMLControls\HTMLControlTextArea;

	class FormView extends \WebFX\WebControl
	{
		public $Items;
		
		public function __construct()
		{
			parent::__construct();
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
				$item->Render();
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
		
		public function __construct($id = null, $name = null, $title = null, $defaultValue = null)
		{
			$this->ID = $id;
			
			if ($name == null) $name = $id;
			$this->Name = $name;
			
			if ($title == null) $title = $name;
			$this->Title = $title;
			
			$this->DefaultValue = $defaultValue;
			$this->Required = false;
			
			$this->ParseChildElements = false;
		}
		
		public function Render()
		{
			$this->RenderContent();
		}
		
		protected abstract function RenderContent();
	}
	class FormViewItemText extends FormViewItem
	{
		public $PlaceholderText;
		
		public function __construct($id = null, $name = null, $title = null, $defaultValue = null)
		{
			parent::__construct($id, $name, $title, $defaultValue);
		}
		
		protected function RenderContent()
		{
			$elem = new HTMLControlInput();
			$elem->ID = $this->ID;
			$elem->Type = HTMLControlInputType::Text;
			$elem->Name = $this->Name;
			$elem->Value = $this->DefaultValue;
			if (isset($this->PlaceholderText))
			{
				$elem->PlaceholderText = $this->PlaceholderText;
			}
			$elem->Render();
		}
	}
	class FormViewItemPassword extends FormViewItemText
	{
		public function __construct($id = null, $name = null, $title = null, $defaultValue = null)
		{
			parent::__construct($id, $name, $title, $defaultValue);
		}
		
		protected function RenderContent()
		{
			$elem = new HTMLControlInput();
			$elem->ID = $this->ID;
			$elem->Type = HTMLControlInputType::Password;
			$elem->Name = $this->Name;
			$elem->Value = $this->DefaultValue;
			if (isset($this->PlaceholderText))
			{
				$elem->PlaceholderText = $this->PlaceholderText;
			}
			$elem->Render();
		}
	}
	class FormViewItemMemo extends FormViewItemText
	{
		public $Rows;
		public $Columns;
		public $PlaceholderText;
		
		public function __construct($id = null, $name = null, $title = null, $defaultValue = null)
		{
			parent::__construct($id, $name, $title, $defaultValue);
		}
		
		protected function RenderContent()
		{
			$elem = new HTMLControlTextArea();
			$elem->ID = $this->ID;
			$elem->Name = $this->Name;
			if (isset($this->Rows)) $elem->Rows = $this->Rows;
			if (isset($this->Columns)) $elem->Columns = $this->Columns;
			$elem->DefaultValue = $this->DefaultValue;
			if (isset($this->PlaceholderText)) $elem->PlaceholderText = $this->PlaceholderText;
			$elem->Render();
		}
	}
?>