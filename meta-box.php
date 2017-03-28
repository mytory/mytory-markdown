<table class="form-table">
    <tr>
        <th scope="row">Mode</th>
        <td>
            <label>
                <input class="js-mode-button" type="radio" name="mytory_md_mode"
                    <?= in_array($md_mode, array('url', '', null)) ? 'checked' : '' ?> value="url"> URL
            </label>
            <label>
                <input class="js-mode-button" type="radio" name="mytory_md_mode" value="text"
                    <?= $md_mode == 'text' ? 'checked' : '' ?>> Text
            </label>
        </td>
    </tr>
    <tr class="js-mode  js-mode--url  hidden">
        <th scope="row"><label for="mytory-md-path">URL</label></th>
        <td>
            <input type="url" name="mytory_md_path" id="mytory-md-path" class="large-text"
                   title="<?php esc_attr_e(__("You can use any URL in addition to Github.", 'mytory-markdown')) ?>"
                   value="<?php echo $md_path ?>">
            <input type="hidden" name="_mytory_markdown_etag" title="etag" value="<?= esc_attr($md_etag) ?>">
        </td>
    </tr>
    <tr class="js-mode  js-mode--text  hidden">
        <th scope="row"><label for="mytory-md-text">Text</label></th>
        <td>
            <p>First line heading is set to title.</p>
            <textarea class="large-text" name="mytory_md_text" id="mytory-md-text"
                      rows="20"><?php echo $md_text ?></textarea>
            <p><button type="button" class="button js-convert-in-text-mode"><?php _e('Update Editor Content',
                        'mytory-markdown') ?></button></p>
        </td>
    </tr>
    <tr class="js-mode js-mode--url hidden">
        <th><?php _e('Update', 'mytory-markdown') ?></th>
        <td>
            <button type="button" class="button js-update-content"><?php _e('Update Editor Content',
                    'mytory-markdown') ?></button>
        </td>
    </tr>
</table>

<p>
    <?php _e('Please let me know about bugs, your ideas, etc!', 'mytory-markdown') ?>
    <a href="mailto:mail@mytory.net">Email</a>
    |
    <a target="_blank" href="https://twitter.com/mytory">Twitter</a>
    |
    <a target="_blank" href="https://github.com/mytory/mytory-markdown/issues">GitHub</a>
</p>
<p>
    <a target="_blank" href="https://wordpress.org/support/plugin/mytory-markdown/reviews/">
        <?php _e('Please Rate and Review', 'mytory-markdown') ?>
    </a>
    |
    <a target="_blank"
       href="https://www.paypal.com/cgi-bin/webscr?cmd=_donations&business=QUWVEWJ3N7M4W&lc=GA&item_name=Mytory%20Markdown&currency_code=USD&bn=PP%2dDonationsBF%3abtn_donate_SM%2egif%3aNonHosted">
        <?php _e('Donate', 'mytory-markdown') ?>
    </a>
</p>
