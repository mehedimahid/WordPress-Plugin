var frame;
;(function($){
    //month year pick 
    $(document).ready(function(){
        $(".book_pby").datepicker({
            changeMonth: true,
            changeYear: true
        });
        //image select
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
                //how much image show 
                multiple:false//single
                // multiple:true // multiple
            });
            // //upload image display
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