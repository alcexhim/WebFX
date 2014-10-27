var HorizontalAlignment =
{
	"Left": 0,
	"Center": 1,
	"Right": 2
};
var VerticalAlignment =
{
	"Top": 0,
	"Middle": 1,
	"Bottom": 2
};
function Callback(sender)
{
	this._items = [];
	this._sender = sender;
	
	this.Add = function(func)
	{
		this._items.push(func);
	};
	this.Execute = function(e)
	{
		for (var i = 0; i < this._items.length; i++)
		{
			this._items[i](this._sender, e);
		}
	};
}
function CallbackArgument()
{
}
CallbackArgument.Empty = new CallbackArgument();

var Page =
{
	"Cookies":
	{
		"Get": function(name)
		{
			var cookie = document.cookie.split(';');
			for (var i = 0; i < cookie.length; i++)
			{
				var cookie1 = cookie[i].split(';', 2);
				if (cookie1[0] == name) return cookie1[1];
			}
			return null;
		},
		"Set": function(name, value, expires)
		{
			var cookie = name + "=" + value;
			if (expires)
			{
				cookie += ";expires=" + expires;
			}
			document.cookie = cookie;
		}
	}
};

var WebFramework =
{
	"EventHandler": function()
	{
		this._functions = new Array();
		this.Add = function (func)
		{
			this._functions.push(func);
		};
		this.Execute = function()
		{
			for (var i = 0; i < this._functions.length; i++)
			{
				var retval = this._functions[i]();
				if (!retval) return false;
			}
			return true;
		};
	},
	"MouseButtons":
	{
		"Left": 0,
		"Middle": 1,
		"Right": 2
	},
	"Navigation":
	{
		/// <summary>
		/// Retrieves partial content from a URL and loads it into the specified element's innerHTML property.
		/// </summary>
		/// <param name="url">The URL to fetch.</param>
		/// <param name="targetFrame">The DOM element in which to load the data.</param>
		/// <param name="throbber">The DOM element used as the waiting indicator (optional).</param>
		/// <param name="throbberClassDefault">The CSS class for the waiting indicator (optional).</param>
		/// <param name="throbberClassHidden">The CSS class for the hidden waiting indicator (optional).</param>
		/// <param name="throbberClassVisible">The CSS class for the visible waiting indicator (optional).</param>
		"LoadPartialContent": function(url, targetFrame, async, throbber, throbberClassDefault, throbberClassHidden, throbberClassVisible)
		{
			if (typeof(async) === "undefined") async = false;
			if (!throbberClassDefault) throbberClassDefault = "";
			if (!throbberClassHidden) throbberClassHidden = "Hidden";
			if (!throbberClassVisible) throbberClassHidden = "Visible";
			
			// fetch the data from the URL, should be a same-origin URL
			var xhr = new XMLHttpRequest();
			xhr.onreadystatechange = function()
			{
				if (this.readyState == 4)
				{
					targetFrame.innerHTML = xhr.responseText;
					if (throbber)
					{
						var cssclass = "";
						if (throbberClassDefault) cssclass += throbberClassDefault + " ";
						if (throbberClassVisible) cssclass += throbberClassHidden;
						throbber.className = cssclass;
					}
				}
			};
			xhr.open('GET', url, async);
			xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
			xhr.send(null);
			
			if (throbber)
			{
				var cssclass = "";
				if (throbberClassDefault) cssclass += throbberClassDefault + " ";
				if (throbberClassVisible) cssclass += throbberClassVisible;
				throbber.className = cssclass;
			}
		}
	},
	"KeyboardKeys":
	{
		"Escape": 27,
		"F1": 112
	},
	"TerminateIfSenderIs": function(sender, compareTo)
	{
		while (sender != null)
		{
			if (sender.classList)
			{
				for (var i = 0; i < compareTo.length; i++)
				{
					if (sender.classList.contains(compareTo[i]))
					{
						// do not close the popup when we click inside itself
						// e.preventDefault();
						// e.stopPropagation();
						// alert(compareTo[i] + " = " + sender.className + " ? true ");
						return true;
					}
				}
			}
			sender = sender.parentNode;
			if (sender == null) break;
		}
		return false;
	},
	"EnterFullScreen": function(element)
	{
		if (!element) element = document.body;
		if (element.requestFullscreen)
		{
			// The HTML5 way
			element.requestFullscreen();
		}
		else if (element.webkitRequestFullscreen)
		{
			// The WebKit (safari/chrome) way
			element.webkitRequestFullscreen();
		}
		else if (element.mozRequestFullScreen)
		{
			// The Firefox way
			element.mozRequestFullScreen();
		}
		else if (element.msRequestFullscreen)
		{
			// The Internet Explorer way
			element.msRequestFullscreen();
		}
	},
	"ExitFullScreen": function()
	{
		if (document.exitFullscreen)
		{
			document.exitFullscreen();
		}
		else if (document.webkitExitFullscreen)
		{
			document.webkitExitFullscreen();
		}
		else if (document.mozCancelFullScreen)
		{
			document.mozCancelFullScreen();
		}
		else if (document.msExitFullscreen)
		{
			document.msExitFullscreen();
		}
	},
	"Events":
	{
		"MouseClick":
		{
			"Name": "click"
		},
		"MouseWheel":
		{
			//FF doesn't recognize mousewheel as of FF3.x
			"Name": ((/Firefox/i.test(navigator.userAgent)) ? "DOMMouseScroll" : "mousewheel"),
			"GetEventArgs": function(e)
			{
				var delta = e.detail ? e.detail * (-120) : e.wheelDelta;
				// delta returns +120 when wheel is scrolled up, -120 when scrolled down
				var evt =
				{
					"Cancel": false,
					"Delta": delta
				};
				return evt;
			}
		}
	},
	"GetClientProperty": function(controlName, propertyName)
	{
		return Page.Cookies.Get(controlName + "__ClientProperty_" + propertyName);
	},
	"SetClientProperty": function(controlName, propertyName, propertyValue)
	{
		Page.Cookies.Set(controlName + "__ClientProperty_" + propertyName, propertyValue);
	},
	"AddEventListener": function(parent, eventTypeOrName, callback)
	{
		function CustomCallback(evt)
		{
			if (typeof eventTypeOrName.GetEventArgs !== 'undefined')
			{
				var eas = eventTypeOrName.GetEventArgs(evt);
				eas.Cancel = false;
				callback(eas);
				if (eas.Cancel)
				{
					evt.preventDefault();
					evt.stopPropagation();
					return false;
				}
			}
			else
			{
				var eas = evt;
				eas.Cancel = false;
				callback(eas);
				if (eas.Cancel)
				{
					evt.preventDefault();
					evt.stopPropagation();
					return false;
				}
			}
			return true;
		}

		if (typeof eventTypeOrName !== "object")
		{
			if (parent.attachEvent)
			{
				//if IE (and Opera depending on user setting)
				parent.attachEvent("on" + eventTypeOrName, callback);
			}
			else if (parent.addEventListener) //WC3 browsers
			{
				parent.addEventListener(eventTypeOrName, callback, false);
			}
		}
		else
		{
			if (parent.attachEvent)
			{
				//if IE (and Opera depending on user setting)
				parent.attachEvent("on" + eventTypeOrName.Name, CustomCallback);
			}
			else if (parent.addEventListener) //WC3 browsers
			{
				parent.addEventListener(eventTypeOrName.Name, CustomCallback, false);
			}
		}
	}
};
var WebPage =
{
	"Postback": function(url)
	{
		var WebPageForm = document.getElementById("WebPageForm");
		if (url)
		{
			// Set the action of the WebPageForm to the specified PostBackURL before submitting
			WebPageForm.action = url;
		}
		if (!WebPageForm)
		{
			console.warn("WebPage.Postback: could not find WebPageForm, postbacks are not enabled");
			return;
		}
		WebPageForm.submit();
	},
	"IsVariableDefined": function(name)
	{
		var txtWebPageVariable = document.getElementById("WebPageVariable_" + name + "_Value");
		if (!txtWebPageVariable) return false;
		return true;
	},
	"IsVariableSet": function(name)
	{
		var txtWebPageVariable_IsSet = document.getElementById("WebPageVariable_" + name + "_IsSet");
		if (!txtWebPageVariable_IsSet)
		{
			console.warn("WebPage.IsVariableSet: undefined variable '" + name + "'");
			return false;
		}
		return true;
	},
	"ClearVariableValue": function(name, value)
	{
		var txtWebPageVariable = document.getElementById("WebPageVariable_" + name + "_Value");
		var txtWebPageVariable_IsSet = document.getElementById("WebPageVariable_" + name + "_IsSet");
		if (!txtWebPageVariable || !txtWebPageVariable_IsSet)
		{
			console.error("WebPage.ClearVariableValue: undefined variable '" + name + "'");
			return false;
		}
		txtWebPageVariable_IsSet.value = "false";
		txtWebPageVariable.value = "";
		
		WebPage.Postback();
		return true;
	},
	"GetVariableValue": function(name)
	{
		var txtWebPageVariable = document.getElementById("WebPageVariable_" + name + "_Value");
		if (!txtWebPageVariable)
		{
			console.error("WebPage.GetVariableValue: undefined variable '" + name + "'");
			return null;
		}
		return txtWebPageVariable.value;
	},
	"SetVariableValue": function(name, value, autoPostback)
	{
		var txtWebPageVariable = document.getElementById("WebPageVariable_" + name + "_Value");
		var txtWebPageVariable_IsSet = document.getElementById("WebPageVariable_" + name + "_IsSet");
		if (!txtWebPageVariable || !txtWebPageVariable_IsSet)
		{
			console.error("WebPage.GetVariableValue: undefined variable '" + name + "'");
			return false;
		}
		txtWebPageVariable_IsSet.value = "true";
		txtWebPageVariable.value = value;
		
		if (autoPostback !== false)
		{
			WebPage.Postback();
		}
		return true;
	}
};

/*
   Provide the XMLHttpRequest constructor for Internet Explorer 5.x-6.x:
   Other browsers (including Internet Explorer 7.x-9.x) do not redefine
   XMLHttpRequest if it already exists.
 
   This example is based on findings at:
   http://blogs.msdn.com/xmlteam/archive/2006/10/23/using-the-right-version-of-msxml-in-internet-explorer.aspx
*/
if (typeof XMLHttpRequest === "undefined")
{
	XMLHttpRequest = function ()
	{
		try
		{
			return new ActiveXObject("Msxml2.XMLHTTP.6.0");
		}
		catch (e) {}
		try
		{
			return new ActiveXObject("Msxml2.XMLHTTP.3.0");
		}
		catch (e) {}
		try
		{
			return new ActiveXObject("Microsoft.XMLHTTP");
		}
		catch (e) {}
		console.log("This browser does not support XMLHttpRequest.");
	};
}