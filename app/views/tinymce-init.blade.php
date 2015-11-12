<?php
    if(!isset($toolbar)) {
        $toolbar = 'undo redo | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist | link image media emoticons';
    }
    if(!isset($watermark)) {
        $watermark = 1;
    }
?>
<script type="text/javascript">
    tinymce.init({
        plugins: [
            "advlist lists link autolink image",
            "wordcount",
            "media table contextmenu",
            "emoticons imagetools",
            "paste"
        ],
        menubar:false,
        toolbar1: "<?php echo $toolbar ?>",
        language: 'ru',
        selector: ".editor",
        image_title: true,
        imagetools_toolbar: 'imageoptions',
        image_advtab: true,
        relative_urls: false,
        remove_script_host : true,
        convert_urls : false,
        paste_text_sticky: true,
        paste_text_sticky_default: true,
        file_browser_callback : function (field_name, url, type, win) {
            if (type == 'file' || type == 'media') {
                return false;
            }

            $("input[name='editor_image']").trigger('click');

            $('#editor_image').change(function () {
                var fileData = new FormData();
                fileData.append('image', $('#editor_image')[0].files[0]);
                fileData.append('tempPath', $('#tempPath').val());

                $.ajax({
                    type: 'POST',
                    url: '<?php echo URL::route('uploadIntoTemp', ['watermark' => $watermark]) ?>',
                    data: fileData,
                    processData: false,
                    contentType: false,
                    dataType: "json",
                    beforeSend: function(request) {
                        return request.setRequestHeader('X-CSRF-Token', $("meta[name='csrf-token']").attr('content'));
                    },
                    success: function(response) {
                        if(response.success) {
                            win.document.getElementById(field_name).value = response.imageUrl;
                        }
                    }
                });
            });
        },
        setup: function (editor) {
            editor.on('init', function(args) {
                editor.getDoc().body.style.fontSize = '14px';
                editor.getDoc().body.style.fontFamily = '"Open Sans", sans-serif';
                editor.getDoc().body.style.lineHeight = '1.42857';

                editor = args.target;

                editor.on('NodeChange', function(e) {
                    if (e && e.element.nodeName.toLowerCase() == 'img') {
                        width = e.element.width;
                        height = e.element.height;
                        tinyMCE.DOM.setAttribs(e.element, {'width': null, 'height': null});
                        tinyMCE.DOM.setAttribs(e.element,
                                {'style': 'width:' + width + 'px; height:' + height + 'px;'});
                    }
                });
            });
            editor.on('focus', function(e) {
                $('.editor').parent().find('.error').text('');
                $('.editor').parent().removeClass('has-error');
            });
        }
    });

    window.onload = function() {
        $('[aria-label="Insert/edit image"]').addClass('tinymce-insert-image');
        $('[aria-label="Insert/edit video"]').addClass('tinymce-insert-video');
        $('[aria-label="Insert/edit link"]').addClass('tinymce-insert-link');
    };
</script>