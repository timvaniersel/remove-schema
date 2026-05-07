=== Remove Schema ===
Contributors: timvaniersel
Donate link: https://plugin.nl/en/remove-schema-plugin/
Tags: schema, schema markup, structured data
Requires at least: 5.0.0
Tested up to: 6.9
Stable tag: 1.5.0
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
Requires PHP: 5.9.4

Remove all Schema Markup / Structured data (Microdata, RDFa and/or JSON-ld) that you don’t want on your site.

== Description ==

Remove Schema optionally removes all schema markup from your website.

You have the option to remove:

* All JSON-ld
* All Microdata
* All RDFa

And remove plugin/theme specific markup:

* WooCommerce
* WooCommerce emails
* Yoast SEO
* Schema Pro
* GeneratePress themes


== Installation ==

You can install Remove Schema at the moment only by downloading it from GitHub and uploading it to your WordPress site:

1. Upload `remove-schema` directory to the `/wp-content/plugins/` directory.
2. Activate the plugin through the 'Plugins' menu in WordPress.
3. Go to settings and check the boxes of the schema markup that you want removed.

== Frequently Asked Questions ==

= Can you make add support for a plugin =

Yes we can. If we have missed a plugin please create an issue on [Github](https://github.com/timvaniersel/remove-schema "Remove Schema Github").

== Screenshots ==

1. Admin interface
2. Page editor

== Changelog ==

= 1.6.2 =
* PHP 8.2 support

= 1.6.1 =
* Improved security

= 1.4.0 =
* Add Yoast SEO Premium support

= 1.3.4 =
* Add multisite support

= 1.3.3 =
* Remove review notice


= 1.3.2 =
* Bugfixes - page specific markup wouldn't save if everything was unchecked.

= 1.3 =
* Improved security
* Add page specific support on all page types

= 1.2 =
* Add support for GeneratePress themes
* Add support for removing hentry classes

= 1.1 =
* Code cleanup

= 1.0 =
* Inital release
