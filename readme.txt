=== Easy Digital Downloads: Software Licensing: Custom Expiration ===
Contributors: n7studios,wpcube
Donate link: http://www.wpcube.co.uk/plugins/edd-software-licensing-custom-expiration
Tags: edd,easy digital downloads,software licensing,licensing,expiration,custom expiration
Requires at least: 3.8
Tested up to: 4.3
Stable tag: trunk
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Define a different license expiration for downloads assigned to a specific Bundle. Requires the EDD Software Licensing Addon

== Description ==

By default, any EDD Download Bundle containing multiple EDD Downloads will generate a single license key based on the
expiration set on the individual EDD Downloads.  However, if you want to offer a different expiration on this single
license key, Easy Digital Downloads Software Licensing addon does not provide a way to do this.

This plugin adds an option to your Bundled Downloads in the WordPress Administration, allowing you to optionally
specify a different expiration period.

When a customer then purchases your Bundle, the download(s) in that Bundle will have license keys with your custom
expiry date.

This does not effect customers when purchasing a single download; they will still get a license key which expires per
the Easy Digital Downloads: Software Licensing addon.

= Support =

We will do our best to provide support through the WordPress forums. However, please understand that this is a free plugin, 
so support will be limited. Please read this article on <a href="http://www.wpbeginner.com/beginners-guide/how-to-properly-ask-for-wordpress-support-and-get-it/">how to properly ask for WordPress support and get it</a>.

= WP Cube =
We produce free and premium WordPress Plugins that supercharge your site, by increasing user engagement, boost site visitor numbers
and keep your WordPress web sites secure.

Find out more about us at <a href="http://www.wpcube.co.uk" rel="friend" title="Premium WordPress Plugins">wpcube.co.uk</a>

== Installation ==

1. Upload the `edd-software-licensing-custom-expiration` folder to the `/wp-content/plugins/` directory
2. Active the plugin through the 'Plugins' menu in WordPress
3. Configure your Bundle(s) in EDD with custom license expirations as necessary

== Frequently Asked Questions ==



== Screenshots ==

1. Options when configuring a Bundle

== Changelog ==

= 1.0.2 =
* Tested with WordPress 4.3
* Fix: plugin_dir_path() and plugin_dir_url() used for Multisite / symlink support

= 1.0.1 =
* Fix: Undefined index notice when creating a new Custom Post Type

= 1.0 =
* First release.

== Upgrade Notice ==
