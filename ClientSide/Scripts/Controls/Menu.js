function ContextMenu()
{
	this.Items = [];
	this.ParentElement = null;
	this.Show = function(x, y, parent)
	{
		if (this.ParentElement == null)
		{
			var elem = document.createElement("div");
			elem.className = "Menu Popup";
			
			for (var i = 0; i < this.Items.length; i++)
			{
				var elem1 = document.createElement("a");
				elem1.setAttribute("href", "#");
				elem1.addEventListener("click", function(e)
				{
					this.NativeObject.Hide();
					this.MenuItem.Execute();
					
					e.preventDefault();
					e.stopPropagation();
					return false;
				});
				elem1.addEventListener("contextmenu", function(e)
				{
					e.preventDefault();
					e.stopPropagation();
					return false;
				});
				elem1.innerHTML = this.Items[i].Title;
				elem1.NativeObject = this;
				elem1.MenuItem = this.Items[i];
				
				elem.appendChild(elem1);
			}
			
			elem.style.left = x + "px";
			elem.style.top = y + "px";
			
			if (parent == null) parent = document.body;
			
			parent.appendChild(elem);
			this.ParentElement = elem;
		}
		this.ParentElement.className = "Menu Popup Visible";
	};
	this.Hide = function()
	{
		if (this.ParentElement == null) return;
		this.ParentElement.className = "Menu Popup";
	};
}
function MenuItemCommand(id, title, onclick)
{
	this.ID = id;
	this.Title = title;
	this.OnClientClick = onclick;
	
	this.Execute = function()
	{
		if (this.OnClientClick != null) this.OnClientClick();
	};
}
function Menu(id)
{
	this.ID = id;
	this.Show = function()
	{
		var obj = document.getElementById("Menu_" + this.ID);
		obj.style.display = "block";
	};
	this.Hide = function()
	{
		var obj = document.getElementById("Menu_" + this.ID);
		obj.style.display = "none";
	};
	
	this.Toggle = function()
	{
		var obj = document.getElementById("Menu_" + this.ID);
		if (obj.style.display == "none")
		{
			obj.style.display = "block";
		}
		else
		{
			obj.style.display = "none";
		}
	};
}