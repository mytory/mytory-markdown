# Mytory Markdown #
**Contributors:** mytory  
**Donate link:** http://mytory.net/paypal-donation  
**Tags:** markdown, md, dropbox  
**Tested up to:** 3.8  
**Stable tag:** 1.3.2  
**License:** GPLv2 or later  
**License URI:** http://www.gnu.org/licenses/gpl-2.0.html  

## Description ##

### Features ###

**[View intro video.](http://youtu.be/mCgzB1aCQgM)**

[youtube http://www.youtube.com/watch?v=mCgzB1aCQgM]

* This plugin get markdown file path on dropbox public link, convert markdown to html, and put it to post content.
* You can update post **by editing file on your computer with dropbox sync function.** Of course, that's ok even if content editor is empty, when you write new post. If you have entered markdown file's URL, plugin will take care of.
* If post was updated once, plugin check only URL server's ETAG not full content. It is for speed. If ETAG was changed, plugin get new content and update post content. Or do nothing.
* Plugin use WordPress's `wp_update_post()` function. So revision history is preserved.
* Plugin's compatibility is good. Because this plugin updates only post content html. This doesn't touch `the_content` process(vary plugins touch the process).
* You can use [markdown extra syntax](http://michelf.ca/projects/php-markdown/extra/).

### Logic ###

#### You have to enable dropbox 'Public Folder' ####

This plugin use dropbox 'Public link'. If you register dropbox account after December 6, 2012 you don't have Public folder. Then, [visit this page to 'enable public folder'](https://www.dropbox.com/enable_public_folder).

#### On admin page ####

On admin write page, put markdown url path. And click 'update editor' button. So markdown content converted to html is putted to editor.

![](http://dl.dropboxusercontent.com/u/15546257/blog/mytory/mytory-markdown/animated.gif)

#### On view page ####

This plugin get file url(Dropbox public link is recommended). And save header's etag to postmeta and converted html to post_content. Next time on request same post, first this plugin olny get dropbox http etag. If changed etag, get dropbox md content and save again, or get html from post_content.

### This plugin divide title and content from md file. ###

If markdown file has `h1` this plugin puts first `h1` string to post_title. Of course, remove the `h1` from post_content so don't print title twice.

#### GitHub ####

[Mytory Markdown Github](https://github.com/mytory/mytory-markdown)

## Installation ##

1. Upload files to the `/wp-content/plugins/mytory-markdown/` directory
1. Activate the plugin through the 'Plugins' menu in WordPress

## Changelog ##

### 1.3.2 ###

* Fixed auto update bug on page. (페이지에서 자동 갱신 작동하지 않는 버그 수정.)

### 1.3.1 ###

* Fixed bug that plugin don't work on default permalink type page.
* Added 'debug message on post/page' feature. You can switch on debug message on setting page.
* Translated to Korean.

### 1.3 ###

* Added 'auto update per x visits' feature.
* Improved performance. 
* Show incorrect 'public link' message gracefully. Then provide 'enable public folder' link.

### 1.2.2 ###

* You can disable auto update feature for normal user. Only when admin or writer visit post, auto update feature work. You can set it in __Setting > Mytory Markdown__ menu.
* If URL is not dropbox public link, alert it.

### 1.2.1 ###

Disabled 'markdown extra' plugin affect post content filter. This plugin was included in markdown php library file. I didn't know.

### 1.2 ###

Updated error handling.

### 1.1 ###

Initial version.