function Ribbon(id, parent)
{
	var ribbon = this;
	this.ID = id;
	this.ParentElement = parent;

	var applicationButton = parent.getElementsByClassName("ApplicationButton")[0];
	var applicationMenu = document.getElementById("Ribbon_" + this.ID + "_ApplicationMenu");

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
				tabs[i].style.display = "block";
			}
			else
			{
				ribbonTabs[i].className = "RibbonTab";
				tabs[i].style.display = "none";
			}
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

	applicationButton.addEventListener("click", function (e)
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
			ribbon.ActivateTab(ribbon.GetTabByName(this.attributes["data-tab-id"].value));
			e.preventDefault();
			e.stopPropagation();
			return false;
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

				if (tt.tooltipTimer != null) window.clearTimeout(tt.tooltipTimer);
				tt.tooltipTimer = window.setTimeout(function(tt)
				{
					var x = tt.mouseX;
					var y = tt.mouseY;

					var tooltip = document.getElementById("Tooltip");

					var tooltipTitle = (tt.attributes["data-tooltip-title"] != null ? tt.attributes["data-tooltip-title"].value : "");
					var tooltipContent = (tt.attributes["data-tooltip-content"] != null ? tt.attributes["data-tooltip-content"].value : "");
				
					if (tooltipTitle == "" || tooltipContent == "") return;

					var tooltipTitleElement = document.getElementById("Tooltip_Title");
					tooltipTitleElement.innerHTML = tooltipTitle;
					var tooltipContentElement = document.getElementById("Tooltip_Content");
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
				if (tt.tooltipTimer != null) window.clearTimeout(tt.tooltipTimer);
			};
		})(tooltips[i]);
	}
});
window.addEventListener("keydown", function (e)
{
	if (e.keyCode == 27)
	{
		// ESCAPE was pressed; hide all application menus
		for (var i = 0; i < Ribbons.length; i++)
		{
			Ribbons[i].SetApplicationMenuVisible(false);
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

	if (TerminateIfSenderIs(sender, ["ApplicationMenu"])) return false;
	// ESCAPE was pressed; hide all application menus
	for (var i = 0; i < Ribbons.length; i++)
	{
		Ribbons[i].SetApplicationMenuVisible(false);
	}

	if (TerminateIfSenderIs(sender, ["Ribbon"])) return false;
	for (var i = 0; i < Ribbons.length; i++)
	{
		if (Ribbons[i].IsCollapsed())
		{
			Ribbons[i].SetOpened(false);
		}
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
					rbc_array[i].className = "RibbonButtonCommand " + this.ID + " Selected";
				}
				break;
			}
			case false:
			{
				var rbc_array = document.getElementsByClassName(this.ID);
				for (var i = 0; i < rbc_array.length; i++)
				{
					rbc_array[i].className = "RibbonButtonCommand " + this.ID;
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
			if (rbc_array[i].className == "RibbonButtonCommand " + this.ID + " Selected") return true;
		}
		return false;
	};
}