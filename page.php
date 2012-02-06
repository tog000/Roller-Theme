<?php 
	get_header();

	/*TODO

		Is the page is in the layout?:
			TRUE:
				Render home.php and add the code.
			
			

	*/
	
	if( roller_is_page_in_layout($post->ID) ){

		$title_hash = str_replace(" ","",$post->post_title);

	?>
	
		<script type="text/javascript">
			jQuery(function(){
				jQuery(this).goToSection({hash:'<?php echo $title_hash; ?>'});
			});
		</script>
	
	<?php

		require_once("home.php");
	}else{
		
		$page = $post;
		
		$bg_color = get_post_meta($page->ID, "roller_bg_color",TRUE);

		$background_image = "";
		if ( has_post_thumbnail($page->ID)) {
			$large_image_url = wp_get_attachment_image_src( get_post_thumbnail_id($page->ID), 'large');
			$background_image = "background-image:url('".$large_image_url[0]."');background-repeat:no-repeat;";
		}
	
		$inner_shadow = get_post_meta($page->ID, "roller_inner_shadow",TRUE)=="1"?"inner_shadow":"";
	
		$title_hash = str_replace(" ","",$page->post_title);
	
		echo "<div id=\"{$title_hash}\" style=\"background-color:{$bg_color};{$background_image}\" class=\"section {$inner_shadow}\">";
	
		$show_title = get_post_meta($page->ID, "roller_show_title",TRUE);
		if($show_title!="0"){
			echo "	<h1>".$page->post_title."</h1>";
		}
		
		$content = apply_filters('the_content', $page->post_content);
	
		echo $content;
		echo "</div>";
	}

	get_footer(); 
?>