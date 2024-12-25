;(function($){
    $(document).ready(function(){
        $('body').on('click',"#an_admin_notice .notice-dismiss" ,function (){
            console.log("clicked")
            setCookie("admin_cookie",'1',60)
        })
    })
})(jQuery)

function setCookie(cookieName, cookieValue, expirySecond){
    let expiry = new Date();
    expiry.setTime(expiry.getTime() + 1000 * expirySecond);
    document.cookie =
        cookieName + "=" +
        encodeURIComponent(cookieValue) +
        ";expires=" + expiry.toUTCString() +
        ";path=/";
}
