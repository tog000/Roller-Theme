<?php
/* 
This file handles the admin area and functions.
You can use this file to make changes to the
dashboard. Updates to this page are coming soon.
It's turned off by default, but you can call it
via the functions file.

Developed by: Eddie Machado
URL: http://themble.com/roller/

Special Thanks for code & inspiration to:
@jackmcconnell - http://www.voltronik.co.uk/
Digging into WP - http://digwp.com/2010/10/customize-wordpress-dashboard/

*/

class RollerAdmin{

	function __construct(){
		// removing the dashboard widgets
		add_action('admin_menu', 			array(&$this, 'disable_default_dashboard_widgets'));
		// adding any custom widgets
		//add_action('wp_dashboard_setup', 	array(&$this, 'widget_rss_dashboard'));

		// calling it only on the login page
		add_action('login_head', 			array(&$this, 'login_css'));
		add_filter('login_headerurl', 		array(&$this, 'login_url'));
		add_filter('login_headertitle', 	array(&$this, 'login_title'));

		// adding it to the admin area
		add_filter('admin_footer_text', 	array(&$this, 'custom_admin_footer'));

		// Let WP know that the theme has options
		add_action('admin_menu', 			array(&$this, 'register_theme_options'));
		// Register the page itself
		add_action('admin_menu', 			array(&$this, 'add_options_page'));

		add_action('admin_menu',			array(&$this, 'add_style_scripts'));

		// Add meta box
		add_action('add_meta_boxes', 		array(&$this, 'add_post_meta_box'));

		// Save posts
		add_action('save_post', 			array(&$this, 'save_posts'));
		
		/*
		// Let WP know that the theme has a setting on the general settings
		add_action('admin_menu', 			array(&$this, 'register_settings'));
		// Register the option itself
		add_action('admin_init', 			array(&$this, 'register_settings_fields'));
		*/

	}

	// disable default dashboard widgets
	function disable_default_dashboard_widgets() {
		// remove_meta_box('dashboard_right_now', 'dashboard', 'core');    // Right Now Widget
		remove_meta_box('dashboard_recent_comments', 'dashboard', 'core'); // Comments Widget
		remove_meta_box('dashboard_incoming_links', 'dashboard', 'core');  // Incoming Links Widget
		remove_meta_box('dashboard_plugins', 'dashboard', 'core');         // Plugins Widget

		// remove_meta_box('dashboard_quick_press', 'dashboard', 'core');  // Quick Press Widget
		remove_meta_box('dashboard_recent_drafts', 'dashboard', 'core');   // Recent Drafts Widget
		remove_meta_box('dashboard_primary', 'dashboard', 'core');         // Wordpress Blog Feed
		remove_meta_box('dashboard_secondary', 'dashboard', 'core');       // Other Wordpress News
		
		// removing plugin dashboard boxes 
		remove_meta_box('yoast_db_widget', 'dashboard', 'normal');         // Yoast's SEO Plugin Widget
		remove_meta_box('rg_forms_dashboard', 'dashboard', 'norma;');      // Gravity Forms Widget
		
		/* 
		have more plugin widgets you'd like to remove? 
		share them with us so we can get a list of 
		the most commonly used. :D
		https://github.com/eddiemachado/roller/issues
		*/
	}

	/*
	Now let's talk about adding your own custom Dashboard widget.
	Sometimes you want to show clients feeds relative to their 
	site's content. For example, the NBA.com feed for a sports
	site. Here is an example Dashboard Widget that displays recent
	entries from an RSS Feed.

	For more information on creating Dashboard Widgets, view:
	http://digwp.com/2010/10/customize-wordpress-dashboard/
	*/

	// RSS Dashboard Widget 
	function widget_rss_dashboard() {
		if(function_exists('fetch_feed')) {
			include_once(ABSPATH . WPINC . '/feed.php');               // include the required file
			$feed = fetch_feed('http://themble.com/feed/rss/');        // specify the source feed
			$limit = $feed->get_item_quantity(7);                      // specify number of items
			$items = $feed->get_items(0, $limit);                      // create an array of items
		}
		if ($limit == 0) echo '<div>The RSS Feed is either empty or unavailable.</div>';   // fallback message 
		else foreach ($items as $item) : ?>

		<h4 style="margin-bottom: 0;">
			<a href="<?php echo $item->get_permalink(); ?>" title="<?php echo $item->get_date('j F Y @ g:i a'); ?>" target="_blank">
				<?php echo $item->get_title(); ?>
			</a>
		</h4>
		<p style="margin-top: 0.5em;">
			<?php echo substr($item->get_description(), 0, 200); ?> 
		</p>
		<?php endforeach; 
	}

