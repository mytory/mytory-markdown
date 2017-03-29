<?php

/*
Plugin Name: Mytory Markdown
Description: The plugin get markdown file URL like github raw content url. It convert markdown file to html, and put it to post content. You can directly write markdown in editing page.
Author: mytory
Version: 1.6.3
Author URI: https://mytory.net
*/

class Mytory_Markdown
{
    public $version = '1.6.0';
    protected $error = array(
        'status' => false,
        'msg' => '',
    );

    protected $post;
    protected $worked;
    protected $debug_msg = array();
    public $markdown;
    public $hasDropboxPublicLink;

    function __construct()
    {
        add_action('plugins_loaded', array(&$this, 'plugin_init'));
        if (get_option('manual_update') != 'yes') {
            add_action('pre_get_posts', array(&$this, 'conditional_apply_markdown'));
        } else {
            add_filter('the_content', array(&$this, 'manual_update_button'));
        }
        if (isset($_POST['mytory_markdown_manual_update'])
            && $_POST['mytory_markdown_manual_update'] == 'do'
        ) {
            add_action('pre_get_posts', array(&$this, 'apply_markdown'));
        }
        add_filter('the_content', array(&$this, 'attach_error_msg'));
        add_action('add_meta_boxes', array(&$this, 'register_meta_box'));
        add_action('save_post', array(&$this, 'update_post'));
        add_action('wp_ajax_mytory_md_update_editor', array(&$this, 'get_post_content_ajax'));
        add_action('wp_ajax_mytory_md_convert_in_text_mode', array(&$this, 'convert_in_text_mode'));
        add_action('admin_menu', array(&$this, 'addMenu'));
        add_action('admin_init', array(&$this, 'register_settings'));
        add_action('admin_enqueue_scripts', array(&$this, 'enqueue_scripts'));

        $this->initMarkdownObject();
        $this->setAboutDropboxPublicLink();
    }

    function enqueue_scripts($hook)
    {
        if (!in_array($hook, array('post.php', 'post-new.php'))) {
            return;
        }
        wp_enqueue_script('mytory-markdown-script', plugins_url('js/script.js', __FILE__), array('jquery'),
            $this->version, true);
    }

    function plugin_init()
    {
        load_plugin_textdomain('mytory-markdown', false, dirname(plugin_basename(__FILE__)) . '/lang');
    }

    function conditional_apply_markdown($query)
    {
        if ($this->worked == true) {
            return null;
        }
        $this->worked = true;

        $auto_update_only_writer_visits = get_option('auto_update_only_writer_visits');

        if ($auto_update_only_writer_visits == 'y' AND !current_user_can('edit_posts')) {
            $this->debug_msg[] = "Auto update only writer or admin visits is Y and current user can't edit posts. So don't work.";
            return null;
        }

        $this->apply_markdown($query);
    }

