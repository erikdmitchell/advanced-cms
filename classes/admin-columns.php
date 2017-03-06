<?php

/**
 * advancedCMSAdminColumns class.
 */
class advancedCMSAdminColumns {

	public $config=array();

	/**
	 * __construct function.
	 *
	 * @access public
	 * @param mixed $config
	 * @return void
	 */
	function __construct($config) {
		$this->config=$config;

		add_filter('manage_edit-'.$this->config['post_type'].'_columns',array($this,'custom_admin_columns'));
		add_action('manage_'.$this->config['post_type'].'_posts_custom_column',array($this,'custom_colun_row'),10,2);
	}

	/**
	 * custom_admin_columns function.
	 *
	 * @access public
	 * @param mixed $columns
	 * @return void
	 */
	public function custom_admin_columns($columns) {
		foreach ($this->config['columns'] as $col) :
			$columns[$col['slug']]=$col['label'];
		endforeach;

		return $columns;
	}

	/**
	 * custom_colun_row function.
	 *
	 * @access public
	 * @param mixed $column_name
	 * @param mixed $post_id
	 * @return void
	 */
	public function custom_colun_row($column_name,$post_id) {
		$custom_fields=get_post_custom($post_id);

		foreach ($this->config['columns'] as $col) :
			if ($col['slug']==$column_name) :
				if (isset($col['type'])) :
				switch ($col['type']) :
					case 'meta':
						if (isset($custom_fields[$col['slug']][0]))
							echo $custom_fields[$col['slug']][0];
						break;
					case 'taxonomy':
						$terms=wp_get_post_terms($post_id,$col['slug']);
						if ($terms)
							echo $terms[0]->name;
						break;
					default: // meta //
						if (isset($custom_fields[$col['slug']][0]))
							echo $custom_fields[$col['slug']][0];
						break;
				endswitch;
				else :
					// assume meta for legacy (1.0.1) purposes //
					if (isset($custom_fields[$col['slug']][0]))
						echo $custom_fields[$col['slug']][0];
				endif;
			endif;
		endforeach;
	}

}
?>
