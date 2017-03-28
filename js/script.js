jQuery(function ($) {

    $('.js-mode-button').click(function () {
        var mode = $(this).val();
        $('.js-mode').addClass('hidden');
        $('.js-mode--' + mode).removeClass('hidden');
    });
    $('.js-mode-button:checked').click();

    $('[name=mytory_md_text]').bind('blur', convert_in_text_mode);
    $('.js-convert-in-text-mode').bind('click', convert_in_text_mode);

    $('.js-update-content').click(function () {

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

    function mytory_markdown_set_content(obj) {
        if (obj.post_title) {
            $('#title').val(obj.post_title);
            $('#title-prompt-text').addClass('screen-reader-text');
        }
        if ($('#content').is(':visible')) {

            // text mode
            $('#content').val(obj.post_content);
        } else {
            $('[name="_mytory_markdown_etag"]').val(obj.etag);
            // wysiwyg mode
            if (tinymce.getInstanceById) {
                tinymce.getInstanceById('content').setContent(obj.post_content);
            } else {
                tinymce.get('content').setContent(obj.post_content);
            }
        }
    }

    function convert_in_text_mode() {
        var content = $('#mytory-md-text').val();
        $.post(wp.ajax.settings.url, {
            action: 'mytory_md_convert_in_text_mode',
            content: content
        }, function (obj) {
            mytory_markdown_set_content(obj);
        }, 'json');
    }
});