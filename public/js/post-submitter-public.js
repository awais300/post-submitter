jQuery(document).ready(function ($) {
    //trigger ajax call
    $('#post-form').submit(function (e) {
        e.preventDefault();
        $('#notify').hide();
        disable_button($);
        ajax_start($);
        var form = $(this);
        submit_post($, form);
    });

    //hide alerts
    $(document).on('click', '#closebtn', function (e) {
        e.preventDefault();
        $(this).parent().hide();
    });

});

/*Ajax to save form entry*/
function submit_post($, form) {
    file_data = $('#file').prop('files')[0];
    form_data = new FormData();
    form_data.append('featured_image', file_data);

    form_data.append('_ajax_nonce', LOCAL_OBJ._ajax_nonce);
    form_data.append('action', 'save_form');
    form_data.append('post_data', form.serialize());

    $.ajax({
        url: LOCAL_OBJ.ajax_url,
        type: 'POST',
        dataType: 'JSON',
        contentType: false,
        processData: false,
        data: form_data,
        success: function (response) {
            // Display info
            if (response.error == true) {
                html = '<div class="alert"><span id="closebtn" class="closebtn">×</span>' + response.message + '</div>';
                $('#notify').html(html).show();
            } else if (response.error == false) {
                html = '<div class="alert success"><span id="closebtn" class="closebtn">×</span>' + response.message + '</div>';
                $('#notify').html(html).show();
                form[0].reset();
            }

            enable_button($);
            ajax_stop($);
        },
        error: function (xhr, status, error) {
            alert(error);
            enable_button($);
            ajax_stop($);
        }
    });
}


/*Utility functions*/
function disable_button($) {
    $('#submit').prop('disabled', true)
}

function enable_button($) {
    $('#submit').prop('disabled', false)
}

function ajax_start($, id) {
    $('#post #loading').show();
}

function ajax_stop($, id) {
    $('#post #loading').hide();
}

