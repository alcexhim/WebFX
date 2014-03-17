var Window = function(id)
{
	this.ID = id;
	
	this.Opened = new Callback(this);
	this.Closed = new Callback(this);
	
	this.DefaultHorizontalAlignment = HorizontalAlignment.Center;
	this.DefaultVerticalAlignment = VerticalAlignment.Middle;
	
	var TitleBar = document.getElementById("Window_" + this.ID + "_TitleBar");
	TitleBar.Parent = this;
	TitleBar.onmousedown = function(e)
	{
		Window.BeginDrag(this.Parent, e);
		e.preventDefault();
		e.stopPropagation();
		return false;
	};
	
	this.GetTitle = function()
	{
		var Title = document.getElementById("Window_" + this.ID + "_TitleBar_Title");
		return Title.innerHTML;
	};
	this.SetTitle = function(title)
	{
		var Title = document.getElementById("Window_" + this.ID + "_TitleBar_Title");
		Title.innerHTML = title;
	};
	this.SetHorizontalAlignment = function(alignment)
	{
		var Window = document.getElementById("Window_" + this.ID);
		switch(alignment)
		{
			case HorizontalAlignment.Left:
			{
				Window.style.left = "16px";
				break;
			}
			case HorizontalAlignment.Center:
			{
				Window.style.left = ((parseInt(window.GetWidth()) - parseInt(Window.clientWidth)) / 2) + "px";
				break;
			}
			case HorizontalAlignment.Right:
			{
				Window.style.left = (parseInt(window.GetWidth()) - parseInt(Window.clientWidth) - 16) + "px";
				break;
			}
		}
	};
	this.SetVerticalAlignment = function(alignment)
	{
		var Window = document.getElementById("Window_" + this.ID);
		switch(alignment)
		{
			case VerticalAlignment.Top:
			{
				Window.style.top = "16px";
				break;
			}
			case VerticalAlignment.Middle:
			{
				Window.style.top = ((parseInt(window.GetHeight()) - parseInt(Window.clientHeight)) / 2) + "px";
				break;
			}
			case VerticalAlignment.Bottom:
			{
				Window.style.top = (parseInt(window.GetHeight()) - parseInt(Window.clientHeight) - 16) + "px";
				break;
			}
		}
	};
	
	this.SetTop = function(y)
	{
		var Window = document.getElementById("Window_" + this.ID);
		Window.style.top = y + "px";
	};
	
	this.Show = function()
	{
		var Window = document.getElementById("Window_" + this.ID);
		Window.style.display = "block";
		this.Opened.Execute(CallbackArgument.Empty);
	};
	this.ShowDialog = function()
	{
		Window.DialogCount++;
		
		var WindowModalBackground = document.getElementById(Window.ModalBackgroundID);
		WindowModalBackground.style.display = "block";
		WindowModalBackground.style.zIndex = (100 + Window.DialogCount);
		
		var WindowDOMObject = document.getElementById("Window_" + this.ID);
		WindowDOMObject.style.display = "block";
		
		WindowDOMObject.style.zIndex = (100 + Window.DialogCount);
		
		this.SetHorizontalAlignment(this.DefaultHorizontalAlignment);
		this.SetVerticalAlignment(this.DefaultVerticalAlignment);
		this.Opened.Execute(CallbackArgument.Empty);
	};
	this.Hide = function()
	{
		var WindowDOMObject = document.getElementById("Window_" + this.ID);
		WindowDOMObject.style.display = "none";
		this.Closed.Execute(CallbackArgument.Empty);
		
		Window.DialogCount--;
		
		var WindowModalBackground = document.getElementById(Window.ModalBackgroundID);
		WindowModalBackground.style.zIndex = (100 + Window.DialogCount);
			
		if (Window.DialogCount == 0)
		{
			WindowModalBackground.style.display = "none";
		}
	};
};
Window.ModalBackgroundID = "smwbKageModal__33661E2DD4B44AC39AD7EA460DF79355";
Window.DialogCount = 0;
Window.BeginDrag = function(sender, e)
{
	Window.DragWindow = sender;
	Window.CursorOriginalX = e.clientX;
	Window.CursorOriginalY = e.clientY;
	
	var obj = document.getElementById("Window_" + Window.DragWindow.ID);
	Window.DragWindowOriginalX = obj.style.left.substring(0, obj.style.left.length - 2);
	Window.DragWindowOriginalY = obj.style.top.substring(0, obj.style.top.length - 2);
};
Window.ContinueDrag = function(e)
{
	if (!Window.DragWindow) return;
	
	var obj = document.getElementById("Window_" + Window.DragWindow.ID);
	
	var ix = parseInt(Window.DragWindowOriginalX), iy = parseInt(Window.DragWindowOriginalY);
	if (ix.toString() == "NaN") ix = 0;
	if (iy.toString() == "NaN") iy = 0;
	
	obj.style.left = (ix + (e.clientX - Window.CursorOriginalX)) + "px";
	obj.style.top = (iy + (e.clientY - Window.CursorOriginalY)) + "px";
};
Window.EndDrag = function()
{
	var obj = document.getElementById("Window_" + Window.DragWindow.ID);
	Window.DragWindow = null;
};

window.addEventListener("mousemove", function(e)
{
	if (Window.DragWindow)
	{
		Window.ContinueDrag(e);
		e.preventDefault();
		e.stopPropagation();
		return false;
	}
});
window.addEventListener("mouseup", function(e)
{
	if (Window.DragWindow)
	{
		Window.EndDrag();
	}
});