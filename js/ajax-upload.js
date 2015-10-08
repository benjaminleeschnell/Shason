(function($) {
    "use strict";

    var newImage = '';
    if (typeof(plupload) !== 'undefined') {
        var uploader = new plupload.Uploader(ajax_vars.plupload);
        uploader.init();
        uploader.bind('FilesAdded', function(up, files) {

            $.each(files, function(i, file) {

                $('#aaiu-upload-imagelist').append(
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
            $('#aaiu-upload-imagelist').append("<div>Error: " + err.code +
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
                $('#new_gallery').val(newImage);
                $('#imagelist').append('<div class="uploaded_images" data-imageid="' + result.attach + '"><img src="' + result.html + '" alt="thumb" /><div class="deleteImage"><span class="fa fa-trash-o"></span></div></div>');
            }
        });

        $('#aaiu-uploader').click(function(e) {
            uploader.start();
            e.preventDefault();
        });
    }

    jQuery("#imagelist").on( "click", ".deleteImage", function() {
        var photos = jQuery("#new_gallery").val();
        var delPhoto = jQuery(this).prev('img').attr('src');
        var newPhotos = photos.replace('~~~' + delPhoto, '');
        newImage = newPhotos;
        jQuery("#new_gallery").val(newPhotos);
        jQuery(this).parent().remove();
    });

    if($('#up_avatar').length > 0) {
        var current_avatar = $('#up_avatar').val();
        if(current_avatar != '') {
            newImage += '~~~' + current_avatar;
            $('#new_gallery').val(newImage);
            $('#imagelist').append('<div class="uploaded_images"><img src="' + current_avatar + '" alt="thumb" /><div class="deleteImage"><span class="fa fa-trash-o"></span></div></div>');
        }
    }

    if($('#edit_gallery').length > 0) {
        var current_gallery = $('#edit_gallery').val();
        var images = current_gallery.split("~~~");

        for(var i = 1; i < images.length; i++) {
            newImage += '~~~' + images[i];
            $('#new_gallery').val(newImage);
            $('#imagelist').append('<div class="uploaded_images"><img src="' + images[i] + '" alt="thumb" /><div class="deleteImage"><span class="fa fa-trash-o"></span></div></div>');
        }
    }

})(jQuery);
