function TextBox(id, name, url)
{
	this.ID = id;
	this.Name = name;
	this.EnableMultipleSelection = true;
	this.SuggestionURL = url;
	this.Focus = function()
	{
		this.GetElement("textbox").focus();
	};
	this.ClearText = function()
	{
		this.GetElement("textbox").value = "";
	};
	this.GetText = function()
	{
		return this.GetElement("textbox").value;
	};
	this.SetText = function(value)
	{
		this.GetElement("textbox").value = value;
	};
	
	this.GetElement = function(section)
	{
		var id = "Textbox_" + this.ID;
		if (section) id += "_" + section;
		return document.getElementById(id);
	};
	this.GetValue = function()
	{
		return this.GetElement("textbox").value;
	};
	
	this.FormatStart = function()
	{
		return "<table class=\"ListView\">";
	};
	this.FormatItemID = function(item)
	{
		return item;
	}
	this.FormatItemText = function(item)
	{
		return item;
	}
	this.FormatItem = function(item, alternate)
	{
		var html = "<tr";
		if (alternate)
		{
			html += " class=\"Alternate\"";
		}
		html += "><td onclick=\"" + this.ID + ".AddItem('" + this.FormatItemID(item) + "');\">" + this.FormatItemText(item) + "</td></tr>";
		return html;
	};
	this.FormatEnd = function()
	{
		return "</table>";
	};
	
	this.Suggest = function(filter)
	{
		return null;
	};
	
	this.GetElement("textbox").onfocus = function(sender, e)
	{
		sender.Refresh();
		sender.DropDown.Open();
	}.PrependArgument(this);
	this.GetElement("textbox").onkeyup = function(sender, e)
	{
		if (e.keyCode == 27 /* ESC */)
		{
			sender.DropDown.Close();
		}
		else
		{
			sender.Refresh();
		}
	}.PrependArgument(this);
	
	this.SelectedItems = new Array();
	
	this.CountItems = function()
	{
		var items = this.GetElement("items");
		return items.childNodes.length - 1;
	};
	
	this.AddItem = function(item)
	{
		if (this.EnableMultipleSelection)
		{
			var i = this.CountItems();
			
			var html = this.GetElement("items").innerHTML;
			html += "<span id=\"Textbox_" + this.ID + "_items_" + i + "\" class=\"TextboxSelectedItem\">";
			html += "<span class=\"TextboxSelectedItemText\">" + this.FormatItemText(item) + "</span>";
			html += "<a class=\"TextboxSelectedItemCloseButton\" onclick=\"" + this.ID + ".RemoveItemAtIndex(" + i + ");\" href=\"#\">x</a>";
			html += "</span>";
			this.GetElement("items").innerHTML = html;
			
			this.GetElement("popup").style.display = "none";
			
			this.SelectedItems.push(item);
		}
		else
		{
			this.SelectedItems = new Array();
			this.SelectedItems.push(item);
			this.SetText(this.FormatItemText(item));
		}
	};
	this.RemoveItemAtIndex = function(index)
	{
		var items = this.GetElement("items");
		index++;
		items.removeChild(items.childNodes[index]);
	};
	
	var uxparent = this;
	this.DropDown = 
	{
		"SetInnerHTML": function(html)
		{
			var popup = uxparent.GetElement("popup");
			popup.innerHTML = html;
		},
		"Open": function()
		{
			var popup = uxparent.GetElement("popup");
			popup.style.position = "absolute";
			popup.style.width = uxparent.GetElement().offsetWidth + "px";
			popup.style.display = "block";
		},
		"Close": function()
		{
			var popup = uxparent.GetElement("popup");
			popup.style.display = "none";
		}
	};
	
	this.Refresh = function()
	{
		var ret = null;
		if (this.Suggest)
		{
			ret = this.Suggest(this.GetValue());
		}
		
		if (ret != null)
		{
			var html = "";
			html += this.FormatStart();
			for (var i = 0; i < ret.length; i++)
			{
				html += this.FormatItem(ret[i], (i % 2) != 0);
			}
			html += this.FormatEnd();
			
			this.DropDown.SetInnerHTML(html);
			this.DropDown.Open();
		}
		else if (this.SuggestionURL)
		{
			var xhr = new XMLHttpRequest();
			xhr.parentTextbox = this;
			xhr.onreadystatechange = function()
			{
				if (xhr.readyState === 4)
				{
					var html = "";
					html += xhr.parentTextbox.FormatStart();
					var obj = JSON.parse(xhr.responseText);
					if (obj.result == "success")
					{
						for (var i = 0; i < obj.content.length; i++)
						{
							html += xhr.parentTextbox.FormatItem(obj.content[i]);
						}
					}
					html += xhr.parentTextbox.FormatEnd();
					
					xhr.parentTextbox.DropDown.SetInnerHTML(html);
					xhr.parentTextbox.DropDown.Open();
				}
			};
			xhr.open('GET', this.SuggestionURL.replace(/\%1/g, this.GetValue()), true);
			xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
			xhr.send(null);  // No data need to send along with the request.
		}
		else
		{
			console.error("TextBox: no data retrieval functionality (SuggestionURL/Suggest) has been implemented");
		}
	};
}