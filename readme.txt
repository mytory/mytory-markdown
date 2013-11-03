=== Mytory Markdown ===
Contributors: mytory
Donate link: http://mytory.net/paypal-donation
Tags: markdown
Tested up to: 3.6.1
Stable tag: 1.1
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

== Description ==

= Features =

**[View intro video.](http://youtu.be/mCgzB1aCQgM)**

[youtube http://www.youtube.com/watch?v=mCgzB1aCQgM]

* This plugin get markdown file path on dropbox public link, convert markdown to html, and put it to post content.
* You can update post **by editing file on your computer with dropbox sync function.** Of course, that's ok even if content editor is empty, when you write new post. If you have entered markdown file's URL, plugin will take care of.
* If post was updated once, plugin check only URL server's ETAG not full content. It is for speed. If ETAG was changed, plugin get new content and update post content. Or do nothing.
* Plugin use WordPress's `wp_update_post()` function. So revision history is preserved.
* Plugin's compatibility is good. Because this plugin updates only post content html. This doesn't touch `the_content` process(vary plugins touch the process).
* You can use [markdown extra syntax](http://michelf.ca/projects/php-markdown/extra/).

= Logic =

#### On admin page ####

On admin write page, put markdown url path. And click 'update editor' button. So markdown content converted to html is putted to editor.

![](http://dl.dropboxusercontent.com/u/15546257/blog/mytory/mytory-markdown/animated.gif)

#### On view page ####

This plugin get file url(Dropbox public link is recommended). And save header's etag to postmeta and converted html to post_content. Next time on request same post, first this plugin olny get dropbox http etag. If changed etag, get dropbox md content and save again, or get html from post_content.

= This plugin divide title and content from md file. =

If markdown file has `h1` this plugin puts first `h1` string to post_title. Of course, remove the `h1` from post_content so don't print title twice.

== Installation ==

1. Upload files to the `/wp-content/plugins/mytory-markdown/` directory
1. Activate the plugin through the 'Plugins' menu in WordPress

== Changelog ==

= 1.1 =

Initial version.