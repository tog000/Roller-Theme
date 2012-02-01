<?php
/*
Author: Eddie Machado
URL: htp://themble.com/roller/

This is where you can drop your custom functions or
just edit things like thumbnail sizes, header images, 
sidebars, comments, ect.
*/

// Initialize Roller
require_once('library/roller.php');				// core functions
if (class_exists("Roller"))
	new Roller();

// Admin Functions
if( is_admin() ){
	
	require_once('library/admin.php');         	// custom admin functions
	if (class_exists("RollerAdmin"))
		new RollerAdmin();
}

require_once('library/shortcodes.php'); 	// shortcode support
	if (class_exists("RollerShortcodes"))
		new RollerShortcodes();

require_once('library/plugins.php');          // plugins & extra functions (optional)
#require_once('library/custom-post-type.php'); // custom post type example

/************* THUMBNAIL SIZE OPTIONS *************/

// Thumbnail sizes
add_image_size( 'roller-thumb-600', 600, 150, true );
add_image_size( 'roller-thumb-300', 300, 100, true );
/* 
to add more sizes, simply copy a line from above 
and change the dimensions & name. As long as you
upload a "featured image" as large as the biggest
set width or height, all the other sizes will be
auto-cropped.

To call a different size, simply change the text
inside the thumbnail function.

For example, to call the 300 x 300 sized image, 
we would use the function:
<?php the_post_thumbnail( 'roller-thumb-300' ); ?>
for the 600 x 100 image:
<?php the_post_thumbnail( 'roller-thumb-600' ); ?>

You can change the names and dimensions to whatever
you like. Enjoy!
*/

/************* COMMENT LAYOUT *********************/
		
// Comment Layout
function roller_comments($comment, $args, $depth) {
   $GLOBALS['comment'] = $comment; ?>
	<li <?php comment_class(); ?>>
		<article id="comment-<?php comment_ID(); ?>" class="clearfix">
			<header class="comment-author vcard">
				<?php echo get_avatar($comment,$size='32',$default='<path_to_url>' ); ?>
				<?php printf(__('<cite class="fn">%s</cite>'), get_comment_author_link()) ?>
				<time><a href="<?php echo htmlspecialchars( get_comment_link( $comment->comment_ID ) ) ?>"><?php printf(__('%1$s'), get_comment_date(),  get_comment_time()) ?></a></time>
				<?php edit_comment_link(__('(Edit)'),'  ','') ?>
			</header>
			<?php if ($comment->comment_approved == '0') : ?>
       			<div class="help">
          			<p><?php _e('Your comment is awaiting moderation.') ?></p>
          		</div>
			<?php endif; ?>
			<section class="comment_content clearfix">
				<?php comment_text() ?>
			</section>
			<?php comment_reply_link(array_merge( $args, array('depth' => $depth, 'max_depth' => $args['max_depth']))) ?>
		</article>
    <!-- </li> is added by wordpress automatically -->
<?php
} 

/************* RELATED POSTS *********************/

function roller_related_posts() {
	echo '<ul id="roller-related-posts">';
	global $post;
	$tags = wp_get_post_tags($post->ID);
	$tag_arr = "";
	if($tags) {
		foreach($tags as $tag) { $tag_arr .= $tag->slug . ','; }
		$args = array(
			'tag' => $tag_arr,
			'numberposts' => 5, /* you can change this to show more */
			'post__not_in' => array($post->ID)
		);
		$related_posts = get_posts($args);
		if($related_posts) {
			foreach ($related_posts as $post) : setup_postdata($post); ?>
				<li class="related_post"><a href="<?php the_permalink() ?>" title="<?php the_title_attribute(); ?>"><?php the_title(); ?></a></li>
			<?php endforeach; } 
		else { ?>
			<li class="no_related_post">No Related Posts Yet!</li>
		<?php }
	}
	wp_reset_query();
	echo '</ul>';
}

/************* NUMERIC NAVIGATION *********************/

