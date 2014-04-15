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
 		
 		$this->fields=$params['fields'];		
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

			foreach ($instance as $key => $value) :
				if ($key!='title') :
					echo '<div class="'.$args['id'].' '.$key.'" id="'.$args['id'].'_'.$key.'">';
						echo __($value,'text_domain');
					echo '</div>';
				endif;
			endforeach;

		echo $args['after_widget'];
	}

	/**
	 * Ouputs the options form on admin
	 *
	 * @param array $instance The widget options
	 */
	public function form( $instance ) {
		foreach ($this->fields as $field) :
			$value=$this->get_input_value($field['id'],$instance);
			echo '<p>';
				echo $this->get_input_label($field['id'],$field['label']);
				echo $this->get_input_box($field['type'],$field['id'],$value);
				if (isset($field['description'])) :
					echo $this->get_input_description($field['description']);
				endif;
			echo '</p>';
		endforeach;
	}

	function get_input_value($id,$instance) {
			if (isset($instance[$id])) :
				return $instance[$id];
			endif;

		return false;
	}

	/**
	 * Processing widget options on save
	 *
	 * @param array $new_instance The new options
	 * @param array $old_instance The previous options
	 */
	public function update( $new_instance, $old_instance ) {
		$instance = array();

		foreach ($this->fields as $field) :
			$instance[$field['id']] = ( ! empty( $new_instance[$field['id']] ) ) ? strip_tags( $new_instance[$field['id']] ) : '';
		endforeach;

		return $instance;
	}
	
	/**
	 * HELPER FUNCTIONS FOR OUR DYNAMIC INPUT
	**/

	/**
	 * outputs the field label in the admin section
	 *
	 * @param string $id the id of the input
	 * @param string $label the label of the input
	**/
	function get_input_label($id,$label) {
		$html=null;
		
		$html.='<label for="'.$this->get_field_id($id).'">'.__( $label ).'</label>';
		
		return $html;
	}
	
	/**
	 * outputs the correct field type in the admin section
	 *
	 * @param string $type the type of the input
	 * @param string $id the id of the input
	 * @param string $value the value of the input - default null
	**/
	function get_input_box($type,$id,$value=null) {
		$html=null;
		
		switch ($type) :
			case 'text' :
				$html.='<input class="widefat" type="text" name="'.$this->get_field_name($id).'" id="'.$this->get_field_id($id).'" value="'.$value.'" />';
				break;
			case 'textarea' :
				$html.='<textarea class="widefat" rows="8" cols="8" name="'.$this->get_field_name($id).'" id="'.$this->get_field_id($id).'">'.$value.'</textarea>';
				break;
			default :
				$html.='<input type="text" name="'.$this->get_field_name($id).'" id="'.$this->get_field_id($id).'" value="'.$value.'" />';
		endswitch;
		
		return $html;
	}

	/**
	 * outputs the field description in the admin section
	 *
	 * @param string $description the description of the input	 
	**/
	function get_input_description($description) {
		$html=null;
		
		$html.='<span class="description">'.$description.'</span>';
		
		return $html;
	}

}
?>