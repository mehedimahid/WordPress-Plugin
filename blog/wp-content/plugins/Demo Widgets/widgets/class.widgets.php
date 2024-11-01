<?php

class DemoWidgets extends WP_Widget {
	function __construct() {
		parent::__construct(
			'demowidgets',
			__('Demo Widgets', 'demowidgets'),
			array('description' => __('Our Demo Widgets', 'demowidgets'))
		);
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
            <p>Latitude: <?php echo isset($instance['latitude']) ? esc_html($instance['latitude']) : 'N/A'; ?></p>
            <p>Longitude: <?php echo isset($instance['longitude']) ? esc_html($instance['longitude']) : 'N/A'; ?></p>
        </div>
		<?php

		echo $args['after_widget'];
	}

	function form($instance) {
		$title = isset($instance['title']) ? $instance['title'] : __('Demo Widgets title', 'demowidgets');
		$latitude = isset($instance['latitude']) ? $instance['latitude'] : 23.3;
		$longitude = isset($instance['longitude']) ? $instance['longitude'] : 90.6;
		?>
        <p>
            <label for="<?php echo esc_attr($this->get_field_id('title')); ?>"><?php _e('Title', 'demowidgets'); ?></label>
            <input type="text" class="widefat"
                   id="<?php echo esc_attr($this->get_field_id('title')); ?>"
                   name="<?php echo esc_attr($this->get_field_name('title')); ?>"
                   value="<?php echo esc_attr($title); ?>">
        </p>
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
		<?php
	}

//	function update($new_instance, $old_instance) {
//		$instance = $old_instance;
//
//		// Sanitize and save the title
//		$instance['title'] = sanitize_text_field($new_instance['title']);
//
//		// Sanitize and save latitude and longitude
//		$instance['latitude'] = is_numeric($new_instance['latitude']) ? $new_instance['latitude'] : '';
//		$instance['longitude'] = is_numeric($new_instance['longitude']) ? $new_instance['longitude'] : '';
//
//		return $instance;
//	}
}
