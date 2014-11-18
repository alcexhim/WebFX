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
		for (var i = 0; i < tabPages.childNodes.length; i++)
		{
			if (tabPages.childNodes[i].className == "TabPage Selected") tabPages.childNodes[i].className = "TabPage";
		}
		if (selectedIndex > -1 && selectedIndex < tabPages.childNodes.length)
		{
			tabPages.childNodes[selectedIndex].className = "TabPage Selected";
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
		(function(i, tc)
		{
			tabs.childNodes[i].addEventListener("click", function(e)
			{
				tc.SetSelectedTab(tabs.childNodes[i]);
				
				e.preventDefault();
				e.stopPropagation();
				return false;
			});
		})(i, this);
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