using System;
using System.Collections.Generic;
using System.ComponentModel;
using System.Linq;
using System.Web;
using System.Web.UI;
using System.Web.UI.WebControls;

namespace SlickUI.Controls
{
	/// <summary>
	/// 
	/// </summary>
	public class Ribbon : WebControl
	{
		private string mvarTitle = String.Empty;
		public string Title { get { return mvarTitle; } set { mvarTitle = value; } }

		private string mvarUserName = String.Empty;
		public string UserName { get { return mvarUserName; } set { mvarUserName = value; } }

		private string mvarImageUrl = String.Empty;
		public string ImageUrl { get { return mvarImageUrl; } set { mvarImageUrl = value; } }

		private RibbonTab mvarActiveTab = null;
		[DesignerSerializationVisibility(DesignerSerializationVisibility.Hidden)]
		public RibbonTab ActiveTab { get { return mvarActiveTab; } set { mvarActiveTab = value; mvarActiveTabID = mvarActiveTab.ID; } }

		private string mvarActiveTabID = null;
		public string ActiveTabID { get { return mvarActiveTabID; } set { mvarActiveTabID = value; } }

		private RibbonCommand.RibbonCommandCollection mvarCommands = new RibbonCommand.RibbonCommandCollection();
		[DesignerSerializationVisibility(DesignerSerializationVisibility.Content), PersistenceMode(PersistenceMode.InnerProperty)]
		public RibbonCommand.RibbonCommandCollection Commands { get { return mvarCommands; } }

		private RibbonTab.RibbonTabCollection mvarTabs = new RibbonTab.RibbonTabCollection();
		[DesignerSerializationVisibility(DesignerSerializationVisibility.Content), PersistenceMode(PersistenceMode.InnerProperty)]
		public RibbonTab.RibbonTabCollection Tabs { get { return mvarTabs; } }

		private RibbonApplicationMenuSettings mvarApplicationMenu = new RibbonApplicationMenuSettings();
		[DesignerSerializationVisibility(DesignerSerializationVisibility.Content), PersistenceMode(PersistenceMode.InnerProperty)]
		public RibbonApplicationMenuSettings ApplicationMenu { get { return mvarApplicationMenu; } }

		private RibbonQuickAccessToolbarSettings mvarQuickAccessToolbar = new RibbonQuickAccessToolbarSettings();
		[DesignerSerializationVisibility(DesignerSerializationVisibility.Content), PersistenceMode(PersistenceMode.InnerProperty)]
		public RibbonQuickAccessToolbarSettings QuickAccessToolbar { get { return mvarQuickAccessToolbar; } }

		private RibbonHelpButtonSettings mvarHelpButton = new RibbonHelpButtonSettings();
		[DesignerSerializationVisibility(DesignerSerializationVisibility.Content), PersistenceMode(PersistenceMode.InnerProperty)]
		public RibbonHelpButtonSettings HelpButton { get { return mvarHelpButton; } }

		private RibbonTabContext.RibbonTabContextCollection mvarContexts = new RibbonTabContext.RibbonTabContextCollection();
		[DesignerSerializationVisibility(DesignerSerializationVisibility.Content), PersistenceMode(PersistenceMode.InnerProperty)]
		public RibbonTabContext.RibbonTabContextCollection Contexts { get { return mvarContexts; } }

		private bool mvarCollapsed = false;
		public bool Collapsed { get { return mvarCollapsed; } set { mvarCollapsed = value; } }

		protected override void OnInit(EventArgs e)
		{
			base.OnInit(e);
			Page.RegisterScript("~/Addons/SlickUI/Scripts/Controls/Ribbon.js");
		}

