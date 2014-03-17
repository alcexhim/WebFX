<?php
	namespace WebFX\Controls;
	
	use WebFX\System;
	use WebFX\WebControl;
	
	\Enum::Create("WebFX\\Controls\\ListViewMode", "Icon", "Tile", "Detail");
	
	class ListViewColumn
	{
		public $Name;
		public $Title;
		public $ImageURL;
		public $Width;
		
		public function __construct($name, $title, $imageURL = null, $width = null)
		{
			$this->Name = $name;
			$this->Title = $title;
			$this->ImageURL = $imageURL;
			$this->Width = $width;
		}
	}
	class ListViewItem
	{
		public $ID;
		public $Columns;
		public $Selected;
		public $NavigateURL;
		public $OnClientClick;
		
		public function __construct($columns = null, $selected = false)
		{
			if ($columns != null)
			{
				$this->Columns = $columns;
			}
			$this->Selected = $selected;
		}
	}
	class ListViewItemColumn
	{
		public $Name;
		public $Text;
		public $Content;
		public $OnRetrieveContent;
		public $UserData;
		
		public function __construct($name, $content, $text = null, $onRetrieveContent = null, $userData = null)
		{
			$this->Name = $name;
			$this->Content = $content;
			if ($text == null) $text = $content;
			$this->Text = $text;
			$this->OnRetrieveContent = $onRetrieveContent;
			$this->UserData = $userData;
		}
	}
	class ListView extends WebControl
	{
		public $AllowFiltering;
		
		public $Columns;
		public $Items;
		
		public $Mode;
		
		public function __construct($id)
		{
			parent::__construct($id);
			$this->Columns = array();
			$this->Items = array();
			$this->AllowFiltering = true;
			$this->Mode = ListViewMode::Detail;
		}
		
		protected function RenderContent()
		{
			if (count($this->Items) <= 0)
			{
?>
<div class="ListView" style="display: table; margin-left: auto; margin-right: auto;">
	There are no items
</div>
<?php
			}
			else
			{
				switch ($this->Mode)
				{
					case ListViewMode::Detail:
					{
?>
<table class="ListView" style="margin-left: auto; margin-right: auto;<?php if ($this->Width != null) echo(" width: " . $this->Width); ?>">
	<thead>
		<tr>
		<?php
			foreach ($this->Columns as $column)
			{
		?>
			<th<?php if ($column->Width != null) { echo (" style=\"width: " . $column->Width . ";\""); } ?>><a href="#" onclick="lvListView.Sort('<?php echo($column->Name); ?>'); return false;"><?php echo($column->Title); ?></a></th>
		<?php
			}
		?>
		</tr>
	<?php
		if ($this->AllowFiltering)
		{
	?>
	<tr class="Filter">
	<?php
		foreach ($this->Columns as $column)
		{
	?>
		<td>
			<form method="POST">
				<input class="Filter" type="text" name="ListView_<?php echo($this->ID); ?>_Filter_<?php echo($column->Name); ?>" placeholder="Filter by <?php echo($column->Title); ?>" value="<?php echo($_POST["ListView_" . $this->ID . "_Filter_" . $column->Name]); ?>" />
			</form>
		</td>
	<?php
		}
	?>
	</tr>
	<?php
		}
	?>
	</thead>
	<tbody>
	<?php
		$alternate = false;
		foreach ($this->Items as $item)
		{
			$continueItem = false;
			if ($this->AllowFiltering)
			{
				foreach ($item->Columns as $column)
				{
					$vps = $_POST["ListView_" . $this->ID . "_Filter_" . $column->Name];
					if (isset($vps))
					{
						if ($vps != "" && (mb_stripos($column->Text, $vps) === false))
						{
							$continueItem = true;
							break;
						}
					}
				}
				if ($continueItem) continue;
			}
	?>
		<tr<?php
		if ($alternate)
		{
			if ($item->Selected)
			{
				echo(" class=\"Alternate Selected\"");
			}
			else
			{
				echo(" class=\"Alternate\"");
			}
		}
		else
		{
			if ($item->Selected)
			{
				echo(" class=\"Selected\"");
			}
			else
			{
			}
		}
		?>>
		<?php
		foreach ($item->Columns as $column)
		{
		?>
			<td><?php
				if ($item->NavigateURL != null)
				{
					?><a class="Wrapper" href="<?php echo(System::ExpandRelativePath($item->NavigateURL)); ?>"><?php
				}
				if ($column->OnRetrieveContent != null)
				{
					call_user_func($column->OnRetrieveContent, $column->UserData);
				}
				else
				{
					echo($column->Content);
				}
				
				if ($item->NavigateURL != null)
				{
					?></a><?php
				}
			?></td>
		<?php
		}
		?>
		</tr>
	<?php
			$alternate = !$alternate;
		}
	?>
	</tbody>
</table>
<?php
						break;
					}
					case ListViewMode::Tile:
					{
?>
<div class="ListView TileView" id="ListView_<?php echo($this->ID); ?>">
<?php
	foreach ($this->Items as $item)
	{
		?>
		<a id="ListView_<?php echo($this->ID); ?>_<?php echo($item->ID); ?>" href="<?php
		if ($item->NavigateURL != null)
		{
			echo($item->NavigateURL);
		}
		else
		{
			echo("#");
		}
		?>" onclick="<?php
		if ($item->OnClientClick != null)
		{
			echo($item->OnClientClick);
		}
		else
		{
			echo("return false;");
		}
		?>">
		<?php
			$max = count($item->Columns);
			
			for ($i = 0; $i < $max; $i++)
			{
				if ($i == 0)
				{
					?><span class="ItemText"><?php
				}
				else
				{
					?><span class="ItemDetail"><?php
				}
				echo($item->Columns[$i]->Text);
				?></span><?php
			}
		?>
		</a>
		<?php
	}
?>
</div>
<?php
						break;
					}
				}
			}
		}
	}
?>