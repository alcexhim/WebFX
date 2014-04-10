function TabContainer(id)
{
	this.ID = id;
	this.SetSelectedTab = function(tabName)
	{
		var tab = document.getElementById("TabContainer_" + this.ID + "_Tabs_" + tabName + "_Tab");
		var tabPage = document.getElementById("TabContainer_" + this.ID + "_TabPages_" + tabName + "_TabPage");
		
		var tabs = tab.parentNode.childNodes;
		for (var i = 0; i < tabs.length; i++)
		{
			if (tabs[i].className == "Tab Selected") tabs[i].className = "Tab";
		}
		
		var tabPages = tabPage.parentNode.childNodes;
		for (var i = 0; i < tabPages.length; i++)
		{
			if (tabPages[i].className == "TabPage Selected") tabPages[i].className = "TabPage";
		}
		
		tab.className = "Tab Selected";
		tabPage.className = "TabPage Selected";
	};
}