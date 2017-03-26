=== Mytory Markdown ===
Contributors: mytory
Donate link: http://mytory.net/paypal-donation
Tags: markdown, md, github, markdown editor
Tested up to: 4.7.3
Requires at least: null
Stable tag: 1.6.1
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html


== Description ==

= Now you can migrate from Public link to Dropbox API =

I made up Mytory Markdown for Dropbox plugin. The plugin access Dropbox using API. So I divide the plugin to Mytory Markdown for Dropbox.
Now you can install it in plugin page. This video describe how to migrate Public link to Dropbox API.

Install Mytory Markdown for Dropbox and activate it. Then see a video below.

[Youtube https://www.youtube.com/watch?v=ZmPWMBvGuS4]

If the video doesn't be show, [click this to go to how to migration video](https://www.youtube.com/watch?v=ZmPWMBvGuS4&).

= 이제 Public 링크를 드롭박스 API로 이전할 수 있습니다 =

Mytory Markdown for Dropbox 플러그인을 만들었습니다. API를 이용해서 드롭박스에 접속하는 플러그인입니다. 별도로 만들었습니다.
지금 플러그인 페이지에서 검색해 설치할 수 있습니다. 아래 비디오는 퍼블릭 링크를 드롭박스 API로 이전하는 방법을 설명합니다.

Mytory Markdown for Dropbox 플러그인을 설치한 뒤, 활성화하고 아래 비디오를 보세요.

(비디오는 위에서 보세요.)

= Intro =

The plugin get markdown file URL like github raw content url. The plugin convert markdown file to html, and put it to post content.

It also provide text mode that write markdown in post edit page. Markdown text converted to html is put to editor. Text mode don't use url.

이 플러그인은 마크다운 파일의 URL을 받아서 html로 변환하고 그걸 포스트 내용으로 집어넣습니다.
기트허브 원본 내용 URL 같은 것을 활용할 수 있습니다.

포스트 편집 페이지에서 마크다운 텍스트를 작성하는 방법도 제공합니다. 마크다운 텍스트는 html로 변환돼 에디터에 들어갑니다.
텍스트 모드를 사용하는 경우 URL은 사용하지 않습니다.

= Features =

**[View intro video.](http://youtu.be/mCgzB1aCQgM)**

[youtube http://www.youtube.com/watch?v=mCgzB1aCQgM]

* This plugin get markdown file url like github raw content url. It convert markdown file to html, and put it to post content.
* You can update post **by editing file on your computer**. e.g. **By push your content to github**. Of course, you can edit directly from github website.
* If post was updated once, plugin check only URL server's ETAG not full content. It is for speed.
  If ETAG was changed, plugin get new content and update post content. Or do nothing.
* The plugin only pass converted html content to editor. Saving is WordPress's work. So revision history is preserved.
* The plugin is compatible with other plugins. Because the plugin updates only post content html. This doesn't touch `the_content` process(vary plugins touch the process).
* You can use [markdown extra syntax](http://michelf.ca/projects/php-markdown/extra/).
* It provide markdown editor that can use in post editing page instead of url.


= Logic =

#### On admin page ####

On admin write page, put markdown url path. And click 'update editor' button. So markdown content converted to html is putted to editor. [See screenshot 1](https://wordpress.org/plugins/mytory-markdown/screenshots/).

#### On view page ####

This plugin get file url(Github raw content url is recommended). And save header's etag to postmeta and converted html to post_content.
Next time on request same post, first this plugin olny get http etag.
If changed etag, get md content and save again, or get html from post_content.

= This plugin divide title and content from md file =

If markdown file has `h1` this plugin puts first `h1` string to post_title. Of course, remove the `h1` from post_content so don't print title twice.

#### Source Code ####

[Mytory Markdown Github](https://github.com/mytory/mytory-markdown)

== Screenshots ==

1. Usage: paste markdown file url. In fact, any markdown file url is accepted.
2. GitHub Usage 1: Create markdown file, and push it to github. So you can see it your github repository. Click it.
3. GitHub Usage 2: And click 'Raw' button on your markdown file page like below.
4. GitHub Usage 3: Next, copy URL and paste it to markdown file path on 'add new post' page in your wordpress site. Last, click 'Update Editor Content' button.

== Installation ==

1. Upload files to the `/wp-content/plugins/mytory-markdown/` directory
1. Activate the plugin through the 'Plugins' menu in WordPress

== Changelog ==

= 1.6.0

* Now, you can use url batch replace feature. Go to **Settings > Mytory Markdown: URL Batch Replace**.
* Dropbox API feature will be provided later.
* 이번부터 URL 일괄 변환 기능을 이용할 수 있습니다. **설정 > Mytory Markdown: URL 일괄 변환** 메뉴로 가세요.
* 드롭박스 API를 이용한 기능은 조금만 더 기다려 주세요.

= 1.5.3 =

* Apologizing. See update plan on description section. Dropbox removed Public link function from 2017-03-15. I plan to update in a week.
* Fixed the problem that markdown conversion in text mode does not work well in some environment (for example, with UIM byeoru input method).
* 사과. 설명에 있는 업데이트 계획을 읽어 보세요. 일주일 안에 드롭박스 퍼블릭 링크 중단에 따른 업데이트를 제공할 예정입니다.
* 일부 환경에서(예를 들면 리눅스 벼루 입력기 환경) 텍스트 모드에서 마크다운 변환이 잘 안 되는 현상을 수정.

= 1.5.2 =

From this version, only first h1 will be moved to title input. there is bug that first h1 is moved to title and rest h1 disappear.

= 1.5.1 =

Backward compatibility for array literal(remove [] array literal).

= 1.5.0 =

Added text mode. This mode only use markdown editor(plain text editor) instead of url. It provide real-time conversion.

= 1.4.3 =

Fix a bug that it not work when `open_basedir` is set(`open_basedir`이 설정돼 있을 때 warning이 뜨면서 작동하지 않는 버그 수정). Cao Quảng Bình reported.

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