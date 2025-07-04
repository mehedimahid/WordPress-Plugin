;(function($){
    $('#simple-contact-form_form').submit(function(event){
        event.preventDefault()
        var form = $(this).serialize()
        console.log(form)
        $.ajax({
            method:'post',
            url:ContactFormData.rest_url,
            headers:{'X-WP-Nonce':ContactFormData.nonce},
            data:form,
        })
    })
})(jQuery)