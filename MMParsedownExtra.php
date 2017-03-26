<?php

class MMParsedownExtra
{
    private $markdown;

    function __construct()
    {
        if (!class_exists('Parsedown')) {
            include 'Parsedown.php';
        }
        if (!class_exists('ParsedownExtra')) {
            include 'ParsedownExtra.php';
        }
        $this->markdown = new ParsedownExtra();
        $this->markdown->setUrlsLinked(false);
    }

    public function convert($md_content)
    {
        return $this->markdown->text($md_content);
    }
}