# Mytory Markdown

This plugin get markdown file on dropbox public link, convert markdown to html, and print to post content. This use `shortcode`.

    [mytory-md path=http://dl.dropboxusercontent.com/u/11111111/my-file.md]

So, this shortcode will change `my-file.md` to html content.

## Logic

This plugin get dropbox md content. And save header's etag and converted html to postmeta. Next time on request same post, first this plugin olny get dropbox http etag. If changed etag, get dropbox md content, or get html from postmeta.

## This plugin remove `h1` from md file

I think markdown document has to be self-contained. But Wordpress post has own title. If markdown document has title(`h1` mark, `#` or `====`), title appear twice on post. So, I write code remove all `h1` from md. You probably will use `h2` for strapline.