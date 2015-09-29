<script type="text/javascript">
    tinymce.init({
        plugins: [
            "advlist autolink lists link image charmap print preview hr anchor pagebreak",
            "searchreplace wordcount visualblocks visualchars code fullscreen",
            "insertdatetime media nonbreaking save table contextmenu directionality",
            "emoticons template paste textcolor colorpicker textpattern imagetools"
        ],
        toolbar1: "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image",
        toolbar2: "print preview media | forecolor backcolor emoticons",
        image_advtab: true,
        language: 'ru',
        selector: ".editor",
        image_title: true,
        imagetools_toolbar: 'imageoptions',
        relative_urls: false,
        remove_script_host : true,
        convert_urls : false,
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
                    url: '<?php echo URL::route('uploadIntoTemp') ?>',
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
                            $('#tempPath').val(request.tempPath);
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
</script>