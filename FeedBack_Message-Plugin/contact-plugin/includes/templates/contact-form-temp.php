<?php 
if(get_plugin_options('contact_plugin_active')){
?>

<div id="success_message" style="background:green; color:white"></div>
<div id="error_message" style="background:red; color:white"></div>

<!-- <div class="simple-contact-form container mt-5 "> -->
    
    <form id="simple-contact-form_form" class="simple-contact-form container mt-5 ">
        <h2 class="text-center">Email Us</h2>
        <div class="form-group mb-2 px-5">
            <input type="text" name="name" placeholder="Name" class="form-control">
        </div>
        <div class="form-group mb-2 px-5">
            <input type="email" name="email" placeholder="Email" class="form-control">
        </div>
        <div class="form-group mb-2 px-5">
            <textarea name="message" placeholder="Type Your Message" class="form-control"></textarea>
        </div>
        <div class="form-group mb-2 px-5">
            <button  class="btn btn-success w-100">Send Message</button>
        </div>
    </form>
<!-- </div> -->

<?php
}else{
    echo '<h2 class="text-center">Contact Form is not active</h2>';
}