    /**
     * apply markdown on pre_get_posts
     * @param $query
     * @return string
     */
    public function apply_markdown($query)
    {

        $auto_update_only_writer_visits = get_option('auto_update_only_writer_visits');

        ini_set('memory_limit', -1);

        ob_start();
        echo "<pre>";
        var_dump($query->query_vars);
        echo "</pre>";
        $this->debug_msg[] = ob_get_contents();
        ob_end_clean();

        if ($query->query_vars['p']) {
            // post인 경우
            $this->post = get_post($query->query_vars['p']);
            $this->debug_msg[] = "This is post.";

        } else {
            if ($query->query_vars['page_id']) {
                // page인 경우
                $this->post = get_post($query->query_vars['page_id']);
                $this->debug_msg[] = "This is page.";

            } else {
                if ($query->query_vars['pagename'] OR $query->query_vars['name']) {

                    // page인 경우 OR slug 형태 주소인 경우.
                    $slug = ($query->query_vars['pagename'] ? $query->query_vars['pagename'] : $query->query_vars['name']);
                    $posts = get_posts(array('post_type' => 'any', 'name' => $slug));
                    $this->debug_msg[] = "This is page or slug type permalink. Continue.";

                    if (isset($posts[0])) {
                        $this->post = $posts[0];
                    } else {
                        $this->debug_msg[] = "There is not post/page that has slug '{$slug}'. So don't work.";
                        return null;
                    }

                } else {
                    // post도 page도 아닌 경우
                    $this->debug_msg[] = "This is not post/page. So don't work.";
                    return null;
                }
            }
        }

        // 'Auto update per x visits' feature work only when 'Auto update only writer visits' feature disabled.
        if ($auto_update_only_writer_visits != 'y') {

            // Auto update per x visits.
            $auto_update_per = get_option('auto_update_per');
            if ($auto_update_per !== false) {
                $visits_count = get_post_meta($this->post->ID, 'mytory_md_visits_count', true);
                if (!$visits_count) {
                    $visits_count = 0;
                }
                update_post_meta($this->post->ID, 'mytory_md_visits_count', $visits_count + 1);
                $visits_count++;
                if ($visits_count % $auto_update_per !== 0) {
                    $this->debug_msg[] = "'Auto update per' option is enabled. And count is not full. So don't work.";
                    return null;
                }
            }
        }

        $markdown_path = get_post_meta($this->post->ID, 'mytory_md_path', true);

        if (!$markdown_path) {
            $this->debug_msg[] = "This don't has markdown path. So don't work.";
            return null;
        }

        if ($this->needToUpdate($markdown_path)) {

            $md_post = $this->_get_post($markdown_path);

            if ($this->error['status'] === true) {
                if (current_user_can('edit_posts')) {
                    return "<p>{$this->error['msg']}</p>" . $md_post['post_content'];
                } else {
                    return $md_post['post_content'];
                }
            } else {
                $postarr = array(
                    'ID' => $this->post->ID,
                    'post_title' => $md_post['post_title'],
                    'post_content' => $md_post['post_content'],
                );
                if (wp_update_post($postarr)) {
                    update_post_meta($this->post->ID, '_mytory_markdown_etag', $this->getEtag($markdown_path));
                }
            }
        } else {
            $this->debug_msg[] = "Etag was not changed. So content has not been updated.";
        }
        return null;
    }

    /**
     * if error occurred, attach error message to post content.
     * @param $post_content
     * @return string
     */
    public function attach_error_msg($post_content)
    {
        if ($this->error['status'] === true AND current_user_can('edit_posts')) {
            $post_content = "<p>{$this->error['msg']}</p>" . $post_content;
        }
        if (!empty($this->debug_msg) AND current_user_can('edit_posts') AND get_option('debug_msg') == 'yes') {
            $debug = '<ul>';
            foreach ($this->debug_msg as $msg) {
                $debug .= "<li>mytory markdown debug: {$msg}</li>";
            }
            $debug .= "</ul>";
            $post_content = $debug . $post_content;
        }

        return $post_content;
    }

    /**
     * get html converted from markdown file path.
     * if error occur, return false.
     * @param $markdown_path
     * @return boolean | string
     */
    private function _get_post($markdown_path)
    {
        $md_content = $this->_file_get_contents($markdown_path);

        if ($md_content === false) {
            return false;
        }

        $post = $this->_convert_md_to_post($md_content);

        return $post;
    }

    public function get_post_content_ajax()
    {

        ini_set('display_errors', 1);
        error_reporting(E_ERROR | E_WARNING);

        $md_path = $_REQUEST['md_path'];

        $etag_new = $this->getEtag($md_path);

        if (!$etag_new) {
            $res = array(
                'error' => true,
                'error_msg' => $this->error['msg'],
                'post_title' => 'error',
                'post_content' => 'error',
                'curl_info' => $this->error['curl_info'],
            );
            echo json_encode($res);
            die();
        }

        $md_post = $this->_get_post($md_path);

        if (!$md_post) {
            // 에러
            $res = array(
                'error' => true,
                'error_msg' => $this->error['msg'],
                'post_title' => 'error',
                'post_content' => 'error',
            );
        } else {
            $res = array(
                // 성공
                'error' => false,
                'error_msg' => '',
                'post_title' => $md_post['post_title'],
                'post_content' => $md_post['post_content'],
                'etag' => $etag_new,
            );
        }
        echo json_encode($res);
        die();
    }

