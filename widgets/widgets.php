<?php
/*
A series of useful utility widgets to include content 
from pages, posts, custom post types in widgetized 
areas. flexible and usefule.

@marshalloram
*/
	

/**
 *	content widget
**/
class MDW_Content_Widget extends WP_Widget {

	/**
	 * Register widget with WordPress.
	 */
	function __construct() {
		parent::__construct(
			'mdw_content_widget',
			__('Content Widget', 'text_domain'),
			array(
				'description' => __( 'Displays a certain set of posts.', 'text_domain' ), 
			),
			array()
		);
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
		$title = apply_filters( 'widget_title', $instance['title'] );
		$excerpt_length=$instance['excerpt-length'];
		$tags=$instance['tags'];
		$more=html_entity_decode($instance['more']);
		$thumbnail_size=$instance['thumbnail-size'];
		if (is_null($thumbnail_size)) :
			$thumbnail_size='thumbnail';
		endif;
		
		if ($instance['more-link']=='on') :
			$more_link=true;
		else :
			$more_link=false;
		endif;
		
		// determine if more link is per article or just once //
		$more_link_each=false;
		if ($more_link) :
			if ($instance['more-link-each']=='on') :
				$more_link_each=true;
			endif;
		endif;
		
		if (empty($instance['more-link-url'])) :
			$more_link_url=false;
		else :
			$more_link_url=$instance['more-link-url'];
		endif;
	
		$post_args=array(
			'posts_per_page' => $instance['posts'],
			'post_type' => $instance['post-type'],
			$instance['post-taxonomy'] => $instance['post-category']
		);
		
		$posts=get_posts($post_args);

		echo $args['before_widget'];
		
		echo '<div class="content-widget category_'.$instance['post-category'].'">';		
			if ($instance['thumbnail-image']):
				$post=get_post($instance['post-category']);
				$thumbnail_size=$instance['thumbnail-size'];
				if(has_post_thumbnail($post->ID)) :
					echo '<div class="featured-image">'.get_the_post_thumbnail($post->ID,$thumbnail_size ).'</div>';
				endif;
			endif;
			if ($instance['post-type']=='page') :
				$post=get_post($instance['post-category']);
				$classes=get_post_class('',$post->ID);
				$classes=implode(' ',$classes);

				echo '<article class="'.$classes.'" id="post-'.$post->ID.'">';				
					/* echo '<h3>'.get_the_title($instance['post-category']).'</h3>'; */ 
					
					echo '<h1>'.$instance['title'].'</h1>';
					if ($instance['page-excerpt']>'') :
						echo $instance['page-excerpt'];
					else :					
						if ($instance['excerpt']=='on') :
							echo $this->pippin_excerpt_by_id_extended($post->ID,$excerpt_length,$tags,$more,$more_link);
						else :
							echo apply_filters('the_content', $post->post_content);
						endif;
					endif;
					if ($more_link ) :
					echo '<a href="'.get_permalink($post->ID).'" class="btn post-'.$post->ID.'">'.$instance['more'].'</a>';
				endif;
				echo '</article>';
			else :
				if ( ! empty( $title ) )
					echo $args['before_title'] . $title . $args['after_title'];
				
				foreach ($posts as $post) :
					$classes=get_post_class('',$post->ID);
					$classes=implode(' ',$classes);
					echo '<article class="'.$classes.'" id="post-'.$post->ID.'">';
						echo '<h3><a href="'.get_permalink($post->ID).'">'.get_the_title($post->ID).'</a></h3>';
						if ($instance['excerpt']=='on') :
							echo $this->pippin_excerpt_by_id_extended($post->ID,$excerpt_length,$tags,$more,$more_link);
						elseif ($more_link_each) :
							echo $this->post_more_link($post->ID,$instance['more'],$more_link_url);
						endif;
					echo '</article>';
				endforeach;
				
				if ($more_link && !$more_link_each) :
					echo $this->post_more_link($post->ID,$instance['more'],$more_link_url);
				endif;
				
		endif;
		echo '</div>';

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
		wp_enqueue_script('mdwm-widgets-js',plugins_url('/mdwm-widgets.js',__FILE__));
		wp_enqueue_style('mdwm-content-widget-form-css',plugins_url('/mdwm-widgets.css',__FILE__));

		if (isset($instance['title'])) :
			$title=$instance['title'];
		else :
			$title=__('New title','text_domain');
		endif;
		
		if (isset($instance['thumbnail-image'])):
			$thumbnail_image=$instance['thumbnail-image'];
		else :
			$thumbnail_image=null;
		endif;
		
		if (isset($instance['thumbnail-size'])):
			$thumbnail_size=$instance['thumbnail-size'];
		else :
			$thumbnail_size=null;
		endif;
		
		if (isset($instance['post-type'])):
			$post_type=$instance['post-type'];
		else :
			$post_type=null;
		endif;

		if (isset($instance['post-category'])):
			$post_category=$instance['post-category'];
		else :
			$post_category=null;
		endif;

		if (isset($instance['post-taxonomy']) && !empty($instance['post-taxonomy'])):
			$post_taxonomy=$instance['post-taxonomy'];
		else :
			$post_taxonomy=null;
		endif;
		
		if (isset($instance['posts'])) :
			$posts=$instance['posts'];
		else :
			$posts=5;
		endif;		

		if (isset($instance['excerpt'])):
			$excerpt=$instance['excerpt'];
		else :
			$excerpt=null;
		endif;
		
		if (isset($instance['page-excerpt'])):
			$page_excerpt=$instance['page-excerpt'];
		else :
			$$page_excerpt=null;
		endif;

		if (isset($instance['excerpt-length'])):
			$excerpt_length=$instance['excerpt-length'];
		else :
			$excerpt_length=null;
		endif;
		
		if (isset($instance['tags'])):
			$tags=$instance['tags'];
		else :
			$tags=null;
		endif;		

		if (isset($instance['more'])):
			$more=$instance['more'];
		else :
			$more=null;
		endif;

		if (isset($instance['more-link'])):
			$more_link=$instance['more-link'];
		else :
			$more_link=null;
		endif;

		if (isset($instance['more-link-each']) && !empty($instance['more-link-each'])):
			$more_link_each=$instance['more-link-each'];
		else :
			$more_link_each=null;
		endif;

		if (isset($instance['more-link-url']) && !empty($instance['more-link-url'])):
			$more_link_url=$instance['more-link-url'];
		else :
			$more_link_url=null;
		endif;
		?>
		<div id="<?php echo $this->id; ?>">
			<p>
				<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' ); ?></label> 
				<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>">
			</p>
			<p>
		 		<label for="<?php echo $this->get_field_id('thumbnail-image'); ?>"><?php _e('Display Thumbnail:'); ?></label>
				<input class="checkbox disp-thumbnail-checkbox" type="checkbox" id="<?php echo $this->get_field_id('thumbnail-image'); ?>" name="<?php echo $this->get_field_name('thumbnail-image'); ?>" <?php checked($thumbnail_image,'on'); ?>>
			</p>
			<p>
				<label for="<?php echo $this->get_field_id('thumbnail-size'); ?>"><?php _e('Thumbnail Size:'); ?></label> 
				<?php echo $this->images_sizes_dropdown($this->get_field_name('thumbnail-size'),$thumbnail_size,$this->id); ?>
			</p>
			<p>
				<label for="<?php echo $this->get_field_id('post-type'); ?>"><?php _e('Post Type:'); ?></label> 
				<?php echo $this->post_types_dropdown($this->get_field_name('post-type'),$post_type,$this->id); ?>
			</p>
			<p>
				<label for="<?php echo $this->get_field_id('post-category'); ?>"><span class="for-post-only"><?php _e('Category:'); ?></span><span class="for-page-only"><?php _e('Page:'); ?></span></label>
				<?php echo $this->get_all_taxonomies($post_type,$post_category,$this->id); ?>
			</p>	
			<p class="for-post-only">
				<label for="<?php echo $this->get_field_id('posts'); ?>"><?php _e('Posts:'); ?></label> 
				<input class="" id="<?php echo $this->get_field_id('posts'); ?>" name="<?php echo $this->get_field_name('posts'); ?>" type="text" value="<?php echo esc_attr($posts); ?>" size="3">
			</p>
			<p class="for-post-only">
		 		<label for="<?php echo $this->get_field_id('excerpt'); ?>"><?php _e('Display Excerpt:'); ?></label>
				<input class="checkbox disp-excerpt-checkbox" type="checkbox" id="<?php echo $this->get_field_id('excerpt'); ?>" name="<?php echo $this->get_field_name('excerpt'); ?>" <?php checked($excerpt,'on'); ?>>
			</p>
			<p class="for-page-only">
		 		<label for="<?php echo $this->get_field_id('page-excerpt'); ?>"><?php _e('Display Custom Excerpt:'); ?></label>
				<textarea class="widefat" id="<?php echo $this->get_field_id('page-excerpt'); ?>" name="<?php echo $this->get_field_name('page-excerpt'); ?>"><?php echo esc_attr($page_excerpt); ?></textarea>
			</p>	
			<p class="disp-excerpt">
				<label for="<?php echo $this->get_field_id('excerpt-length'); ?>"><?php _e('Excerpt Length:'); ?></label> 
				<input class="" id="<?php echo $this->get_field_id('excerpt-length'); ?>" name="<?php echo $this->get_field_name('excerpt-length'); ?>" type="text" value="<?php echo esc_attr($excerpt_length); ?>" size="3">
			</p>
			<p class="disp-excerpt">
				<label for="<?php echo $this->get_field_id('tags'); ?>"><?php _e('Tags:'); ?></label> 
				<input class="widefat" id="<?php echo $this->get_field_id('tags'); ?>" name="<?php echo $this->get_field_name('tags'); ?>" type="text" value="<?php echo $tags; ?>">
				<span class="description">The allowed HTML tags in the excerpt.</span>
			</p>
			<p>
		 		<label for="<?php echo $this->get_field_id('more-link'); ?>"><?php _e('More Link:'); ?></label>
				<input class="checkbox more-link-checkbox" type="checkbox" id="<?php echo $this->get_field_id('more-link'); ?>" name="<?php echo $this->get_field_name('more-link'); ?>" <?php checked($more_link,'on'); ?>>
			</p>
			<p class="more-link">
		 		<label for="<?php echo $this->get_field_id('more-link-each'); ?>"><?php _e('Add More Link to Each Post:'); ?></label>
				<input class="checkbox" type="checkbox" id="<?php echo $this->get_field_id('more-link-each'); ?>" name="<?php echo $this->get_field_name('more-link-each'); ?>" <?php checked($more_link_each,'on'); ?>>
			</p>
			<p class="more-link">
				<label for="<?php echo $this->get_field_id('more'); ?>"><?php _e('More Link Text:'); ?></label> 
				<input class="widefat" id="<?php echo $this->get_field_id('more'); ?>" name="<?php echo $this->get_field_name('more'); ?>" type="text" value="<?php echo $more; ?>">
			</p>
			<p class="more-link">
				<label for="<?php echo $this->get_field_id('more-link-url'); ?>"><?php _e('More Link URL:'); ?></label> 
				<input class="widefat" id="<?php echo $this->get_field_id('more-link-url'); ?>" name="<?php echo $this->get_field_name('more-link-url'); ?>" type="text" value="<?php echo $more_link_url; ?>">
				<span class="description">Default is permalink of post.</span>
			</p>			
												
			<input type="hidden" id="<?php echo $this->get_field_id('post-category'); ?>" name="<?php echo $this->get_field_name('post-category'); ?>" value="<?php echo $post_category; ?>" />
			<input type="hidden" id="<?php echo $this->get_field_id('post-taxonomy'); ?>" name="<?php echo $this->get_field_name('post-taxonomy'); ?>" value="<?php echo $post_taxonomy; ?>" />
		</div><!-- #widget id -->
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

		$instance['title']=(!empty($new_instance['title'])) ? strip_tags($new_instance['title']) : '';
		$instance['thumbnail-image']=(!empty($new_instance['thumbnail-image'])) ? strip_tags($new_instance['thumbnail-image']) : '';
		$instance['thumbnail-size']=(!empty($new_instance['thumbnail-size'])) ? strip_tags($new_instance['thumbnail-size']) : '';
		$instance['post-type']=(!empty($new_instance['post-type'])) ? strip_tags($new_instance['post-type']) : '';
		$instance['post-category']=(!empty($new_instance['post-category'])) ? strip_tags($new_instance['post-category']) : '';
		$instance['post-taxonomy']=(!empty($new_instance['post-taxonomy'])) ? strip_tags($new_instance['post-taxonomy']) : '';
		$instance['posts']=(!empty($new_instance['posts'])) ? strip_tags($new_instance['posts']) : '';
		$instance['excerpt']=(!empty($new_instance['excerpt'])) ? strip_tags($new_instance['excerpt']) : '';
		$instance['page-excerpt']=(!empty($new_instance['page-excerpt'])) ? strip_tags($new_instance['page-excerpt']) : '';
		$instance['excerpt-length']=(!empty($new_instance['excerpt-length'])) ? strip_tags($new_instance['excerpt-length']) : '';
		$instance['tags']=(!empty($new_instance['tags'])) ? htmlentities($new_instance['tags']) : '';
		$instance['more']=(!empty($new_instance['more'])) ? htmlentities($new_instance['more']) : '';
		$instance['more-link']=(!empty($new_instance['more-link'])) ? strip_tags($new_instance['more-link']) : '';
		$instance['more-link-each']=(!empty($new_instance['more-link-each'])) ? strip_tags($new_instance['more-link-each']) : '';
		$instance['more-link-url']=(!empty($new_instance['more-link-url'])) ? htmlentities($new_instance['more-link-url']) : '';

		return $instance;
	}
	
