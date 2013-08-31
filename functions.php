<?php
function theme_setup() {
	// This theme styles the visual editor with editor-style.css to match the theme style.
	add_editor_style();

	// Adds RSS feed links to <head> for posts and comments.
	add_theme_support( 'automatic-feed-links' );

	// This theme uses a custom image size for featured images, displayed on "standard" posts.
	add_theme_support( 'post-thumbnails' );
}
add_action( 'after_setup_theme', 'theme_setup' );

/**
 * Enqueue styles/scripts
 */
function theme_enqueue_scripts(){
	// enqueue jquery from CDN
	wp_deregister_script('jquery');
	wp_register_script('jquery', '//ajax.googleapis.com/ajax/libs/jquery/1.10.1/jquery.min.js', array(), '1.10.1');
	wp_enqueue_script('jquery');
	// use jQuery migrate
	wp_enqueue_script('jquery-migrate', '//code.jquery.com/jquery-migrate-1.2.1.min.js', array('jquery'), '1.2.1', true);

	// foundation javascripts
	wp_register_script('foundation', get_template_directory_uri().'/javascripts/foundation/foundation.js', array('jquery'), '4.2.5', true);
	wp_register_script('foundation-alerts', get_template_directory_uri().'/javascripts/foundation/foundation.alerts.js', array('foundation'), '4.2.5', true);
	wp_register_script('foundation-clearing', get_template_directory_uri().'/javascripts/foundation/foundation.clearing.js', array('foundation'), '4.2.5', true);
	wp_register_script('foundation-cookie', get_template_directory_uri().'/javascripts/foundation/foundation.cookie.js', array('foundation'), '4.2.5', true);
	wp_register_script('foundation-dropdown', get_template_directory_uri().'/javascripts/foundation/foundation.dropdown.js', array('foundation'), '4.2.5', true);
	wp_register_script('foundation-forms', get_template_directory_uri().'/javascripts/foundation/foundation.forms.js', array('foundation'), '4.2.5', true);
	wp_register_script('foundation-interchange', get_template_directory_uri().'/javascripts/foundation/foundation.interchange.js', array('foundation'), '4.2.5', true);
	wp_register_script('foundation-joyride', get_template_directory_uri().'/javascripts/foundation/foundation.joyride.js', array('foundation'), '4.2.5', true);
	wp_register_script('foundation-magellan', get_template_directory_uri().'/javascripts/foundation/foundation.magellan.js', array('foundation'), '4.2.5', true);
	wp_register_script('foundation-orbit', get_template_directory_uri().'/javascripts/foundation/foundation.orbit.js', array('foundation'), '4.2.5', true);
	wp_register_script('foundation-placeholder', get_template_directory_uri().'/javascripts/foundation/foundation.placeholder.js', array('foundation'), '4.2.5', true);
	wp_register_script('foundation-reveal', get_template_directory_uri().'/javascripts/foundation/foundation.reveal.js', array('foundation'), '4.2.5', true);
	wp_register_script('foundation-section', get_template_directory_uri().'/javascripts/foundation/foundation.section.js', array('foundation'), '4.2.5', true);
	wp_register_script('foundation-tooltips', get_template_directory_uri().'/javascripts/foundation/foundation.tooltips.js', array('foundation'), '4.2.5', true);
	wp_register_script('foundation-topbar', get_template_directory_uri().'/javascripts/foundation/foundation.topbar.js', array('foundation'), '4.2.5', true);
	wp_register_script('modernizr', get_template_directory_uri().'/javascripts/vendor/custom.modernizr.js', array('jquery'), '2.6.2', true);

	// stylesheets
	wp_enqueue_style('app', get_stylesheet_uri());

	// enqueue scripts
}
add_action('wp_enqueue_scripts', 'theme_enqueue_scripts');

/**
 * Register navigation menus
 */
function register_menus() {
	register_nav_menus(
		array(
			'main-nav' => 'Main Navigation',
			'secondary-nav' => 'Secondary Navigation',
			'sidebar-menu' => 'Sidebar Menu'
		)
	);
}
add_action( 'init', 'register_menus' );

function register_widgets(){
	register_sidebar( array(
		'name' => __( 'Sidebar' ),
		'id' => 'main-sidebar',
		'before_widget' => '<li id="%1$s" class="widget-container %2$s">',
		'after_widget' => '</li>',
		'before_title' => '<h3 class="widget-title">',
		'after_title' => '</h3>',
	) );
}
add_action( 'widgets_init', 'register_widgets' );


