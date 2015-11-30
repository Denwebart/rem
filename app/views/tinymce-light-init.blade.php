<?php
    if(!isset($toolbar)) {
        $toolbar = 'undo redo | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist | link image media emoticons';
    }
?>
<script type="text/javascript">
    tinymce.init({
        plugins: [
            "link image",
            "emoticons",
            "paste"
        ],
        menubar:false,
        toolbar: false,
        language: 'ru',
        selector: ".editor-light",
        relative_urls: false,
        remove_script_host : true,
        convert_urls : false,
        paste_text_sticky: true,
        paste_text_sticky_default: true,
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