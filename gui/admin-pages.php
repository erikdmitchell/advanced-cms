<?php
class MDWCMSgui {
	
	protected $options=array();
	protected $base_url;
	
	function __construct() {
		add_action('admin_menu',array($this,'build_admin_menu'));
		add_action('admin_enqueue_scripts',array($this,'scripts_styles'));
		add_action('admin_notices',array($this,'admin_notices')); // may not be needed
		
		$this->options=get_option('mdw_cms');
		$this->base_url=admin_url('tools.php?page=mdw-cms&tab=mdw-cms-cpt');
	}
	
	function build_admin_menu() {
		add_management_page('MDW CMS','MDW CMS','administrator','mdw-cms',array($this,'mdw_cms_page'));
	}
	
	function scripts_styles() {
		wp_enqueue_style('mdw-cms-gui-style',plugins_url('/css/admin.css',__FILE__));
	}
	
	function mdw_cms_page() {
		$html=null;
		$tabs=array(
			'cms-main' => 'Main',
			'mdw-cms-cpt' => 'Custom Post Types',
			'mdw-cms-metaboxes' => 'Metaboxes'
		);
		$active_tab = isset( $_GET[ 'tab' ] ) ? $_GET[ 'tab' ] : 'cms-main';
				
		$html.='<div class="wrap">';
			$html.='<h2>MDW CMS</h2>';
		
			$html.='<h2 class="nav-tab-wrapper">';
				foreach ($tabs as $tab => $name) :				
					if ($active_tab==$tab) :
						$class='nav-tab-active';
					else :
						$class=null;
					endif;
					
					$html.='<a href="?page=mdw-cms&tab='.$tab.'" class="nav-tab '.$class.'">'.$name.'</a>';
				endforeach;
			$html.='</h2>';
		
			switch ($active_tab) :
				case 'cms-main':
					$html.=$this->default_admin_page();
					break;
				case 'mdw-cms-cpt':
					$html.=$this->cpt_admin_page();
					break;
				case 'mdw-cms-metaboxes':
					$html.=$this->metaboxes_admin_page();
					break;
				default:
					$html.=$this->default_admin_page();
					break;
			endswitch;

		$html.='</div><!-- /.wrap -->';
		
		echo $html;
	}

	/**
	 *
	 */
	function default_admin_page() {
		$html=null;

		$html.='<h3>CMS</h3>';

		$html.='<div class="mdw-cms-default">';
			$html.='text';
		$html.='</div>';

		return $html;
	}	
	
