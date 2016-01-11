<?php
/**
 * MDWAdminColumns class.
 *
 * @since 2.2.0
 */
class MDWAdminColumns {

	public $version='0.1.1';
	public $config=array();

	/**
	 * __construct function.
	 *
	 * @access public
	 * @param array $config (default: array())
	 * @return void
	 */
	public function __construct($config=array()) {
		$default_config=array(
			'post_type' => 'post',
			'columns' => array(),
		);
		$this->config=array_merge($default_config,$config);
		$this->config=json_decode(json_encode($this->config),FALSE); // make object

		if (isset($_GET['post_type']) && $_GET['post_type']==$this->config->post_type) :

			add_action('manage_'.$this->config->post_type.'_posts_custom_column',array($this,'custom_column_row'),10,2);

			add_filter('manage_edit-'.$this->config->post_type.'_columns',array($this,'custom_admin_columns'));
		endif;
	}

	/**
	 * custom_admin_columns function.
	 *
	 * @access public
	 * @param mixed $columns
	 * @return void
	 */
	public function custom_admin_columns($columns) {
		foreach ($this->config->columns as $slug => $col) :
			$columns[$slug]=$col->label;
		endforeach;

		return $columns;
	}

	/**
	 * custom_column_row function.
	 *
	 * @access public
	 * @param mixed $column_name
	 * @param mixed $post_id
	 * @return void
	 */
	public function custom_column_row($column_name,$post_id) {
		$custom_fields=get_post_custom($post_id);

		foreach ($this->config->columns as $slug => $col) :
			if ($slug==$column_name) :
				if (isset($col->type)) :
					switch ($col->type) :
						case 'meta':
							echo get_post_meta($post_id,$col->value,true);
							break;
						case 'taxonomy':
							$args=array(
								'fields' => 'names'
							);
							$terms=wp_get_post_terms($post_id,$col->value,$args);
							if ($terms && !empty($terms))
								echo implode(', ',$terms);
							break;
						default:
							echo get_post_meta($post_id,$col->value,true);
							break;
					endswitch;
				else :
					// assume custom field for legacy (1.0.1) purposes //
					if (isset($custom_fields[$col->slug][0]))
						echo $custom_fields[$col->slug][0];
				endif;
			endif;
		endforeach;
	}

	/**
	 * get_version function.
	 *
	 * @access public
	 * @return void
	 */
	public function get_version() {
		return $this->version;
	}

}

/**
 * mdwcms_add_admin_column function.
 *
 * @access public
 * @param array $args (default: array())
 * @return void
 */
function mdwcms_add_admin_column($args=array()) {
	$MDWAdminColumns=new MDWAdminColumns($args);
}
?>
