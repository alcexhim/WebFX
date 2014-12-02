var Window = function(parentElement)
{
	this.ParentElement = parentElement;
	
	this.Opened = new Callback(this);
	this.Closed = new Callback(this);
	
	this.DefaultHorizontalAlignment = HorizontalAlignment.Center;
	this.DefaultVerticalAlignment = VerticalAlignment.Middle;
	
	var TitleBar = parentElement.childNodes[0];
	TitleBar.Parent = this;
	TitleBar.addEventListener("mousedown", function(e)
	{
		Window.BeginDrag(this.Parent, e);
		e.preventDefault();
		e.stopPropagation();
		return false;
	});
	
	this.GetWidth = function()
	{
		return this.ParentElement.clientWidth;
	};
	this.GetHeight = function()
	{
		return this.ParentElement.clientHeight;
	};
	
	this.GetTitle = function()
	{
		return this.ParentElement.childNodes[0].childNodes[0].innerHTML;
	};
	this.SetTitle = function(title)
	{
		this.ParentElement.childNodes[0].childNodes[0].innerHTML = title;
	};
	this.SetHorizontalAlignment = function(alignment)
	{
		var Window = this.ParentElement;
		switch(alignment)
		{
			case HorizontalAlignment.Left:
			{
				Window.style.left = "16px";
				break;
			}
			case HorizontalAlignment.Center:
			{
				Window.style.left = ((parseInt(this.GetWidth()) - parseInt(Window.clientWidth)) / 2) + "px";
				break;
			}
			case HorizontalAlignment.Right:
			{
				Window.style.left = (parseInt(this.GetWidth()) - parseInt(Window.clientWidth) - 16) + "px";
				break;
			}
		}
	};
	this.SetVerticalAlignment = function(alignment)
	{
		var Window = this.ParentElement;
		switch(alignment)
		{
			case VerticalAlignment.Top:
			{
				Window.style.top = "16px";
				break;
			}
			case VerticalAlignment.Middle:
			{
				Window.style.top = ((parseInt(this.GetHeight()) - parseInt(Window.clientHeight)) / 2) + "px";
				break;
			}
			case VerticalAlignment.Bottom:
			{
				Window.style.top = (parseInt(this.GetHeight()) - parseInt(Window.clientHeight) - 16) + "px";
				break;
			}
		}
	};
	
	this.SetTop = function(y)
	{
		var Window = this.ParentElement;
		Window.style.top = y + "px";
	};
	
	this.Show = function()
	{
		var Window = this.ParentElement;
		Window.style.display = "block";
		this.Opened.Execute(CallbackArgument.Empty);
	};
	this.ShowDialog = function()
	{
		Window.DialogCount++;
		
		if (Window.ModalBackgroundElement == null)
		{
			Window.ModalBackgroundElement = document.createElement("div");
			Window.ModalBackgroundElement.className = "WindowModalBackground";
			document.body.appendChild(Window.ModalBackgroundElement);
		}
		Window.ModalBackgroundElement.style.display = "block";
		Window.ModalBackgroundElement.style.zIndex = (100 + Window.DialogCount);
		
		var WindowDOMObject = this.ParentElement;
		WindowDOMObject.className = "Window Visible";
		
		WindowDOMObject.style.zIndex = (100 + Window.DialogCount + 1);
		
		this.SetHorizontalAlignment(this.DefaultHorizontalAlignment);
		this.SetVerticalAlignment(this.DefaultVerticalAlignment);
		this.Opened.Execute(CallbackArgument.Empty);
	};
	this.Hide = function()
	{
		var WindowDOMObject = this.ParentElement;
		WindowDOMObject.className = "Window";
		this.Closed.Execute(CallbackArgument.Empty);
		
		Window.DialogCount--;
		
		if (Window.ModalBackgroundElement != null)
		{
			Window.ModalBackgroundElement.style.zIndex = (100 + Window.DialogCount);
		}
			
		if (Window.DialogCount == 0)
		{
			if (Window.ModalBackgroundElement != null)
			{
				Window.ModalBackgroundElement.parentNode.removeChild(Window.ModalBackgroundElement);
				Window.ModalBackgroundElement = null;
			}
		}
	};
	
	if (this.ParentElement.id != "") eval("window." + this.ParentElement.id + " = this;");
};
Window.ModalBackgroundElement = null;
Window.DialogCount = 0;
Window.BeginDrag = function(sender, e)
{
	Window.DragWindow = sender;
	Window.CursorOriginalX = e.clientX;
	Window.CursorOriginalY = e.clientY;
	
	var obj = Window.DragWindow.ParentElement;
	Window.DragWindowOriginalX = obj.style.left.substring(0, obj.style.left.length - 2);
	Window.DragWindowOriginalY = obj.style.top.substring(0, obj.style.top.length - 2);
};
Window.ContinueDrag = function(e)
{
	if (!Window.DragWindow) return;
	
	var obj = Window.DragWindow.ParentElement;
	
	var ix = parseInt(Window.DragWindowOriginalX), iy = parseInt(Window.DragWindowOriginalY);
	if (ix.toString() == "NaN") ix = 0;
	if (iy.toString() == "NaN") iy = 0;
	
	obj.style.left = (ix + (e.clientX - Window.CursorOriginalX)) + "px";
	obj.style.top = (iy + (e.clientY - Window.CursorOriginalY)) + "px";
};
Window.EndDrag = function()
{
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
window.addEventListener("load", function(e)
{
	var items = document.getElementsByClassName("Window");
	for (var i = 0; i < items.length; i++)
	{
		items[i].NativeObject = new Window(items[i]);
	}
});