/**
 * Creates a nicely formatted and more specific title element text
 * for output in head of document, based on current view.
 *
 * @param string $title Default title text for current view.
 * @param string $sep Optional separator.
 * @return string Filtered title.
 */
function theme_title( $title, $sep ) {
	global $paged, $page;

	if ( is_feed() )
		return $title;

	// Add the site name.
	$title .= get_bloginfo( 'name' );

	// Add the site description for the home/front page.
	$site_description = get_bloginfo( 'description', 'display' );
	if ( $site_description && ( is_home() || is_front_page() ) )
		$title = "$title $sep $site_description";

	// Add a page number if necessary.
	if ( $paged >= 2 || $page >= 2 )
		$title = "$title $sep " . sprintf( __( 'Page %s', 'resolve' ), max( $paged, $page ) );

	return $title;
}
add_filter( 'wp_title', 'theme_title', 10, 2 );

/**
 * Sets the post excerpt length to 40 words.
 *
 * To override this length in a child theme, remove the filter and add your own
 * function tied to the excerpt_length filter hook.
 */
function twentyeleven_excerpt_length( $length ) {
	return 40;
}
add_filter( 'excerpt_length', 'twentyeleven_excerpt_length' );

/**
 * Replaces "[...]" (appended to automatically generated excerpts) with an ellipsis and twentyeleven_continue_reading_link().
 *
 * To override this in a child theme, remove the filter and add your own
 * function tied to the excerpt_more filter hook.
 */
function twentyeleven_auto_excerpt_more( $more ) {
	return ' <a href="'. esc_url( get_permalink() ) . '" class="read-more">' . __( 'Read More &gt;', 'twentyeleven' ) . '</a>';
}
add_filter( 'excerpt_more', 'twentyeleven_auto_excerpt_more' );

function setup_theme_admin_menus() {
	add_menu_page('Theme Settings', 'Theme Settings', 'manage_options',
		'theme_settings', 'theme_settings_page');
}
// This tells WordPress to call the function named "setup_theme_admin_menus"
// when it's time to create the menu pages.
add_action("admin_menu", "setup_theme_admin_menus");

// We also need to add the handler function for the top level menu
function theme_settings_page() {
	?>
	<div class="wrap">
		<?php screen_icon('themes'); ?> <h2>Theme Settings</h2>
		<?php
		if (isset($_POST["theme_facebook"])) {
			update_option("social_facebook", esc_url($_POST["theme_facebook"]));
			update_option("theme_twitter", esc_url($_POST["theme_twitter"]));
			update_option("theme_pinterest", esc_url($_POST["theme_pinterest"]));
			update_option("theme_instagram", esc_url($_POST["theme_instagram"]));
			update_option("theme_gplus", esc_url($_POST["theme_gplus"]));

			echo '<div id="message" class="updated">Settings saved</div>';
		} ?>

		<form method="POST" action="">
			<table class="form-table">
				<tr valign="top">
					<th scope="row">
						<label for="theme_facebook">
							Facebook
						</label>
					</th>
					<td>
						<input type="text" id="theme_facebook" name="theme_facebook" value="<?php echo get_option('theme_facebook'); ?>" />
					</td>
				</tr>
				<tr valign="top">
					<th scope="row">
						<label for="theme_twitter">
							Twitter
						</label>
					</th>
					<td>
						<input type="text" id="theme_twitter" name="theme_twitter" value="<?php echo get_option('theme_twitter'); ?>" />
					</td>
				</tr>
				<tr valign="top">
					<th scope="row">
						<label for="theme_pinterest">
							Pinterest
						</label>
					</th>
					<td>
						<input type="text" id="theme_pinterest" name="theme_pinterest" value="<?php echo get_option('theme_pinterest'); ?>" />
					</td>
				</tr>
				<tr valign="top">
					<th scope="row">
						<label for="theme_pinterest">
							Instagram
						</label>
					</th>
					<td>
						<input type="text" id="theme_instagram" name="theme_instagram" value="<?php echo get_option('theme_instagram'); ?>" />
					</td>
				</tr>
				<tr valign="top">
					<th scope="row">
						<label for="theme_pinterest">
							Google+
						</label>
					</th>
					<td>
						<input type="text" id="theme_gplus" name="theme_gplus" value="<?php echo get_option('theme_gplus'); ?>" />
					</td>
				</tr>
				<tr>
					<td scope="row"><input type="submit" value="save" class="button"></td>
				</tr>
			</table>
		</form>
	</div>
<?php
}