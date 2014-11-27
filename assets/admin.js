(function($) {
    $(document).ready(function(){

        if($('#cleaner_general_login_logo').val() == '') {
            $('#cleaner_general_login_logo_height').prop('disabled', true);
            $('#cleaner_general_login_logo_width').prop('disabled', true);
        }

        $('.add-logo').on('click', function(e) {
            e.preventDefault();

            var $el = $(this).parent();

            var uploader = wp.media({
                title: 'Envoyer une image',
                button: {
                    text: 'Choisir un fichier'
                },
                multiple: false
            })
            .on('select', function() {
                var selection = uploader.state().get('selection');
                var attachment = selection.first().toJSON();

                $('input', $el).val(attachment.url);
                $('img', $el).attr('src', attachment.url);

                $('#cleaner_general_login_logo_height').prop('disabled', false);
                $('#cleaner_general_login_logo_width').prop('disabled', false);
            })
            .open();

            $('.del-logo', $el).removeClass('hidden');
        }); 

        $('.del-logo').on('click', function(e) {
            e.preventDefault();

            var $el = $(this).parent();
            $('input', $el).val('');
            $('img', $el).attr('src', '');

            $(this).addClass('hidden');

            $('#cleaner_general_login_logo_height').prop('disabled', true);
            $('#cleaner_general_login_logo_width').prop('disabled', true);
        });    
    });
})(jQuery);