	// some custom functions to get our content //
	
	/**
	 *	converts post types into a dropdown menu
	**/
	function post_types_dropdown($name,$selected,$widget_id=0) {
		$html=null;
		$post_types=get_post_types();
		
		$post_types_ignore=array('attachment','revision','nav_menu_item');
		
		$html.='<select name="'.$name.'" class="post-type-dd" data-type-widget-id="'.$widget_id.'">';
			$html.='<option>Select One</option>';
			foreach ($post_types as $post_type) :
				if (!in_array($post_type,$post_types_ignore)) :
					$html.='<option value="'.$post_type.'" '.selected($selected,$post_type,false).'>'.ucwords($post_type).'</option>';
				endif;
			endforeach;
		$html.='</select>';
		
		return $html;
	}
	
	/**
	 *	converts image sizes into a dropdown menu
	**/
	function images_sizes_dropdown($name,$selected,$widget_id=0) {
		
		$html=null;
		$images_sizes=get_intermediate_image_sizes();
			
		$html.='<select name="'.$name.'" class="post-type-dd" data-type-widget-id="'.$widget_id.'">';
			$html.='<option>Select One</option>';
			foreach ($images_sizes as $images_size) :
				$html.='<option value="'.$images_size.'" '.selected($selected,$images_size,false).'>'.$images_size.'</option>';
			endforeach;
		$html.='</select>';
		
		return $html;
	}
		