	// calling all custom dashboard widgets
	function custom_dashboard_widgets() {
		wp_add_dashboard_widget('roller_rss_dashboard_widget', 'Recently on Themble (Customize on admin.php)', 'roller_rss_dashboard_widget');
		/*
		Be sure to drop any other created Dashboard Widgets 
		in this function and they will all load.
		*/
	}

	// calling your own login css so you can style it 
	function login_css() {
		/* i couldn't get wp_enqueue_style to work :( */
		echo '<link rel="stylesheet" href="' . get_stylesheet_directory_uri() . '/library/css/login.css">';
	}

	// changing the logo link from wordpress.org to your site 
	function login_url() { echo bloginfo('url'); }

	// changing the alt text on the logo to show your site name 
	function login_title() { echo get_option('blogname'); }

	// Custom Backend Footer
	function custom_admin_footer() {
		echo '<span id="footer-thankyou">Developed by <a href="http://yoursite.com" target="_blank">Your Site Name</a></span>. Built using <a href="http://themble.com/roller" target="_blank">roller</a>.';
	}

	/************* SETTINGS IN GENERAL *******************/

	function register_settings() {
		register_setting('general','roller_general_setting');
	}
	
	function register_settings_fields() {
		add_settings_field( "roller_general_setting", "Roller General Setting", "roller_general_setting_renderer", "general");
	}
	
	function roller_general_setting_renderer(){
		echo "<input name=\"roller_general_setting\" value=\"".get_option("roller_general_setting")."\"/>";
	}

	/************* THEME OPTIONS PAGE *******************/

	function register_theme_options() {
		register_setting( 'roller_options', 'roller_theme_options', array(&$this, 'theme_options_validate'));
	}
	
	function add_options_page() {
		add_theme_page('Roller Options', 'Roller Options', 'manage_options', 'roller-configuration', array(&$this, 'roller_options'));
	}
	
