using System;
using System.Collections.Generic;
using System.Drawing;
using System.Linq;
using System.Text;
using System.Web;

namespace SlickUI
{
	/// <summary>
	/// Summary description for GradientGenerator
	/// </summary>
	public static class GradientGenerator
	{
		public enum ColorCSSMode
		{
			Hexadecimal,
			Decimal
		}
		public static string GetColorCSS(Color color, ColorCSSMode mode = ColorCSSMode.Hexadecimal)
		{
			StringBuilder sb = new StringBuilder();
			switch (mode)
			{
				case ColorCSSMode.Hexadecimal:
				{
					sb.Append("#");
					sb.Append(color.R.ToString("X").PadLeft(2, '0'));
					sb.Append(color.G.ToString("X").PadLeft(2, '0'));
					sb.Append(color.B.ToString("X").PadLeft(2, '0'));
					break;
				}
				case ColorCSSMode.Decimal:
				{
					sb.Append("rgb(");
					sb.Append(color.R.ToString());
					sb.Append(", ");
					sb.Append(color.G.ToString());
					sb.Append(", ");
					sb.Append(color.B.ToString());
					sb.Append(")");
					break;
				}
			}
			return sb.ToString();
		}

		public static string GenerateCSS(params GradientColorStop[] colorStops)
		{
			if (colorStops.Length < 1) return String.Empty;

			string css = "background: " + GetColorCSS(colorStops[0].Color) + ";"; // Old browsers
			#region Firefox
			css += "background: -moz-linear-gradient(top, ";
			for (int i = 0; i < colorStops.Length; i++)
			{
				GradientColorStop gcs = colorStops[i];
				css += GetColorCSS(gcs.Color) + " " + (gcs.Position * 100) + "%";
				if (i < colorStops.Length - 1) css += ",";
			}
			css += ");";
			#endregion
			#region Webkit (old)
			css += "background: -webkit-gradient(linear, left top, left bottom, ";
			for (int i = 0; i < colorStops.Length; i++)
			{
				css += "color-stop(";
				GradientColorStop gcs = colorStops[i];
				css += (gcs.Position * 100) + "%";
				css += ",";
				css += GetColorCSS(gcs.Color);
				css += ")";
				if (i < colorStops.Length - 1) css += ",";
			}
			css += ");";
			#endregion
			#region Webkit (new)
			css += "background: -webkit-linear-gradient(top, ";
			for (int i = 0; i < colorStops.Length; i++)
			{
				GradientColorStop gcs = colorStops[i];
				css += GetColorCSS(gcs.Color) + " " + (gcs.Position * 100) + "%";
				if (i < colorStops.Length - 1) css += ",";
			}
			css += ");";
			#endregion
			#region Opera
			css += "background: -o-linear-gradient(top, ";
			for (int i = 0; i < colorStops.Length; i++)
			{
				GradientColorStop gcs = colorStops[i];
				css += GetColorCSS(gcs.Color) + " " + (gcs.Position * 100) + "%";
				if (i < colorStops.Length - 1) css += ",";
			}
			css += ");";
			#endregion
			#region IE modern (10+)
			css += "background: -ms-linear-gradient(top, ";
			for (int i = 0; i < colorStops.Length; i++)
			{
				GradientColorStop gcs = colorStops[i];
				css += GetColorCSS(gcs.Color) + " " + (gcs.Position * 100) + "%";
				if (i < colorStops.Length - 1) css += ",";
			}
			css += ");";
			#endregion
			#region W3C recommendation
			css += "background: linear-gradient(to bottom, ";
			for (int i = 0; i < colorStops.Length; i++)
			{
				GradientColorStop gcs = colorStops[i];
				css += GetColorCSS(gcs.Color) + " " + (gcs.Position * 100) + "%";
				if (i < colorStops.Length - 1) css += ",";
			}
			css += ");";
			#endregion
			#region IE ancients
			// Microsoft only supports begin and ending gradients
			css += "filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='" + GetColorCSS(colorStops[0].Color) + "', endColorstr='" + GetColorCSS(colorStops[colorStops.Length - 1].Color) + "',GradientType=0 );"; // IE6-9
			#endregion
			return css;
		}
	}
	public class GradientColorStop
	{
		public GradientColorStop(double position, Color color)
		{
			mvarPosition = position;
			mvarColor = color;
		}

		private double mvarPosition = 0.0;
		public double Position { get { return mvarPosition; } set { mvarPosition = value; } }

		private Color mvarColor = Color.Empty;
		public Color Color { get { return mvarColor; } set { mvarColor = value; } }
	}
}