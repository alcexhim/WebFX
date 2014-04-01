function Ribbon(id, parent)
{
	var ribbon = this;
	this.ID = id;
	this.ParentElement = parent;

	WebFramework.AddEventListener(parent, WebFramework.Events.MouseWheel, function(e)
	{
		if (e.Delta > 0)
		{
			ribbon.PreviousTab();
			e.Cancel = true;
		}
		else if (e.Delta < 0)
		{
			// forward
			ribbon.NextTab();
			e.Cancel = true;
		}
	});

	var applicationButton = parent.getElementsByClassName("ApplicationButton")[0];
	var applicationMenu = document.getElementById("Ribbon_" + this.ID + "_ApplicationMenu");

	this.PreviousTab = function()
	{
		var tabContainer = document.getElementById("Ribbon_" + this.ID + "_TabContainer");
		var tabContentContainer = document.getElementById("Ribbon_" + this.ID + "_TabContentContainer");
		var tabs = tabContentContainer.getElementsByClassName("RibbonTabContent");
		for (var i = 1; i < tabs.length; i++)
		{
			if (tabs[i].className == "RibbonTabContent Selected")
			{
				ribbon.ActivateTab(tabs[i - 1]);
				return;
			}
		}
	};
	this.NextTab = function()
	{
		var tabContainer = document.getElementById("Ribbon_" + this.ID + "_TabContainer");
		var tabContentContainer = document.getElementById("Ribbon_" + this.ID + "_TabContentContainer");
		var tabs = tabContentContainer.getElementsByClassName("RibbonTabContent");
		for (var i = 0; i < tabs.length - 1; i++)
		{
			if (tabs[i].className == "RibbonTabContent Selected")
			{
				ribbon.ActivateTab(tabs[i + 1]);
				return;
			}
		}
	};

	this.GetActiveTab = function()
	{
		var tabContentContainer = document.getElementById("Ribbon_" + this.ID + "_TabContentContainer");
		var tabs = tabContentContainer.getElementsByClassName("RibbonTabContent");
		for (var i = 0; i < tabs.length; i++)
		{
			if (tabs[i].classList.contains("Selected"))
			{
				return tabs[i];
			}
		}
		return null;
	};
	this.ActivateTab = function (tab)
	{
		if (tab != null) ribbon.SetOpened(true);
		var tabContainer = document.getElementById("Ribbon_" + this.ID + "_TabContainer");
		var ribbonTabs = tabContainer.getElementsByTagName("A");

		var tabContentContainer = document.getElementById("Ribbon_" + this.ID + "_TabContentContainer");
		var tabs = tabContentContainer.getElementsByClassName("RibbonTabContent");
		for (var i = 0; i < tabs.length; i++)
		{
			if (tabs[i] === tab)
			{
				ribbonTabs[i].className = "RibbonTab Selected";
				tabs[i].className = "RibbonTabContent Selected";
			}
			else
			{
				ribbonTabs[i].className = "RibbonTab";
				tabs[i].className = "RibbonTabContent";
			}
		}

		if (tab == null)
		{
			// WebFramework.ClearClientProperty(this.ID, "ActiveTabID");
		}
		else
		{
			WebFramework.SetClientProperty(this.ID, "ActiveTabID", tab.attributes["data-tab-id"].value);
		}
	};

	this.ToggleOpened = function ()
	{
		if (ribbon.IsOpened())
		{
			ribbon.SetOpened(false);
		}
		else
		{
			ribbon.SetOpened(true);
		}
	};
	this.IsOpened = function ()
	{
		return (ribbon.ParentElement.className != "Ribbon Collapsed");
	}
	this.SetOpened = function (value)
	{
		switch (value)
		{
			case true:
			{
				ribbon.ParentElement.className = "Ribbon";
				break;
			}
			case false:
			{
				ribbon.ParentElement.className = "Ribbon Collapsed";
				ribbon.ActivateTab(null);
				break;
			}
		}
	};

	this.ToggleCollapsed = function ()
	{
		if (ribbon.IsCollapsed())
		{
			ribbon.SetCollapsed(false);
		}
		else
		{
			ribbon.SetCollapsed(true);
		}
	};
	this.IsCollapsed = function ()
	{
		var ribbonSpacer = document.getElementById("Ribbon_" + ribbon.ID + "_Spacer");
		return (ribbonSpacer.className == "RibbonSpacer Collapsed");
	};
	this.SetCollapsed = function (value)
	{
		var ribbonSpacer = document.getElementById("Ribbon_" + ribbon.ID + "_Spacer");
		ribbon.SetOpened(!value);
		switch (value)
		{
			case true:
			{
				ribbonSpacer.className = "RibbonSpacer Collapsed";
				ribbon.ActivateTab(null);
				break;
			}
			case false:
			{
				ribbonSpacer.className = "RibbonSpacer";
				break;
			}
		}

		WebFramework.SetClientProperty(this.ID, "Collapsed", value);
	};
	this.SetApplicationMenuVisible = function (value)
	{
		switch (value)
		{
			case true:
			{
				applicationButton.className = "ApplicationButton Selected";
				applicationMenu.className = "ApplicationMenu Visible";
				break;
			}
			case false:
			{
				applicationButton.className = "ApplicationButton";
				applicationMenu.className = "ApplicationMenu";
				break;
			}
		}
	};

	this.GetTabByIndex = function (i)
	{
		var tabContentContainer = document.getElementById("Ribbon_" + this.ID + "_TabContentContainer");
		var tabs = tabContentContainer.getElementsByClassName("RibbonTabContent");
		return tabs[i];
	};
	this.GetTabByName = function (name)
	{
		var tabContentContainer = document.getElementById("Ribbon_" + this.ID + "_TabContentContainer");
		var tabs = tabContentContainer.getElementsByClassName("RibbonTabContent");
		for (var i = 0; i < tabs.length; i++)
		{
			if (tabs[i].attributes["data-tab-id"].value == name)
			{
				return tabs[i];
			}
		}
		return null;
	};

	WebFramework.AddEventListener(applicationButton, WebFramework.Events.MouseClick, function (e)
	{
		if (applicationButton.className == "ApplicationButton")
		{
			if (ribbon.IsCollapsed() && ribbon.IsOpened())
			{
				ribbon.SetOpened(false);
			}
			ribbon.SetApplicationMenuVisible(true);
		}
		else
		{
			ribbon.SetApplicationMenuVisible(false);
		}
	});

	var tabContainer = document.getElementById("Ribbon_" + this.ID + "_TabContainer");

	var ribbonTabs = tabContainer.getElementsByTagName("A");
	for (var i = 0; i < ribbonTabs.length; i++)
	{
		var tab = ribbonTabs[i];
		tab.addEventListener("click", function (e)
		{
			if (this.attributes["href"].value == "#")
			{
				// only activate tab if the tab is not a "navigational" tab
				ribbon.ActivateTab(ribbon.GetTabByName(this.attributes["data-tab-id"].value));
				e.preventDefault();
				e.stopPropagation();
				return false;
			}
		});
		tab.addEventListener("dblclick", function (e)
		{
			ribbon.ToggleCollapsed();
			e.preventDefault();
			e.stopPropagation();
			return false;
		});
	}
}

