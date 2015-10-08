(function($) {
    "use strict";

    var newImage = '';
    if (typeof(plupload) !== 'undefined') {
        var uploader = new plupload.Uploader(ajax_vars.plupload);
        uploader.init();
        uploader.bind('FilesAdded', function(up, files) {

            $.each(files, function(i, file) {

                $('#aaiu-upload-imagelist-plans').append(
                    '<div id="' + file.id + '" style="margin-bottom:5px;font-size:12px;">' +
                    file.name + ' (' + plupload.formatSize(file.size) + ') <div></div>' +
                    '</div>');

            });

            up.refresh(); // Reposition Flash/Silverlight
            uploader.start();
        });

        uploader.bind('UploadProgress', function(up, file) {
            $('#' + file.id + " div").html('<div class="progress progress-sm progress-striped active" style="margin-top:5px;">' + 
                '<div class="progress-bar progress-bar-green" role="progressbar" aria-valuenow="'+ file.percent +'" aria-valuemin="0" aria-valuemax="100" style="width: ' + file.percent + '%" data-toggle="tooltip" title="'+ file.percent +'">' + 
                '</div>' + 
            '</div>');
        });

        // On error occur
        uploader.bind('Error', function(up, err) {
            $('#aaiu-upload-imagelist-plans').append("<div>Error: " + err.code +
                ", Message: " + err.message +
                (err.file ? ", File: " + err.file.name : "") +
                "</div>"
            );
            up.refresh(); // Reposition Flash/Silverlight
        });

        uploader.bind('FileUploaded', function(up, file, response) {
            var result = $.parseJSON(response.response);

            $('#' + file.id).remove();
            if (result.success) {
                newImage += '~~~' + result.html;
                $('#new_plans').val(newImage);
                $('#imagelist-plans').append('<div class="uploaded_images" data-imageid="' + result.attach + '"><img src="' + result.html + '" alt="thumb" /><div class="deletePlanImage"><span class="fa fa-trash-o"></span></div></div>');
            }
        });

        $('#aaiu-uploader-plans').click(function(e) {
            uploader.start();
            e.preventDefault();
        });
    }

    jQuery("#imagelist-plans").on( "click", ".deletePlanImage", function() {
        var photos = jQuery("#new_plans").val();
        var delPhoto = jQuery(this).prev('img').attr('src');
        var newPhotos = photos.replace('~~~' + delPhoto, '');
        newImage = newPhotos;
        jQuery("#new_plans").val(newPhotos);
        jQuery(this).parent().remove();
    });

    if($('#edit_plans').length > 0) {
        var current_gallery = $('#edit_plans').val();
        var images = current_gallery.split("~~~");

        for(var i = 1; i < images.length; i++) {
            newImage += '~~~' + images[i];
            $('#new_plans').val(newImage);
            $('#imagelist-plans').append('<div class="uploaded_images"><img src="' + images[i] + '" alt="thumb" /><div class="deletePlanImage"><span class="fa fa-trash-o"></span></div></div>');
        }
    }

})(jQuery);
