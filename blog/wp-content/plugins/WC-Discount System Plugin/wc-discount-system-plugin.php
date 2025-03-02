<?php
/*
Plugin Name: WooCommerce Smart Discount System
Plugin URI:
Description: A simple plugin that shows a welcome message to new visitors.
Version: 1.0
Author: Mehedi Hasan
Author URI: https://github.com/mehedimahid
License: GPL2
*/

add_action('wp_enqueue_scripts', function () {
    wp_enqueue_style('wcdf-discount-offer', plugin_dir_url(__FILE__) . 'src/css/wcdf_discount.css');
});
function wc_discount_fees(){
    if (is_admin()&& !defined('DOING_AJAX')) {
        return;
    }

    $total_cart_amount = WC()->cart->subtotal;
    if($total_cart_amount >= 500 && $total_cart_amount < 1000){
        $discount_percentage = 10;
    }elseif($total_cart_amount >=1000 ){
        $discount_percentage = 15;
    }else{
        return;
    }
    $discount_tk = floor(($total_cart_amount*$discount_percentage)/100);
    WC()->cart->add_fee(__('Discount '.$discount_percentage."%"),-$discount_tk,true);
}
add_action('woocommerce_cart_calculate_fees', 'wc_discount_fees');

function wc_display_discount_notice() {

    if ( is_shop() ) {
        ?>
        <div class ='discount-container' >
            <div class="disc ount-card">
                <h2>🎉 Special offers!</h2>
                <p>
                    <strong> 10% </strong> off on orders over 500 taka!
                </p>
                <a href="<?php echo esc_url(wc_get_page_permalink('shop')); ?>" class="discount-btn">🛒 Shop Now</a>
            </div>
            <div class="discount-card">
                <h2>🎉 Special offers!</h2>
                <p>
                     <strong> 15% </strong> off on orders over 1000 taka!
                </p>
                <a href="<?php echo esc_url(wc_get_page_permalink('shop')); ?>" class="discount-btn">🛒 Shop Now</a>
            </div>
        </div>
        <?php
    }
}
add_action('woocommerce_before_shop_loop', 'wc_display_discount_notice');


function wcds_active(){
    if(!class_exists('WooCommerce')){
        wp_die(__('Please Activate wooCommerce Plugin', 'woocommerce-smart-discount'));
    }
}

register_activation_hook(__FILE__, 'wcds_active');