var Ribbons = new Array();
window.addEventListener('load', function (e)
{
	var ribbons = document.getElementsByClassName("Ribbon");
	for (var i = 0; i < ribbons.length; i++)
	{
		var parent = ribbons[i];
		if (parent.tagName != "DIV") continue;
		var ribbon = new Ribbon(parent.attributes["data-id"].value, parent);
		parent.Ribbon = ribbon;

		parent.addEventListener("contextmenu", function(e)
		{
			e.preventDefault();
			e.stopPropagation();
			return false;
		});

		Ribbons.push(ribbon);
	}

	var tooltips = document.getElementsByTagName("*");

	for (var i = 0; i < tooltips.length; i++)
	{
		(function(tt)
		{
			if (typeof(tt.attributes["data-tooltip-content"]) === 'undefined' && typeof(tt.attributes["data-tooltip-title"]) === 'undefined') return;
		
			var delay = 1000;
			if (tt.attributes["data-tooltip-delay"])
			{
				delay = tt.attributes["data-tooltip-delay"];
			}

			tt.tooltipTimer = null;
			tt.onmousemove = function(e)
			{
				tt.mouseX = e.clientX;
				tt.mouseY = e.clientY;
			};
			tt.onmouseover = function(e)
			{
				// alert(ribbonTooltips[i].attributes["data-control-id"].value);
				if (tt.classList.contains("Disabled")) return;

				if (tt.tooltipTimer != null) window.clearTimeout(tt.tooltipTimer);
				tt.tooltipTimer = window.setTimeout(function(tt)
				{
					var x = tt.mouseX;
					var y = tt.mouseY;

					var tooltip = document.getElementById("Tooltip");

					var tooltipTitle = (tt.attributes["data-tooltip-title"] != null ? tt.attributes["data-tooltip-title"].value : "");
					var tooltipContent = (tt.attributes["data-tooltip-content"] != null ? tt.attributes["data-tooltip-content"].value : "");
					var tooltipContextHelpURL = (tt.attributes["data-tooltip-contexthelpurl"] != null ? tt.attributes["data-tooltip-contexthelpurl"].value : "");
					var tooltipContextHelpTargetName = (tt.attributes["data-tooltip-contexthelptarget"] != null ? tt.attributes["data-tooltip-contexthelptarget"].value : "_blank");
				
					if (tooltipTitle == "" || tooltipContent == "") return;

					var tooltipTitleElement = document.getElementById("Tooltip_Title");
					tooltipTitleElement.innerHTML = tooltipTitle;
					var tooltipContentElement = document.getElementById("Tooltip_Content");
					var tooltipContextHelpElement = document.getElementById("Tooltip_ContextHelp");
					if (tooltipContextHelpURL != "")
					{
						tooltipContextHelpElement.style.display = "block";
					}
					else
					{
						tooltipContextHelpElement.style.display = "none";
					}
					Ribbon.CurrentContextHelpURL = tooltipContextHelpURL;
					Ribbon.CurrentContextHelpTargetName = tooltipContextHelpTargetName;

					tooltipContentElement.innerHTML = tooltipContent;

					tooltip.style.left = x + "px";
					tooltip.style.top = (y + 16) + "px";
					tooltip.className = "RibbonTooltip Visible";
				}, delay, tt);
			};
			tt.onmouseout = function(e)
			{
				var tooltip = document.getElementById("Tooltip");
				tooltip.className = "RibbonTooltip";
				Ribbon.CurrentContextHelpURL = "";
				Ribbon.CurrentContextHelpTargetName = "_blank";
				if (tt.tooltipTimer != null) window.clearTimeout(tt.tooltipTimer);
			};
		})(tooltips[i]);
	}
});
window.addEventListener("keydown", function (e)
{
	switch (e.keyCode)
	{
		case 27: /* ESC */
		{
			// ESCAPE was pressed; hide all application menus
			for (var i = 0; i < Ribbons.length; i++)
			{
				Ribbons[i].SetApplicationMenuVisible(false);
			}
			break;
		}
		case 112: /* F1 */
		{
			if (Ribbon.CurrentContextHelpURL != "")
			{
				var path = Ribbon.CurrentContextHelpURL.replace(/~\//g, ApplicationPath + "/");
				window.open(path, Ribbon.CurrentContextHelpTargetName);
			}
			break;
		}
	}
});


function TerminateIfSenderIs(sender, compareTo)
{
	while (sender != null)
	{
		if (sender.classList)
		{
			for (var i = 0; i < compareTo.length; i++)
			{
				if (sender.classList.contains(compareTo[i]))
				{
					// do not close the popup when we click inside itself
					// e.preventDefault();
					// e.stopPropagation();
					// alert(compareTo[i] + " = " + sender.className + " ? true ");
					return true;
				}
			}
		}
		sender = sender.parentNode;
		if (sender == null) break;
	}
	return false;
}

window.addEventListener("mousedown", function (e)
{
	var sender = null;
	if (!e) e = window.event;
	if (e.target)
	{
		sender = e.target;
	}
	else if (e.srcElement)
	{
		sender = e.srcElement;
	}

	if (!TerminateIfSenderIs(sender, ["ApplicationMenu"]))
	{
		for (var i = 0; i < Ribbons.length; i++)
		{
			Ribbons[i].SetApplicationMenuVisible(false);
		}
	}

	if (!TerminateIfSenderIs(sender, ["Ribbon"]))
	{
		for (var i = 0; i < Ribbons.length; i++)
		{
			if (Ribbons[i].IsCollapsed())
			{
				Ribbons[i].SetOpened(false);
			}
		}
	}

	if (!TerminateIfSenderIs(sender, ["RibbonDropDownCommand"]))
	{
		Ribbon.CloseAllRibbonDropDownMenus();
	}
});
Ribbon.FromID = function (id)
{
	for (var i = 0; i < Ribbons.length; i++)
	{
		if (Ribbons[i].ID == id) return Ribbons[i];
	}
	return null;
};
Ribbon.CloseAllRibbonDropDownMenus = function()
{
	var RibbonDropDownItems = document.getElementsByClassName("RibbonDropDownCommand");
	for (var i = 0; i < RibbonDropDownItems.length; i++)
	{
		RibbonDropDownItems[i].classList.remove("Selected");
	}
};
Ribbon.CurrentContextHelpURL = "";
Ribbon.CurrentContextHelpTargetName = "_blank";

function RibbonButtonCommand(id)
{
	this.ID = id;
	this.SetSelected = function(value)
	{
		switch (value)
		{
			case true:
			{
				var rbc_array = document.getElementsByClassName(this.ID);
				for (var i = 0; i < rbc_array.length; i++)
				{
					if (rbc_array[i].classList.contains("Disabled")) continue;
					rbc_array[i].className = "RibbonCommand RibbonButtonCommand " + this.ID + " Selected";
				}
				break;
			}
			case false:
			{
				var rbc_array = document.getElementsByClassName(this.ID);
				for (var i = 0; i < rbc_array.length; i++)
				{
					if (rbc_array[i].classList.contains("Disabled")) continue;
					rbc_array[i].className = "RibbonCommand RibbonButtonCommand " + this.ID;
				}
				break;
			}
		}
	};
	this.IsSelected = function()
	{
		var rbc_array = document.getElementsByClassName(this.ID);
		for (var i = 0; i < rbc_array.length; i++)
		{
			if (rbc_array[i].className == "RibbonCommand RibbonButtonCommand " + this.ID + " Selected") return true;
		}
		return false;
	};
	this.ToggleSelected = function()
	{
		this.SetSelected(!this.IsSelected());
		return false;
	};
}
function RibbonDropDownCommand(id)
{
	this.ID = id;
	this.SetSelected = function(value)
	{
		switch (value)
		{
			case true:
			{
				Ribbon.CloseAllRibbonDropDownMenus();
				var rbc_array = document.getElementsByClassName(this.ID);
				for (var i = 0; i < rbc_array.length; i++)
				{
					if (rbc_array[i].classList.contains("Disabled")) continue;
					rbc_array[i].className = "RibbonCommand RibbonDropDownCommand " + this.ID + " Selected";
				}
				break;
			}
			case false:
			{
				var rbc_array = document.getElementsByClassName(this.ID);
				for (var i = 0; i < rbc_array.length; i++)
				{
					if (rbc_array[i].classList.contains("Disabled")) continue;
					rbc_array[i].className = "RibbonCommand RibbonDropDownCommand " + this.ID;
				}
				break;
			}
		}
	};
	this.IsSelected = function()
	{
		var rbc_array = document.getElementsByClassName(this.ID);
		for (var i = 0; i < rbc_array.length; i++)
		{
			if (rbc_array[i].className == "RibbonCommand RibbonDropDownCommand " + this.ID + " Selected") return true;
		}
		return false;
	};
	this.ToggleSelected = function()
	{
		this.SetSelected(!this.IsSelected());
		return false;
	};
}