	/**
	 *	get a list of taxonomies for all our post types
	**/
	function get_all_taxonomies($selected_post_type,$selected_val,$widget_id=0) {
		$html=null;
		$all_post_types=get_post_types();
		$post_types_ignore=array('attachment','revision','nav_menu_item');
		$post_types_tax_arr=array();
		
		$term_args=array(
			'hide_empty' => 0
		);
		
		// remove uneeded post types //
		foreach ($all_post_types as $post_type) :
			if (!in_array($post_type,$post_types_ignore)) :
				array_push($post_types_tax_arr,$post_type);
			endif;
		endforeach;
				
		// get our taxonomies //
		foreach ($post_types_tax_arr as $key => $post_type) :
			$taxonomy_names=get_object_taxonomies($post_type);

			foreach ($taxonomy_names as $tax_name_key => $taxonomy_name) :
				$terms=get_terms($taxonomy_name,$term_args);
				$taxonomy_names[$taxonomy_name]=$terms;
				unset($taxonomy_names[$tax_name_key]);
			endforeach;

			$post_types_tax_arr[$key]=array(
				'post_type' => $post_type,
				'tax' => $taxonomy_names
			);
		endforeach;
		
		// setup out pages (taxonomy) //
		foreach ($post_types_tax_arr as $key => $post_type) :
			if ($post_type['post_type']=='page') :
				$pages=get_pages();
				$pages_arr=array();
				
				foreach ($pages as $page) :
					$object=new stdClass();
						$object->term_id=$page->ID;
						$object->name=$page->post_title;
						$object->slug=$page->post_name;
						//$object->taxonomy='category';
						$object->parent=$page->post_parent;
						
					array_push($pages_arr,$object);
				endforeach;
				
				$post_types_tax_arr[$key]['tax']['pages']=$pages_arr;
			endif;
		endforeach;

		foreach ($post_types_tax_arr as $types) :
			if ($selected_post_type==$types['post_type']) {
				$class='active';
			} else {
				$class=null;
			}
			
			$html.='<select name="'.$types['post_type'].'" id="post-type-tax-'.$types['post_type'].'" class="post-type-tax '.$class.'" data-type-widget-id="'.$widget_id.'">';
				$html.='<option>All</option>';
				foreach ($types['tax'] as $key => $tax) :
					foreach ($tax as $term) :				
						if ($key=='category' || $key=='pages') :
							$value=$term->term_id;
						else :
							$value=$term->slug;
						endif;
						$html.='<option value="'.$value.'" data-type-tax="'.$key.'" '.selected($selected_val,$value,false).'>'.$term->name.'</option>';
					endforeach;
				endforeach;
			$html.='</select>';
		endforeach;

		return $html;
	}
	
