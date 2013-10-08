# Mytory Markdown

This plugin get markdown file on dropbox public link, convert markdown to html, and print to post content. This use `shortcode`.

    [mytory-md path=http://dl.dropboxusercontent.com/u/11111111/my-file.md]

So, this shortcode will change `my-file.md` to html content.

## logic

This plugin get dropbox md content. And save header's etag and converted html to postmeta. Next time on request same post, first this plugin olny get dropbox http etag. If changed etag, get dropbox md content, or get html from postmeta.