=== Mytory Markdown ===
Contributors: mytory
Donate link: http://mytory.net/paypal-donation
Tags: markdown, md, dropbox, github
Tested up to: 4.2.3
Stable tag: 1.4.2
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

== Description ==

This plugin get markdown file path on dropbox public link or github raw content url. It convert markdown file to html, and put it to post content.

== Features ==

**[View intro video.](http://youtu.be/mCgzB1aCQgM)**

[youtube http://www.youtube.com/watch?v=mCgzB1aCQgM]

* This plugin get markdown file path on dropbox public link or github raw content url. It convert markdown file to html, and put it to post content.
* You can update post **by editing file on your computer with dropbox sync feature.** Or you can update post **by push your content to github**. Of course, you can edit directly from github website.
* If post was updated once, plugin check only URL server's ETAG not full content. It is for speed. If ETAG was changed, plugin get new content and update post content. Or do nothing.
* Plugin only pass converted html content to editor. Saving is WordPress's work. So revision history is preserved.
* Plugin's compatibility is good. Because this plugin updates only post content html. This doesn't touch `the_content` process(vary plugins touch the process).
* You can use [markdown extra syntax](http://michelf.ca/projects/php-markdown/extra/).


== Notice about Dropbox ==

This plugin use dropbox 'Public link'. Currently only Dropbox Pro and Business users may enable Public folders.

If you are Pro and Business user, [visit this page to 'enable public folder'.](https://www.dropbox.com/enable_public_folder)

If you are not, you can use GitHub, instead.


== Use GitHub for Mytory Markdown ==

I do not describe github usage. Find it other site.

Create markdown file, and push it to github. So you can see it your github repository. Click it.

![](https://dl.dropboxusercontent.com/u/15546257/blog/mytory/mytory-markdown/mytory-markdown-github-1.jpg)

And click 'Raw' button on your markdown file page like below.

![](https://dl.dropboxusercontent.com/u/15546257/blog/mytory/mytory-markdown/mytory-markdown-github-2.jpg)

Next, copy URL and paste it to markdown file path on 'add new post' page in your wordpress site.

![](https://dl.dropboxusercontent.com/u/15546257/blog/mytory/mytory-markdown/mytory-markdown-github-3.jpg)

Last, click 'Update Editor Content' button.


== Logic ==

#### On admin page ####

On admin write page, put markdown url path. And click 'update editor' button. So markdown content converted to html is putted to editor.

![](http://dl.dropboxusercontent.com/u/15546257/blog/mytory/mytory-markdown/animated.gif)

#### On view page ####

This plugin get file url(Dropbox public link is recommended). And save header's etag to postmeta and converted html to post_content. Next time on request same post, first this plugin olny get dropbox http etag. If changed etag, get dropbox md content and save again, or get html from post_content.

== This plugin divide title and content from md file ==

If markdown file has `h1` this plugin puts first `h1` string to post_title. Of course, remove the `h1` from post_content so don't print title twice.

#### Source Code ####

[Mytory Markdown Github](https://github.com/mytory/mytory-markdown)

== Installation ==

1. Upload files to the `/wp-content/plugins/mytory-markdown/` directory
1. Activate the plugin through the 'Plugins' menu in WordPress

== Changelog ==

= 1.4.2 =

It can use github raw url for markdown file.

= 1.4.1 =

1. It doesn't show manual update button for post that don't use mytory markdown.
2. Added memory unlimit code against memory exhausted.

= 1.4.0 =

Feature that manual update on view page.

= 1.3.4 =

Fixed it does not work. Enabled cURL redirection. Thanks to [WickedSik](https://github.com/WickedSik).

= 1.3.3 =

* Fixed bug 'update content' is not work for wp 3.9.
* Enhanced legibility tag on debug mode.

= 1.3.2 =

* Fixed auto update bug on page. (페이지에서 자동 갱신 작동하지 않는 버그 수정.)

= 1.3.1 =

* Fixed bug that plugin don't work on default permalink type page.
* Added 'debug message on post/page' feature. You can switch on debug message on setting page.
* Translated to Korean.

= 1.3 =

* Added 'auto update per x visits' feature.
* Improved performance. 
* Show incorrect 'public link' message gracefully. Then provide 'enable public folder' link.

= 1.2.2 =

* You can disable auto update feature for normal user. Only when admin or writer visit post, auto update feature work. You can set it in __Setting > Mytory Markdown__ menu.
* If URL is not dropbox public link, alert it.

= 1.2.1 =

Disabled 'markdown extra' plugin affect post content filter. This plugin was included in markdown php library file. I didn't know.

= 1.2 =

Updated error handling.

= 1.1 =

Initial version.