<div class="wrap">
    <h2><?php _e('How to Migrate to Dropbox API', 'mytory-markdown') ?></h2>

    <?php
    $help_file_path = dirname(__FILE__) . '/help/how-to-migrate-' . get_user_locale() . '.md';
    if (file_exists($help_file_path)) {
        $md_content = file_get_contents($help_file_path);
    } else {
        $md_content = file_get_contents(dirname(__FILE__) . '/help/how-to-migrate-en_US.md');
    }
    $help = $this->markdown->convert($md_content);
    echo $help;
    ?>
</div>