		protected override void RenderContents(HtmlTextWriter writer)
		{
			writer.Write("<div class=\"RibbonContainer\">");
			if (!String.IsNullOrEmpty(mvarTitle) || !String.IsNullOrEmpty(mvarImageUrl))
			{
				writer.Write("<div class=\"RibbonTitleBar\">");
				if (!String.IsNullOrEmpty(mvarImageUrl))
				{
					writer.Write("<img src=\"" + mvarImageUrl.Replace("~/", Page.Request.ApplicationPath + "/") + "\" /> ");
				}
				if (!String.IsNullOrEmpty(mvarTitle))
				{
					writer.Write("<span class=\"Text\">" + mvarTitle + "</span>");
				}
				writer.Write("</div>");
			}
			writer.Write("<div class=\"Ribbon");
			if (mvarCollapsed) writer.Write(" Collapsed");
			writer.Write("\" data-id=\"" + base.ID + "\">");
			writer.Write("<a id=\"Ribbon_" + this.ID + "_ApplicationButton\" class=\"ApplicationButton\" data-tooltip-title=\"" + mvarApplicationMenu.ToolTipTitle + "\" data-tooltip-content=\"" + mvarApplicationMenu.ToolTipText + "\">");
			writer.Write("<img class=\"Icon\" src=\"data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABoAAAALCAYAAABhwJ3wAAAABmJLR0QA/wD/AP+gvaeTAAAACXBIWXMAAAsTAAALEwEAmpwYAAAAB3RJTUUH3gIcEwcFpaQT2QAAAB1pVFh0Q29tbWVudAAAAAAAQ3JlYXRlZCB3aXRoIEdJTVBkLmUHAAACW0lEQVQ4y3WTS4sVVxSFv33vqUduJ62ooQUVGhRaElQcBASTQaATW3CoE9H8CSeC0JHuQWfgLwgZCf4B8RVQBCGIBJJBHghOkoCgA1/d9PXWedRyUFW3b2vnTHadfdbZq9baZ9vj/16wf+8O0a6//n1tR2a3j/dbrX+er9rZlZuU23YDILVwCdQEBCBUN9HN7dshHxtg7mx8qctttWZnpuWHQ3NTdYPvoGJir4awjU5AShHJyJwjtbgYE/Pf37dNDIJ7y19LzhGDCD5NnLWFJ4gmc66qPDEmJKiqGl95utydxS9lEz8MEGLAqIkpEkMaWxfWXxJHaw2o1geWOh8CMSSEAIcPAR8CIUTmF3+xSTUAd5ePCzlSECHUYwWjtTc8+vH8Fn43quzV6zd6O/IYoiwLfv3jqX1xaI9GowoBNsHTfZdlwdy3l80NZpqkIPk1FIY8/nlRo9GI9eGQqcGAsiw5eGLJnOqa4CtAFHmGJFTXeF9x7LtrTW0DU1Px4dVzKvIMsdnSfv4JCZhbWLK/b11SmQqyPOPgwpJZNsClOuG9ByDViVo1TS7w4Kcz4j1Fla8Y1CX0etDvjYkM6H20DSrj81Mr9uf1i/rs1Ir18ymsmMal2BIZpJhIEikmfPAsXLhtnV+dottXTirFRD93uDLbIBL0AMqdpGqVw6evWDY1Tb+cBoGTRIyxbZqwXjNLMQRu/PBNU942BjDGgCT6RU6/LNuD7nX1ENArirZ1rQ8y3IPfnthXRw+oe44d0ae7dv3vwP7+5Jllg49xRY4h1DSxLdrIa8aiozPeARJkctn1QJKkAAAAAElFTkSuQmCC\" />");
			writer.Write("</a>");

			writer.Write("<div class=\"ApplicationMenu\" id=\"Ribbon_" + this.ID + "_ApplicationMenu\">");
			writer.Write("<div class=\"ApplicationMenuHeader\">&nbsp;</div>");
			writer.Write("<div class=\"ApplicationMenuItems\">");
			foreach (RibbonMenuItem item in mvarApplicationMenu.Commands)
			{
				RenderMenuItem(item, writer);
			}
			writer.Write("</div>");
			writer.Write("</div>");

			writer.Write("<div class=\"RibbonTabContainer\" id=\"Ribbon_" + this.ID + "_TabContainer\">");
			foreach (RibbonTab tab in mvarTabs)
			{
				RenderTab(tab, writer);
			}
			foreach (RibbonTabContext ctx in mvarContexts)
			{
				writer.Write("<div class=\"RibbonTabContext\" style=\"");

				HSLColor borderColor = new HSLColor(ctx.BackgroundColor);
				HSLColor startColor = new HSLColor(borderColor);
				startColor.Hue += 10;
				startColor.Saturation -= 54;
				startColor.Luminosity += 10;
				HSLColor endColor = new HSLColor(borderColor);
				endColor.Saturation -= 54;
				endColor.Luminosity += 20;

				string css = "border-color: " + GradientGenerator.GetColorCSS(borderColor) + ";";
				css += GradientGenerator.GenerateCSS(new GradientColorStop(0.0, startColor), new GradientColorStop(1.0, endColor));
				writer.Write(css);

				if (ctx.Active)
				{
					writer.Write(" display: block;\"");
				}
				writer.Write("\">");
				writer.Write("<div class=\"Title\">");
				writer.Write(ctx.Title);
				writer.Write("</div>");
				foreach (RibbonTab tab in ctx.Tabs)
				{
					RenderTab(tab, writer);
				}
				writer.Write("</div>");
			}
			writer.Write("</div>");

			writer.Write("<div class=\"RibbonTabContentContainer\" id=\"Ribbon_" + this.ID + "_TabContentContainer\">");
			foreach (RibbonTab tab in mvarTabs)
			{
				writer.Write("<div class=\"RibbonTabContent\" data-tab-id=\"" + tab.ID + "\"");
				if ((mvarActiveTabID != null && tab.ID == mvarActiveTabID) || tab == mvarActiveTab)
				{
					writer.Write(" style=\"display: block;\"");
				}
				writer.Write(">");
				foreach (RibbonMenuItem cref in tab.Commands)
				{
					RenderMenuItem(cref, writer);
				}
				writer.Write("</div>");
			}
			writer.Write("</div>");

			if (!String.IsNullOrEmpty(mvarUserName))
			{
				writer.Write("<span class=\"UserName\">" + mvarUserName + "</span>");
			}

			writer.Write("<a class=\"HelpButton\" id=\"Ribbon_" + this.ID + "_HelpButton\" data-tooltip-title=\"" + mvarHelpButton.ToolTipTitle + "\" data-tooltip-content=\"" + mvarHelpButton.ToolTipText + "\"");
			if (mvarHelpButton.TargetURL != null)
			{
				writer.Write(" href=\"" + mvarHelpButton.TargetURL + "\"");
				if (mvarHelpButton.TargetFrame != null)
				{
					writer.Write(" target=\"" + mvarHelpButton.TargetFrame + "\"");
				}
			}
			else
			{
				writer.Write(" href=\"#\"");
			}
			if (mvarHelpButton.OnClientClick != null)
			{
				writer.Write(" onclick=\"" + mvarHelpButton.OnClientClick + "\"");
			}
			if (mvarHelpButton.Visible)
			{
				writer.Write(" style=\"display: inline-block\"");
			}
			writer.Write(">");

			writer.Write("<img src=\"data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAA4AAAAOCAYAAAAfSC3RAAAABmJLR0QA/wD/AP+gvaeTAAAACXBIWXMAAAsTAAALEwEAmpwYAAAAB3RJTUUH3gMDEQIZ+kwBxAAAAB1pVFh0Q29tbWVudAAAAAAAQ3JlYXRlZCB3aXRoIEdJTVBkLmUHAAACPklEQVQoz0WST0gUYRjGf7Nqu4kxg2n+Kd0BIwapGNgQpQ5DpzoEe8ujN7vlsUu0l87VTbpkkGDQYQ0KDyojRGypMEjlSJJjbamZ9H2xa6PQvh0G9T0/v+eB53mNta0qhyfC9p+/Cyvl/dzmz39sV39zuq2ZgXOZxVbr+CXEOJQaB2Blt8bLxR25fN7CtU3MdCLQexBEmpmgzOBAp9GYSR+Bld0awbcdudGXJQjBDyGIEtC1wXM0tm0yPr3ENffMcPOJzGPWNivcfxGJikXGfBHvjsjYlIiKRUREIiUyMioyNqUkUiJ3n76V1Y0Kxvsv2wu7+w05YpPCRJLwYBgm30GxBPl+8FzIFzQjeQBFU6ae1NyH/Zxrm/ghEEOk4NEkFCbADzV+SWOmwcpA0Y/wnCzzH9dJLa7+wkxDEIICogjGpkDF4DlQuGUyt6IJggA/UJgmzM4vU39Qr4qTRIWGGFwHntw2mZzTjIwGKBTW0XCk2prq0Hvg2hoVa1AKYoVnJ4Khh1FiCri2hdaai90d1Of7ThFEGs+Foq9QKIiTSdRzBSRGAHnPJggVV/q6SHW1NxqvFxSuYyatxYnYc+DezewhNDJo4/VbvAlXudCTNYy1rSplvSszn6oMeVn80jrFkiIIE8B1LPL9Fp5rMf5qiasDWdpPtiRgyjD4ulOVZ7PLXM914zkWpmkmL6c1QahYLq/j9tp0trQaNZGjXwWgJoTff8h06TPR6gb22Q5ajjXg9Lbj9mSNVF0DNREA/gPgtSGQXugIvQAAAABJRU5ErkJggg==\" alt=\"Help\" />");
			writer.Write("</a>");

			writer.Write("</div>");
			writer.Write("</div>");
			writer.Write("<div id=\"Ribbon_" + this.ID + "_Spacer\" class=\"RibbonSpacer");
			if (mvarCollapsed) writer.Write(" Collapsed");
			writer.Write("\"></div>");

			writer.Write("<div class=\"RibbonTooltip\" id=\"Tooltip\"><div class=\"Title\" id=\"Tooltip_Title\"></div><div class=\"Content\" id=\"Tooltip_Content\"></div></div>");
		}

