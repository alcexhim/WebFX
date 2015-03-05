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
		
		/**
		 * The size of the TrackBar (width when horizontal, height when vertical). 
		 * @var int
		 */
		public $Size;
		
		public function __construct()
		{
			parent::__construct();
			
			$this->TagName = "div";
			$this->ClassList[] = "TrackBar";
			$this->Size = null;
			$this->Orientation = TrackBarOrientation::Horizontal;
		}
		protected function OnInitialize()
		{
			$this->Controls = array();
			
			$this->Attributes[] = new WebControlAttribute("data-maximum-value", $this->MaximumValue);
			$this->Attributes[] = new WebControlAttribute("data-minimum-value", $this->MinimumValue);
			$this->Attributes[] = new WebControlAttribute("data-current-value", $this->CurrentValue);
			
			if (is_string($this->Orientation))
			{
				switch (strtolower($this->Orientation))
				{
					case "horizontal":
					{
						$this->Orientation = TrackBarOrientation::Horizontal;
						break;
					}
					case "vertical":
					{
						$this->Orientation = TrackBarOrientation::Vertical;
						break;
					}
				}
			}
			
			switch ($this->Orientation)
			{
				case TrackBarOrientation::Horizontal:
				{
					$this->ClassList[] = "Horizontal";
					break;
				}
				case TrackBarOrientation::Vertical:
				{
					$this->ClassList[] = "Vertical";
					break;
				}
			}
			
			if ($this->Size != null)
			{
				switch ($this->Orientation)
				{
					case TrackBarOrientation::Horizontal:
					{
						$this->StyleRules[] = new WebStyleSheetRule("width", $this->Size);
						break;
					}
					case TrackBarOrientation::Vertical:
					{
						$this->StyleRules[] = new WebStyleSheetRule("height", $this->Size);
						break;
					}
				}
			}
			
			$leftPos = (($this->CurrentValue - $this->MinimumValue) / ($this->MaximumValue - $this->MinimumValue)) * 100;
			if ($this->Orientation == TrackBarOrientation::Vertical)
			{
				$leftPos = 100 - $leftPos;
			}
			
			$divTrack = new HTMLControl("div");
			$divTrack->ClassList[] = "Track";
			
			$divQuantity = new HTMLControl("div");
			$divQuantity->ClassList[] = "Quantity";
			
			switch ($this->Orientation)
			{
				case TrackBarOrientation::Horizontal:
				{
					$divQuantity->StyleRules[] = new WebStyleSheetRule("width", $leftPos . "%");
					break;
				}
				case TrackBarOrientation::Vertical:
				{
					$divQuantity->StyleRules[] = new WebStyleSheetRule("top", $leftPos . "%");
					break;
				}
			}
			$divTrack->Controls[] = $divQuantity;
			
			$divThumb = new HTMLControl("div");
			$divThumb->ClassList[] = "Thumb";
			switch ($this->Orientation)
			{
				case TrackBarOrientation::Horizontal:
				{
					$divThumb->StyleRules[] = new WebStyleSheetRule("left", $leftPos . "%");
					break;
				}
				case TrackBarOrientation::Vertical:
				{
					$divThumb->StyleRules[] = new WebStyleSheetRule("top", $leftPos . "%");
					break;
				}
			}
			$divTrack->Controls[] = $divThumb;
			
			$this->Controls[] = $divTrack;
			
			parent::OnInitialize();
		}
	}
?>