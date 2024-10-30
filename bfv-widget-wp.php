<?php
/**
 *     ---------------       DO NOT DELETE!!!     ---------------
 * 
 *     Plugin Name:  bfv Widget WP
 *     Plugin URI:   http://www.streys.net
 *     Description:  plugin to include the bfv Widget in post or pages within your Worrdpress Blog
 *     Version:      1.0
 *     Author:       Georg Strey (georg.strey@googlemail.com)
 *     Author URI:   http://www.streys.net
 *
 *     ---------------       DO NOT DELETE!!!     ---------------
 *
 *    This is the required license information for a Wordpress plugin.
 *
 *    Copyright 2007  Keith Huster  (email : husterk@doubleblackdesign.com)
 *
 *    This program is free software; you can redistribute it and/or modify
 *    it under the terms of the GNU General Public License as published by
 *    the Free Software Foundation; either version 2 of the License, or
 *    (at your option) any later version.
 *
 *    This program is distributed in the hope that it will be useful,
 *    but WITHOUT ANY WARRANTY; without even the implied warranty of
 *    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *    GNU General Public License for more details.
 *
 *    You should have received a copy of the GNU General Public License
 *    along with this program; if not, write to the Free Software
 *    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
 *
 *     ---------------       DO NOT DELETE!!!     ---------------
 */
 
if (function_exists('add_shortcode')) {
	add_shortcode('bfv-widgets', 'bfv_widgets_shortcode_replacement');
	}
	
add_action('wp_print_styles', 'add_bfv_widget_styles');
    
    
// <<=== adding stylesheet if needed 

//================================================================================================
//		define output constansts. See bfv howto
// - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
//	BVF_SCRIPT		the javascript, that has to be in the site body, before the widget is showing
//	BFV_DIV			html div where the widget is placed in
//	BFV_LOAD_PRE	script loading the widget part 1 
//	BFV_LOAD_POST	script loading the widget part 2 
//================================================================================================
define(BFV_SCRIPT, "<p><script src=\"http://ergebnisse.bfv.de/javascript/widgets/tmwrWidgetFunctions.js\" type=\"text/javascript\">\n</script></p>\n");
define(BFV_DIV, "<div id=\"bfv_widget_ligaAnzeige\">bfv Liga Widget</div>\n");
define(BFV_LOAD_PRE, "<p><script type=\"text/javascript\">// <![CDATA[\n var liga = new BFVLigaWidget(); \n");
define(BFV_LOAD_POST, "// ]]></script></p>\n");
//================================================================================================
//		shortcode hook function
// - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
//	sms_liga_nr	=> league id to show
//	highlight	=> no of club to highlight
//	show		=> one of 'Tabelle', 'Ergebnisse' (default), 'Fairplay', 'Vorschau'
//================================================================================================
function bfv_widgets_shortcode_replacement($atts)
{
	// setting default for show instruction
	$ShowInstruction = 'Ergebnisse';
	
	$Replacement = BFV_SCRIPT . BFV_DIV . BFV_LOAD_PRE;
	
	foreach ($atts as $key => $value) {		
		switch (strtolower($key)) {
			case 'sms_liga_nr' :
				$Replacement .= "liga.setzeLigaNr('$value'); \n";
				break;
			case 'highlight' :
				$Replacement .= "liga.setzeVereinNr('$value'); \n";
				break;
			case 'show' :
				$ShowInstruction = ucfirst(strtolower($value));
				break;
		}
	}
	
	$Replacement .= "liga.zeige$ShowInstruction('bfv_widget_ligaAnzeige');\n";
	$Replacement .= BFV_LOAD_POST;

	return $Replacement;
} // end function bfv_widgets_shortcode_replacement

//================================================================================================
//		add_bfv_widget_styles
// - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
//	resets some css selectors
//================================================================================================
function add_bfv_widget_styles()
{
	wp_enqueue_style('bfv_widget', '/wp-content/plugins/' . plugin_basename(dirname(__FILE__)) . '/bfv-widget-wp.css', false , false, 'screen' );
} // end function add_bfv_widget_styles