		private void RenderTab(RibbonTab tab, HtmlTextWriter writer)
		{
			writer.Write("<a class=\"RibbonTab");
			if ((mvarActiveTabID != null && tab.ID == mvarActiveTabID) || tab == mvarActiveTab)
			{
				writer.Write(" Selected");
			}
			writer.Write("\" data-tab-id=\"" + tab.ID + "\" data-tooltip-title=\"" + tab.ToolTipTitle + "\" data-tooltip-content=\"" + tab.ToolTipText + "\" href=\"#\"");
			if (!tab.Visible)
			{
				writer.Write(" style=\"display: none;\"");
			}
			writer.Write(">");
			if (!String.IsNullOrEmpty(tab.ImageUrl))
			{
				writer.Write("<img src=\"" + tab.ImageUrl.Replace("~/", Page.Request.ApplicationPath + "/") + "\" />");
			}
			writer.Write(tab.Title);

			writer.Write("</a>");
		}

		private void RenderMenuItem(RibbonMenuItem cref, HtmlTextWriter writer)
		{
			if (cref is RibbonCommandReferenceMenuItem)
			{
				RenderCommand(mvarCommands[(cref as RibbonCommandReferenceMenuItem).TargetID], writer);
			}
			else if (cref is RibbonSeparatorMenuItem)
			{
				writer.Write("<span class=\"Separator\">&nbsp;</span>");
			}
		}

