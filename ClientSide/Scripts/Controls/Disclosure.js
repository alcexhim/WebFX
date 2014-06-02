function Disclosure(id)
{
	this.ID = id;
	this.mvarExpanded = false;
	this.GetExpanded = function()
	{
		return this.mvarExpanded;
	};
	this.SetExpanded = function(value)
	{
		this.mvarExpanded = value;
		this.Refresh();
	};
	this.ToggleExpanded = function()
	{
		this.SetExpanded(!this.GetExpanded());
	};
	
	this.Refresh = function()
	{
		var disclosure = document.getElementById("Disclosure_" + this.ID);
		if (this.GetExpanded())
		{
			disclosure.className = "Disclosure Expanded";
		}
		else
		{
			disclosure.className = "Disclosure";
		}
	};
}