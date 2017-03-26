<?php

class MMMarkdownExtra
{
    function __construct()
    {
        if (!defined('MARKDOWN_PARSER_CLASS')) {
            include 'markdown.php';
        }
    }

    public function convert($md_content)
    {
        return Markdown($md_content);
    }
}