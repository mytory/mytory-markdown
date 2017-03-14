<table class="form-table">
    <tr>
        <th scope="row">Mode</th>
        <td>
            <label>
                <input class="js-mode-button" type="radio" name="mytory_md_mode"
                    <?= $md_mode == 'url' ? 'checked' : '' ?> value="url" checked> URL
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
            <input type="url" name="mytory_md_path" id="mytory-md-path" class="large-text" value="<?php echo $md_path ?>">
            <p>You can use any URL in addition to Github.</p>
        </td>
    </tr>
    <tr class="js-mode  js-mode--text  hidden">
        <th scope="row"><label for="mytory-md-text">Text</label></th>
        <td>
            <p>First line heading is set to title.</p>
            <textarea class="large-text" name="mytory_md_text" id="mytory-md-text" rows="20"><?php echo $md_text ?></textarea>
        </td>
    </tr>
    <tr class="js-mode js-mode--url hidden">
        <th><?php _e('Update', 'mytory-markdown')?></th>
        <td>
            <button type="button" class="button js-update-content"><?php _e('Update Editor Content', 'mytory-markdown')?></button>
        </td>
    </tr>
</table>
<script type="text/javascript">
    jQuery(document).ready(function($){

        $('.js-mode-button').click(function(){
            var mode = $(this).val();
            $('.js-mode').addClass('hidden');
            $('.js-mode--' + mode).removeClass('hidden');
        });
        $('.js-mode-button:checked').click();

        $('[name=mytory_md_text]').bind('keyup blur', function(){
            var content = marked($(this).val());
            var title = '';
            var obj;
            if(content.substr(0, 3) == '<h1'){
                var tmp = content.split("\n");
                title = $(tmp.shift()).text();
                content = tmp.join("\n");
            }
            obj = {
                'post_title': title,
                'post_content': content
            };
            mytory_markdown_set_content(obj);
        });

         $('.js-update-content').click(function(){

             $('.js-mytory-markdown-error-info').remove();

             var md_path = $.trim($('#mytory-md-path').val());
             if (!md_path) {
                 alert('<?php _e("Please fill the markdown file URL.", "mytory-markdown")?>');
                 $('#mytory-md-path').focus();
                 return false;
             }

             var ajax_result = $.get(wp.ajax.settings.url, {
                 action: 'mytory_md_update_editor',
                 md_path: $('#mytory-md-path').val(),
                 post_id: $('#post_ID').val()
             }, function (res) {

                 if (res.error) {
                     alert(res.error_msg);
                     return false;
                 } else {
                     mytory_markdown_set_content(res);
                 }
             }, 'json');

             ajax_result.fail(function () {
                 $('#mytory-md-path').after($('<textarea />', {
                     'html': ajax_result.responseText,
                     'class': 'large-text  js-mytory-markdown-error-info',
                     'style': 'height: 100px'
                 }));
                 alert('<?php _e("Unknown error. Please copy error text on textarea bottom of markdown plugin url input element and send to me(mytory@gmail.com). So, I can recognize the reason for the error.", "mytory-markdown") ?>');
             });
         });

        function mytory_markdown_set_content(obj){
            if (obj.post_title) {
                $('#title').val(obj.post_title);
                $('#title-prompt-text').addClass('screen-reader-text');
            }
            if ($('#content').is(':visible')) {

                // text mode
                $('#content').val(obj.post_content);
            } else {

                // wysiwyg mode
                if (tinymce.getInstanceById) {
                    tinymce.getInstanceById('content').setContent(obj.post_content);
                } else {
                    tinymce.get('content').setContent(obj.post_content);
                }
            }
        }
    });
</script>
