=== Pluginception === 
Contributors: Otto42
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_donations&business=otto%40ottodestruct%2ecom
Tags: plugin, creation, new
Requires at least: 3.0
Tested up to: 3.4
Stable Tag: trunk
License: GPLv2
License URI: http://www.opensource.org/licenses/GPL-2.0

A plugin that lets you quickly and easily create new plugins.

== Description ==

A plugin that lets you quickly and easily create new plugins.

Install, activate, and then go to the Plugins->Create a New Plugin menu to create and activate a new blank plugin, live, on your site.

After the plugin is created, you'll be taken directly to the Plugin Editor screen, to type or paste in your new plugin's code.


== Installation ==

1. Upload the files to the `/wp-content/plugins/pluginception/` directory or install through WordPress directly.
1. Activate the "Pluginception" plugin through the 'Plugins' menu in WordPress
1. Try the "Create a New Plugin" option in the Plugins menu.


== Frequently Asked Questions ==

= Is this safe? =

Nope, not in the slightest. You have been warned.

That said, it's perfectly "safe", given a certain defintion of the word "safe". The "create a new plugin" screen itself is secure, and inaccessible to anybody who lacks the capability to "edit_plugins" to begin with. Pluginception also uses the proper WP_Filesystem methods to create the plugin, so there's no worries about incorrect file ownership on shared hosting. You may have to give it FTP credentials on some hosts for it to be able to create the plugin, that's the WP_Filesystem at work, making sure the files are correctly "owned".

All this plugin really does is make it quick and easy to create a new one-off plugin and take you directly to editing it. Cowboy-coding at its finest.

= For the love of god, man, why? =

Because I frequently spin off new plugins in order to paste simple code snippets into handy places. I know the code works, I've already tested it. Having to then encapsulate it into a plugin, fire up Filezilla, navigate, upload the plugin... It's a pain. This gives me an easy way to create a new blank plugin and go to the editor to paste in the known working code.

If you paste in non-working code, or decide to use the plugin editor to experiment, then you'll likely break your site. So don't do this if you don't know what you're doing. This is a power user's tool.

= I want this plugin to do something other than what it does. = 

Then modify the plugin however you like to do whatever you want. Don't bother me with it. This plugin does what I want it to do.


== Changelog ==

= 1.1 =
* Fix problems with quotes being slash-escaped (magic quotes).

= 1.0 = 
* First version.
