<?php 
	get_header();

	/*TODO

		Is the page is in the layout?:
			TRUE:
				Render home.php and add the code.
			
			

	*/

	global $post;
	$title_hash = str_replace(" ","",$post->post_title);

?>

	<script type="text/javascript">
		jQuery(function(){
			jQuery(this).goToSection({hash:'<?php echo $title_hash; ?>'});
		});
	</script>

<?php

	require_once("home.php");
/*
			TRUE:
				- Render single.php 
				- Replace links in the menu to point back to home.php
*/


	get_footer(); 
?>