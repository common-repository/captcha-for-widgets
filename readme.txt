=== Plugin Name ===
Contributors: 
Tags: forms, spam, reCAPTCHA, realperson plugin
Requires at least: 3.0.1
Tested up to: 3.5.2
Stable tag: 1.0
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Flexible way to inject captca for forms on site.

== Description ==

This plugin allows you to add captcha for forms in your site. You can choose between (http://www.google.com/recaptcha "reCAPTCHA") and (http://keith-wood.name/realPerson.html "Realperson jQuery plugin") (only one will be available at a time).

The captcha can be visible on: 
*   **all site's pages** - excluding the default search form and the admin bar search.
*   **certain pages** - you can choose from a pages' list
*   **certain forms** - if you know how to, you can introduce the jQuery selector for the form (e.g. `#myform`, `.myform`) 

Note: the plugin replaces DOM elements with name and/or id `submit`. If there is any CSS formatting on the id *#submit* you need to replace it.
== Installation ==

To install this plugin: 

e.g.

1. Upload the content of the `captcha-for-widgets.zip` to the `/wp-content/plugins/` directory
1. Activate the plugin through the 'Plugins' menu in WordPress

== Screenshots ==

1. The admin panel for reCAPTCHA.
2. The admin panel for Realperson.

== Changelog ==

= 1.0 =
* First version of the plugin