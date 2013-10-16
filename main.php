<?php
/*
Plugin Name: Mytory Markdown
Description: Markdown edit using Dropbox public link. Only support file url in current version.
Author: mytory
Version: 0.9
Author URI: http://mytory.net
*/

class Mytory_Markdown {

    function Mytory_Markdown(){
        add_shortcode( 'mytory-md', array(&$this, 'apply_markdown'));
    }

    /**
     * apply markdown
     * @param  array $attr shortcod attributes
     * @return string contents markdown apply
     */
    public function apply_markdown( $attr ){
        global $post;

        $attr['path'] = str_replace('https://', 'http://', $attr['path']);

        if($this->_need_to_save($attr['path'])){

            update_post_meta($post->ID, '_mytory_markdown_etag', $this->_get_etag($attr['path']));
            $md_content = $this->_file_get_contents($attr['path']);

            if( ! function_exists('Markdown')){
                include_once 'markdown.php';
            }

            $html_content = Markdown($md_content);
            $html_content = preg_replace('/<h1>(.*)<\/h1>/', '', $html_content);
            $html_content = wptexturize($html_content);
            $html_content = convert_smilies($html_content);
            $html_content = convert_chars($html_content);
            // $html_content = wpautop($html_content);
            $html_content = shortcode_unautop($html_content);
            $html_content = prepend_attachment($html_content);
            update_post_meta($post->ID, 'mytory_markdown_html', $html_content);
            return $html_content;
            
        }else{
            return get_post_meta($post->ID, 'mytory_markdown_html', true);
        }
    }

    /**
     * This function use etag in http header.
     * If etag change, need new save.
     * @param  string $url
     * @return boolean
     */
    private function _need_to_save($url){
        global $post;
        
        $etag_saved = get_post_meta($post->ID, '_mytory_markdown_etag', true);

        if($etag_saved){
            $etag_remote = $this->_get_etag($url);
            
            // if etag different each other, need to save
            return ($etag_saved != $etag_remote);
        }else{
            // if no cache, need to save
            return true;
        }
    }

    /**
     * get etag from dropbox url
     * @param  string $url
     * @return string
     */
    private function _get_etag($url){
        $header = $this->_get_header_from_url($url);
        $header = $this->_http_parse_headers($header);
        return $header['etag'];
    }

    /**
     * get header from url
     * @param  string $url dropbox public url
     * @return string
     */
    private function _get_header_from_url($url){
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_HEADER, true);
        curl_setopt($curl, CURLOPT_NOBODY, true);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
        return curl_exec($curl);
    }

    /**
     * get contents form url
     * @param  string $url dropbox public url
     * @return string
     */
    private function _file_get_contents($url){
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_HEADER, false);
        curl_setopt($curl, CURLOPT_NOBODY, false);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        return curl_exec($curl);
    }

    /**
     * parse header to array
     * http://www.php.net/manual/en/function.http-parse-headers.php#112986
     * @param  string $raw_headers
     * @return array
     */
    private function _http_parse_headers($raw_headers)
    {
        $headers = array();
        $key = ''; // [+]

        foreach(explode("\n", $raw_headers) as $i => $h)
        {
            $h = explode(':', $h, 2);

            if (isset($h[1]))
            {
                if (!isset($headers[$h[0]]))
                    $headers[$h[0]] = trim($h[1]);
                elseif (is_array($headers[$h[0]]))
                {
                    // $tmp = array_merge($headers[$h[0]], array(trim($h[1]))); // [-]
                    // $headers[$h[0]] = $tmp; // [-]
                    $headers[$h[0]] = array_merge($headers[$h[0]], array(trim($h[1]))); // [+]
                }
                else
                {
                    // $tmp = array_merge(array($headers[$h[0]]), array(trim($h[1]))); // [-]
                    // $headers[$h[0]] = $tmp; // [-]
                    $headers[$h[0]] = array_merge(array($headers[$h[0]]), array(trim($h[1]))); // [+]
                }

                $key = $h[0]; // [+]
            }
            else // [+]
            { // [+]
                if (substr($h[0], 0, 1) == "\t") // [+]
                    $headers[$key] .= "\r\n\t".trim($h[0]); // [+]
                elseif (!$key) // [+]
                    $headers[0] = trim($h[0]);trim($h[0]); // [+]
            } // [+]
        }

        return $headers;
    }
}

$mytory_markdown = new Mytory_Markdown;