function roller_page_navi($before = '', $after = '') {
	global $wpdb, $wp_query;
	$request = $wp_query->request;
	$posts_per_page = intval(get_query_var('posts_per_page'));
	$paged = intval(get_query_var('paged'));
	$numposts = $wp_query->found_posts;
	$max_page = $wp_query->max_num_pages;
	if ( $numposts <= $posts_per_page ) { return; }
	if(empty($paged) || $paged == 0) {
		$paged = 1;
	}
	$pages_to_show = 7;
	$pages_to_show_minus_1 = $pages_to_show-1;
	$half_page_start = floor($pages_to_show_minus_1/2);
	$half_page_end = ceil($pages_to_show_minus_1/2);
	$start_page = $paged - $half_page_start;
	if($start_page <= 0) {
		$start_page = 1;
	}
	$end_page = $paged + $half_page_end;
	if(($end_page - $start_page) != $pages_to_show_minus_1) {
		$end_page = $start_page + $pages_to_show_minus_1;
	}
	if($end_page > $max_page) {
		$start_page = $max_page - $pages_to_show_minus_1;
		$end_page = $max_page;
	}
	if($start_page <= 0) {
		$start_page = 1;
	}
	echo $before.'<nav class="page-navigation"><ol class="roller_page_navi clearfix">'."";
	if ($start_page >= 2 && $pages_to_show < $max_page) {
		$first_page_text = "First";
		echo '<li class="bpn-first-page-link"><a href="'.get_pagenum_link().'" title="'.$first_page_text.'">'.$first_page_text.'</a></li>';
	}
	echo '<li class="bpn-prev-link">';
	previous_posts_link('<<');
	echo '</li>';
	for($i = $start_page; $i  <= $end_page; $i++) {
		if($i == $paged) {
			echo '<li class="bpn-current">'.$i.'</li>';
		} else {
			echo '<li><a href="'.get_pagenum_link($i).'">'.$i.'</a></li>';
		}
	}
	echo '<li class="bpn-next-link">';
	next_posts_link('>>');
	echo '</li>';
	if ($end_page < $max_page) {
		$last_page_text = "Last";
		echo '<li class="bpn-last-page-link"><a href="'.get_pagenum_link($max_page).'" title="'.$last_page_text.'">'.$last_page_text.'</a></li>';
	}
	echo '</ol></nav>'.$after."";
}

/************* MENUS *********************/

function roller_main_nav() {
	// display the wp3 menu if available
	/**/
	$pages_list = roller_get_option("layout");
	$pages_array = explode(",",$pages_list);
	
	echo '<ul>';
	echo '<li class="page_item" id="menu_Inicio"><a href="#">Inicio</a></li>';

	foreach($pages_array as $page){
		$page = get_page($page);
		$title_hash = str_replace(" ","",$page->post_title);

		if(get_post_meta($page->ID, "roller_show_title",TRUE)!="0"){
			echo '<li class="page_item" id="menu_'.$title_hash.'"><a href="#'.$title_hash.'"">';
			echo $page->post_title;//<li class="current_page_item">
			echo '</a></li>';
		}
	}
	echo '</ul>';
	/**/
	/**
	wp_nav_menu( 
		array( 
			'menu' => 'main_nav',
			'theme_location' => 'main_nav',
			'container_class' => 'menu clearfix', 
			'fallback_cb' => 'roller_main_nav_fallback' 
		)
	);
	/**/
}

function roller_footer_links() { 
	// display the wp3 menu if available
	wp_nav_menu(
		array(
			'menu' => 'footer_links', /* menu name */
			'theme_location' => 'footer_links', /* where in the theme it's assigned */
			'container_class' => 'footer-links clearfix', /* container class */
			'fallback_cb' => 'roller_footer_links_fallback' /* menu fallback */
		)
	);
}

// this is the fallback for header menu
function roller_main_nav_fallback() { 
	wp_page_menu( 'show_home=Home&menu_class=menu' ); 
}

// this is the fallback for footer menu
function roller_footer_links_fallback() { 
	/* you can put a default here if you like */ 
}

/************* SEARCH FORM LAYOUT *****************/

// Search Form
function roller_wpsearch($form) {
    $form = '<form role="search" method="get" id="searchform" action="' . home_url( '/' ) . '" >
    <label class="screen-reader-text" for="s">' . __('Search for:', 'rollertheme') . '</label>
    <input type="text" value="' . get_search_query() . '" name="s" id="s" placeholder="Search the Site..." />
    <input type="submit" id="searchsubmit" value="'. esc_attr__('Search') .'" />
    </form>';
    return $form;
} 

/************ GET THEME OPTIONS *******************/

function roller_get_option($option){
	$options = get_option( 'roller_theme_options' );
	return $options[$option];
}

?>