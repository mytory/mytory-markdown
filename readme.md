# Mytory Markdown #
**Contributors:** mytory  
**Donate link:** http://mytory.net/paypal-donation  
**Tags:** markdown, md, github, markdown editor  
**Tested up to:** 4.7.3  
**Requires at least:** null  
**Stable tag:** 1.5.3  
**License:** GPLv2 or later  
**License URI:** http://www.gnu.org/licenses/gpl-2.0.html  


## Description ##

**Notice**:

(한국어 사용자들은 아래쪽에 한글로 알림을 적어 뒀습니다.)

[Dropbox disabled Public link to basic users from 2017-03-15.](https://www.dropbox.com/help/16)

I've promised to update. But I could not meet the deadline. I'm sorry. I was crazy busy from the end of last October. Now I have some time to spare. So I will update the plugin in a week.

I will solve the problem of public link removal in two ways.

**1. Batch change function of the path of the Markdown file:** You can change root path of files.  
   e.g. `https://dl.dropboxusercontent.com/u/15546257/blog/markdown/my-post.md` to `https://raw.githubusercontent.com/mytory/mytory-markdown/master/blog/markdown/my-post.md`
   You can migrate files to github or etc using this feature.

1. Dropbox API to reconnect files with batch change function of the path of the Markdown files.

I will be updating in a week (3/22).

**I apologize again for not keeping my promise.**

**알림**:

[3월 15일부터 드롭박스는 무료 사용자들에게 퍼블릭 링크 기능을 더이상 제공하지 않습니다.](https://www.dropbox.com/help/16)

그 전에 업데이트할 것이라고 약속을 드린 바 있는데, 기한을 지키지 못했습니다. 매우 죄송합니다. 작년 10월 말부터 미친듯이 바빠서 도무지 여유를 낼 수가 없었습니다. 다행히 이제 여유가 좀 생겼습니다. 일주일 안에 업데이트를 제공하려고 합니다.

두 가지 방법으로 문제를 해결하려고 합니다.

**1. 마크다운 파일 경로를 한꺼번에 업데이트하는 기능:** 파일의 루트 경로를 변경할 수 있습니다.  
   예를 들면, `https://dl.dropboxusercontent.com/u/15546257/blog/markdown/my-post.md`를 `https://raw.githubusercontent.com/mytory/mytory-markdown/master/blog/markdown/my-post.md`로 바꾸는 식입니다.
   이 기능을 이용하면 기트허브 등으로 파일을 옮길 수 있을 것입니다.

1. 드롭박스 API를 이용해서 기존의 마크다운 파일을 새로 연결하는 방법입니다. 역시 한꺼번에 연결할 수 있는 기능을 제공할 것입니다.

일주일 안에(3월 22일까지) 업데이트하도록 하겠습니다.

**기한을 맞추지 점, 다시 한 번 사과드립니다.**

-----

This plugin get markdown file path on dropbox public link or github raw content url. It convert markdown file to html, and put it to post content.

It also provide text mode that write markdown in post edit page. Markdown text converted to html is put to editor by real-time. Text mode don't use url.

### Features ###

**[View intro video.](http://youtu.be/mCgzB1aCQgM)**

[youtube http://www.youtube.com/watch?v=mCgzB1aCQgM]

* This plugin get markdown file path on dropbox public link or github raw content url. It convert markdown file to html, and put it to post content.
* You can update post **by editing file on your computer with dropbox sync feature.** Or you can update post **by push your content to github**. Of course, you can edit directly from github website.
* If post was updated once, plugin check only URL server's ETAG not full content. It is for speed. If ETAG was changed, plugin get new content and update post content. Or do nothing.
* Plugin only pass converted html content to editor. Saving is WordPress's work. So revision history is preserved.
* Plugin's compatibility is good. Because this plugin updates only post content html. This doesn't touch `the_content` process(vary plugins touch the process).
* You can use [markdown extra syntax](http://michelf.ca/projects/php-markdown/extra/).
* It provide markdown editor that can use in post editing page instead of url.


### Notice about Dropbox ###

This plugin use dropbox 'Public link' not 'Share link'. Currently only Dropbox Pro and Business users may enable Public folders. Below is a example.

- (Public link)[https://dl.dropboxusercontent.com/u/15546257/test.md]
- (Share link)[https://www.dropbox.com/s/rgin3gbpa5y0505/test.md?dl=0]

If you are Pro and Business user, [visit this page to 'enable public folder'.](https://www.dropbox.com/enable_public_folder)

If you are not, you can use GitHub, instead.


### Logic ###

#### On admin page ####

On admin write page, put markdown url path. And click 'update editor' button. So markdown content converted to html is putted to editor. [See screenshot 1](https://wordpress.org/plugins/mytory-markdown/screenshots/).

#### On view page ####

This plugin get file url(Dropbox public link is recommended). And save header's etag to postmeta and converted html to post_content. Next time on request same post, first this plugin olny get dropbox http etag. If changed etag, get dropbox md content and save again, or get html from post_content.

### This plugin divide title and content from md file ###

If markdown file has `h1` this plugin puts first `h1` string to post_title. Of course, remove the `h1` from post_content so don't print title twice.

#### Source Code ####

[Mytory Markdown Github](https://github.com/mytory/mytory-markdown)

## Screenshots ##

**1. Usage:** paste markdown file url. In fact, any markdown file url is accepted. Although it's not Dropbox nor Github, if the file is markdown file, it is acceptable.  
/legacy/mytory-markdown/
![](https://mytory.net/legacy/mytory-markdown/animated.gif)

**2. GitHub Usage 1:** Create markdown file, and push it to github. So you can see it your github repository. Click it.  

![](https://mytory.net/legacy/mytory-markdown/mytory-markdown-github-1.jpg)

**3. GitHub Usage 2:** And click 'Raw' button on your markdown file page like below.  

![](https://mytory.net/legacy/mytory-markdown/mytory-markdown-github-2.jpg)

**4. GitHub Usage 3:** Next, copy URL and paste it to markdown file path on 'add new post' page in your wordpress site. Last, click 'Update Editor Content' button.  

![](https://mytory.net/legacy/mytory-markdown/mytory-markdown-github-3.jpg)

## Installation ##

1. Upload files to the `/wp-content/plugins/mytory-markdown/` directory
1. Activate the plugin through the 'Plugins' menu in WordPress

## Changelog ##

### 1.5.3 ###

* Apologizing. See update plan on description section. Dropbox removed Public link function from 2017-03-15. I plan to update in a week.
* Fixed the problem that markdown conversion in text mode does not work well in some environment (for example, with UIM byeoru input method).
* 사과. 설명에 있는 업데이트 계획을 읽어 보세요. 일주일 안에 드롭박스 퍼블릭 링크 중단에 따른 업데이트를 제공할 예정입니다.
* 일부 환경에서(예를 들면 리눅스 벼루 입력기 환경) 텍스트 모드에서 마크다운 변환이 잘 안 되는 현상을 수정.

### 1.5.2 ###

From this version, only first h1 will be moved to title input. there is bug that first h1 is moved to title and rest h1 disappear.

### 1.5.1 ###

Backward compatibility for array literal(remove [] array literal).

### 1.5.0 ###

Added text mode. This mode only use markdown editor(plain text editor) instead of url. It provide real-time conversion.

### 1.4.3 ###

Fix a bug that it not work when `open_basedir` is set(`open_basedir`이 설정돼 있을 때 warning이 뜨면서 작동하지 않는 버그 수정). Cao Quảng Bình reported.

### 1.4.2 ###

It can use github raw url for markdown file.

### 1.4.1 ###

1. It doesn't show manual update button for post that don't use mytory markdown.
2. Added memory unlimit code against memory exhausted.

### 1.4.0 ###

Feature that manual update on view page.

### 1.3.4 ###

Fixed it does not work. Enabled cURL redirection. Thanks to [WickedSik](https://github.com/WickedSik).

### 1.3.3 ###

* Fixed bug 'update content' is not work for wp 3.9.
* Enhanced legibility tag on debug mode.

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