<?php
class MDW_Widget_Creator {
	
	function __construct($params) {
		$this->params=$params;
				
		add_action('widgets_init',array($this,'mdw_register_widget'));
	}
	
	function mdw_register_widget() {
		global $wp_widget_factory;
		
		$mdw_widget_factory=new MDW_Widget_Factory();
		
		foreach ($this->params as $params) :
			$mdw_widget_factory->register('My_Widget_Class',$params);
		endforeach;
		//$mdw_widget_factory->register('My_Widget_Class',$params2);
	
		foreach ($mdw_widget_factory->widgets as $key => $widget) :
			$wp_widget_factory->widgets[$key]=$widget;
		endforeach;
	}
	
}

/**
 * extends the WP_Widget_Factory register function to include configuration options
 * works with our Sample_Widget to build a more dynamic widget
**/
class MDW_Widget_Factory extends WP_Widget_Factory {

  function register($widget_class,$params=null) {
  	$key=$widget_class;
  	if (!empty($params)) {
  		$key.=md5(maybe_serialize($params));
  	}
    $this->widgets[$key] = new $widget_class($params);
  }
  
}

class My_Widget_Class extends WP_Widget {

	/**
	 * Sets up the widgets name etc
	 */
 	function __construct( $params ) {
 		$id = 'my_widget_'.$params['id'];
 		$widget_ops = array(
 			'classname' => $id,
 			'description' => $params['description'],
 			'data' => $params // pass any additional params to the widget instance.
 		);
 		$control_ops = array( 'id_base' => $id );
 		parent::__construct( $id, $params['title'], $widget_ops, $control_ops );
 	}
 	
	/**
	 * Outputs the content of the widget
	 *
	 * @param array $args
	 * @param array $instance
	 */
	public function widget( $args, $instance ) {
		$title = apply_filters( 'widget_title', $instance['title'] );

		echo $args['before_widget'];
		if ( ! empty( $title ) )
			echo $args['before_title'] . $title . $args['after_title'];
		echo __( 'Hello, World!', 'text_domain' );
		echo $args['after_widget'];
	}

	/**
	 * Ouputs the options form on admin
	 *
	 * @param array $instance The widget options
	 */
	public function form( $instance ) {
		if ( isset( $instance[ 'title' ] ) ) {
			$title = $instance[ 'title' ];
		}
		else {
			$title = __( 'New title', 'text_domain' );
		}
		?>
		<p>
		<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' ); ?></label> 
		<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>">
		</p>
		<?php 
	}

	/**
	 * Processing widget options on save
	 *
	 * @param array $new_instance The new options
	 * @param array $old_instance The previous options
	 */
	public function update( $new_instance, $old_instance ) {
		$instance = array();
		$instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';

		return $instance;
	}
	 	
}
?>