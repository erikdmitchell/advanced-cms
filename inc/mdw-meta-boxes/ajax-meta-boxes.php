<?php
class AJAXMetaBoxes {

	public $meta_box_id='ajax_meta_box_id';

	function __construct() {
		add_action('wp_ajax_ajaxmb-field',array($this,'ajax_update_field'));
		add_action('admin_enqueue_scripts',array($this,'admin_scripts_styles'));
	
		add_action('add_meta_boxes',array($this,'_add_meta_box')); // temporary ??
		add_action('save_post',array($this,'_save_meta_box_data')); // temporary ??		
	}
	
	// scripts stlyes
	function admin_scripts_styles() {
		wp_enqueue_script('ajax-meta-boxes-js',plugins_url('js/ajax-meta-boxes.js',__FILE__),array('jquery'),'1.0.0');
		
		wp_enqueue_style('aja-meta-boxes-css',plugins_url('css/ajax-meta-boxes.css',__FILE__),array(),'1.0.0','all');
	}
	
	// ajax functions
	function ajax_update_field() {
		switch ($_POST['type']) :
			case 'add': // not in use yet
				$this->add_field();		
				break;
			case 'delete':			
				echo $this->delete_field($_POST['post_id'],$_POST['key']);				
				break;
			case 'update':
				echo $this->update_field($_POST['post_id'],$_POST['key'],$_POST['value'],$_POST['parent_id']);
				break;			
		endswitch;
		
		exit;
	}	
	
	// add field
	function add_field() {
	
	}
	
	// removes field
	function delete_field($post_id,$meta_key) {
		$id='_prefix-'.strtolower(str_replace(' ','-',$meta_key));
	
		return delete_post_meta($post_id,$id);
	}
	
	// save-update box
	// would like to add some nonces here to prevent junk data
	function update_field($post_id,$meta_key,$meta_value,$div_id) {
		$id='_prefix-'.strtolower(str_replace(' ','-',$meta_key));
		$arr=array(
			'label' => $meta_key,
			'value' => $meta_value,
			'div_id' => $div_id
		);
		$update=update_post_meta($post_id,$id,$arr);
		
		return $update;
	}
	
	// display box
	
	
	function _add_meta_box() {
		add_meta_box($this->meta_box_id,'AJAX Meta Box',array($this,'_meta_box'),'suppliers','normal','high');
	}

	function _meta_box($post) {
		// Add an nonce field so we can check for it later.
		wp_nonce_field( 'myplugin_meta_box', 'myplugin_meta_box_nonce' );
	
		/*
		 * Use get_post_meta() to retrieve an existing value
		 * from the database and use the value for the form.
		 */
		/* $value = get_post_meta( $post->ID, '_my_meta_value_key', true ); */
		$meta=get_post_meta($post->ID);

		foreach ($meta as $key => $value) :
			if (strpos($key,'prefix')!==false) :
				$arr=unserialize($value[0]);

				echo '<div class="ajaxmb-field" id="'.$arr['div_id'].'">';
					echo '<label for="ajaxmb-field-default">'.$arr['label'].'</label>';
					echo '<input type="text" id="ajaxmb-field-value" name="ajaxmb-field-value" value="'.$arr['value'].'" size="25" />';
					echo '<button type="button" class="ajaxmb-field-btn delete">Delete Field</button>';
				echo '</div>';			
			endif;
		endforeach;
	
		echo '<button type="button" class="ajaxmb-field-btn add">Add Field</button>';
		
		echo '<div class="ajaxmb-field new" id="ajaxmb-field-default-0">';
			echo '<label for="ajaxmb-field-default"></label>';
			echo '<input type="text" id="ajaxmb-field-label" name="ajaxmb-field-label" value="Description for this field" size="25" />';
			echo '<input type="text" id="ajaxmb-field-value" name="ajaxmb-field-value" value="Field value" size="25" />';
			echo '<button type="button" class="ajaxmb-field-btn update">Update Field</button>';
			//echo '<button type="button" class="ajaxmb-field-btn add">Add Field</button>';
		echo '</div>';
		
		echo '<input type="hidden" id="post-id" value="'.$post->ID.'" />';
	}

	function _save_meta_box_data($post_id) {
		/*
		 * We need to verify this came from our screen and with proper authorization,
		 * because the save_post action can be triggered at other times.
		*/
	
		// Check if our nonce is set.
		if ( ! isset( $_POST['myplugin_meta_box_nonce'] ) )
			return false;
	
		// Verify that the nonce is valid.
		if ( ! wp_verify_nonce( $_POST['myplugin_meta_box_nonce'], 'myplugin_meta_box' ) )
			return false;
	
		// If this is an autosave, our form has not been submitted, so we don't want to do anything.
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) 
			return false;
	
		// Check the user's permissions. -- removed
	
		/* OK, its safe for us to save the data now. */
		
		// Make sure that it is set.
		/*
		if ( ! isset( $_POST['myplugin_new_field'] ) ) {
			return;
		}
		*/
		
		// Update the meta field in the database.
		//update_post_meta( $post_id, '_my_meta_value_key', sanitize_text_field( $_POST['myplugin_new_field'] ) );

	}
}
new AJAXMetaBoxes();
?>