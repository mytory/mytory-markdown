<?php
$is_legacy_php = (phpversion() < '5.3');
?>
<div class="wrap">
    <h2><?php _e('Mytory Markdown Setting', 'mytory-markdown') ?></h2>

    <form method="post" action="options.php">
        <?php
        settings_fields('mytory-markdown-option-group');
        do_settings_sections('mytory-markdown-option-group');
        ?>

        <table class="form-table">

            <?php
            $auto_update_per = get_option('auto_update_per');
            if (empty($auto_update_per)) {
                $auto_update_per = 1;
            }
            ?>
            <tr valign="top">
                <th scope="row"><?php _e('Auto update per x visits', 'mytory-markdown') ?></th>
                <td>
                    <input class="small-text" type="number" name="auto_update_per"
                           value="<?php echo $auto_update_per ?>"/>

                    <p class="description">
                        <?php _e("This feature is for site traffic, too. If you check y to above 'auto update only when writer (or admin) visits', this feature don't be applied.",
                            'mytory-markdown'); ?>
                    </p>
                </td>
            </tr>

            <?php
            $checked = array(
                'Y' => '',
                'N' => ''
            );
            if (get_option('auto_update_only_writer_visits') == 'y') {
                $checked['Y'] = 'checked';
            } else {
                $checked['N'] = 'checked';
            }
            ?>
            <tr valign="top">
                <th scope="row"><?php _e('Auto update only when writer (or admin) visits', 'mytory-markdown') ?></th>
                <td>
                    <label>
                        <input type="radio" name="auto_update_only_writer_visits"
                               value="y" <?php echo $checked['Y'] ?> /> Y
                    </label>
                    <label>
                        <input type="radio" name="auto_update_only_writer_visits"
                               value="n" <?php echo $checked['N'] ?> /> N
                    </label>

                    <p class="description">
                        <?php _e('This feature is for site traffic.', 'mytory-markdown') ?>
                    </p>
                </td>
            </tr>

            <?php
            $manual_update = get_option('manual_update');
            if (empty($manual_update)) {
                $manual_update = 'no';
            }
            ?>
            <tr valign="top">
                <th scope="row"><?php _e('Manual update on view page', 'mytory-markdown') ?></th>
                <td>
                    <label>
                        <input type="radio" name="manual_update" id="manual_update_yes" value="yes"
                            <?php echo($manual_update == 'yes' ? 'checked' : '') ?>>
                        yes
                    </label>
                    <label>
                        <input type="radio" name="manual_update" id="manual_update_no" value="no"
                            <?php echo($manual_update == 'no' ? 'checked' : '') ?>>
                        no
                    </label>

                    <p class="description"><?php _e("Post will update only when you click update button on view page.",
                            'mytory-markdown') ?></p>
                </td>
            </tr>

            <?php
            $debug_msg = get_option('debug_msg');
            if (empty($debug_msg)) {
                $debug_msg = 'no';
            }
            ?>
            <tr valign="top">
                <th scope="row"><?php _e('Print Debug Message on post/page', 'mytory-markdown') ?></th>
                <td>
                    <label>
                        <input type="radio" name="debug_msg" id="debug_msg_yes" value="yes"
                            <?php echo($debug_msg == 'yes' ? 'checked' : '') ?>>
                        yes
                    </label>
                    <label>
                        <input type="radio" name="debug_msg" id="debug_msg_no" value="no"
                            <?php echo($debug_msg == 'no' ? 'checked' : '') ?>>
                        no
                    </label>

                    <p class="description"><?php _e("Of course, it doesn't be showed normal users.",
                            'mytory-markdown') ?></p>
                </td>
            </tr>
            <tr valign="top">
                <th scope="row"><?php _e('Markdown Engine', 'mytory-markdown') ?></th>
                <td>
                    <p>
                        <label>
                            <input type="radio" name="mytory_markdown_engine" value="parsedown"
                                <?= (get_option('mytory_markdown_engine') == 'parsedown') ? 'checked' : '' ?>
                                <?= $is_legacy_php ? 'disabled' : '' ?>>
                            Parsedown
                        </label>
                    </p>

                    <p class="description">
                        <?php
                        if ($is_legacy_php) {
                            echo sprintf(__('Your PHP version is %s, so cannot use Parsedown.'), phpversion());
                            echo '<br>';
                        } ?>
                        <?php _e('GitHub Flavored.', 'mytory-markdown') ?>
                        <?php _e('PHP 5.3 or later.', 'mytory-markdown') ?>
                        <a target="_blank" href="http://parsedown.org/">Website</a>
                    </p>

                    <hr>

                    <p>
                        <label>
                            <input type="radio" name="mytory_markdown_engine" value="parsedownExtra"
                                <?= (get_option('mytory_markdown_engine') == 'parsedownExtra') ? 'checked' : '' ?>
                                <?= $is_legacy_php ? 'disabled' : '' ?>>
                            ParsedownExtra
                        </label>
                    </p>

                    <p class="description">
                        <?php
                        if ($is_legacy_php) {
                            echo sprintf(__('Your PHP version is %s, so cannot use ParsedownExtra.'), phpversion());
                            echo '<br>';
                        } ?>
                        <?php _e('An extension of Parsedown that adds support for Markdown Extra.', 'mytory-markdown') ?>
                        <?php _e('PHP 5.3 or later.', 'mytory-markdown') ?>
                        <a target="_blank" href="http://parsedown.org/extra/">Website</a>
                    </p>

                    <hr>

                    <p>
                        <label>
                            <input type="radio" name="mytory_markdown_engine" value="markdownExtra"
                                <?= (get_option('mytory_markdown_engine') == 'markdownExtra') ? 'checked' : '' ?>>
                            php Markdown Extra classic version
                        </label>
                    </p>

                    <p class="description">
                        <?php _e('It works with PHP 4.0.5 or later. <strong>This version is no longer supported since February 1, 2014.</strong>', 'mytory-markdown') ?>
                        <a target="_blank" href="https://michelf.ca/projects/php-markdown/extra/">Website</a>
                    </p>

                    <hr>

                    <p><?= sprintf(__('Your PHP version is %s', 'mytory-markdown'), phpversion()) ?></p>

                </td>
            </tr>
        </table>
        <p><a href="http://wordpress.org/support/view/plugin-reviews/mytory-markdown">
                <?php _e('If you like this plugin, please rate on wordpress plugin site.', 'mytory-markdown'); ?>
            </a></p>
        <p>
            <a href="https://mytory.net/paypal-donation/">
                <?php _e('If you like this plugin, please donate :)', 'mytory-markdown'); ?>
            </a>
        </p>

        <?php
        submit_button();
        ?>
    </form>

    <p>
        <?php _e('Please let me know about bugs, your ideas, etc!', 'mytory-markdown') ?>
        <a href="mailto:mail@mytory.net">Email</a>
        |
        <a target="_blank" href="https://twitter.com/mytory">Twitter</a>
        |
        <a target="_blank" href="https://github.com/mytory/mytory-markdown-for-dropbox/issues">GitHub</a>
    </p>
    <p>
        <a target="_blank" href="https://wordpress.org/support/plugin/mytory-markdown-for-dropbox/reviews/">
            <?php _e('Please Rate and Review', 'mytory-markdown') ?>
        </a>
        |
        <a target="_blank"
           href="https://www.paypal.com/cgi-bin/webscr?cmd=_donations&business=QUWVEWJ3N7M4W&lc=GA&item_name=Mytory%20Markdown&currency_code=USD&bn=PP%2dDonationsBF%3abtn_donate_SM%2egif%3aNonHosted">
            <?php _e('Donate', 'mytory-markdown') ?>
        </a>
    </p>
</div>
<script type="text/javascript">
    jQuery('[name=manual_update], [name=auto_update_only_writer_visits]').on('change, click', mm_setting_dependency);
    mm_setting_dependency();
    function mm_setting_dependency() {
        var $ = jQuery;

        // If manual update is on, rest settings are not needed.
        if ($('[name=manual_update]:checked').val() == 'yes') {
            $('[name=auto_update_only_writer_visits], [name=auto_update_per]').parents('tr').hide();
        } else {
            $('[name=auto_update_only_writer_visits], [name=auto_update_per]').parents('tr').show();

            // If auto update only writer visits, auto update per setting is not needed.
            if ($('[name=auto_update_only_writer_visits]:checked').val() == 'y') {
                $('[name=auto_update_per]').parents('tr').hide();
            } else {
                $('[name=auto_update_per]').parents('tr').show();
            }
        }


    }
</script>