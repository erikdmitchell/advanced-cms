<?php
/**
 * Adds SocialMedia widget.
 *
 * Displays social media icons/links that have been setup in the admin area
 *
 */
class SocialMedia extends WP_Widget {

	/**
	 * Register widget with WordPress.
	 */
	function __construct() {
		parent::__construct(
			'mdw_social_media', // Base ID
			__( 'Social Media', 'mdw_social_media' ), // Name
			array( 'description' => __( 'Displays custom Social Media settings', 'mdw_social_media' ), ) // Args
		);

		add_action('wp_enqueue_scripts',array($this,'scripts_styles'));
	}

	/**
	 * scripts_styles function.
	 *
	 * @access public
	 * @return void
	 */
	function scripts_styles() {
		wp_enqueue_style('social-media-widget-style',plugins_url('social-media.css',__FILE__));
	}

	/**
	 * Front-end display of widget.
	 *
	 * @see WP_Widget::widget()
	 *
	 * @param array $args     Widget arguments.
	 * @param array $instance Saved values from database.
	 */
	public function widget( $args, $instance ) {
		echo $args['before_widget'];
		if ( ! empty( $instance['title'] ) ) {
			echo $args['before_title'] . apply_filters( 'widget_title', $instance['title'] ). $args['after_title'];
		}

		if (get_option('social_media_options')) :
			$sm_options=get_option('social_media_options');
				?>
				<ul class="social-media">
					<?php foreach ($sm_options as $option_id => $option) : ?>
						<li id="social-media-<?php echo $option_id; ?>"><a href="<?php echo $option['url']; ?>"><i class="fa <?php echo $option['icon']; ?>"></i></a></li>
					<?php endforeach; ?>
				</ul>
			<?php
		endif;

		echo $args['after_widget'];
	}

	/**
	 * Back-end widget form.
	 *
	 * @see WP_Widget::form()
	 *
	 * @param array $instance Previously saved values from database.
	 */
	public function form( $instance ) {
		$title = ! empty( $instance['title'] ) ? $instance['title'] : __( 'New title', 'mdw_social_media' );
		?>
		<p>
		<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' ); ?></label>
		<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>">
		</p>
		<?php
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
	public function update( $new_instance, $old_instance ) {
		$instance = array();
		$instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';

		return $instance;
	}

}
?>