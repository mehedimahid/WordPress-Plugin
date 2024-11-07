<?php

/**
 * Advertisement Widget
 *copy from https://github.com/LearnWithHasinHayder/AdvertisementWidget/blob/master/widgets/class.advertisementwidget.php
 */

class AdvertisementWidget extends WP_Widget
{

	/**
	 * Register widget with WordPress.
	 */
	public function __construct()
	{
		parent::__construct(
			'demowidget_advertisement', // Base ID
			__('Advertisement Widget','demowidget'), // Name
			array('description' => __('Add advertisement blocks', 'demowidget'),) // Args
		);
	}

	/**
	 * Front-end display of widget.
	 *
	 * @see WP_Widget::widget()
	 *
	 * @param array $args Widget arguments.
	 * @param array $instance Saved values from database.
	 */
	public function widget($args, $instance)
	{

		$display_image = false;
		if($instance['image']){
			$display_image=1;
			$image_src = wp_get_attachment_image_src($instance['image'],"thumbnail");
		}

		echo $args['before_widget'];
		?>
		<div class="about-widget widget">
			<?php
			if(isset($instance['title']) && $instance['title']!='') {
				echo wp_kses_post($args['before_title']);
				echo apply_filters('widget_title', $instance['title']);
				echo wp_kses_post($args['after_title']);
			}
			?>
			<div class="about-info">
				<?php if($display_image){?>
					<?php if($instance['url']){?>
						<a target="_blank" href='<?php echo esc_url($instance['url']);?>'><img alt="<?php _e('Advertisements','demowidget');?>" src="<?php echo esc_url($image_src[0]);?>"></a>
					<?php } else {?>
						<img alt="<?php _e('Advertisements','demowidget');?>" src="<?php echo esc_url($image_src[0]);?>">
					<?php } ?>
				<?php } ?>
			</div>
		</div>
		<?php
		echo $args['after_widget'];
	}

	/**
	 * Sanitize widget form values as they are saved.
	 *
	 * @see WP_Widget::update()
	 *
	 * @param array $new_instance Values just sent to be saved.
	 * @param array $old_instance Previously saved values from database.
	 *
	 * @return array Updated safe values to be saved.
	 */
	public function update($new_instance, $old_instance)
	{
		$instance = array();
		$instance['title'] = sanitize_text_field($new_instance['title']);
		$instance['image'] = sanitize_text_field($new_instance['image']);
		$instance['url'] = sanitize_text_field($new_instance['url']);

		return $instance;
	}

	/**
	 * Back-end widget form.
	 *
	 * @see WP_Widget::form()
	 *
	 * @param array $instance Previously saved values from database.
	 */
	public function form($instance)
	{

		$title = isset($instance['title'])?$instance['title']:__('Advertisement Block', 'demowidget');

		if(!isset($instance['url'])) {
			$instance['url'] = "";
		}
		if(!isset($instance['image'])) {
			$instance['image'] = "";
		}

		?>
		<p>
			<label for="<?php echo esc_attr($this->get_field_id('title')); ?>"><?php _e('Title:','demowidget'); ?></label>
			<input class="widefat" id="<?php echo esc_attr($this->get_field_id('title')); ?>"
			       name="<?php echo esc_attr($this->get_field_name('title')); ?>" type="text"
			       value="<?php echo esc_attr($title); ?>"/>
		</p>
		<p>
			<label for="<?php echo esc_attr($this->get_field_id('image')); ?>"><?php _e('Image:','demowidget'); ?></label>
			<br/>
		<p class="imgpreview"></p>
		<input class="imgph" type="hidden" id="<?php echo esc_attr($this->get_field_id('image')); ?>" name="<?php echo esc_attr($this->get_field_name('image')); ?>"  value="<?php echo esc_attr($instance['image']);?>"  />
		<input type="button" class="button btn-primary widgetuploader" value="<?php _e('Add Image','demowidget'); ?>" />
		</p>
		<p>
			<label for="<?php echo esc_attr($this->get_field_id('url')); ?>"><?php _e('Target URL:','demowidget'); ?></label>
			<br/>
			<input class="widefat" type="url" id="<?php echo esc_attr($this->get_field_id('url')); ?>" name="<?php echo esc_attr($this->get_field_name('url')); ?>" value="<?php echo esc_attr($instance['url']);?>" />
		</p>
		<?php
	}
}

