(function($) {
    var frame;

    $('#gn-upload-logo').on('click', function(e) {
        e.preventDefault();
        if (frame) {
            frame.open();
            return;
        }
        frame = wp.media({
            title: 'Select Logo',
            button: { text: 'Use as Logo' },
            multiple: false,
            library: { type: 'image' }
        });
        frame.on('select', function() {
            var attachment = frame.state().get('selection').first().toJSON();
            $('#gn-logo-id').val(attachment.id);
            $('#gn-logo-img').attr('src', attachment.url);
            $('#gn-logo-preview').show();
            $('#gn-remove-logo').show();
        });
        frame.open();
    });

    $('#gn-remove-logo').on('click', function() {
        $('#gn-logo-id').val('');
        $('#gn-logo-img').attr('src', '');
        $('#gn-logo-preview').hide();
        $(this).hide();
    });

    $('#gn-save-settings').on('click', function() {
        var btn = $(this);
        var spinner = $('#gn-settings-spinner');
        var msg = $('#gn-settings-message');

        btn.prop('disabled', true);
        spinner.addClass('is-active');
        msg.text('').removeClass('notice-success notice-error');

        var data = {
            action: 'globalnews_save_site_settings',
            nonce: globalnewsAdmin.nonce,
            site_name: $('#gn-site-name').val(),
            site_tagline: $('#gn-site-tagline').val(),
            site_logo: $('#gn-logo-id').val()
        };

        $.post(globalnewsAdmin.ajaxUrl, data, function(response) {
            btn.prop('disabled', false);
            spinner.removeClass('is-active');
            if (response.success) {
                $('#gn-summary-name').text(response.data.site_name);
                $('#gn-summary-tagline').text(response.data.site_tagline);
                $('#gn-summary-logo').text(response.data.site_logo ? 'Set' : 'None');
                $('#gn-success-modal').show();
            } else {
                msg.text(response.data || 'Error saving settings.').addClass('notice-error');
            }
        }).fail(function() {
            btn.prop('disabled', false);
            spinner.removeClass('is-active');
            msg.text('Request failed.').addClass('notice-error');
        });
    });

    $('.gn-modal-overlay, .gn-modal-close, .gn-modal-close-btn').on('click', function() {
        $('#gn-success-modal').hide();
    });

})(jQuery);
