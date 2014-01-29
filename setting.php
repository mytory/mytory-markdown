<div class="wrap">
<h2><?php _e('Mytory Markdown Setting', 'mytory-markdown')?></h2>
<form method="post" action="options.php"> 
    <?php 
    settings_fields('mytory-markdown-option-group');
    do_settings_sections( 'mytory-markdown-option-group' );
    ?>

    <table class="form-table">
        <?php
        $checked = array(
            'Y' => '',
            'N' => ''
        );
        if(get_option('auto_update_only_writer_visits') == 'y'){
            $checked['Y'] = 'checked';
        }else{
            $checked['N'] = 'checked';
        }
        ?>
        <tr valign="top">
            <th scope="row"><?php _e('Auto update only when writer (or admin) visits', 'mytory-markdown')?></th>
            <td>
                <label>
                    <input type="radio" name="auto_update_only_writer_visits" value="y" <?php echo $checked['Y'] ?> /> Y
                </label>
                <label>
                    <input type="radio" name="auto_update_only_writer_visits" value="n" <?php echo $checked['N'] ?> /> N
                </label>
                <p class="description">
                    <?php _e('This feature is for site traffic.', 'mytory-markdown') ?>
                </p>
            </td>
        </tr>
        
        <?php
        $auto_update_per = get_option('auto_update_per');
        if(empty($auto_update_per)){
            $auto_update_per = 1;
        }
        ?>
        <tr valign="top">
            <th scope="row"><?php _e('Auto update per x visits', 'mytory-markdown')?></th>
            <td>
                <input class="small-text" type="number" name="auto_update_per" value="<?php echo $auto_update_per ?>" />
                <p class="description">
                    <?php _e("This feature is for site traffic, too. If you check y to above 'auto update only when writer (or admin) visits', this feature don't be applied.", 'mytory-markdown'); ?>
                </p>
            </td>
        </tr>
        <?php
        $debug_msg = get_option('debug_msg');
        if(empty($debug_msg)){
            $debug_msg = 'no';
        }
        ?>
        <tr valign="top">
            <th scope="row"><?php _e('Print Debug Message on post/page', 'mytory-markdown')?></th>
            <td>
                <label>
                    <input type="radio" name="debug_msg" id="debug_msg_yes" value="yes"
                        <?php echo ($debug_msg == 'yes' ? 'checked' : '')?>>
                    yes
                </label>
                <label>
                    <input type="radio" name="debug_msg" id="debug_msg_no" value="no"
                        <?php echo ($debug_msg == 'no' ? 'checked' : '')?>>
                    no
                </label>
                <p class="description"><?php _e("Of course, it doesn't be showed normal users.", 'mytory-markdown') ?></p>
            </td>
        </tr>
    </table>
    <p><a href="http://wordpress.org/support/view/plugin-reviews/mytory-markdown">If you like this plugin, please rate on wordpress plugin site.</a></p>

    <?php
    submit_button();
    ?>
</form>
</div>