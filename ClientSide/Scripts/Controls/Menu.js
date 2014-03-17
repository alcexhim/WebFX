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