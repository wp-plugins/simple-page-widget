<?php
/**
 * Plugin Name: Simple Page Widget
 * Plugin URI: 
 * Description: A simple widget to display a page inside a widget
 * Version: 0.1
 * Author: Gorm Haug Eriksen
 * Author URI: 
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 */

class Simple_Page_Widget extends WP_Widget {
	
	protected $defaults;

	function Simple_Page_Widget() {
		$this->defaults = array(
			'title' => __('About', 'spw-plugin'), 
			'page_id' => '1');

		$widget_ops = array('classname' => 'spw_widget',
					  'description' => __('Show a post as a widget', 
									 'spw-plugin'));

		$this->WP_Widget('spw_widget_id', __('Simple Page Widget', 
				'spw-plugin'), $widget_ops);
	}

	function form($instance) {
		$instance = wp_parse_args( (array) $instance, $this->defaults);
		$title = strip_tags($instance['title']);
		$page_id = strip_tags($instance['page_id']);
?>
<p><?php _e('Title', 'spw-plugin') ?>: <input class="widefat" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title) ?>" /></p>
<p><?php _e('Page ID', 'spw-plugin') ?>: <input class="widefat" name="<?php echo $this->get_field_name('page_id'); ?>" type="text" value="<?php echo esc_attr($page_id) ?>" /></p>
<?php
	}

	function update($new_instance, $old_instance) {
		$instance = $old_instance;
		$instance['title']   = strip_tags($new_instance['title']);
		$instance['page_id'] = strip_tags($new_instance['page_id']);
		
		return $instance;
	}

	function widget($args, $instance) {	
		extract($args);
		// also assign defaults in the case it was never added to the widgets panel
		// that is, added by using the_widget programatically
		$instance = wp_parse_args( (array) $instance, $this->defaults);

		echo $before_widget;

		$title = apply_filters('widget_title', $instance['title']);
		$page_id = empty($instance['page_id']) ? '&nbsp;' :
			apply_filters('widget_page_id', $instance['page_id']);

		if (! empty($title)) {
			echo $before_title . $title . $after_title; 
		}
		
		$page_data = get_page($page_id);

		if ($page_data) {
			echo '<ul><li>' . $page_data->post_content . '</li></ul>';
		} else {
			printf(__("Page with id %d not found", 'spw-plugin'), $page_id);
		}
		
		echo $after_widget;
		
	}
}

add_action('widgets_init', 'spw_register_widgets');

function spw_register_widgets() {
	register_widget('Simple_Page_Widget');
}

?>