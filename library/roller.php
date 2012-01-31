<?php
/* Welcome to roller :)
This is the core roller file where most of the
main functions & features reside. If you have 
any custom functions, it's best to put them
in the functions.php file.

Developed by: Eddie Machado
URL: http://themble.com/roller/
*/

// Adding Translation Option
load_theme_textdomain( 'rollertheme', TEMPLATEPATH.'/languages' );
$locale = get_locale();
$locale_file = TEMPLATEPATH."/languages/$locale.php";
if ( is_readable($locale_file) ) require_once($locale_file);

class Roller{

	function __construct(){
		
		// Cleanup unnecessary stuff
		add_action('init', 				array(&$this, 'cleanup'));

		// launching this stuff after theme setup
		add_action('after_setup_theme', array(&$this, 'theme_support'));
		
		// adding sidebars and widgetized areas
		add_action( 'widgets_init', 	array(&$this, 'register_sidebars'));

		// Remove RSS version
		add_filter('the_generator', 	array(&$this, 'rss_version'));
		
		// loading jquery reply elements on single pages automatically
		add_action('wp_print_scripts', 	array(&$this, 'queue_js'));

		// Fixing the Read More in the Excerpts
		// This removes the annoying […] to a Read More link
		add_filter('excerpt_more', 		array(&$this, 'excerpt_more'));

		// adding the roller search form (created in functions.php)
		add_filter( 'get_search_form', 	array(&$this, 'search'));

		// remove the p from around imgs (http://css-tricks.com/snippets/wordpress/remove-paragraph-tags-from-around-images/)
		add_filter('the_content', 		array(&$this, 'filter_ptags_on_images'));

	}

	function theme_support() {
		add_theme_support('post-thumbnails');      // wp thumbnails (sizes handled in functions.php)
		set_post_thumbnail_size(125, 125, true);   // default thumb size
		add_custom_background();                   // wp custom background
		add_theme_support('automatic-feed-links'); // rss thingy
		// to add header image support go here: http://themble.com/support/adding-header-background-image-support/
		// adding post format support
		add_theme_support( 'post-formats',      // post formats
			array( 
				'aside',   // title less blurb
				'gallery', // gallery of images
				'link',    // quick link to other site
				'image',   // an image
				'quote',   // a quick quote
				'status',  // a Facebook like status update
				'video',   // video 
				'audio',   // audio
				'chat'     // chat transcript 
			)
		);	
		add_theme_support( 'menus' );            // wp menus
		register_nav_menus(                      // wp3+ menus
			array( 
				'main_nav' => 'The Main Menu',   // main nav in header
				'footer_links' => 'Footer Links' // secondary nav in footer
			)
		);
	}

	function register_sidebars() {
		register_sidebar(array(
			'id' => 'header',
			'name' => 'Header Area',
			'description' => 'The header area.',
			'before_widget' => '<div id="%1$s" class="widget %2$s">',
			'after_widget' => '</div>',
			'before_title' => '<h4 class="widgettitle">',
			'after_title' => '</h4>',
		));
		
		/* 
		to add more sidebars or widgetized areas, just copy
		and edit the above sidebar code. In order to call 
		your new sidebar just use the following code:
		
		Just change the name to whatever your new
		sidebar's id is, for example:
		
		register_sidebar(array(
			'id' => 'sidebar2',
			'name' => 'Sidebar 2',
			'description' => 'The second (secondary) sidebar.',
			'before_widget' => '<div id="%1$s" class="widget %2$s">',
			'after_widget' => '</div>',
			'before_title' => '<h4 class="widgettitle">',
			'after_title' => '</h4>',
		));
		
		To call the sidebar in your template, you can just copy
		the sidebar.php file and rename it to your sidebar's name.
		So using the above example, it would be:
		sidebar-sidebar2.php
		
		*/
	} 

	function cleanup() {
		// remove header links
		// remove_action( 'wp_head', 'feed_links_extra', 3 );                    // Category Feeds
		// remove_action( 'wp_head', 'feed_links', 2 );                          // Post and Comment Feeds
		remove_action( 'wp_head', 'rsd_link' );                               // EditURI link
		remove_action( 'wp_head', 'wlwmanifest_link' );                       // Windows Live Writer
		remove_action( 'wp_head', 'index_rel_link' );                         // index link
		remove_action( 'wp_head', 'parent_post_rel_link', 10, 0 );            // previous link
		remove_action( 'wp_head', 'start_post_rel_link', 10, 0 );             // start link
		remove_action( 'wp_head', 'adjacent_posts_rel_link_wp_head', 10, 0 ); // Links for Adjacent Posts
		remove_action( 'wp_head', 'wp_generator' );                           // WP version
		if (!is_admin()) {
			wp_deregister_script('jquery');                                   // De-Register jQuery
			wp_register_script('jquery', '', '', '', true);                   // It's already in the Header
		}	
	}
		
	function rss_version() { return ''; }
	
	function queue_js(){ 
		if (!is_admin()){ 
			if ( is_singular() AND comments_open() AND (get_option('thread_comments') == 1)) {
				wp_enqueue_script( 'comment-reply' ); 
			}
		}
	}

	function excerpt_more($more) {
		global $post;
		// edit here if you like
		return '...  <a href="'. get_permalink($post->ID) . '" title="Read '.get_the_title($post->ID).'">Read more &raquo;</a>';
	}
		
	// Adding WP 3+ Functions & Theme Support

	function filter_ptags_on_images($content){
		return preg_replace('/<p>\s*(<a .*>)?\s*(<img .* \/>)\s*(<\/a>)?\s*<\/p>/iU', '\1\2\3', $content);
	}

}
?>