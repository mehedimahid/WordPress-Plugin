var frame;
;(function($){
    $(document).ready(function(){
        //save image show in editor
        var image_url = $("#omb_image_url").val();
        if(image_url){
            $("#image_container").html(`<img src="${image_url}"/>`);
        }
        $(".book_pby").datepicker({
            changeMonth: true,
            changeYear: true
        });
        $("#upload_image").on('click',function(){
            if(frame){
                frame.open()
                return false

            }
            frame = wp.media({
                title: "Select Image",
                button:{
                    text:"Insert Image"
                },
                multiple:false
                // multiple:true
            });
            frame.on('select',function(){
                var attachment = frame.state().get('selection').first().toJSON();
                console.log(attachment);
                $("#omb_image_id").val(attachment.id);
                $("#omb_image_url").val(attachment.sizes.medium.url);
                $("#image_container").html(`<img src="${attachment.sizes.medium.url}"/>`);
            })
            frame.open()
            return false;
        })
    })
}(jQuery))
//https://jqueryui.com/datepicker/