	function roller_options() {

		$options = get_option( 'roller_theme_options' );
		//echo "----->";print_r(get_option('roller_theme_options'));echo "<-----";

		$select_color = array(
			array('label'=>"Red",'value'=>"red"),
			array('label'=>"Green",'value'=>"green"),
			array('label'=>"Blue",'value'=>"blue")
		);

		if ( ! isset( $_REQUEST['settings-updated'] ) )
			$_REQUEST['settings-updated'] = false;
		?>
		<div class="wrap">
			<div class="icon32"><img src="<?php bloginfo('template_directory'); ?>/library/images/config-icon.png"/></div>

			<?php echo "<h2>".__( 'Roller Options', 'rollertheme' ) . "</h2>"; ?>

			<?php if ( false !== $_REQUEST['settings-updated'] ) : ?>
				<div class="updated fade"><p><strong><?php _e( 'Options saved', 'rollertheme' ); ?></strong></p></div>
			<?php endif; ?>
			
			<form method="post" action="options.php" id="roller_config_form">

				<?php
					settings_fields( 'roller_options' );
				?>

				<h2>Style Options</h2>
				<div id="roller_custom_styles_config">
					<div class="sidebar-name">
								<h3 class="hndle"><span><?php _e( 'Custom Styles', 'rollertheme' ); ?></span></h3>
					</div>
					<div class="sidebar-container">
						<p class="description">The styles added here show up in the "Select Style" combobox in the post/page editors. The class name must exist and be defined in some CSS file for this to take effect.</p>
						<div class="col">
							<label>Description:&nbsp;</label><input placeholder="eg. &quot;Big White Text&quot;" class="regular-text" type="text" id="roller_config_style_class_description"/><br/>
							<label>Class Name:&nbsp;</label><input placeholder="eg. &quot;big-white-text&quot;"  class="regular-text" type="text" id="roller_config_style_class_name"/>
							<br/><br/>
							<input id="roller_config_styles_add" type="submit" class="button-secondary" value="<?php _e( "Add Class", 'rollertheme' ); ?>" />
						</div>
						<div class="col last">
							<label>Current Styles:&nbsp;</label>
							<select id="roller_config_styles_list"  size="6">
							<?php 
								if(array_key_exists('styles',$options) && $options['styles']!=""){
									$styles_array = explode(",",$options['styles']);
									foreach($styles_array as $style){
										echo "<option>".$style."</option>";
									}
								}
							?>
							</select>
							<br/><br/>
							<input id="roller_config_styles_remove" type="button" class="button-secondary" value="<?php _e( "Remove Class", 'rollertheme' ); ?>" />
						</div>
					</div>
				</div>
				<input type="hidden" id="roller_theme_options_styles" name="roller_theme_options[styles]" value="<?php echo array_key_exists('styles',$options)?$options['styles']:""; ?>" />
				<input type="submit" class="button-primary" value="<?php _e( "Save Options", 'rollertheme' ); ?>" /><br/><br/>
						
				<h2>Layout Options</h2>
				<!--
					<p>
						<label class="description" for="roller_theme_options[input_sample]"><?php _e( 'Sample Input', 'rollertheme' ); ?></label>
						<input id="roller_theme_options[input_sample]" name="roller_theme_options[input_sample]" value="<?php echo array_key_exists('input_sample',$options)?$options['input_sample']:""; ?>" />
					</p>

					<p>
						<label class="description" for="roller_theme_options[select_sample]"><?php _e( 'Sample Select', 'rollertheme' ); ?></label>
						<select name="roller_theme_options[select_sample]">
						<?php
							$selected = $options['select_sample'];
							foreach($select_color as $option){
								echo "<option value=\"".$option['value']."\" ";

								if ($selected == $option['value'])
									echo "selected='selected'";

								echo ">";
								_e( $option['label'], 'rollertheme' );
								echo "</option>\n";
							}
						?>
						</select>
					</p>
					
					<p>
						<label class="description" for="roller_theme_options[checkbox_sample]"><?php _e( 'Sample Checkbox', 'rollertheme' ); ?></label>
						<input id="roller_theme_options[checkbox_sample]" name="roller_theme_options[checkbox_sample]" type="checkbox" value="1" <?php checked( '1', $options['checkbox_sample'] ); ?> />
					</p>
					<p>
						<label class="description" for="roller_theme_options[textarea_sample]"><?php _e( 'Sample textarea', 'rollertheme' ); ?></label>
						<textarea id="roller_theme_options[textarea_sample]" class="large-text" cols="50" rows="10" name="roller_theme_options[textarea_sample]"><?php echo esc_textarea( array_key_exists('textarea_sample',$options)?$options['textarea_sample']:"" ); ?></textarea>	
					</p>
				-->

				<div id="roller_layout_config">
					<div id="roller_config_layout_left">
						<div class="sidebar-name">
							<h3 class="hndle"><span><?php _e( 'Available Pages', 'rollertheme' ); ?></span></h3>
						</div>
						<div class="sidebar-container">
							<p class="description">Drag pages from here to the layout on the right to activate them. <br/>Drag pages back here to remove them from the layout.</p>
							<ul id="roller_config_layout_inactive_pages" class="connectedSortable">
							<?php
								$pages = get_pages();
								$active = array_key_exists('layout',$options)?$options['layout']:"";
								$active_array = explode(",",$active);
								foreach($pages as $page){
									if(array_search($page->ID,$active_array)===FALSE){
										echo "<li id=\"roller_config_layout_page".$page->ID."\" class=\"roller_config_layout_page\"><h1>".$page->post_title."</h1>";
										preg_match_all("/\[(one|two|three)_(half|third|forth)(_last)?/i",$page->post_content,$out,PREG_PATTERN_ORDER
);											foreach($out[0] as $column){
											$class = substr($column,1);
											echo "<span class=\"".$class."\"></span>";
										}
										echo "</li>";
									}
								}
							?>
							</ul>
						</div>
					</div>
					<div class="roller_config_arrows"><img src="<?php bloginfo('template_directory'); ?>/library/images/arrows.png"/></div>
					<div id="roller_config_layout_right">
						<div class="sidebar-name">
							<h3 class="hndle"><span><?php _e( 'Layout', 'rollertheme' ); ?></span></h3>
						</div>
						<div class="sidebar-container">
							<p class="description">These boxes represent the order of the layout</p>
							<div class="roller_config_layout_page roller_config_layout_fixed">HEADER</div>
							<ul id="roller_config_layout_active_pages" class="connectedSortable">
							<?php
								foreach($active_array as $active){
									foreach($pages as $page){
										if($page->ID == $active){
											echo "<li id=\"roller_config_layout_page".$page->ID."\" class=\"roller_config_layout_page\"><h1>".$page->post_title."</h1>";
											preg_match_all("/\[(one|two|three)_(half|third|forth)(_last)?/i",$page->post_content,$out,PREG_PATTERN_ORDER
);											foreach($out[0] as $column){
												$class = substr($column,1);
												echo "<span class=\"".$class."\"></span>";
											}
											echo "</li>";
											break;
										}
									}
								}
							?>
							</ul>
							<div class="roller_config_layout_page roller_config_layout_fixed">FOOTER</div>
						</div>
					</div>
				</div>

				<input type="hidden" id="roller_theme_options_layout" name="roller_theme_options[layout]" value="<?php echo array_key_exists('layout',$options)?$options['layout']:""; ?>" />

				<div style="clear:both;"></div>
				<p class="submit">
					<input type="submit" class="button-primary" value="<?php _e( "Save Options", 'rollertheme' ); ?>" />
				</p>
			</form>
		</div>
		<?php

	}

