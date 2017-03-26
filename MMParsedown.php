<?php

class MMParsedown
{
    private $markdown;

    function __construct()
    {
        if (!class_exists('Parsedown')) {
            include 'Parsedown.php';
        }
        $this->markdown = new Parsedown();
        $this->markdown->setUrlsLinked(false);
    }

    public function convert($md_content)
    {
        return $this->markdown->text($md_content);
    }
}