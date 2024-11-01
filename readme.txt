=== mb.vimeoPlayer for background videos ===

Contributors: pupunzi
Tags: video player, Vimeo, full background, video, HTML5, mov, jquery, pupunzi, mb.components, cover video, embed, embed videos, embed Vimeo, shortcode, video cover, video HTML5, Vimeo, Vimeo embed, Vimeo player, Vimeo videos
Requires at least: 3.0
Tested up to: 5.7
Stable tag:  1.2.3
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=DSHAHSJJCQ53Y
License: GPLv2 or later

Play any Vimeo's video as background of your page or as custom player inside an element of the page.

== Description ==

A Chrome-less Vimeo® video player that let you play any Vimeo® video as background of your WordPress® page or post.
You can activate it for your home page from the settings panel (no license needed) or on any post or page using the short code (need the <a href="https://pupunzi.com/wpPlus/go-plus.php?plugin_prefix=VIPL" target="_blank">Plus version</a>) as described in the Reference section of the settings.

The mb.vimeo_player doesn't work on any mobile devices (iOs, Android, Windows, etc.) due to restrictions applied by the vendors on media controls via javascript.
Adding a background image to the body as mobile devices fallback is a good practice and it will also prevent unwanted white flickering on desktop browsers when the video is buffering.

* demo: http://pupunzi.com/mb.components/mb.vimeoPlayer/demo/
* pupunzi blog: http://pupunzi.open-lab.com
* pupunzi site: http://pupunzi.com

This plug in has been tested successfully on:

* Chrome 11+, Firefox 7+, Opera 9+    on Mac OsX, Windows and Linux
* Safari 5+    on Mac OsX
* IE7+    on Windows (via Adobe Flash player)

with the <a href="https://pupunzi.com/wpPlus/go-plus.php?plugin_prefix=VIPL" target="_blank">Plus version</a> you'll get:

     Remove the water-mark from the video.
     Activate all the advanced features:
			 Fallback image url
			 Set the opacity
			 Set the aspect ratio
			 Set the seconds the video should start at
			 Set the seconds the video should end at
			 Show the control bar
			 Choose the full screen behavior
			 Set the audio volume
			 Choose if the video should start mute or not
			 Choose if the video should loop
			 Add the raster image
			 Track the video views on Google Analytics
			 Choose if the player should pause if the windows blur
     Activate the short-code editor that let you add any YTPlayer video on any page of your site.
     Use the YTPlayer to display a clean Vimeo video player (via short-code).
     Set the YTPlayer as background of any element of your page (via short-code).

== Installation ==

Extract the zip file and upload the contents to the wp-content/plugins/ directory of your WordPress installation, and then activate the plugin from the plugins page.

== Screenshots ==

1. The settings panel.

== To set your homepage background video: ==

1. Go to the mb.vimeo-player settings page.
2. set the complete Vimeo video url
3. set all the other parameters as you need.

You can also set it by placing a shortcode in the home page via the YTPlayer shortcode window (<a href="https://pupunzi.com/wpPlus/go-plus.php?plugin_prefix=VIPL" target="_blank">Plus version</a>). 
You can open it by clicking on the YTPlayer button in the top toolbar of the page editor.

== To set a video as background of a post or a page: ==
Use the editor button or write the below shortcode into the content of your post or page (<a href="https://pupunzi.com/wpPlus/go-plus.php?plugin_prefix=VIPL" target="_blank">Plus version</a>):

[vimeo_player url="https://vimeo.com/202763350" ratio="16/9" isinline="false" showcontrols="true" realfullscreen="true" printurl="true" autoplay="false" mute="false" loop="false" addraster="false" stopmovieonblur="false" gaTrack="false" ]

