<?php
	namespace WebFX\Controls;
	
	use WebFX\WebControl;
	use WebFX\HTMLControl;
	use WebFX\Enumeration;
	
	use WebFX\WebStyleSheetRule;
use WebFX\WebControlAttribute;
					
	abstract class TrackBarOrientation extends Enumeration
	{
		const Horizontal = 1;
		const Vertical = 2;
	}
	
	class TrackBar extends WebControl
	{
		/**
		 * The orientation of this TrackBar.
		 * @var TrackBarOrientation
		 */
		public $Orientation;
		
		/**
		 * The minimum value of this TrackBar.
		 * @var int
		 */
		public $MinimumValue;
		
		/**
		 * The maximum value of this TrackBar.
		 * @var int
		 */
		public $MaximumValue;
		
		/**
		 * The current value of this TrackBar.
		 * @var int
		 */
		public $CurrentValue;
		
		public function __construct()
		{
			parent::__construct();
			
			$this->TagName = "div";
			$this->ClassList[] = "TrackBar";
			$this->Orientation = TrackBarOrientation::Horizontal;
		}
		protected function OnInitialize()
		{
			$this->Controls = array();
			
			$this->Attributes[] = new WebControlAttribute("data-maximum-value", $this->MaximumValue);
			$this->Attributes[] = new WebControlAttribute("data-minimum-value", $this->MinimumValue);
			$this->Attributes[] = new WebControlAttribute("data-current-value", $this->CurrentValue);
			
			$leftPos = (($this->CurrentValue - $this->MinimumValue) / ($this->MaximumValue - $this->MinimumValue)) * 100;
			
			$divTrack = new HTMLControl("div");
			$divTrack->ClassList[] = "Track";
			
			$divQuantity = new HTMLControl("div");
			$divQuantity->ClassList[] = "Quantity";
			$divQuantity->StyleRules[] = new WebStyleSheetRule("width", $leftPos . "%");
			$divTrack->Controls[] = $divQuantity;
			
			$divThumb = new HTMLControl("div");
			$divThumb->ClassList[] = "Thumb";
			$divThumb->StyleRules[] = new WebStyleSheetRule("left", $leftPos . "%");
			$divTrack->Controls[] = $divThumb;
			
			$this->Controls[] = $divTrack;
			
			parent::OnInitialize();
		}
	}
?>