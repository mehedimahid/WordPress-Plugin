var frame;
;(function($){
    $(document).ready(function(){
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
                // multiple:false
                multiple:true
            })
            frame.open()
            return false;
        })
    })
}(jQuery))
//https://jqueryui.com/datepicker/