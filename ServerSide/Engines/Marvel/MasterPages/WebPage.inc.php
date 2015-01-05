<?php
	namespace WebFX\Engines\Marvel\MasterPages;
	
	use WebFX\HTMLControls\HTMLControl;
	use WebFX\HTMLControls\HTMLControlAnchor;
	
	/**
	 * The bare minimum base class for a Marvel WebPage.
	 * @author Michael Becker
	 */
	class WebPage extends \WebFX\WebPage
	{
		/**
		 * Array of BreadcrumbItems that are rendered on the breadcrumb bar.
		 * @var BreadcrumbItem[]
		 */
		public $BreadcrumbItems;
		
		/**
		 * Array of MenuItems that are rendered on the header.
		 * @var MenuItem[]
		 */
		public $HeaderMenuItems;
		/**
		 * Array of MenuItems that are rendered on the sidebar.
		 * @var MenuItem[]
		 */
		public $SidebarMenuItems;
	
		private function RenderBreadcrumbItem($item)
		{
			$a = new HTMLControlAnchor();
			$a->TargetURL = $item->TargetURL;
			$a->InnerHTML = $item->Title;
			$a->Render();
		}
		private function RenderMenuItem($item)
		{
			if (get_class($item) == "WebFX\\Engines\\Marvel\\MenuItems\\MenuItemSeparator")
			{
				if ($item->Title == "")
				{
					echo("<hr />");
				}
				else
				{
					echo("<div class=\"Title\">");
				}	
			}
			else if (get_class($item) == "WebFX\\Engines\\Marvel\\MenuItems\\MenuItemCommandReference")
			{
				echo("<a href=\"" . ($item->TargetURL == "" ? "#" : $item->TargetURL) . "\"");
				if ($item->TargetScript != "")
				{
					echo(" onclick=\"" . $item->TargetScript . "\"");
				}
				echo(">" . $item->Title . "</a>");
			}
		}
	
		protected function BeforeContent()
		{
			echo("<div class=\"Page\">");
			echo("<header>");
			echo("<nav class=\"Top\">");
			foreach ($this->HeaderMenuItems as $item)
			{
				$this->RenderMenuItem($item);
			}
			echo("</nav>");
			echo("<nav class=\"Breadcrumbs\">");
			$i = 0;
			$count = count($this->BreadcrumbItems);
			foreach ($this->BreadcrumbItems as $item)
			{
				$this->RenderBreadcrumbItem($item, $i == $count - 1);
			}
			echo("</nav>");
			echo("</header>");
			echo("<div class=\"Sidebar\">");
			foreach ($this->SidebarMenuItems as $item)
			{
				$this->RenderMenuItem($item);
			}
			echo("</div>");
			echo("<div class=\"Content\">");
		}
		protected function AfterContent()
		{
			echo("</div>");
			echo("</div>");
		}
	}
?>