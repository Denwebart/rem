<?php
    if(!isset($toolbar)) {
        $toolbar = 'undo redo | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist | link image media emoticons';
    }
?>
<script type="text/javascript">
    tinymce.init({
        plugins: [
            "advlist autolink lists link image charmap hr anchor pagebreak",
            "searchreplace wordcount visualblocks visualchars",
            "insertdatetime media nonbreaking save table contextmenu directionality",
            "emoticons template paste textcolor textpattern imagetools"
        ],
        menubar:false,
        toolbar1: "<?php echo $toolbar ?>",
        language: 'ru',
        selector: ".editor",
        image_title: true,
        imagetools_toolbar: 'imageoptions',
        image_advtab: true,
        file_browser_callback : function (field_name, url, type, win) {

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
        }
    });
</script>