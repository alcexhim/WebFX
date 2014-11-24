<?php
	namespace WebFX\Controls;
	
	use WebFX\WebControl;
	
	/// <summary>
	/// Provides a page section that can hold any <see cref="WebControl" />. Content is rendered to
	/// the portion of the master page with the same ID as the <see cref="PlaceholderID" />.
	/// </summary>
	class Section extends WebControl
	{
		public $PlaceholderID;
	}
	class SectionPlaceholder extends WebControl
	{
	}
?>
