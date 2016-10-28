<?php
global $mdw_cms_gallery;
global $mdw_cms_image;

class MDWCMSGallery {

	public $images;

	public $current_image=-1;

	public $image_count=0;

	public $image;

	public $id;

	public $show_indicators;

	public $show_controls;

	/**
	 * __construct function.
	 *
	 * @access public
	 * @param string $args (default: '')
	 * @return void
	 */
	public function __construct($args='') {
		global $mdw_cms_gallery;

		if (!empty($args))
			$mdw_cms_gallery=$this->query($args);
	}

	/**
	 * default_query_vars function.
	 *
	 * @access public
	 * @return void
	 */
	public function default_query_vars() {
		$array=array(
			'id' => 'gallery',
			'image_ids' => '',
			'size' => 'full',
			'show_indicators' => true,
			'show_controls' => true,
		);

		return $array;
	}

	/**
	 * set_query_vars function.
	 *
	 * @access public
	 * @param string $query (default: '')
	 * @return void
	 */
	public function set_query_vars($query='') {
		$args=wp_parse_args($query, $this->default_query_vars());

		if (!empty($args['image_ids']) && !is_array($args['image_ids']))
			$args['image_ids']=explode(',', $args['image_ids']);

		if ($args['show_indicators']) :
			$this->show_indicators=true;
		else :
			$this->show_indicators=false;
		endif;

		if ($args['show_controls']) :
			$this->show_controls=true;
		else :
			$this->show_controls=false;
		endif;

		return $args;
	}

	/**
	 * query function.
	 *
	 * @access public
	 * @param string $query (default: '')
	 * @return void
	 */
	public function query($query='') {
		$this->query_vars=$this->set_query_vars($query);

		$this->set_gallery_id($this->query_vars['id']);

		$this->get_images($this->query_vars);

		return $this;
	}

	/**
	 * get_images function.
	 *
	 * @access public
	 * @param string $vars (default: '')
	 * @return void
	 */
	public function get_images($vars='') {
		$images=array();

		if (!isset($vars['image_ids']) || empty($vars['image_ids']))
			return false;

		foreach ($vars['image_ids'] as $key => $image_id) :

			$metadata=wp_get_attachment_metadata($image_id);
			$image_post=get_post($image_id);

			if (!$metadata || !$image_post)
				continue;

			$image=new stdClass();
			$image->id='image-'.$key;
			$image->image=wp_get_attachment_image($image_id, $vars['size']);
			$image->caption=$image_post->post_excerpt;
			$image->description=$image_post->post_content;

			$images[]=$image;
		endforeach;

		$this->images=$images;
		$this->image_count=count($images);

		return $this->images;
	}

	/**
	 * set_gallery_id function.
	 *
	 * @access public
	 * @param mixed $id
	 * @return void
	 */
	public function set_gallery_id($id) {
		$this->id=$id;
	}

	/**
	 * have_images function.
	 *
	 * @access public
	 * @return void
	 */
	public function have_images() {
		if ($this->current_image + 1 < $this->image_count) :
			return true;
		elseif ( $this->current_image + 1 == $this->image_count && $this->image_count > 0 ) :
			$this->rewind_images();
		endif;

		return false;
	}

	/**
	 * the_image function.
	 *
	 * @access public
	 * @return void
	 */
	public function the_image() {
		global $mdw_cms_image;

		$mdw_cms_image = $this->next_image();
	}

  /**
   * next_image function.
   *
   * @access public
   * @return void
   */
  public function next_image() {
		$this->current_image++;

		$this->image = $this->images[$this->current_image];

		return $this->image;
	}

	/**
	 * rewind_images function.
	 *
	 * @access public
	 * @return void
	 */
	public function rewind_images() {
		$this->current_image = -1;

		if ( $this->image_count > 0 )
			$this->image = $this->images[0];
	}

}

/**
 * mdw_cms_gallery_have_images function.
 *
 * @access public
 * @return void
 */
function mdw_cms_gallery_have_images() {
	global $mdw_cms_gallery;

	return $mdw_cms_gallery->have_images();
}

/**
 * mdw_cms_gallery_the_image function.
 *
 * @access public
 * @return void
 */
function mdw_cms_gallery_the_image() {
	global $mdw_cms_gallery;

	$mdw_cms_gallery->the_image();
}

/**
 * mdw_cms_gallery_id function.
 *
 * @access public
 * @return void
 */