		private void RenderCommand(RibbonCommand command, HtmlTextWriter writer)
		{
			if (command == null) return;

			if (command is RibbonButtonCommand)
			{
				RibbonButtonCommand cmd = (command as RibbonButtonCommand);
				string titleText = cmd.Title;
				string accessKey = null;
				if (titleText.Contains("&"))
				{
					string titleTextBefore = titleText.Substring(0, titleText.IndexOf('&'));
					string titleTextAfter = titleText.Substring(titleText.IndexOf('&') + 2);
					accessKey = titleText.Substring(titleText.IndexOf('&') + 1, 1);
					titleText = titleTextBefore + "<u>" + accessKey + "</u>" + titleTextAfter;
				}

				writer.Write("<a class=\"RibbonButtonCommand Ribbon_" + this.ID + "_Commands_" + cmd.ID + "\" data-tooltip-title=\"" + cmd.ToolTipTitle + "\" data-tooltip-content=\"" + cmd.ToolTipText + "\"");

				if (accessKey != null)
				{
					writer.Write(" data-accesskey=\"" + accessKey + "\"");
				}
				if (cmd.TargetURL != null)
				{
					writer.Write(" href=\"" + cmd.TargetURL.Replace("~/", Page.Request.ApplicationPath + "/") + "\"");
				}
				else
				{
					writer.Write(" href=\"#\"");
				}
				if (cmd.TargetFrame != null)
				{
					writer.Write(" target=\"" + cmd.TargetFrame + "\"");
				}

				string onclickstr = "var ribbon = Ribbon.FromID('" + this.ID + "'); ribbon.SetApplicationMenuVisible(false);";

				onclickstr += "if (ribbon.IsCollapsed() && ribbon.IsOpened())";
				onclickstr += "{ ribbon.SetOpened(false); };";
				if (cmd.OnClientClick != null)
				{
					onclickstr += cmd.OnClientClick;
				}
				writer.Write(" onclick=\"" + onclickstr + "\"");
				writer.Write(">");

				if (cmd.ImageURL != null)
				{
					writer.Write("<img class=\"Icon\" src=\"" + cmd.ImageURL.Replace("~/", Page.Request.ApplicationPath + "/") + "\" />");
				}

				writer.Write("<span class=\"Text\">");
				writer.Write(titleText);
				writer.Write("</span>");

				writer.Write("<span class=\"SpacerText\">");
				writer.Write(titleText);
				writer.Write("</span>");

				writer.Write("</a>");
			}
		}
	}

