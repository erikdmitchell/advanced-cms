<?php
class MDWBootstrapSlider {

	public $slider=null;
	public $version='1.0.5';

	private $posts=null;

	function __construct() {
		add_shortcode('bootstrap-slider',array($this,'slider_shortcode'));
	}
	
	function setup_slider($config=array()) {
		$default_config=array(
			'slider_id' => 'slider-id',
			'post_type' => 'posts',
			'limit' => -1,
			'indicators' => true,
			'slides' => true,
			'captions' => false,
			'caption_field' => 'excerpt',
			'more_button' => true,
			'more_text' => 'Read More',
			'controls' => true
		);

		$this->config=array_merge($default_config,$config);

		$args=array(
			'posts_per_page' => $this->config['limit'],
			'post_type' => $this->config['post_type'],
		);
		
		$this->posts=get_posts($args);

		$this->build_slider();	
	}

	function slider_shortcode($atts) {
		foreach ($atts as $key => $value) :
			if ($value=='false') :
				$atts[$key]=0;
			elseif ($value=='true') :
				$atts[$key]=1;
			endif;
		endforeach;	

		$this->setup_slider($atts);
		
		return $this->slider;
	}
	
	public function get_slider() {
		return $this->slider;
	}
	
	function build_slider() {
		$html=null;
	
		$html.='<div id="'.$this->config['slider_id'].'" class="carousel slide" data-ride="carousel">';
			$html.=$this->generate_indicators();
			$html.=$this->generate_slides($this->config['captions']);
			$html.=$this->generate_controls();
		$html.='</div>';
		
		$this->slider=$html;
	}
	
	function generate_indicators() {
		if (!count($this->posts) || !$this->config['indicators'])
			return false;
				
		$html=null;
		$counter=0;
		
		$html.='<ol class="carousel-indicators">';
			foreach ($this->posts as $post) :
				if ($counter==0) :
					$class='active';
				else :
					$class=null;
				endif;
				
				$html.='<li data-target="#'.$this->config['slider_id'].'" data-slide-to="'.$counter.'" class="'.$class.'"></li>';
			  
			  $counter++;
		  endforeach;
		$html.='</ol>';
		  
		return $html;
	}

	function generate_slides($captions=false) {
		if (!count($this->posts) || !$this->config['slides'])
			return false;
			
		$html=null;
		$counter=0;

		$html.='<div class="carousel-inner">';
			foreach ($this->posts as $post) :
				if ($counter==0) :
					$class='active';
				else :
					$class=null;
				endif;
				
				$html.='<div class="item '.$class.'">';
					$html.=get_the_post_thumbnail($post->ID,'slide-image');
					if ($captions) :
						$html.='<div class="carousel-caption">';
							$html.='<p class="caption-text">'.$this->get_caption($post).'</p>';
							if ($this->config['more_button'])
								$html.='<p><a class="btn btn-primary btn-lg" role="button">'.$this->config['more_text'].'</a></p>';
						$html.='</div>';
					endif;
				$html.='</div>'; 
				
				$counter++;
			endforeach;
		$html.='</div>';
		
		return $html;		
	}

	function generate_controls() {
		if (!count($this->posts) || !$this->config['controls'])
			return false;
				
		$html=null;
		
		$html.='<a class="left carousel-control" href="#'.$this->config['slider_id'].'" data-slide="prev">';
			$html.='<span class="glyphicon glyphicon-chevron-left"></span>';
		$html.='</a>';
		$html.='<a class="right carousel-control" href="#'.$this->config['slider_id'].'" data-slide="next">';
			$html.='<span class="glyphicon glyphicon-chevron-right"></span>';
		$html.='</a>';
		
		return $html;		
	}
	
	function get_caption($post=false) {
		$html=null;
		if (!$post)
			return false;
		
		switch ($this->config['caption_field']):
			case 'excerpt' :
				$html.=$post->post_excerpt;
				break;
			case 'content' :
				$html.=$post->post_content;
				break;
			case 'title' :
				$html.=$post->post_title;
				break;
			default:
				$html.=$post->post_excerpt;
				break;
		endswitch;
			
		return $html;
	}	

}

new MDWBootstrapSlider();
?>