<?php
	namespace WebFX\Controls;
	use System;
	use WebFX\WebControl;
	
	use WebFX\HorizontalAlignment;
	use WebFX\VerticalAlignment;
	
	class Window extends WebControl
	{
		public $Title;
		
		private $HasButtons;
		
		public function __construct($id, $title = null)
		{
			parent::__construct($id);
			$this->Title = $title;
			$this->HasButtons = false;
		}
		
		protected function BeforeContent()
		{
?>
<div class="Window" id="Window_<?php echo($this->ID); ?>"<?php if (!($this->Visible)) { echo(" style=\"display: none;\""); } ?>>
	<div class="TitleBar" id="Window_<?php echo($this->ID); ?>_TitleBar"><span class="Title" id="Window_<?php echo($this->ID); ?>_TitleBar_Title"><?php echo($this->Title); ?></span></div>
	<div class="Content"<?php
	if ($this->Width != null)
	{
		if (is_numeric($this->Width))
		{
			echo(" style=\"width: " . $this->Width . "px;\"");
		}
		else
		{
			echo(" style=\"width: " . $this->Width . ";\"");
		}
	} ?>>
<?php
		}
		
		public function BeginButtons()
		{
?>
	</div>
	<div class="Buttons">
<?php
		}
		public function EndButtons()
		{
?>
	</div>
<?php
			$this->HasButtons = true;
		}
		
		protected function AfterContent()
		{
if (!$this->HasButtons)
{
?>
	</div>
<?php
}
?>
</div>
<script type="text/javascript">
	var <?php echo($this->ID); ?> = new Window("<?php echo($this->ID); ?>");
	<?php
	switch($this->HorizontalAlignment)
	{
		case HorizontalAlignment::Left:
		{
			echo($this->ID . ".SetHorizontalAlignment(HorizontalAlignment.Left);");
			break;
		}
		case HorizontalAlignment::Center:
		{
			echo($this->ID . ".SetHorizontalAlignment(HorizontalAlignment.Center);");
			break;
		}
		case HorizontalAlignment::Right:
		{
			echo($this->ID . ".SetHorizontalAlignment(HorizontalAlignment.Right);");
			break;
		}
		default:
		{
			echo("// Window: invalid value \"" . $this->HorizontalAlignment . "\" for \"HorizontalAlignment\"\n");
			break;
		}
	}
	switch($this->VerticalAlignment)
	{
		case VerticalAlignment::Top:
		{
			echo($this->ID . ".SetVerticalAlignment(VerticalAlignment.Top);");
			break;
		}
		case VerticalAlignment::Middle:
		{
			echo($this->ID . ".SetVerticalAlignment(VerticalAlignment.Middle);");
			break;
		}
		case VerticalAlignment::Bottom:
		{
			echo($this->ID . ".SetVerticalAlignment(VerticalAlignment.Bottom);");
			break;
		}
		default:
		{
			echo("// Window: invalid value \"" . $this->VerticalAlignment . "\" for \"VerticalAlignment\"\n");
			break;
		}
	}
	if ($this->Top != null)
	{
		echo($this->ID . ".SetTop(" . $this->Top . ");");
	}
	?>
</script>
<?php
		}
	}
?>