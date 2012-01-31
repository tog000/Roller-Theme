<?php

class RollerShortcodes{

	function __construct(){
		add_shortcode( 'bigbutton', 	array(&$this, 'bigbutton'));

		add_shortcode('one_half', 		array(&$this, 'et_columns'));
		add_shortcode('one_half_last', 	array(&$this, 'et_columns'));
		add_shortcode('one_third', 		array(&$this, 'et_columns'));
		add_shortcode('one_third_last',	array(&$this, 'et_columns'));
		add_shortcode('one_fourth', 	array(&$this, 'et_columns'));
		add_shortcode('one_fourth_last',array(&$this, 'et_columns'));
		add_shortcode('two_third', 		array(&$this, 'et_columns'));
		add_shortcode('two_third_last',	array(&$this, 'et_columns'));
		add_shortcode('three_fourth',	array(&$this, 'et_columns'));
		add_shortcode('three_fourth_last',
										array(&$this, 'et_columns'));

		add_filter("mce_buttons", array(&$this, "enable_style_select"));

		add_filter('tiny_mce_before_init', 
										array(&$this, 'add_custom_classes'));

		add_shortcode( 'product_showcase', 
										array(&$this, 'product_showcase'));

		add_shortcode( 'product',		array(&$this, 'product'));
		add_shortcode( 'product_first',	array(&$this, 'product'));
		add_shortcode( 'product_last',	array(&$this, 'product'));
	}

	function add_custom_classes($init_array){
		if(array_key_exists("theme_advanced_styles",$init_array)){
			$init_array['theme_advanced_styles'] .= roller_get_option("styles");
		}else{
			$init_array['theme_advanced_styles'] = roller_get_option("styles");
		}
		return $init_array;
	}

	function enable_style_select($buttons) {
		$buttons[ ] = 'styleselect';
		return $buttons;
	}

	function bigbutton($atts,$content = null){
		extract( shortcode_atts( array(
			'color' => '#8FAAC6',
			'href' => '#',
			'title' => '',
			'width' => ''
		), $atts ) );

		if($width=="full"){
			$width = "80%;display:block;";
		}

		if(strstr($content,"<a")===FALSE){
			return "<a href=\"".$href."\" title=\"".$title."\" class=\"big-btn\" style=\"background-color:".$color.";width:".$width."\">".$content."</a>";
		}else{
			$pos = strpos($content,"<a")+2;
			$new_link = substr($content,0,$pos)." class=\"big-btn\" style=\"background-color:".$color.";width:".$width."\" ".substr($content,$pos,strlen($content));
			return $new_link;
		}
	}

	function et_columns($atts, $content = null, $name='') {
		$content = do_shortcode($content);
		
		$pos = strpos($name,'_last');	

		if($pos !== false)
			$name = str_replace('_last',' last',$name);
		
		extract( shortcode_atts( array(
			'separator' => '1'
		), $atts ) );

		if($separator != '1'){
			$name .= " no-separator";
		}

		$output = "<div class='{$name}'>
						{$content}
					</div>";
		if($pos !== false) 
			$output .= "<div class='clear'></div>";
		
		return $output;
	}

	function product_showcase($atts, $content = null){
		$this->clean_content(&$content);

		return '<div class="product_showcase">
					<div id="products_list">'.$content.'</div>
					<div id="product_image"></div>
				</div>';
	}

	function product($atts, $content = null, $name=''){
		$this->clean_content(&$content);
		
		if(strpos($name,'_first'))
			$name = str_replace('_first',' first',$name);
		
		if(strpos($name,'_last'))
			$name = str_replace('_last',' last',$name);
	
		
		return '<div class="'.$name.'">'.$content.'</div>';
	}

	function clean_content($content){
		$content = do_shortcode(shortcode_unautop( $content ));
		if(strpos('</p>',$content)==0){
			$content = substr($content,4);
		}
	}

}

?>