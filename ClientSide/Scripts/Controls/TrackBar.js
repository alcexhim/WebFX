function TrackBarOrientation(value)
{
	this._value = value;
}
/**
 * The TrackBar is displayed horizontally.
 */
TrackBarOrientation.Horizontal = new TrackBarOrientation(1);
/**
 * The TrackBar is displayed vertically.
 */
TrackBarOrientation.Vertical = new TrackBarOrientation(2);

function TrackBar(parentElement)
{
	this.ParentElement = parentElement;
	this.TrackElement = this.ParentElement.childNodes[0];
	this.QuantityElement = this.TrackElement.childNodes[0];
	this.ThumbElement = this.TrackElement.childNodes[1];
	this.ThumbTextElement = this.ThumbElement.childNodes[0];
	
	this.get_Orientation = function()
	{
		if (WebFramework.ClassList.Contains(this.ParentElement, "Vertical"))
		{
			return TrackBarOrientation.Vertical;
		}
		else
		{
			return TrackBarOrientation.Horizontal;
		}
	};
	this.set_Orientation = function(value)
	{
		switch (value)
		{
			case TrackBarOrientation.Horizontal:
			{
				WebFramework.ClassList.Remove(this.ParentElement, "Vertical");
				WebFramework.ClassList.Add(this.ParentElement, "Horizontal");
				break;
			}
			case TrackBarOrientation.Vertical:
			{
				WebFramework.ClassList.Remove(this.ParentElement, "Horizontal");
				WebFramework.ClassList.Add(this.ParentElement, "Vertical");
				break;
			}
			default:
			{
				console.log("Invalid value '" + value + "' for property 'Orientation'");
				break;
			}
		}
	};
	
	this.get_MinimumValue = function()
	{
		return this.ParentElement.getAttribute("data-minimum-value");
	};
	this.set_MinimumValue = function(value)
	{
		this.ParentElement.setAttribute("data-minimum-value", value);
	};
	this.get_MaximumValue = function()
	{
		return this.ParentElement.getAttribute("data-maximum-value");
	};
	this.set_MaximumValue = function(value)
	{
		this.ParentElement.setAttribute("data-maximum-value", value);
	};
	this.get_CurrentValue = function()
	{
		return parseInt(this.ParentElement.getAttribute("data-current-value"));
	};
	this.set_CurrentValue = function(value)
	{
		value = parseInt(value);
		this.ParentElement.setAttribute("data-current-value", value);
		
		var decimalPos = ((value - this.get_MinimumValue()) / (this.get_MaximumValue() - this.get_MinimumValue()));
		var intPos = decimalPos * 100;
		var percentPos = intPos + "%";
		
		if (this.get_Orientation() == TrackBarOrientation.Vertical)
		{
			intPos = 100 - intPos;
			this.ThumbElement.style.top = intPos + "%";
			this.QuantityElement.style.top = intPos + "%";
		}
		else
		{
			this.ThumbElement.style.left = percentPos;
			this.QuantityElement.style.width = percentPos;
		}
		
		this.ParentElement.setAttribute("data-tooltip-content", this.ParentElement.getAttribute("data-current-value"));
		this.ThumbTextElement.innerHTML = value;
		return value;
	};
	
	this.ParentElement.addEventListener("contextmenu", function(e)
	{
		
		
		e.preventDefault();
		e.stopPropagation();
		return false;
	});
	
	this.TrackElement.NativeObject = this;
	this.TrackElement.addEventListener("mousedown", function(ee)
	{
		var e = MouseEventArgs.FromNativeEventArgs(ee);
		if (e.Button == MouseButtons.Primary)
		{
			var elementSize = this.NativeObject.ParentElement.clientWidth;
			var currentPos = e.X - this.NativeObject.TrackElement.offsetLeft;
			if (this.NativeObject.get_Orientation() == TrackBarOrientation.Vertical)
			{
				elementSize = this.NativeObject.ParentElement.clientHeight;
				currentPos = e.Y - this.NativeObject.TrackElement.offsetTop;
			}
			
			var decimalPos = (currentPos / elementSize);
			if (decimalPos < 0) decimalPos = 0;
			if (decimalPos > 1) decimalPos = 1;
			
			var intPos = decimalPos * 100;
			var percentPos = (intPos + "%");
			
			if (this.NativeObject.get_Orientation() == TrackBarOrientation.Vertical)
			{
				decimalPos = 1 - decimalPos;
			}
			
			this.NativeObject.set_CurrentValue(parseInt(this.NativeObject.get_MinimumValue()) + (decimalPos * (this.NativeObject.get_MaximumValue() - this.NativeObject.get_MinimumValue())));
		}
	});
	
	this.BeginDrag = function(e)
	{
		TrackBar._draggingObject = this;
	};
	this.ContinueDrag = function(e)
	{
		var elementSize = this.ParentElement.clientWidth;
		var currentPos = e.X - this.ParentElement.offsetLeft;
		if (this.get_Orientation() == TrackBarOrientation.Vertical)
		{
			elementSize = this.ParentElement.clientHeight;
			currentPos = e.Y - this.ParentElement.offsetTop;
		}
		
		var decimalPos = (currentPos / elementSize);
		if (decimalPos < 0) decimalPos = 0;
		if (decimalPos > 1) decimalPos = 1;
		
		var intPos = decimalPos * 100;
		var percentPos = (intPos + "%");

		if (this.get_Orientation() == TrackBarOrientation.Vertical)
		{
			decimalPos = 1 - decimalPos;
		}
		
		this.set_CurrentValue(parseInt(this.get_MinimumValue()) + (decimalPos * (this.get_MaximumValue() - this.get_MinimumValue())));
	};
	this.EndDrag = function()
	{
		TrackBar._draggingObject = null;
	};
	
	this.ThumbElement.NativeObject = this;
	this.ThumbElement.addEventListener("mousedown", function(ee)
	{
		var e = MouseEventArgs.FromNativeEventArgs(ee);
		if (e.Button == MouseButtons.Primary)
		{
			this.NativeObject.BeginDrag(e);
		}
		ee.preventDefault();
		ee.stopPropagation();
		return false;
	});
	window.addEventListener("mousemove", function(e)
	{
		if (TrackBar._draggingObject != null)
		{
			TrackBar._draggingObject.ContinueDrag(MouseEventArgs.FromNativeEventArgs(e));
		}
	});
	window.addEventListener("mouseup", function(e)
	{
		if (TrackBar._draggingObject != null)
		{
			TrackBar._draggingObject.EndDrag();
		}
	});
	
	this.ParentElement.setAttribute("data-tooltip-content", this.ParentElement.getAttribute("data-current-value"));
}

/**
 * The currently-dragging TrackBar object.
 * @var TrackBar
 */
TrackBar._draggingObject = null;

window.addEventListener("load", function(e)
{
	var items = document.getElementsByClassName("TrackBar");
	for (var i = 0; i < items.length; i++)
	{
		items[i].NativeObject = new TrackBar(items[i]);
	}
});