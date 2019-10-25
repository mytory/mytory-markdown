<?php

class MMMultimarkdown
{
    public function convert($md_content)
    {
    	global $mytory_markdown;
	    if (!$mytory_markdown->hasMultimarkdownExecution()) {
		    return '<p>There is no <code>multimarkdown</code> execution or web server cannot run it.</p>';
	    }

	    $execution = $this->getExecution();
	    $md_file = tempnam('', '');
	    file_put_contents($md_file, $md_content);
    	$command = escapeshellcmd("$execution $md_file -t html");
    	exec($command, $result, $return_var);
    	if ($return_var > 0) {
    		return "<p>There is an error: code <code>{$return_var}</code></p>";
	    }
        return implode("\n", $result);
    }

    private function getExecution()
    {
	    if (defined('MYTORY_MARKDOWN_MULTIMARKDOWN_EXECUTION')) {
		    return MYTORY_MARKDOWN_MULTIMARKDOWN_EXECUTION;
	    }
	    return 'multimarkdown';
    }
}