	/**
	* Sanitize and validate input. Accepts an array, return a sanitized array.
	*/
	function theme_options_validate( $input ) {
		
		$keys = array_keys($input);

		foreach ($keys as $key){

			$value = $input[$key];

			// If the key starts with "checkbox", value is either 0 or 1
			if ( strstr($key, "checkbox") == $key ){
				$input[$key] = ( $input[$key] == 1 ? 1 : 0 );

			// If the key starts with "select", sanitize
			}else if ( strstr($key, "select") == $key ){
				$input[$key] = wp_filter_nohtml_kses( $input[$key] );
			}

			// Say our text option must be safe text with no HTML tags
			$input['sometext'] = wp_filter_nohtml_kses( $input['sometext'] );

			// Our select option must actually be in our array of select options
			if ( ! array_key_exists( $input['selectinput'], $select_options ) )
				$input['selectinput'] = null;

			// Our radio option must actually be in our array of radio options
			if ( ! isset( $input['radioinput'] ) )
				$input['radioinput'] = null;
			if ( ! array_key_exists( $input['radioinput'], $radio_options ) )
				$input['radioinput'] = null;

			// Say our textarea option must be safe text with the allowed tags for posts
			$input['sometextarea'] = wp_filter_post_kses( $input['sometextarea'] );
		}

		return $input;
	}

	function add_style_scripts(){
		
		wp_enqueue_script("jquery-ui", get_template_directory_uri()."/library/js/libs/jquery-ui-1.8.17.custom.min.js");
		wp_enqueue_style("roller-admin", get_template_directory_uri()."/library/css/admin.css");
		wp_enqueue_script("roller-admin", get_template_directory_uri()."/library/js/admin.js");
	}

	function add_post_meta_box(){
		add_meta_box('ml_box', __('Roller Theme Options','rollertheme'), array(&$this, 'meta_box_renderer'), '', 'side','high');
	}
		function meta_box_renderer(){
			global $post;

			wp_enqueue_style('farbtastic');
			wp_enqueue_script('farbtastic');

			$show_title	= get_post_meta($post->ID, "roller_show_title",TRUE);
			$bg_color	= get_post_meta($post->ID, "roller_bg_color",TRUE);
			$inner_shadow= get_post_meta($post->ID, "roller_inner_shadow",TRUE);
			include('includes/roller-meta-box.php');
		}

	function save_posts($post_id){
		if(isset($_REQUEST['roller_post_options'])){
			foreach($_REQUEST['roller_post_options'] as $option=>$value){
				if(get_post_meta($post_id, $option, TRUE)==""){
					add_post_meta($post_id, $option, $value,TRUE);
				}else{
					update_post_meta($post_id, $option, $value);
				}
			}
		}

		
	}


}