    /**
     * This function use etag in http header.
     * If etag change, need to update.
     * @param  string $url
     * @return boolean
     */
    private function needToUpdate($url)
    {
        $post = $this->post;

        if (empty($post)) {
            return false;
        }

        $etag_saved = get_post_meta($post->ID, '_mytory_markdown_etag', true);

        if ($etag_saved) {
            // If the post has etag saved, determine.
            // If the post hasn't etag saved, need to save.
            $etag_remote = $this->getEtag($url);

            // if remote has not an etag, don't need to save.
            if ($etag_remote === null) {
                return false;
            }

            // if etag different each other, need to save
            return ($etag_saved != $etag_remote);
        } else {
            // if no cache, need to save
            return true;
        }
    }

    /**
     * get etag from url
     * @param  string $url
     * @return string
     */
    private function getEtag($url)
    {
        $header = $this->_get_header_from_url($url);
        $header = $this->_http_parse_headers($header);

        foreach ($header as $key => $value) {
            $key_lower = strtolower($key);
            $header[$key_lower] = $value;
        }

        if (!empty($header['etag'])) {
            return $header['etag'];
        } else {
            return null;
        }
    }

    /**
     * get header from url
     * @param  string $url dropbox public url
     * @return string
     */
    private function _get_header_from_url($url)
    {
        if (!function_exists('curl_init')) {
            $this->error = array(
                'status' => true,
                'msg' => 'Mytory Markdown plugin need PHP cURL module. But, your Server has not the module. So you cannot use this plugin. I\'m Sorry. If you can, request to install cURL module to your hosting service. Common hosting service provides cURL module.',
            );
            return false;
        }
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_HEADER, true);
        curl_setopt($curl, CURLOPT_NOBODY, true);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array('Cache-Control: no-cache'));
        curl_setopt($curl, CURLOPT_FRESH_CONNECT, true);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        if (!ini_get('open_basedir')) {
            curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
        }
        $header = curl_exec($curl);

        if (!$this->_check_curl_error($curl)) {
            return false;
        }

        return $header;
    }

    /**
     * get contents form url
     * @param  string $url dropbox public url
     * @return string
     */
    private function _file_get_contents($url)
    {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_HEADER, false);
        curl_setopt($curl, CURLOPT_NOBODY, false);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array('Cache-Control: no-cache'));
        curl_setopt($curl, CURLOPT_FRESH_CONNECT, true);
        if (!ini_get('open_basedir')) {
            curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
        }
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        $content = curl_exec($curl);

        if (!$this->_check_curl_error($curl)) {
            return false;
        }

        return $content;
    }

    private function _check_curl_error($curl)
    {
        $curl_info = curl_getinfo($curl);
        if ($curl_info['http_code'] != '200') {
            $this->error = array(
                'status' => true,
                'msg' => __('Network Error! HTTP STATUS is ', 'mytory-markdown') . $curl_info['http_code'],
            );
            if ($curl_info['http_code'] == '404') {
                $this->error['msg'] = 'Incorrect URL. File not found.';
            }
            if ($curl_info['http_code'] == 0) {
                $this->error['msg'] = __('Network Error! Maybe, connection error.', 'mytory-markdown');
            }
            $this->error['curl_info'] = $curl_info;
            return false;
        }
        return true;
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

        foreach (explode("\n", $raw_headers) as $i => $h) {
            $h = explode(':', $h, 2);

            if (isset($h[1])) {
                if (!isset($headers[$h[0]])) {
                    $headers[$h[0]] = trim($h[1]);
                } elseif (is_array($headers[$h[0]])) {
                    // $tmp = array_merge($headers[$h[0]], array(trim($h[1]))); // [-]
                    // $headers[$h[0]] = $tmp; // [-]
                    $headers[$h[0]] = array_merge($headers[$h[0]], array(trim($h[1]))); // [+]
                } else {
                    // $tmp = array_merge(array($headers[$h[0]]), array(trim($h[1]))); // [-]
                    // $headers[$h[0]] = $tmp; // [-]
                    $headers[$h[0]] = array_merge(array($headers[$h[0]]), array(trim($h[1]))); // [+]
                }

                $key = $h[0]; // [+]
            } else // [+]
            { // [+]
                if (substr($h[0], 0, 1) == "\t") // [+]
                {
                    $headers[$key] .= "\r\n\t" . trim($h[0]);
                } // [+]
                elseif (!$key) // [+]
                {
                    $headers[0] = trim($h[0]);
                }
                trim($h[0]); // [+]
            } // [+]
        }

        return $headers;
    }

    function register_meta_box()
    {
        add_meta_box(
            'mytory-markdown-path',
            __('Markdown File Path', 'mytory-markdown'),
            array(&$this, 'meta_box_inner')
        );
    }

    function meta_box_inner()
    {
        $md_path = '';
        $md_mode = 'url';
        $md_text = '';
        $md_etag = '';
        if (isset($_GET['post'])) {
            $md_path = get_post_meta($_GET['post'], 'mytory_md_path', true);
            $md_mode = get_post_meta($_GET['post'], 'mytory_md_mode', true);
            $md_text = get_post_meta($_GET['post'], 'mytory_md_text', true);
            $md_etag = get_post_meta($_GET['post'], '_mytory_markdown_etag', true);
        }
        include 'meta-box.php';
    }

    function update_post($post_id)
    {
        if (!current_user_can('edit_post', $post_id)) {
            return null;
        }

        // 데이터 저장
        if (isset($_POST['mytory_md_path'])) {
            update_post_meta($post_id, 'mytory_md_path', $_POST['mytory_md_path']);
            update_post_meta($post_id, 'mytory_md_text', $_POST['mytory_md_text']);
            update_post_meta($post_id, 'mytory_md_mode', $_POST['mytory_md_mode']);
            update_post_meta($post_id, '_mytory_markdown_etag', $_POST['_mytory_markdown_etag']);
        }
    }

    function register_settings()
    { // whitelist options
        if (!current_user_can('activate_plugins')) {
            return null;
        }
        register_setting('mytory-markdown-option-group', 'auto_update_only_writer_visits');
        register_setting('mytory-markdown-option-group', 'auto_update_per');
        register_setting('mytory-markdown-option-group', 'debug_msg');
        register_setting('mytory-markdown-option-group', 'manual_update');
        register_setting('mytory-markdown-option-group', 'mytory_markdown_engine');
    }

    function addMenu()
    {
        if (!current_user_can('activate_plugins')) {
            return null;
        }
        add_submenu_page('options-general.php', 'Mytory Markdown: ' . __('Settings', 'mytory-markdown'),
            'Mytory Markdown: <span style="white-space: nowrap;">' . __('Settings', 'mytory-markdown') . '</span>',
            'activate_plugins', 'mytory-markdown',
            array(&$this, 'print_setting_page'));

        add_submenu_page('options-general.php', 'Mytory Markdown: ' . __('URL Batch replace', 'mytory-markdown'),
            'Mytory Markdown: ' . __('URL Batch replace', 'mytory-markdown'),
            'activate_plugins', 'mytory-markdown-batch-update',
            array(&$this, 'print_batch_update_page'));
    }

    function print_setting_page()
    {
        include "setting.php";
    }

    function get_posts_has_md_path()
    {
        $the_query = new WP_Query(array(
            'posts_per_page' => -1,
            'meta_query' => array(
                array(
                    'key' => 'mytory_md_path',
                    'value' => '',
                    'compare' => '!=',
                ),
            ),
        ));
        return $the_query->posts;
    }

    function print_batch_update_page()
    {
        // 실행 취소한 경우
        if (!empty($_GET['action']) and $_GET['action'] == 'undo') {
            foreach ($this->get_posts_has_md_path() as $post) {
                $old = get_post_meta($post->ID, 'mytory_md_path_old', true);
                if (!$old) {
                    continue;
                }
                update_post_meta($post->ID, 'mytory_md_path', $old);
                delete_post_meta($post->ID, 'mytory_md_path_old');
            }
            $message = __('Undo', 'mytory-markdown') . ' ' . __('Complete.', 'mytory-markdown');
        }

        if (!empty($_POST)) {
            $change_from = $_POST['change_from'];
            $change_to = $_POST['change_to'];

            foreach ($this->get_posts_has_md_path() as $post) {
                $md_path = get_post_meta($post->ID, 'mytory_md_path', true);
                $new_md_path = str_replace($change_from, $change_to, $md_path);
                update_post_meta($post->ID, 'mytory_md_path_old', $md_path);
                update_post_meta($post->ID, 'mytory_md_path', $new_md_path);
            }
            $message = __('Complete.', 'mytory-markdown');
        }
        include "batch.php";
    }

    function manual_update_button($post_content)
    {
        global $post;

        if (!current_user_can('edit_post', get_the_ID())
            or !get_post_meta($post->ID, 'mytory_md_path', true)
            or get_post_meta($post->ID, 'mytory_md_mode', 'text')
        ) {
            return $post_content;
        }

        ob_start();
        ?>
        <form style="margin: 1em 0; text-align: center" method="post">
            <input type="hidden" name="mytory_markdown_manual_update" value="do">
            <input type="submit" value="<?php _e('Manual Update with Mytory Markdown', 'mytory-markdown') ?>">
        </form>
        <?php
        $manual_update_button_html = ob_get_contents();
        ob_end_clean();

        return $manual_update_button_html . $post_content . $manual_update_button_html;
    }

    /**
     * When it use text mode.
     */
    function convert_in_text_mode()
    {
        // Because Wordpress escape all $_POST variable. So, stripslashes again.
        $_POST = stripslashes_deep($_POST);

        $post = $this->_convert_md_to_post($_POST['content']);
        $response = array(
            'error' => false,
            'error_msg' => '',
            'post_title' => $post['post_title'],
            'post_content' => $post['post_content'],
            'raw' => $_POST['content'],
        );
        echo json_encode($response);
        die();
    }

    /**
     * @param $md_content
     * @return array
     */
    private function _convert_md_to_post($md_content)
    {
        $content = $this->markdown->convert($md_content);
        $post = array();
        $matches = array();
        preg_match('/<h1>(.*)<\/h1>/', $content, $matches);
        if (!empty($matches)) {
            $post['post_title'] = $matches[1];
        } else {
            $post['post_title'] = '';
        }
        $post['post_content'] = preg_replace('/<h1>(.*)<\/h1>/', '', $content, 1);
        return $post;
    }

    private function initMarkdownObject()
    {
        if (!get_option('mytory_markdown_engine')) {
            update_option('mytory_markdown_engine', 'markdownExtra');
        }

        switch (get_option('mytory_markdown_engine')) {
            case 'parsedown':
                include 'MMParsedown.php';
                $this->markdown = new MMParsedown;
                break;
            case 'parsedownExtra':
                include 'MMParsedownExtra.php';
                $this->markdown = new MMParsedownExtra;
                break;
            case 'markdownExtra':
                include 'MMMarkdownExtra.php';
                $this->markdown = new MMMarkdownExtra;
                break;
            default:
                include 'MMMarkdownExtra.php';
                $this->markdown = new MMMarkdownExtra;
            // pass through
        }
    }

    function alertHowToMigrate() {
        ?>
        <div class="notice  notice-warning  is-dismissible">
            <p><?= sprintf(__( '<strong>Mytory Markdown: </strong> You can migrate Dropbox Public link to Dropbox API. <a href="%s">See ‘how to’ description.</a>', 'mytory-markdown'), menu_page_url('mytory-markdown-how-to-migrate', false)); ?></p>
        </div>
        <?php
    }

    private function setAboutDropboxPublicLink()
    {
        global $wpdb;
        $results = $wpdb->get_results("select count(*) count from {$wpdb->prefix}postmeta where meta_value like '%dropboxusercontent%' and meta_key = 'mytory_md_path' limit 1");
        if ($results[0]->count > 0) {
            $this->hasDropboxPublicLink = true;
            if (!empty($_GET['page']) and $_GET['page'] != 'mytory-markdown-how-to-migrate') {
                add_action('admin_notices', array(&$this, 'alertHowToMigrate'));
            }
            add_action('admin_menu', array(&$this, 'addMenuHowToMigrate'));
        }
    }

    function addMenuHowToMigrate()
    {
        add_submenu_page(
            'options-general.php',
            'Mytory Markdown: ' . __('How to Migrate to Dropbox API', 'mytory-markdown'),
            'Mytory Markdown: ' . __('How to Migrate to Dropbox API', 'mytory-markdown'),
            'activate_plugins', 'mytory-markdown-how-to-migrate',
            array(&$this, 'printHowToMigrate')
        );
    }

    function printHowToMigrate()
    {
        include 'how-to-migrate.php';
    }
}

$mytory_markdown = new Mytory_Markdown;
