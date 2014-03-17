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