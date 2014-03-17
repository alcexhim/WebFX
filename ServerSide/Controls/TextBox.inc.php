<?php
	namespace WebFX\Controls;
	use WebFX\WebControl;
	
	use WebFX\Controls\ListView;
	use WebFX\Controls\ListViewColumn;
	use WebFX\Controls\ListViewItem;
	use WebFX\Controls\ListViewItemColumn;
	
	use WebFX\System;
	
	class TextBox extends WebControl
	{
		public $Name;
		public $CssClass;
		public $PlaceholderText;
		
		public $Columns;
		public $Items;
		
		public $InnerStyle;
		
		public $RequireSelectionFromChoices;
		public $EnableMultipleSelection;
		
		public $ShowColumnHeaders;
		
		public $SuggestionURL;
		
		public function __construct($id, $name = null)
		{
			parent::__construct($id);
			if ($name == null) $name = $id;
			
			$this->Name = $name;
			$this->ShowColumnHeaders = true;
			$this->Items = array();
		}
		
		protected function RenderContent()
		{
?>
<div class="Textbox<?php if ($this->RequireSelectionFromChoices) echo(" TextboxMustSelect"); if ($this->CssClass != null) echo (" " . $this->CssClass); ?>" id="Textbox_<?php echo($this->ID); ?>" onclick="<?php echo($this->ID); ?>.Focus();">
	<div class="TextboxContent">
		<span class="TextboxSelectedItems" id="Textbox_<?php echo($this->ID); ?>_items">
		<?php
		$i = 0;
		foreach ($this->Items as $item)
		{
			if (!$item->Selected) continue;
		?>
			<span id="Textbox_txtReceiver_items_<?php echo($i); ?>" class="TextboxSelectedItem">
				<span class="TextboxSelectedItemText"><?php echo($item->Title); ?></span>
				<a class="TextboxSelectedItemCloseButton" onclick="txtReceiver.RemoveItem(<?php echo($i); ?>);" href="#">x</a>
			</span>
		<?php
		$i++;
		}
		?>
		</span>
		<input type="text" autocomplete="off" id="Textbox_<?php echo($this->ID); ?>_textbox" name="<?php echo($this->Name); ?>" placeholder="<?php echo($this->PlaceholderText); ?>"<?php
			if ($this->Width != null)
			{
				echo(" style=\"width: " . $this->Width . ";\"");
			}
			if ($this->InnerStyle != null)
			{
				echo (" style=\"" . $this->InnerStyle . "\"");
			}
		?> />
	</div>
	<div class="TextboxSuggestionList Popup" id="Textbox_<?php echo($this->ID); ?>_popup">
	<?php
		$lv = new ListView("Textbox_" . $this->ID . "_ListView");
		$lv->ShowColumnHeaders = $this->ShowColumnHeaders;
		$lv->Columns = $this->Columns;
		$lv->Items = $this->Items;
		$lv->Render();
	?>
	</div>
</div>
<script type="text/javascript">
	var <?php echo($this->ID); ?> = new TextBox("<?php echo($this->ID); ?>", "<?php echo($this->Name); ?>");
	<?php echo($this->ID); ?>.EnableMultipleSelection = <?php echo($this->EnableMultipleSelection ? "true" : "false"); ?>;
	<?php echo($this->ID); ?>.SuggestionURL = "<?php echo(System::ExpandRelativePath($this->SuggestionURL)); ?>";
</script>
<?php
		}
	}
?>