* @ url = the YT url of the video you want as background
* @ ratio = the aspect ratio of the video 4/3 or 16/9
* @ mute = a boolean to mute the video
* @ loop = a boolean to loop the video on its end
* @ showcontrols = a boolean to show or hide controls and progression of the video
* @ opacity = a value from 0 to 1 that set the opacity of the background video
* @ id = The ID of the element in the DOM where you want to target the player (default is the BODY)
* @ quality:
  * small: Player height is 240px, and player dimensions are at least 320px by 240px for 4:3 aspect ratio.
  * medium: Player height is 360px, and player dimensions are 640px by 360px (for 16:9 aspect ratio) or 480px by 360px (for 4:3 aspect ratio).
  * large: Player height is 480px, and player dimensions are 853px by 480px (for 16:9 aspect ratio) or 640px by 480px (for 4:3 aspect ratio).
  * hd720: Player height is 720px, and player dimensions are 1280px by 720px (for 16:9 aspect ratio) or 960px by 720px (for 4:3 aspect ratio).
  * hd1080: Player height is 1080px, and player dimensions are 1920px by 1080px (for 16:9 aspect ratio) or 1440px by 1080px (for 4:3 aspect ratio).
  * highres: Player height is greater than 1080px, which means that the player's aspect ratio is greater than 1920px by 1080px.
  * default: Vimeo selects the appropriate playback quality.

== What about mobile ==

The mb.vimeo_player works on any modern mobile devices (iOs, Android) but needs a first touch to start.

== Changelog ==

= 1.2.3 =
Feature: Updated to the latest jquery script.

= 1.2.2 =
Bugfix: With the new Mac OS update (Big Sur) the browser user agent has changed and the os verification was firing a blocking bug.

= 1.2.0 =
Revision: Performed a code revision to check security issues.

= 1.1.8 =
New feature: Now it works on mobile too.

= 1.1.7 =
Bug fix: With the last Chrome version the video didn't start.

= 1.1.6 =
Bug fix: Fixed a bug if the ratio was set to "auto".
Updated the resize behavior to remove the flickering.

= 1.1.5 =
Bug fix: With the latest Webkit update the Vimeo API was manipulating the player size to always fit the window size compromising the optimized display.

= 1.1.2 =
Bug fix: fix a blocking bug on plugin installation due to a call to a non instantiated function.

= 1.1.1 =
Updates: Better performances and some changes on the admin panel.

= 1.1.0 =
Updates: Updated to the latest version of the jquery.mb.vimeo_player.js file (10.11) fixing a error if viewed on mobile devices; updated the PLUS box.

= 1.0.10 =
Bug fix: The settings page was broken.

= 1.0.9 =
Bug fix: With the previous release I introduced a bug on OS detection that was generating an error on Linux OS.

= 1.0.8 =
Update: Updated the upgrade to Plus procedure to remove the free plug-in once installed the Plus.

= 1.0.7 =
Bug fix: The mobileFallbackImage was not applied correctly again.

= 1.0.6 =
Bug fix: The settings page didn't load correctly.

= 1.0.5 =
Bug fix: At video start the audio was on for 1 sec. also if the mute option was set to true.

= 1.0.4 =
Update: Updated the jquery.mb.vimeo_player.js file to the latest release. Now all the jquery.mb.vimeo_player API events are available (see the javascript documentation for more: https://github.com/pupunzi/jquery.mb.vimeo_player/wiki/Documentation ).

= 1.0.3 =
Bug fix: The mobileFallbackImage was not applied correctly.


= 1.0.2 =
New feature: Added the thumbnails of the videos you are using in the settings page.

= 1.0.1 =
Bug fix: In the plug-in list the setting link was broken.

= 1.0.0 =
First release.

== Frequently Asked Questions ==

= I'm using the plug in as background video and I can see the control bar on the bottom but the video doesn't display =
 Your theme is probably using a wrapper for the content and it probably has a background color or image. You should check the CSS and remove that background to let the video that is behind do display correctly.

= Everything is working fine on my desktop but it doesn't work on any mobile devices =
Due to restrictions adopted by browser vendors and Vimeo team this plugin can't work on touch devices.

= I would have an image on the background before the video starts and after the video end; how can I do? =
The simplest way is to add an image as background of the body via CSS.

= I love your plugin! What can I do to help?
Creating and supporting this plugin takes up a lot of my free time, therefore I would highly appreciate if you could take a couple of minutes to write a review.
This will help other WordPress users to start using this plugin and keep me motivated to maintain and support it. You can also make a donation to support my work!
