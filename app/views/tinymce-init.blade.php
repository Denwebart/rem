<?php
    if(!isset($toolbar)) {
        $toolbar = 'undo redo | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist | link image media emoticons';
    }
?>
<script type="text/javascript">
    tinymce.init({
        plugins: [
            "advlist lists link autolink image",
            "wordcount",
            "media table contextmenu",
            "emoticons imagetools"
        ],
        menubar:false,
        toolbar1: "<?php echo $toolbar ?>",
        language: 'ru',
        selector: ".editor",
        image_title: true,
        imagetools_toolbar: 'imageoptions',
        image_advtab: true,
        relative_urls: false,
        remove_script_host : false,
        convert_urls : true,
        file_browser_callback : function (field_name, url, type, win) {
            console.log(type);
            if (type == 'file' || type == 'media') {
                return false;
            }

            $("input[name='editor_image']").trigger('click');

            $('#editor_image').change(function () {
                var fileData = new FormData();
                fileData.append('image', $('#editor_image')[0].files[0]);

                $.ajax({
                    type: 'POST',
                    url: '<?php echo URL::route('postUploadImage', ['path' => urlencode($imagePath)]) ?>',
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
        }
    });

//    window.onload = function() {
////        tinyMCE.activeEditor.windowManager.close(function() {
////            console.log('close');
////        });
//        $('[aria-label="Insert/edit link"]').on('click', function() {
//            console.log('asfdasf');
////            console.log($('[aria-label="Insert link"]'));
//
//            $('[aria-label="Insert link"]').find('.mce-open').remove();
//        });
//    };
</script>