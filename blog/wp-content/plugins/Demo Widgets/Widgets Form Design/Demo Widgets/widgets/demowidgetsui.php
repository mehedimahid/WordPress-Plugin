<?php

class DemoWidgetsUI extends WP_Widget {
	function __construct() {
		parent::__construct(
			'demowidgetsui',
			__('Demo Widgets UI', 'demowidgets'),
			array('description' => __('Our Demo Widgets', 'demowidgets'))
		);
	}

	function form($instance) {
		$title = $instance['title'] ?? __( 'Demo Widgets title', 'demowidgets' );
		$latitude = $instance['latitude'] ?? 23.3;
		$longitude =$instance['longitude'] ??  90.6;
		$email =$instance['email'] ??  'jone@deo.com';
//$title = isset($instance['title']) ? $instance['title'] : __('Demo Widgets title', 'demowidgets');
//		$latitude = isset($instance['latitude']) ? $instance['latitude'] : 23.3;
//		$longitude = isset($instance['longitude']) ? $instance['longitude'] : 90.6;
		?>
            <div class="dw-section dw-col-2">
                <p>
                    <label for="<?php echo esc_attr($this->get_field_id('title')); ?>"><?php _e('Title', 'demowidgets'); ?></label>
                    <input type="text" class="widefat"
                           id="<?php echo esc_attr($this->get_field_id('title')); ?>"
                           name="<?php echo esc_attr($this->get_field_name('title')); ?>"
                           value="<?php echo esc_attr($title); ?>">
                </p>
                <p>
                    <label for="<?php echo esc_attr($this->get_field_id('email')); ?>"><?php _e('Email', 'demowidgets'); ?></label>
                    <input type="text" class="widefat"
                           id="<?php echo esc_attr($this->get_field_id('email')); ?>"
                           name="<?php echo esc_attr($this->get_field_name('email')); ?>"
                           value="<?php echo esc_attr($email); ?>">
                </p>
            </div>
		<div class="dw-section dw-col-2">
            <p>
                <label for="<?php echo esc_attr($this->get_field_id('latitude')); ?>"><?php _e('Latitude', 'demowidgets'); ?></label>
                <input type="text" class="widefat"
                       id="<?php echo esc_attr($this->get_field_id('latitude')); ?>"
                       name="<?php echo esc_attr($this->get_field_name('latitude')); ?>"
                       value="<?php echo esc_attr($latitude); ?>">
            </p>
            <p>
                <label for="<?php echo esc_attr($this->get_field_id('longitude')); ?>"><?php _e('Longitude', 'demowidgets'); ?></label>
                <input type="text" class="widefat"
                       id="<?php echo esc_attr($this->get_field_id('longitude')); ?>"
                       name="<?php echo esc_attr($this->get_field_name('longitude')); ?>"
                       value="<?php echo esc_attr($longitude); ?>">
            </p>
        </div>
        <label for=""> Some Info</label>
        <div class="wp-tab-panel">
            <ui>
                <li>
                    <input type="checkbox" value="1">
                    <label for="">Some Data</label>
                </li>
                <li>
                    <input type="checkbox" value="1">
                    <label for="">Some Data</label>
                </li>
                <li>
                    <input type="checkbox" value="1">
                    <label for="">Some Data</label>
                </li>

            </ui>
        </div>

		<?php
	}

	function widget($args, $instance) {
		echo $args['before_widget'];

		// Check if the title is set and not empty
		if (isset($instance['title']) && $instance['title'] != "") {
			echo $args['before_title'];
			echo apply_filters('widget_title', $instance['title']);
			echo $args['after_title'];
		}

		// Display latitude and longitude, if set
		?>
		<div class="demowidgets">
			<p>Latitude: <?php echo isset($instance['latitude']) ? esc_attr($instance['latitude']) : 'N/A'; ?></p>
			<p>Longitude: <?php echo isset($instance['longitude']) ? esc_attr($instance['longitude']) : 'N/A'; ?></p>
			<p>Email: <?php echo isset($instance['email']) ? esc_attr($instance['email']) : 'N/A'; ?></p>
		</div>
		<?php

		echo $args['after_widget'];
	}
	//sanitize and validation check  korar জন্য update() ব্যাবহার করা হয়
	function update( $new_instance, $old_instance ) {
		//sanitize and validation check

		$instance = $new_instance;
		$instance['title'] = sanitize_text_field( $new_instance['title'] );
		if(!is_email($new_instance['email'])){
			$instance['email'] =$old_instance['email'];
		}
		if(!is_numeric($new_instance['latitude'])){
			$instance['email'] =$old_instance['latitude'];
		}
		if(!is_numeric($new_instance['longitude'])){
			$instance['email'] =$old_instance['longitude'];
		}
		return $instance;
	}
}

//error_log( print_r( $instance['latitude'], true ) . "\n\n", 3, __DIR__ . '/log.txt' );