<?php

/**
 * mdw_cms_gallery_shortcode function.
 *
 * @access public
 * @param mixed $atts
 * @return void
 */
function mdw_cms_gallery_shortcode($atts) {
	global $post;

  extract(shortcode_atts(array(
    'id' => ''
  ), $atts));

  if (empty($id))
  	return false;

  $atts=new stdClass();
  $atts->id=$id;
  $atts->image_ids=get_post_meta($post->ID, $id, true);

  return mdw_cms_get_template_part('gallery', $atts);
}
add_shortcode('mdw-cms-gallery', 'mdw_cms_gallery_shortcode');

/**
 * mdw_cms_setup_gallery_images function.
 *
 * @access public
 * @param string $image_ids (default: '')
 * @return void
 */
function mdw_cms_setup_gallery_images($image_ids='') {
	if (empty($image_ids))
		return false;

	$images=array();
	$size='full';

	if (!is_array($image_ids))
		$image_ids=explode(',', $image_ids);

	foreach ($image_ids as $image_id) :
		$metadata=wp_get_attachment_metadata($image_id);

		$image=new stdClass();
		$image->image=wp_get_attachment_image($image_id, $size);
		$image->caption=$metadata['image_meta']['caption'];

		$images[]=$image;
	endforeach;

	return $images;
}

function mdw_cms_gallery_item_classes($classes='') {
	global $image;

print_r($image);
}

global $mdw_cms_gallery;

class MDWCMSGallery {

	public $posts;

	public $query;

	public $current_post=-1;

	public $post_count=0;

	public $post;

	public $found_posts=0;

	public function __construct($image_ids='') {
		global $mdw_cms_gallery;

		if (!empty($image_ids))
			$mdw_cms_gallery=$this->query($image_ids);
	}

/*
	public function default_query_vars() {
		$array=array(
			'per_page' => 30,
			'order_by' => '', // date (races) -- name (riders)
			'order' => 'DESC', // DESC (races -- ASC (riders))
			'class' => false, // races
			'season' => false, // races, rider ranks
			'nat' => false,
			'name' => false, // riders
			'search' => false,
			'rider_id' => 0, // riders
			'start_date' => false, // races
			'end_date' => false, // races
			'paged' => get_query_var('page'),
			'type' => 'races',
			'rankings' => false, // riders
			'results' => false, // riders
			'meta' => array()
		);

		// for our admin, we pass a get var //
		if (is_admin() && empty($array['paged']) && isset($_GET['paged']))
			$array['paged']=$_GET['paged'];

		return $array;
	}
*/
/*
	public function set_query_vars($query='') {
		$args=wp_parse_args($query, $this->default_query_vars());

		// set default order by type if need be //
		if (empty($args['order_by'])) :
			switch ($args['type']) :
				case 'races':
					$args['order_by']='date';

					if (empty($args['order']))
						$args['order']='DESC';
					break;
				case 'riders':
					break;
			endswitch;
		endif;

		// setup some defaults for rankings //
		if ($args['rankings']) :
			if (!$args['season'] || empty($args['season'])) :
				$args['season']=uci_results_get_default_rider_ranking_season();
				$args['week']=uci_results_get_default_rider_ranking_week();
			endif;
		endif;

		// check for search //
		if (isset($_GET['search']) || (isset($query['search']) && $query['search']))
			$this->is_search=true;

		// due to a weird api pagination issue //
		if (isset($args['api_page']))
			$args['paged']=$args['api_page'];

		// check if paged //
		if ($args['paged'])
			$this->is_paged=true;

		return $args;
	}
*/


	public function query($image_ids='') {
		//$this->query_vars=$this->set_query_vars($query);
		//$q=$this->query_vars;


		$this->get_images();


		return $this;
	}

	public function get_images() {
		global $wpdb;

		$posts=$wpdb->get_results($this->query);

		// set total number of posts found //
		$this->found_posts = $wpdb->get_var('SELECT FOUND_ROWS()');

		$this->posts=$posts;
		$this->post_count=count($posts);

		return $this->posts;
	}




	/**
	 * have_posts function.
	 *
	 * @access public
	 * @return void
	 */
	public function have_posts() {
		if ($this->current_post + 1 < $this->post_count) :
			return true;
		elseif ( $this->current_post + 1 == $this->post_count && $this->post_count > 0 ) :
			$this->rewind_posts();
		endif;

		return false;
	}

	/**
	 * the_post function.
	 *
	 * @access public
	 * @return void
	 */
	public function the_post() {
		global $uci_results_post;

		$uci_results_post = $this->next_post();
	}

  /**
   * next_post function.
   *
   * @access public
   * @return void
   */
  public function next_post() {
		$this->current_post++;

		$this->post = $this->posts[$this->current_post];

		return $this->post;
	}

	/**
	 * rewind_posts function.
	 *
	 * @access public
	 * @return void
	 */
	public function rewind_posts() {
		$this->current_post = -1;

		if ( $this->post_count > 0 )
			$this->post = $this->posts[0];
	}


}
?>