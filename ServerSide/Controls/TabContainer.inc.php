<?php
	namespace WebFX\Controls;
	
	use WebFX\System;
	use WebFX\WebControl;
	
	\Enum::Create("WebFX\\Controls\\TabContainerTabPosition", "Top", "Bottom", "Left", "Right");
	
	class TabContainer extends WebControl
	{
		public function BeginTabs()
		{
?>
			<div class="Tabs" id="TabContainer_<?php echo($this->ID); ?>_Tabs">
<?php
		}
		public function EndTabs()
		{
?>
			</div>
<?php
		}
		public function BeginTabPages()
		{
?>
			<div class="TabPages" id="TabContainer_<?php echo($this->ID); ?>_TabPages">
<?php
		}
		public function EndTabPages()
		{
?>
			</div>
<?php
		}
		
		protected function RenderContent()
		{
?>
			<div class="TabContainer" id="TabContainer_<?php echo($this->ID); ?>">
<?php
			$this->BeginTabs();
			foreach ($this->TabPages as $tabPage)
			{
?>
				<a id="TabContainer_<?php echo($this->ID); ?>_Tabs_<?php echo($tabPage->ID); ?>_Tab" class="Tab<?php
				if ($tabPage == $this->CurrentTab)
				{
					echo (" Selected");
				} ?>"><?php echo($tabPage->Title); ?></a>
<?php
			}
			$this->EndTabs();
			$this->BeginTabPages();
			foreach ($this->TabPages as $tabPage)
			{
?>
				<div id="TabContainer_<?php echo($this->ID); ?>_TabPages_<?php echo($tabPage->ID); ?>_TabPage" class="TabPage<?php
				if ($tabPage == $this->CurrentTab)
				{
					echo (" Selected");
				} ?>"><?php echo($tabPage->Content); ?></div>
<?php
			}
			$this->EndTabPages();
		}
	}
?>