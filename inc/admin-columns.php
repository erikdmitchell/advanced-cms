<?php
class MDW_Admin_Columns {

	protected $config=array();

	function __construct($config) {
		$this->config=$config;
		
		add_filter('manage_edit-'.$this->config['post_type'].'_columns',array($this,'custom_admin_columns'));
		add_action('manage_'.$this->config['post_type'].'_posts_custom_column',array($this,'custom_colun_row'),10,2);
	}

	public function custom_admin_columns($columns) {
		foreach ($this->config['columns'] as $col) :
			$columns[$col['slug']]=$col['label'];
		endforeach;
		
		return $columns;
	}
	
	
	public function custom_colun_row($column_name,$post_id) {
		$custom_fields=get_post_custom($post_id);
	
		foreach ($this->config['columns'] as $col) :
			if ($col['slug']==$column_name) :
				echo $custom_fields[$col['slug']][0];
			endif;
		endforeach;
	}

}


$arr=array(
	'post_type' => 'suppliers',
	'columns' => array(
		array (
			'slug' => '_mdwmb_url',
			'label' => 'URL'
		),
		array(
			'slug' => '_mdwmb_address',
			'label' => 'Address'
		)
	),
);

$mdw_admin_columns=new MDW_Admin_Columns($arr);
?>