	/*
	* Gets the excerpt of a specific post ID or object
	* @param - $post - object/int - the ID or object of the post to get the excerpt of
	* @param - $length - int - the length of the excerpt in words
	* @param - $tags - string - the allowed HTML tags. These will not be stripped out
	* @param - $extra - string - text to append to the end of the excerpt
	* @param - $link - true/false - display $extra as a link (wp permalink)
	*/
	function pippin_excerpt_by_id_extended($post, $length = 10, $tags = '<a><em><strong>', $extra = ' . . .',$link=false) {
	 
		if(is_int($post)) {
			// get the post object of the passed ID
			$post = get_post($post);
		} elseif(!is_object($post)) {
			return false;
		}
	 
		if(has_excerpt($post->ID)) {
			$the_excerpt = $post->post_excerpt;
			return apply_filters('the_content', $the_excerpt);
		} else {
			$the_excerpt = $post->post_content;
		}
	 
		$the_excerpt = strip_shortcodes(strip_tags($the_excerpt), $tags);
		$the_excerpt = preg_split('/\b/', $the_excerpt, $length * 2+1);
		$excerpt_waste = array_pop($the_excerpt);
		$the_excerpt = implode($the_excerpt);
		
		if (!$link) :
			$the_excerpt .= $extra;
		else :
			$the_excerpt .= '<a href="'.get_permalink($post->ID).'">'.$extra.'</a>';
		endif;
		
		return apply_filters('the_content', $the_excerpt);
	}
	
	function post_more_link($post_id,$link_text,$override=false) {
		if ($override) :
			$url=$override;
		else :
			$url=get_permalink($post_id);
		endif;
		
		$link='<a href="'.$url.'">'.$link_text.'</a>';
		return $link;
	}
}

// register our widgets //
add_action('widgets_init','mdw_widgets');
function mdw_widgets() {
	register_widget('MDW_Content_Widget');
}

?>