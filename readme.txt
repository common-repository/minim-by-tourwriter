=== Tourwriter Itineraries ===

Contributors: tourwriter, thornberrypie
Plugin Name: Tourwriter Itineraries
Plugin URI: https://itinerarybuilder.tourwriter.com/help-articles/integrations/wordpress-plugin
Tags: travel, itinerary, tours, software, tour operator, dmc, tourwriter, minim, embed itinerary, dmo, itineraries
Requires at least: 4.4
Tested up to: 6.0
Requires PHP: 7.2
Stable tag: 2.2.4

== Description ==

= Easily display your Tourwriter itineraries on your website =

Tourwriter is beautifully simple itinerary builder software that allows tour operators to craft, book, and manage their itineraries.

This plugin allows you to integrate your website with Tourwriter. Together, you can seamlessly showcase your itineraries on your website for your current and potential customers to be inspired by.

Not currently using Tourwriter to build your itineraries? [Find out more](https://www.tourwriter.com/?ref=plugin).

= How it works =

Simply build your itinerary in Tourwriter and then use the plugin shortcode to embed the itinerary on any post or page on your website. 

== Links ==

[Click here for a detailed overview of the plugin set up](https://itinerarybuilder.tourwriter.com/help-articles/integrations/wordpress-plugin/?ref=plugin).

== Screenshots ==

1. The plugin in action with a generic Wordpress theme

== Frequently Asked Questions ==

= What is Tourwriter? =

[Tourwriter](https://www.tourwriter.com/?ref=plugin) is a tour operator software solution complete with beautiful mobile optimised digital itineraries, automatic bookings functionality, complex pricing management, traveller collaboration and more.

This plugin allows you to embed your Tourwriter itineraries directly onto your WordPress website.

Not currently using Tourwriter to build your itineraries? [Find out more](https://www.tourwriter.com/?ref=plugin).

= How do I show Tourwriter itineraries on my website? =

This plugin uses a shortcode which you can paste onto any page or post on your WordPress website. Simply copy the ID of the itinerary you want to display from Tourwriter and paste it into the plugin shortcode to embed your itinerary on your website.

= Shortcode =

Copy and paste this shortcode - add your desired itinerary ID in between the inverted commas 
[tourwriter id="Itinerary ID goes here"]

Once your changes are saved you should be able to visit the page and see your Tourwriter Itinerary embedded in the place where you inserted the shortcode.

[Click here](https://itinerarybuilder.tourwriter.com/help-articles/integrations/wordpress-plugin/?ref=plugin) for a more detailed overview of the plugin set up.

= Where do I find the ID of an itinerary in Tourwriter? =

The ID is a string of characters found in the URL (address bar) when you visit your itinerary in Tourwriter. It's the section of the URL after *itineraries/* and before the next forward slash.

= What will happen when I click the "Check API Key" button in the plugin settings?

It should give you a verification message that the key you have entered is valid, or an error message if the key is not valid.

When you first enter your **Tourwriter API Key**, clicking the **Check API Key** button only validates the key, but does not save it. You still need to click the **Save Settings** button before you can start using the plugin.

== Installation ==

= An active Tourwriter account is required to use this plugin =

Not currently using Tourwriter to build your itineraries? [Find out more](https://www.tourwriter.com/?ref=plugin).

Once the plugin is installed and activated on your WordPress website go to the Tourwriter heading in the main menu on the left and follow the instructions to add your **API Key** which can be found on the profile page in Tourwriter.

This plugin uses a shortcode which you can paste onto any page or post on your WordPress website. Simply copy the ID of the itinerary you want to display from Tourwriter and paste it into the plugin shortcode to embed your itinerary on your website.

= Shortcode =

Copy and paste this shortcode - add your desired itinerary ID in between the inverted commas

**[tourwriter id="Itinerary ID goes here"]**

Once your changes are saved you should be able to visit the page and see your Tourwriter itinerary embedded in the place where you inserted the shortcode.

== Changelog ==

= 2.2.4 =
*Release Date - 27 April 2023*

* Fix: Updated calculation for non accommodation types
* Fix: Updated css hiding some data
* Fix: Hidden items were still showing

= 2.2.3 =
*Release Date - 28 September 2022*

* Maintenance: Background updates to work with latest Tourwriter version

= 2.2.2 =
*Release Date - 24 March 2022*

* New: Renamed plugin to Tourwriter Itineraries and moved to Wordpress Main Menu
* New: Added option in settings to switch between 1 and 2 column layouts as default
* Fix: Minor styling updates
* Fix: Orders object changed to order field
* Fix: API url change
* Fix: Multiple options showing as duplicate products

= 2.2.1 =
*Release Date - 20 September 2021*

* Fix: Update rules for displaying item titles on itineraries pushed from Tourwriter.NET

= 2.2.0 =
*Release Date - 21 July 2021*

* New: Rebrand plugin removing Minim references, rename to Embed itineraries with Tourwriter (Formerly Minim by Tourwriter)
* Tweak: Update webpack and other dev dependencies, remove redundant gulpfile.babel.js

= 2.1.0 =
*Release Date - 23 March 2021*

* New: Add button to delete cache in Minim settings
* New: Add new "minim" shortcode for displaying itineraries
* Enhancement: Use large size for itinerary images
* Fix: Display paragraphs correctly in itinerary descriptions

= 2.0.1 =
*Release Date - 27 November 2020*

* Fix - Add missing CSS and JS assets

= 2.0 =
*Release Date - 27 November 2020*

* New - Add ability to insert itinerary using shortcode instead of attaching it to the main content area via a custom field.
* Enhancement - Add basic layout styling for itinerary items
* Enhancement - Use heading tags for itinerary item titles to better adopt existing website styling.
* Enhancement - Add caching for data returned from the Minim API
* Fix - Prevent PHP errors and warnings when WP_DEBUG="true"

= 1.0.1 =
*Release Date - 15 May 2020*

* Enhancement - Improve error reporting for API requests.
* Fix - Add missing /languages and .pot file.
* Minor - Modify readme content.

= 1.0 =
*Release Date - 23 March 2020*

* Minim by Tourwriter

== Upgrade Notice ==

= 2.2.3 =
This version is a minor update to work with the latest Tourwriter version and contains no user-facing changes.

= 2.2.2 =
This version changes the plugin name and menu location. It also gives the option to display itineraries in 1 or 2 columns. A few bugs squashed.

= 2.1.0 =
This version improves the display of itineraries and allows managing of cache

= 2.0.1 =
This version adds missing CSS and JS assets for the front end

= 2.0 =
This version changes the way itineraries are inserted on pages and posts by introducing the use of shortcodes rather than attaching an itinerary to the main content area via a custom field. It also greatly improves how itineraries are displayed by adopting the style of your website, providing a more seamless integration for the end user. Upgrade is highly recommended. [Visit our help article](https://itinerarybuilder.tourwriter.com/help-articles/integrations/wordpress-plugin/?ref=plugin) for more information.

= 1.0.1 =
This version contains minor updates and fixes.