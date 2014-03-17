function ToolTip(id)
{
	this.ID = id;
	
	var ToolTip = document.getElementById("ToolTip_" + id);
	var ToolTip_Activator = document.getElementById("ToolTip_" + id + "_Activator");
	
	ToolTip_Activator.Parent = this;
	ToolTip_Activator.onmouseover = function(e)
	{
		this.Parent.Show(e.clientX + 32, e.clientY + 32, 500);
	};
	ToolTip_Activator.onmouseout = function(e)
	{
		this.Parent.Hide();
	};
	
	ToolTip.Parent = this;
	ToolTip.onmouseout = function(e)
	{
		this.Parent.Hide();
	};
	
	this.Show = function(x, y, delay)
	{
		if (delay)
		{
			this.HTimer = window.setTimeout(function(parent, x, y)
			{
				parent.Show();
			}, delay, this);
		}
		else
		{
			var ToolTip = document.getElementById("ToolTip_" + this.ID);
			if (!x) x = MousePosition.X + 32;
			if (!y) y = MousePosition.Y + 32;
			
			ToolTip.style.left = x + "px";
			ToolTip.style.top = y + "px";
			ToolTip.style.display = "block";
		}
	};
	this.Hide = function()
	{
		if (this.HTimer) window.clearTimeout(this.HTimer);
		
		var ToolTip = document.getElementById("ToolTip_" + this.ID);
		ToolTip.style.display = "none";
	};
}