<table class="form-table">
    <tr>
        <th scope="row">URL</th>
        <td>
            <input type="url" name="mytory_md_path" id="mytory-md-path" class="large-text" value="<?php echo $markdown_path?>">
            <p class="js-dropbox-public-link-alert description" style="display: none">
                URL may not be dropbox 'public link'. If you copy 'share link', it will not work.
                You have to copy 'public link' from file in Public (or its child) folder. 
                If you don't have Public folder, <a href="https://www.dropbox.com/enable_public_folder">please enable public folder with this link.</a>
            </p>
            <p class="js-dropbox-public-link-alert-ko_KR description" style="display: none">
                드롭박스 '링크 공유'로 복사한 링크를 넣은 것 같습니다. 그게 아니라 '공개 링크 복사'를 이용하셔야 합니다.
                '공개 링크'는 Public 폴더 아래 있는 파일과 폴더에서 복사할 수 있습니다.
                만약 Public 폴더가 없으면 <a href="https://www.dropbox.com/enable_public_folder">이 링크에서 Public 폴더를 활성화하세요.</a>
            </p>
        </td>
    </tr>
    <tr>
        <th><?php _e('Update', 'mytory-markdown')?></th>
        <td>
            <button type="button" class="button js-update-content"><?php _e('Update Editor Content', 'mytory-markdown')?></button>
        </td>
    </tr>
</table>
<script type="text/javascript">
    jQuery(document).ready(function($){
         $('.js-update-content').click(function(){

            var md_path = $.trim($('#mytory-md-path').val());
            if( ! md_path){
                alert('<?php _e("Please fill the markdown file URL.", "mytory-markdown")?>');
                $('#mytory-md-path').focus();
                return false;
            }

            if(/dl\.dropboxusercontent\.com/.test(md_path) == false){
                $('<?php _e('.js-dropbox-public-link-alert', 'mytory-markdown') ?>').show();
            }else{
                $('<?php _e('.js-dropbox-public-link-alert', 'mytory-markdown') ?>').hide();
            }

            var ajax_result = $.get(wp.ajax.settings.url, {
                action: 'mytory_md_update_editor',
                md_path: $('#mytory-md-path').val(),
                post_id: $('#post_ID').val()
            }, function(res){

                if(res.error){
                    alert(res.error_msg);
                    return false;
                }else{
                    if(res.post_title){
                        $('#title').val(res.post_title).focus().blur();
                    }
                    if($('#content').is(':visible')){

                        // text mode
                        $('#content').val(res.post_content);
                    }else{

                        // wysiwyg mode
                        if(tinymce.getInstanceById){
                            tinymce.getInstanceById('content').setContent(res.post_content); 
                        }else{
                            tinymce.get('content').setContent(res.post_content); 
                        }
                    }
                }
            }, 'json');

            ajax_result.fail(function(){
                $('#mytory-md-path').after($('<textarea />', {
                    'html': ajax_result.responseText,
                    'class': 'large-text',
                    'style': 'height: 100px'
                }));
                alert('<?php _e("Unknown error. Please copy error text on textarea bottom of markdown plugin url input element and send to me(mytory@gmail.com). So, I can recognize the reason for the error.", "mytory-markdown") ?>');
            });
        });
    });
</script>
