;(function($){
    $('#simple-contact-form_form').on('submit', function(event) {
        event.preventDefault();
        var form = $(this);
        console.log( form);
        $.ajax({
            type:'POST',
            url: contactForm.url,
            data: form.serialize(),
            headers: {
                'X-WP-Nonce': contactForm.nonce
            },
            success:function(res){
                form.hide()
                $('#success_message').html(res).fadeIn();
            },
            error:function(){
                $('#error_message').html('There was an error submitting the form. Please try again later.').fadeIn();
            }
        })
    });

})(jQuery)