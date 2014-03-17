var ListViewMode =
{
	"Detail": 1,
	"Tile": 2
};

function ListView(id)
{
	var table = document.getElementById("ListView_" + id);
	if (table == null || table.tagName != "TABLE") return;
	
	for (var j = 0; j < table.rows.length; j++)
	{
		table.rows[j].onclick = function()
		{
			ListView_Row_SetSelected(this);
		};
	}
}
function ListView_Row_SetSelected(row)
{
	if (row.className == "Filter") return;
	
	var childNodes = row.parentNode.childNodes;
	var alternate = (row.className == "Alternate" || row.className == "Selected Alternate");
	
	for (var i = 0; i < childNodes.length; i++)
	{
		if (childNodes[i].className == "Filter") continue;
		
		if (childNodes[i].className == "Selected Alternate")
		{
			childNodes[i].className = "Alternate";
		}
		else if (childNodes[i].className == "Selected")
		{
			childNodes[i].className = "";
		}
		childNodes[i].selected = false;
	}
	if (alternate)
	{
		row.className = "Selected Alternate";
	}
	else
	{
		row.className = "Selected";
	}
	row.selected = true;
}
