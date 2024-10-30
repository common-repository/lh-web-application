=== LH Web Application ===
Contributors: shawfactor
Donate link: https://lhero.org/portfolio/lh-web-application/
Tags: service worker, pwa, web app, chrome, android
Requires at least: 4.0
Tested up to: 5.5
Stable tag: trunk
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Makes your WordPress website into a fully configurable web app.

== Description ==

This plugin transform your WordPress website into a fully configurable progressive web application. 

Features Include:

* Upload a home screen icon into the WordPress media library, this plugin will resize the image appropriately (for different device viewports).
* Customise the short_name of your application.
* Set the display mode of your application.
* Works offline!

Check out [our documentation][docs] for more information. 

[docs]: https://lhero.org/portfolio/lh-web-application/



== Installation ==
1. Unpack the download-package
2. Upload the file to your plugin-directory, default is `/wp-content/plugins/`
3. Activate the plugin through the 'Plugins' menu in WordPress or alternative Network Activation
4. Go to Appearance->Customise and add a Site Icon under Site Identity. This icon is used as the app icon (Some browsers require this icon to be a PNG)
5. Configure the options under Settings -> Web Application
6. You may need to go to Settings->Permalinks and regenerate links
7. If you are upgrading the plugin you may need to redo setps 4 and 5 after the upgrade


== Frequently Asked Questions ==

= Can I see an example of a site using this plugin? =
Sure my sports league uses this plugin and therefore has a progressive web app: https://princesparktouch.com/

= How can I check if my site works as a progressive web app? =
There are three ways:

*1. Try installing your site on a modern mobile using android chrome (or another compliant browser).

*2. Use the validator here: https://manifest-validator.appspot.com/

*3. Install the "Lighthouse" browser extension on your Chrome desktop browser (this will give you great feedback)

= Will this work on an IOS device? =
Yes. However IOS does not not spport the full functionality as yet.

== Changelog ==

= 1.00 - March 17, 2017 =
* Initial Release

= 1.01 - March 23, 2017 =
* Added options and documentation

= 1.02 - March 25, 2017 =
* Cron flush rewrites etc

= 1.03 - March 30, 2017 =
* Use isset

= 1.04 - April 02, 2017 =
* Etag support

= 1.05 - April 02, 2017 =
* Add to home support

= 1.06 - May 21, 2017 =
* Moved inline to own file

= 1.07 - May 30, 2017 =
* Better service worker and file loading

= 1.08 - June 08, 2017 =
* Multiple fixes

= 1.10 - July 18, 2017 =
* Various

= 1.13 - November 09, 2017 =
* Major improvements

= 1.14 - November 09, 2017 =
* Bug fix

= 1.15 - November 09, 2017 =
* extra error checking

= 1.16 - November 13, 2017 =
* static manifest

= 1.18 - November 13, 2017 =
* added write manifest on activate

= 1.21 - November 27, 2017 =
* removed versions, filemtime everywhere

= 1.22 - December 01, 2017 =
* better apple support

= 1.23 - December 23, 2017 =
* Checked response status

= 1.24 - December 26, 2017 =
* Clear old caches

= 1.25 - March 05, 2018 =
* Additional functionality

= 1.26 - March 05, 2018 =
* Bundling assets

= 1.27 - May 16, 2018 =
* Multiple changes

= 1.28 - September 14, 2020 =
* Multiple changes