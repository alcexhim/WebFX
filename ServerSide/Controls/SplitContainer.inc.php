<?php
	namespace WebFX\Controls;
	
	\Enum::Create("WebFX\\Controls\\SplitContainerOrientation", "Horizontal", "Vertical");
	
	class SplitContainer extends \WebFX\WebControl
	{
		public $Orientation;
		public $PrimaryPanel;
		public $SecondaryPanel;
		public $SplitterPosition;
		
		public function __construct($id)
		{
			parent::__construct($id);
			$this->Orientation = SplitContainerOrientation::Vertical;
			$this->PrimaryPanel = new SplitContainerPanel($this, "Primary");
			$this->SecondaryPanel = new SplitContainerPanel($this, "Secondary");
		}
		
		protected function BeforeContent()
		{
			echo ("<div class=\"SplitContainer");
			switch ($this->Orientation)
			{
				case SplitContainerOrientation::Horizontal:
				{
					echo(" Horizontal");
					break;
				}
				case SplitContainerOrientation::Vertical:
				{
					echo(" Vertical");
					break;
				}
			}
			echo("\">");
		}
		protected function AfterContent()
		{
			echo("</div>");
			echo("<script type=\"text/javascript\">var " . $this->ID . " = new SplitContainer('" . $this->ID . "');</script>");
		}
	}
	class SplitContainerPanel extends \WebFX\WebControl
	{
		public $ID;
		public $ParentContainer;
		
		public function __construct($parent, $id)
		{
			$this->ParentContainer = $parent;
			$this->ID = $id;
		}
		
		protected function BeforeContent()
		{
			echo("<div class=\"SplitContainerPanel " . $this->ID . "\" id=\"SplitContainer_" . $this->ParentContainer->ID . "_" . $this->ID . "\"");
			if ($this->ID == "Primary" && $this->ParentContainer->SplitterPosition != null)
			{
				echo(" style=\"width: " . $this->ParentContainer->SplitterPosition . "\"");
			}
			echo(">");
		}
		protected function AfterContent()
		{
			echo("</div>");
			if ($this->ID == "Primary")
			{
				echo("<div class=\"Splitter\" id=\"SplitContainer_" . $this->ParentContainer->ID . "_Splitter\"><span class=\"SplitterGrip\">&nbsp;</span></div>");
			}
		}
	}
?>