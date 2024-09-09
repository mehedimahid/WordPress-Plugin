let frame,mframe;
;(function($){
    $(document).ready(function(){
        //save single image show in editor
        let image_url = $("#omb_image_url").val();
        if(image_url){
            $("#image_container").html(`<img src="${image_url}"/>`);
        }
        // //save multiple image show in editor
        let images_url = $("#omb_images_url").val();
        images_url = images_url?images_url.split(';'):[];
        for(i in images_url){
            let _images_url = images_url[i];
            $("#images_container").append(`<img style="margin-left: 10px" src="${_images_url}"/>`);
        }
        //
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

                let attachment = frame.state().get('selection').first().toJSON();

                console.log(attachment);
                $("#omb_image_id").val(attachment.id);
                $("#omb_image_url").val(attachment.sizes.medium.url);
                $("#image_container").html(`<img src="${attachment.sizes.medium.url}"/>`);
            })
            frame.open()
            return false;
        });
        //multiple image
        $("#upload_images").on('click',function(){
            if(mframe){
                mframe.open()
                return false

            }
            mframe = wp.media({
                title: "Select Image",
                button:{
                    text:"Insert Image"
                },
                multiple:true
                // multiple:true
            });
            mframe.on('select',function(){
                let image_ids = [];
                let image_urls = [];
                let attachments = mframe.state().get('selection').toJSON();
                $("#images_container").html('');//ager image empty kore neya holo
                for(i in attachments){
                    let attachment =attachments[i];
                        image_ids.push(attachment.id);
                        image_urls.push(attachment.sizes.thumbnail.url);
                    $("#images_container").append(`<img style="margin-right: 10px;margin-bottom: 10px" src="${attachment.sizes.thumbnail.url}"/>`);
                }
                console.log(attachments);
                $("#omb_images_id").val(image_ids.join(';'));
                $("#omb_images_url").val(image_urls.join(';'));

            })
            mframe.open()
            return false;
        })
    })
}(jQuery))
//https://jqueryui.com/datepicker/