	/**
	 *
	 */
	function cpt_admin_page() {
		$btn_text='Create';
		$name=null;
		$label=null;
		$singular_label=null;
		$description=null;
		$title=1;
		$thumbnail=1;
		$editor=1;
		$revisions=1;
		$id=-1;
		
		if (isset($_POST['add-cpt']) && $_POST['add-cpt']=='Create') :		
			if ($this->update_custom_post_types($_POST)) :
				$this->admin_notices('updated','Post type has been created.');			
			else :
				$this->admin_notices('error','There was an issue creating the post type');
			endif;
		endif;
		
		// remove ctp //
		if (isset($_GET['delete']) && $_GET['delete']=='cpt') :
			foreach ($this->options['custom-post-types'] as $key => $cpt) :
				if ($cpt['name']==$_GET['slug'])
					unset($this->options['custom-post-types'][$key]);
			endforeach;

			$this->options['custom-post-types']=array_values($this->options['custom-post-types']);

			update_option('mdw_cms',$this->options);
		endif;
		
		// edit cpt //
		if (isset($_GET['edit']) && $_GET['edit']=='cpt') :
			foreach ($this->options['custom-post-types'] as $key => $cpt) :
				if ($cpt['name']==$_GET['slug']) :
					extract($this->options['custom-post-types'][$key]);
					$id=$key;
				endif;
			endforeach;
		endif;
		
		if ($id!=-1)
			$btn_text='Update';	
		
		$html=null;
		
		$html.='<form class="custom-post-types" method="post">';
			$html.='<h3>Add New Custom Post Type</h3>';
			$html.='<div class="form-row">';
				$html.='<label for="name" class="required">Post Type Name</label>';
				$html.='<input type="text" name="name" id="name" value="'.$name.'" />';
				$html.='<span class="description">(e.g. movie)</span>';
				$html.='<div class="description-ext">Max 20 characters, can not contain capital letters or spaces. Reserved post types: post, page, attachment, revision, nav_menu_item.</div>';
			$html.='</div>';

			$html.='<div class="form-row">';
				$html.='<label for="label">Label</label>';
				$html.='<input type="text" name="label" id="label" value="'.$label.'" />';
				$html.='<span class="description">(e.g. Movies)</span>';
			$html.='</div>';
			
			$html.='<div class="form-row">';
				$html.='<label for="singular_label">Singular Label</label>';
				$html.='<input type="text" name="singular_label" id="singular_label" value="'.$singular_label.'" />';
				$html.='<span class="description">(e.g. Movie)</span>';
			$html.='</div>';
			
			$html.='<div class="form-row">';
				$html.='<label for="description">Description</label>';
				$html.='<textarea name="description" id="description" rows="4" cols="40">'.$description.'</textarea>';
				//$html.='<span class="description">description</span>';
			$html.='</div>';
			
			$html.='<div class="advanced-options">';
				$html.='<div class="form-row">';
					$html.='<label for="title">Title</label>';
					$html.='<select name="title" id="title">';
						$html.='<option value="1" '.selected($title,1,false).'>True</option>';
						$html.='<option value="0" '.selected($title,0,false).'>False</option>';
					$html.='</select>';
					$html.='<span class="description">(default True)</span>';
				$html.='</div>';
				$html.='<div class="form-row">';
					$html.='<label for="thumbnail">Thumbnail</label>';
					$html.='<select name="thumbnail" id="thumbnaill">';
						$html.='<option value="1" '.selected($thumbnail,1,false).'>True</option>';
						$html.='<option value="0" '.selected($thumbnail,0,false).'>False</option>';
					$html.='</select>';
					$html.='<span class="description">(default True)</span>';
				$html.='</div>';
				$html.='<div class="form-row">';
					$html.='<label for="editor">Editor</label>';
					$html.='<select name="editor" id="editor">';
						$html.='<option value="1" '.selected($editor,1,false).'>True</option>';
						$html.='<option value="0" '.selected($editor,0,false).'>False</option>';
					$html.='</select>';
					$html.='<span class="description">(default True)</span>';
				$html.='</div>';
				$html.='<div class="form-row">';
					$html.='<label for="revisions">Revisions</label>';
					$html.='<select name="revisions" id="revisions">';
						$html.='<option value="1" '.selected($revisions,1,false).'>True</option>';
						$html.='<option value="0" '.selected($revisions,0,false).'>False</option>';
					$html.='</select>';
					$html.='<span class="description">(default True)</span>';
				$html.='</div>';								
			$html.='</div>';			
			$html.='<p class="submit"><input type="submit" name="add-cpt" id="submit" class="button button-primary" value="'.$btn_text.'"></p>';
			$html.='<input type="hidden" name="cpt-id" id="cpt-id" value='.$id.' />';
		$html.='</form>';
		
		$html.='<div class="custom-post-types-list">';
			$html.='<h3>Custom Post Types</h3>';
			
			if ($this->options['custom-post-types']) :			
				foreach ($this->options['custom-post-types'] as $cpt) :
					$html.='<div class="cpt-row">';
						$html.=$cpt['label'].'<span class="edit">[<a href="'.$this->base_url.'&edit=cpt&slug='.$cpt['name'].'">Edit</a>]</span><span class="delete">[<a href="'.$this->base_url.'&delete=cpt&slug='.$cpt['name'].'">Delete</a>]</span>';
					$html.='</div>';
				endforeach;
			endif;
			
		$html.='</div>';
	
		return $html;
	}
	
	/**
	 *
	 */
	function metaboxes_admin_page() {
		$html=null;
		
		$html.='<h3>Metaboxes</h3>';

		
		return $html;	
	}
	
	/**
	 *
	 */
	function update_custom_post_types($data=array()) {		
		if (!isset($data['name']) || $data['name']=='')
			return false;
	
		$arr=array(
			'name' => $data['name'],
			'label' => $data['label'],
			'singular_label' => $data['singular_label'],
			'description' => $data['description'],
			'title' => $data['title'],
			'thumbnail' => $data['thumbnail'],
			'editor' => $data['editor'],
			'revisions' => $data['revisions'],
		);
		
		if ($_POST['cpt-id']!=-1) :
			$this->options['custom-post-types'][$data['cpt-id']]=$arr;
		else :
			foreach ($this->options['custom-post-types'] as $cpt) :
				if ($cpt['name']==$data['name'])
					return false;
			endforeach;		
			
			$this->options['custom-post-types'][]=$arr;		
		endif;

		return update_option('mdw_cms',$this->options);
	}

	/**
	 *
	 */
	function admin_notices($class='error',$message='') {
		echo '<div class="'.$class.'"><p>'.$message.'</p></div>';
	}
}

new MDWCMSgui();
?>