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
            <th scope="row"><?php _e('Auto update only when writer visits', 'mytory-markdown')?></th>
            <td>
                <label>
                    <input type="radio" name="auto_update_only_writer_visits" value="y" <?php echo $checked['Y'] ?> /> Y
                </label>
                <label>
                    <input type="radio" name="auto_update_only_writer_visits" value="n" <?php echo $checked['N'] ?> /> N
                </label>
            </td>
        </tr>
        
        <?php
        $check_update_per_visits = get_option('check_update_per_visits');
        if(empty($check_update_per_visits)){
            $check_update_per_visits = 1;
        }
        ?>
        <!-- <tr valign="top">
            <th scope="row"><?php _e('Check update per visits', 'mytory-markdown')?></th>
            <td>
                <input class="small-text" type="number" name="check_update_per_visits" value="<?php echo $check_update_per_visits ?>" />
                <p class="description">
                    If you check y to above 'auto update only when writer visits', this item don't be applied.
                </p>
            </td>
        </tr> -->
    </table>
    <p><a href="http://wordpress.org/support/view/plugin-reviews/mytory-markdown">If you like this plugin, please rate on wordpress plugin site.</a></p>

    <?php
    submit_button();
    ?>
</form>
</div>