	[ParseChildren(true, "Tabs")]
	public class RibbonTabContext
	{
		public class RibbonTabContextCollection
			: System.Collections.ObjectModel.Collection<RibbonTabContext>
		{

		}

		private string mvarID = String.Empty;
		public string ID { get { return mvarID; } set { mvarID = value; } }

		private string mvarTitle = String.Empty;
		public string Title { get { return mvarTitle; } set { mvarTitle = value; } }

		private System.Drawing.Color mvarBackgroundColor = System.Drawing.Color.Empty;
		public System.Drawing.Color BackgroundColor { get { return mvarBackgroundColor; } set { mvarBackgroundColor = value; } }

		private bool mvarActive = false;
		public bool Active { get { return mvarActive; } set { mvarActive = value; } }

		private RibbonTab.RibbonTabCollection mvarTabs = new RibbonTab.RibbonTabCollection();
		public RibbonTab.RibbonTabCollection Tabs { get { return mvarTabs; } }
	}


	[ParseChildren(true, "Commands")]
	public class RibbonTab
	{
		public class RibbonTabCollection
			: System.Collections.ObjectModel.Collection<RibbonTab>
		{
			public RibbonTab Add(string id, string title, string imageURL = null, string tooltipTitle = null, string tooltipText = null)
			{
				RibbonTab item = new RibbonTab();
				item.ID = id;
				item.Title = title;
				item.ImageUrl = (imageURL == null ? String.Empty : imageURL);
				item.ToolTipTitle = (tooltipTitle == null ? String.Empty : tooltipTitle);
				item.ToolTipText = (tooltipText == null ? String.Empty : tooltipText);
				Add(item);
				return item;
			}

			public RibbonTab this[string id]
			{
				get
				{
					foreach (RibbonTab tab in this)
					{
						if (tab.ID == id) return tab;
					}
					return null;
				}
			}
		}

		private string mvarID = String.Empty;
		public string ID { get { return mvarID; } set { mvarID = value; } }

		private string mvarTitle = String.Empty;
		public string Title { get { return mvarTitle; } set { mvarTitle = value; } }

		private string mvarToolTipTitle = String.Empty;
		public string ToolTipTitle { get { return mvarToolTipTitle; } set { mvarToolTipTitle = value; } }

		private string mvarToolTipText = String.Empty;
		public string ToolTipText { get { return mvarToolTipText; } set { mvarToolTipText = value; } }

		private string mvarImageUrl = null;
		public string ImageUrl { get { return mvarImageUrl; } set { mvarImageUrl = value; } }

		private RibbonMenuItem.RibbonMenuItemCollection mvarCommands = new RibbonMenuItem.RibbonMenuItemCollection();
		public RibbonMenuItem.RibbonMenuItemCollection Commands { get { return mvarCommands; } }

		private bool mvarVisible = true;
		public bool Visible { get { return mvarVisible; } set { mvarVisible = value; } }
	}

	[ParseChildren(true, "Commands")]
	public class RibbonApplicationMenuSettings
	{
		private string mvarToolTipTitle = String.Empty;
		public string ToolTipTitle { get { return mvarToolTipTitle; } set { mvarToolTipTitle = value; } }

		private string mvarToolTipText = String.Empty;
		public string ToolTipText { get { return mvarToolTipText; } set { mvarToolTipText = value; } }

		private RibbonMenuItem.RibbonMenuItemCollection mvarCommands = new RibbonMenuItem.RibbonMenuItemCollection();
		public RibbonMenuItem.RibbonMenuItemCollection Commands { get { return mvarCommands; } }
	}

	public enum RibbonQuickAccessToolbarPosition
	{
		AboveRibbon,
		BelowRibbon
	}

	[ParseChildren(true, "Commands")]
	public class RibbonQuickAccessToolbarSettings
	{
		private RibbonQuickAccessToolbarPosition mvarPosition = RibbonQuickAccessToolbarPosition.AboveRibbon;
		public RibbonQuickAccessToolbarPosition Position { get { return mvarPosition; } set { mvarPosition = value; } }

