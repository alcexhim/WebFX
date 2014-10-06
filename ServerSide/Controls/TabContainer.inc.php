<?php
	namespace WebFX\Controls;
	
	use WebFX\System;
	use WebFX\WebControl;
	
	\Enum::Create("WebFX\\Controls\\TabContainerTabPosition", "Top", "Bottom", "Left", "Right");
	
	class TabPage
	{
		public $ID;
		public $Title;
		public $Content;
		
		public $Visible;
		
		public $ImageURL;
		public $TargetURL;
		public $TargetScript;
		public $ContentFunction;
		
		public function __construct($id, $title, $imageURL = null, $targetURL = null, $targetScript = null, $contentFunction = null, $visible = true)
		{
			$this->ID = $id;
			$this->Title = $title;
			$this->ImageURL = $imageURL;
			$this->TargetURL = $targetURL;
			$this->TargetScript = $targetScript;
			$this->ContentFunction = $contentFunction;
			$this->Visible = $visible;
		}
	}
	
	class TabContainer extends WebControl
	{
		public $CurrentTab;
		public $TabPages;
		
		public $OnClientTabChanged;
		
		public function GetTabByID($id)
		{
			foreach ($this->TabPages as $tabPage)
			{
				if ($tabPage->ID == $id) return $tabPage;
			}
			return null;
		}
		
		protected function Initialize()
		{
			$oldtab = $this->CurrentTab;
			$this->CurrentTab = $this->GetTabByID($this->GetClientProperty("SelectedTabID"));
			if ($this->CurrentTab == null) $this->CurrentTab = $oldtab;
		}
		
		protected function RenderContent()
		{
?>
			<div class="TabContainer" id="TabContainer_<?php echo($this->ID); ?>" data-onclienttabchanged="<?php echo($this->OnClientTabChanged); ?>">
				<div class="Tabs" id="TabContainer_<?php echo($this->ID); ?>_Tabs"><?php
				foreach ($this->TabPages as $tabPage)
				{
				?><a id="TabContainer_<?php echo($this->ID); ?>_Tabs_<?php echo($tabPage->ID); ?>_Tab" class="Tab<?php
					if ($tabPage->Visible)
					{
						echo (" Visible");
					}
					if ($tabPage->ID == $this->CurrentTab->ID)
					{
						echo (" Selected");
					} ?>" onclick="<?php echo($this->ID); ?>.SetSelectedTab('<?php echo($tabPage->ID); ?>');"><?php echo($tabPage->Title); ?></a><?php
				}
				?></div>
				<div class="TabPages" id="TabContainer_<?php echo($this->ID); ?>_TabPages">
				<?php
				foreach ($this->TabPages as $tabPage)
				{
				?>
					<div id="TabContainer_<?php echo($this->ID); ?>_TabPages_<?php echo($tabPage->ID); ?>_TabPage" class="TabPage<?php
					if ($tabPage == $this->CurrentTab)
					{
						echo (" Selected");
					} ?>"><?php
					if (is_callable($tabPage->ContentFunction))
					{
						call_user_func($tabPage->ContentFunction);
					}
					else
					{
						echo($tabPage->Content);
					}
					?></div>
				<?php
				}
				?>
				</div>
			</div>
			<script type="text/javascript">
				var <?php echo($this->ID); ?> = new TabContainer("<?php echo($this->ID); ?>");<?php
				if ($this->CurrentTab != null)
				{
					echo($this->ID . ".SetSelectedTab('" . $this->CurrentTab->ID . "');");
				}
				?>
			</script>
			<?php
		}
	}
?>