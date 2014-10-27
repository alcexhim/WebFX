function TabContainer(parentElement)
{
	this.ParentElement = parentElement;
	this.SetSelectedTab = function(tab)
	{
		var tabContainer = this.ParentElement;
		var tabs = tabContainer.childNodes[0];
		var tabPages = tabContainer.childNodes[1];
		var selectedIndex = -1;
		for (var i = 0; i < tabs.childNodes.length; i++)
		{
			if (tabs.childNodes[i].className == "Tab Visible Selected") tabs.childNodes[i].className = "Tab Visible";
			if (tabs.childNodes[i] === tab)
			{
				selectedIndex = i;
				tabs.childNodes[i].className = "Tab Visible Selected";
			}
		}
		for (var i = 0; i < tabPages.length; i++)
		{
			if (tabPages[i].className == "TabPage Selected") tabPages[i].className = "TabPage";
		}
		if (selectedIndex > -1 && selectedIndex < tabPages.length)
		{
			tabPages[selectedIndex].className = "TabPage Selected";
		}
		
		WebFramework.SetClientProperty(this.ID, "SelectedTabIndex", selectedIndex);
		
		if (tabContainer != null)
		{
			var attOnClientTabChanged = tabContainer.attributes["data-onclienttabchanged"];
			if (attOnClientTabChanged != null)
			{
				eval(attOnClientTabChanged.value);
			}
		}
	};
	
	var tabContainer = this.ParentElement;
	var tabs = tabContainer.childNodes[0];
	for (var i = 0; i < tabs.childNodes.length; i++)
	{
		(function(i)
		{
			tabs.childNodes[i].addEventListener("click", function(e)
			{
				tabContainer.SetSelectedTab(tabs.childNodes[i]);
			});
		})(i);
	}
}
window.addEventListener("load", function(e)
{
	var tbss = document.getElementsByClassName("TabContainer");
	for (var i = 0; i < tbss.length; i++)
	{
		tbss[i].ObjectReference = new TabContainer(tbss[i]);
	}
});