		private RibbonMenuItem.RibbonMenuItemCollection mvarCommands = new RibbonMenuItem.RibbonMenuItemCollection();
		public RibbonMenuItem.RibbonMenuItemCollection Commands { get { return mvarCommands; } }
	}

	public class RibbonHelpButtonSettings
	{
		private bool mvarVisible = false;
		public bool Visible { get { return mvarVisible; } set { mvarVisible = value; } }

		private string mvarOnClientClick = null;
		public string OnClientClick { get { return mvarOnClientClick; } set { mvarOnClientClick = value; } }

		public event EventHandler Click = null;

		private string mvarTargetURL = null;
		public string TargetURL { get { return mvarTargetURL; } set { mvarTargetURL = value; } }

		private string mvarTargetFrame = null;
		public string TargetFrame { get { return mvarTargetFrame; } set { mvarTargetFrame = value; } }

		private string mvarToolTipText = String.Empty;
		public string ToolTipText { get { return mvarToolTipText; } set { mvarToolTipText = value; } }

		private string mvarToolTipTitle = String.Empty;
		public string ToolTipTitle { get { return mvarToolTipTitle; } set { mvarToolTipTitle = value; } }
	}

	public class RibbonCommandReferenceMenuItem : RibbonMenuItem
	{
		private string mvarTargetID = String.Empty;
		public string TargetID { get { return mvarTargetID; } set { mvarTargetID = value; } }
	}
	public class RibbonSeparatorMenuItem : RibbonMenuItem
	{

	}
	public abstract class RibbonMenuItem
	{
		public class RibbonMenuItemCollection
			: System.Collections.ObjectModel.Collection<RibbonMenuItem>
		{
			public RibbonCommandReferenceMenuItem AddCommand(string targetID)
			{
				RibbonCommandReferenceMenuItem item = new RibbonCommandReferenceMenuItem();
				item.TargetID = targetID;
				Add(item);
				return item;
			}
			public RibbonSeparatorMenuItem AddSeparator()
			{
				RibbonSeparatorMenuItem item = new RibbonSeparatorMenuItem();
				Add(item);
				return item;
			}
		}
	}

	public abstract class RibbonCommand
	{
		private string mvarID = String.Empty;
		public string ID { get { return mvarID; } set { mvarID = value; } }

		public event EventHandler Click = null;

		private string mvarOnClientClick = null;
		public string OnClientClick { get { return mvarOnClientClick; } set { mvarOnClientClick = value; } }

		private string mvarTargetURL = null;
		public string TargetURL { get { return mvarTargetURL; } set { mvarTargetURL = value; } }

		private string mvarTargetFrame = null;
		public string TargetFrame { get { return mvarTargetFrame; } set { mvarTargetFrame = value; } }

		private string mvarToolTipText = String.Empty;
		public string ToolTipText { get { return mvarToolTipText; } set { mvarToolTipText = value; } }

		private string mvarToolTipTitle = String.Empty;
		public string ToolTipTitle { get { return mvarToolTipTitle; } set { mvarToolTipTitle = value; } }

		public class RibbonCommandCollection
			: System.Collections.ObjectModel.Collection<RibbonCommand>
		{
			public RibbonCommand this[string ID]
			{
				get
				{
					foreach (RibbonCommand cmd in this)
					{
						if (cmd.ID == ID) return cmd;
					}
					return null;
				}
			}
		}
	}

	public class RibbonButtonCommand : RibbonCommand
	{
		private string mvarTitle = String.Empty;
		public string Title { get { return mvarTitle; } set { mvarTitle = value; } }

		private string mvarImageURL = null;
		public string ImageURL { get { return mvarImageURL; } set { mvarImageURL = value; } }
	}

	[ParseChildren(true, "Commands")]
	public class RibbonDropDownCommand : RibbonButtonCommand
	{

		private RibbonMenuItem.RibbonMenuItemCollection mvarCommands = new RibbonMenuItem.RibbonMenuItemCollection();
		public RibbonMenuItem.RibbonMenuItemCollection Commands { get { return mvarCommands; } }

	}

	[ParseChildren(true, "Controls")]
	public class RibbonControlContainer : RibbonMenuItem
	{
		private System.Collections.ObjectModel.Collection<Control> mvarControls = new System.Collections.ObjectModel.Collection<Control>();
		public System.Collections.ObjectModel.Collection<Control> Controls { get { return mvarControls; } }
	}
}