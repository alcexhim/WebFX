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
		public $SelectedTab;
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
			$oldtab = $this->SelectedTab;
			$this->SelectedTab = $this->GetTabByID($this->GetClientProperty("SelectedTabID"));
			if ($this->SelectedTab == null) $this->SelectedTab = $oldtab;
		}
		
		protected function RenderContent()
		{
?>
			<div class="TabContainer" data-onclienttabchanged="<?php echo($this->OnClientTabChanged); ?>"><div class="Tabs"><?php
				$j = 0;
				foreach ($this->TabPages as $tabPage)
				{
				?><a data-id="<?php echo($tabPage->ID); ?>" class="Tab<?php
					if ($tabPage->Visible)
					{
						echo (" Visible");
					}
					if ($this->SelectedTab != null && ($tabPage->ID == $this->SelectedTab->ID))
					{
						echo (" Selected");
					} ?>" href="<?php if ($tabPage->TargetURL != null) { echo(System::ExpandRelativePath($tabPage->TargetURL)); } else { echo("#"); } ?>"><?php echo($tabPage->Title); ?></a><?php
					$j++;
				}
				?></div><div class="TabPages"><?php
				foreach ($this->TabPages as $tabPage)
				{
				?><div class="TabPage<?php
					if ($this->SelectedTab != null && ($tabPage->ID == $this->SelectedTab->ID))
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
					?></div><?php
				}
				?></div>
			</div>
			<?php
		}
	}
?>