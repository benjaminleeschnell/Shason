(function($) {
    "use strict";

    // Upload logo
    $('#logoImageBtn').click(function() {
        tb_show('', 'media-upload.php?width=800&amp;height=500&amp;type=image&amp;TB_iframe=true');
        $('#TB_ajaxWindowTitle').html('Logo Image');
        window.send_to_editor = function(html) {
            var imgURL = $('img',html).attr('src');
            $('#logoImage').val(imgURL);
            tb_remove();
        }
        return false;
    });

    // Upload app logo
    $('#appLogoImageBtn').click(function() {
        tb_show('', 'media-upload.php?width=800&amp;height=500&amp;type=image&amp;TB_iframe=true');
        $('#TB_ajaxWindowTitle').html('App Logo Image');
        window.send_to_editor = function(html) {
            var imgURL = $('img',html).attr('src');
            $('#appLogoImage').val(imgURL);
            tb_remove();
        }
        return false;
    });

    // Upload video
    $('#homeVideoBtn').click(function() {
        tb_show('', 'media-upload.php?width=800&amp;height=500&amp;type=image&amp;TB_iframe=true');
        $('#TB_ajaxWindowTitle').html('Homepage video');
        window.send_to_editor = function(html) {
            var videoURL = $(html).attr('href');
            $('#homeVideo').val(videoURL);
            tb_remove();
        }
        return false;
    });

    $('#amenities_field').tagsInput({
        'height': 'auto',
        'width': '100%',
        'defaultText': settings_vars.amenities_placeholder
    });

    // Upload page header image
    $('#pageHeaderBtn').click(function() {
        tb_show('', 'media-upload.php?width=800&amp;height=500&amp;type=image&amp;TB_iframe=true');
        $('#TB_ajaxWindowTitle').html('Page Header Image');
        window.send_to_editor = function(html) {
            var imgURL = $('img',html).attr('src');
            $('#page_header').val(imgURL);
            tb_remove();
        }
        return false;
    });

    $(function() {
        $('.color-field').wpColorPicker();
    });

    $('#add_fields_btn').click(function() {
        var propsAjaxURL = settings_vars.admin_url + 'options.php';
        var security = $('#securityAddCustomFields').val();

        $.ajax({
            type: 'POST',
            dataType: 'json',
            url: ajaxurl,
            data: {
                'action': 'reales_add_custom_fields',
                'name': $('#custom_field_name').val(),
                'label': $('#custom_field_label').val(),
                'type': $('#custom_field_type').val(),
                'mandatory': $('#custom_field_mandatory').val(),
                'security': security
            },
            success: function(data) {
                if(data.add == true) {
                    // var key = $('#custom_field_name').val().toLowerCase().trim().replace(/\s+/g, '_').replace(/ +/g,'').replace(/[^a-z0-9-_]/g,'');
                    var newField = '<tr>' +
                                        '<td><input type="text" name="reales_fields_settings[' + data.var_name + '][name]" value="' + data.name + '"></td>' +
                                        '<td><input type="text" name="reales_fields_settings[' + data.var_name + '][label]" value="' + data.label + '"></td>' +
                                        '<td>';
                    newField +=             '<select name="reales_fields_settings[' + data.var_name + '][type]">' +
                                                '<option value="text_field"';
                    if(data.type == 'text_field') {
                        newField += ' selected ';
                    }
                    newField += '>' + settings_vars.text + '</option>';
                    newField += '<option value="numeric_field"';
                    if(data.type == 'numeric_field') {
                        newField += ' selected ';
                    }
                    newField += '>' + settings_vars.numeric + '</option>';
                    newField += '<option value="date_field"';
                    if(data.type == 'date_field') {
                        newField += ' selected ';
                    }
                    newField += '>' + settings_vars.date + '</option>';
                    newField +=             '</select></td>';
                    newField +=             '<td><select name="reales_fields_settings[' + data.var_name + '][mandatory]">' +
                                                '<option value="no"';
                    if(data.mandatory == 'no') {
                        newField += ' selected ';
                    }
                    newField += '>' + settings_vars.no + '</option>';
                    newField += '<option value="yes"';
                    if(data.mandatory == 'yes') {
                        newField += ' selected ';
                    }
                    newField += '>' + settings_vars.yes + '</option>';
                    newField +=             '</select></td>';
                    newField +=         '<td><a href="javascript:void(0);" data-row="' + data.var_name + '" class="delete-field">' + settings_vars.delete + '</a></td>' +
                                    '</tr>';
                    $('#customFieldsTable').append(newField);
                    $('#custom_field_name').val('');
                    $('#custom_field_label').val('');
                    $('#custom_field_type').val('text_field');
                    $('#custom_field_mandatory').val('no');
                } else {
                    alert(data.message);
                }
            }
        });
    });

    $(document).on('click', '.delete-field', function() {
        var _self = $(this);
        var field_name = $(this).attr('data-row');
        var propsAjaxURL = settings_vars.admin_url + 'options.php';
        var security = $('#securityAddCustomFields').val();

        $.ajax({
            type: 'POST',
            dataType: 'json',
            url: ajaxurl,
            data: {
                'action': 'reales_delete_custom_fields',
                'field_name': field_name,
                'security': security
            },
            success: function(data) {
                if(data.delete == true) {
                    _self.parent().parent().remove();
                }
            }
        });
    });

    $('.datePicker').datepicker();

})(jQuery);