function mdw_cms_gallery_id() {
	echo 	mdw_cms_gallery_get_id();
}

/**
 * mdw_cms_gallery_get_id function.
 *
 * @access public
 * @return void
 */
function mdw_cms_gallery_get_id() {
	global $mdw_cms_gallery;

	return $mdw_cms_gallery->id;
}

/**
 * mdw_cms_image_class function.
 *
 * @access public
 * @param string $class (default: '')
 * @param mixed $image_id (default: null)
 * @return void
 */
function mdw_cms_image_class($class='', $image_id=null) {
	echo join(' ', mdw_cms_get_image_class($class, $image_id));
}

/**
 * mdw_cms_get_image_class function.
 *
 * @access public
 * @param string $class (default: '')
 * @param mixed $image_id (default: null)
 * @return void
 */
function mdw_cms_get_image_class($class='', $image_id=null) {
	global $mdw_cms_gallery, $mdw_cms_image;

	$classes=array();

	if ($class) :
		if (!is_array($class))
			$class=explode(',', $classe);

		$classes=array_map('esc_attr', $class);
	else :
		$class=array();
	endif;

	if (!$image_id)
		$image_id=$mdw_cms_image->id;

	$classes[]=$image_id;
	$classes[]='item';

	if ($mdw_cms_gallery->current_image==0)
		$classes[]='active';

	$classes=array_map('esc_attr', $classes);

	return array_unique($classes);
}

/**
 * mdw_cms_gallery_image function.
 *
 * @access public
 * @return void
 */
function mdw_cms_gallery_image() {
	echo mdw_cms_gallery_get_image();
}

/**
 * mdw_cms_gallery_get_image function.
 *
 * @access public
 * @return void
 */
function mdw_cms_gallery_get_image() {
	global $mdw_cms_image;

	return $mdw_cms_image->image;
}

/**
 * mdw_cms_gallery_image_caption function.
 *
 * @access public
 * @return void
 */
function mdw_cms_gallery_image_caption() {
	echo mdw_cms_gallery_get_image_caption();
}

/**
 * mdw_cms_gallery_get_image_caption function.
 *
 * @access public
 * @return void
 */
function mdw_cms_gallery_get_image_caption() {
	global $mdw_cms_image;

	return $mdw_cms_image->caption;
}

/**
 * mdw_cms_gallery_indicators function.
 *
 * @access public
 * @return void
 */
function mdw_cms_gallery_indicators() {
	echo mdw_cms_gallery_get_indicators();
}

/**
 * mdw_cms_gallery_get_indicators function.
 *
 * @access public
 * @return void
 */
function mdw_cms_gallery_get_indicators() {
	global $mdw_cms_gallery;

	$html=null;

	if (!$mdw_cms_gallery->image_count || !$mdw_cms_gallery->show_indicators)
		return false;

	$html.='<ol class="carousel-indicators">';
		foreach ($mdw_cms_gallery->images as $key => $image) :
			if ($key==0) :
				$class='active';
			else :
				$class='';
			endif;

	  	$html.='<li data-target="#mdw-cms-carousel-'.mdw_cms_gallery_get_id().'" data-slide-to="'.$key.'" class="'.$class.'"></li>';
		endforeach;
	$html.='</ol>';

	return $html;
}

/**
 * mdw_cms_gallery_controls function.
 *
 * @access public
 * @return void
 */
function mdw_cms_gallery_controls() {
	echo mdw_cms_gallery_get_controls();
}

/**
 * mdw_cms_gallery_get_controls function.
 *
 * @access public
 * @return void
 */
function mdw_cms_gallery_get_controls() {
	global $mdw_cms_gallery;

	$html=null;

	if (!$mdw_cms_gallery->image_count || !$mdw_cms_gallery->show_controls)
		return false;

  $html.='<a class="left carousel-control" href="#mdw-cms-carousel-'.mdw_cms_gallery_get_id().'" role="button" data-slide="prev">';
    $html.='<span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>';
    $html.='<span class="sr-only">Previous</span>';
  $html.='</a>';
  $html.='<a class="right carousel-control" href="#mdw-cms-carousel-'.mdw_cms_gallery_get_id().'" role="button" data-slide="next">';
    $html.='<span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>';
    $html.='<span class="sr-only">Next</span>';
  $html.='</a>';

	return $html;
}
?>
