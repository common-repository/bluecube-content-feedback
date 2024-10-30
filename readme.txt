=== Blue Cube Content Feedback ===
Contributors: thebluecube
Donate link:
Tags: feedback, widget, content quality, user experience
Requires at least: 4.5
Tested up to: 4.5
Stable tag: 1.0
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

This plugin provides a simple content feedback system.

== Description ==

Sometimes you need to know whether users of your website find the content useful or not. This plugin provides an easy YES / NO  feedback system for your website content. Users would only need to click on a 'Yes' or 'No' button, simple as that!

To disable the widget on any specific pages, you can use the 'bc_show_content_feedback_widget' filter like this:

`add_filter('bc_show_content_feedback_widget', 'turn_off_content_feedback_widget');
function turn_off_content_feedback_widget($show_widget) {
	global $post;
	if ($post->ID == 198) {
		$show_widget = false;
	}
	return $show_widget;
}`


== Installation ==

Download and unzip the plugin. Then upload the `"bluecube-content-feedback"` folder to the `/wp-content/plugins/` directory (or the relevant directory if your WordPress file structure is different), or you can simply install it using WordPress plugin installer.


== Frequently asked questions ==


== Screenshots ==


== Changelog ==

= 1.0 =
* This is the first version of the plugin, providing a very simple content feedback system

== Upgrade notice ==
