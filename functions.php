<?php // Widgetized Sidebar.
function paramitopia_widgets_init() {
	register_sidebar(array(
		'name' => __('Primary Widget Area','paramitopia'),
		'id' => 'primary-widget-area',
		'description' => __('The primary widget area','paramitopia'),
		'before_widget' => '<div id="%1$s" class="widget %2$s">',
		'after_widget' => '</div><div class="widget-foot"></div>',
		'before_title' => '<h3 class="widgettitle">',
		'after_title' => '</h3>'
	));
}
add_action( 'widgets_init', 'paramitopia_widgets_init' );

// Custom Comments List.
function paramitopia_mytheme_comment($comment, $args, $depth) {
   $GLOBALS['comment'] = $comment;
?>
<li <?php comment_class(); ?> id="li-comment-<?php comment_ID(); ?>">
	<div id="comment-<?php comment_ID(); ?>">
		<div class="comment-author vcard">
			<?php echo get_avatar($comment,$size='40',$default='' ); ?>
			<cite class="fn"><?php comment_author_link(); ?></cite>
			<span class="comment-meta commentmetadata"><a href="<?php echo htmlspecialchars( get_comment_link( $comment->comment_ID ) ); ?>"><?php printf(__('%1$s at %2$s'), get_comment_date(),  get_comment_time()); ?></a><?php edit_comment_link(__('[Edit]','paramitopia'),' ',''); ?></span>
		</div>
		<?php if ($comment->comment_approved == '0') : ?>
		<em class="approved"><?php _e('Your comment is awaiting moderation.','paramitopia'); ?></em>
		<br />
		<?php endif; ?>
		<div class="comment-text">
			<?php comment_text(); ?>
		</div>
		<div class="reply">
			<?php comment_reply_link(array_merge( $args, array('depth' => $depth, 'max_depth' => $args['max_depth']))); ?>
		</div>
	</div>
<?php }

/* wp_list_comments()->pings callback */
function paramitopia_custom_pings($comment, $args, $depth) {
    $GLOBALS['comment'] = $comment;
    if('pingback' == get_comment_type()) $pingtype = 'Pingback';
    else $pingtype = 'Trackback';
?>
    <li id="comment-<?php echo $comment->comment_ID ?>">
        <?php comment_author_link(); ?> - <?php echo $pingtype; ?> on <?php echo mysql2date('Y/m/d/ H:i', $comment->comment_date); ?>
<?php }

if ( ! isset( $content_width ) )
	$content_width = 620;
	
// WP nav menu
if (function_exists('wp_nav_menu')) {
	register_nav_menus(array('primary' => 'Primary Navigation'));
}

// LOCALIZATION
load_theme_textdomain('paramitopia', get_template_directory() . '/lang');

// custom excerpt
function paramitopia_excerpt_length( $length ) {
	return 40;
}
add_filter( 'excerpt_length', 'paramitopia_excerpt_length' );

function paramitopia_continue_reading_link() {
	return '<p class="read-more"><a href="'. get_permalink() . '">' . __( 'Read more &raquo;', 'paramitopia' ) . '</a></p>';
}

function paramitopia_auto_excerpt_more( $more ) {
	return ' &hellip;' . paramitopia_continue_reading_link();
}
add_filter( 'excerpt_more', 'paramitopia_auto_excerpt_more' );

function paramitopia_custom_excerpt_more( $output ) {
	if ( has_excerpt() && ! is_attachment() ) {
		$output .= paramitopia_continue_reading_link();
	}
	return $output;
}
add_filter( 'get_the_excerpt', 'paramitopia_custom_excerpt_more' );

// Tell WordPress to run paramitopia_setup() when the 'after_setup_theme' hook is run.
add_action( 'after_setup_theme', 'paramitopia_setup' );
if ( ! function_exists( 'paramitopia_setup' ) ):
function paramitopia_setup() {

	// Add default posts and comments RSS feed links to head
	add_theme_support( 'automatic-feed-links' );

	// This theme styles the visual editor with editor-style.css to match the theme style.
	add_editor_style();

	// This theme allows users to set a custom background
	//add_custom_background();
	
	// This theme uses post thumbnails
	add_theme_support( 'post-thumbnails' );
	add_image_size( 'extra-featured-image', 620, 200, true );
	function paramitopia_featured_content($content) {
		if (is_home() || is_archive()) {
			the_post_thumbnail( 'extra-featured-image' );
		}
		return $content;
	}
	add_filter( 'the_content', 'paramitopia_featured_content',1 );
	function paramitopia_post_image_html( $html, $post_id, $post_image_id ) {
		$html = '<a href="' . get_permalink( $post_id ) . '" title="' . esc_attr( get_post_field( 'post_title', $post_id ) ) . '">' . $html . '</a>';
		return $html;
	}
	add_filter( 'post_thumbnail_html', 'paramitopia_post_image_html', 10, 3 );

	// Your changeable header business starts here
	define( 'HEADER_TEXTCOLOR', '' );
	// No CSS, just IMG call. The %s is a placeholder for the theme template directory URI.
	define( 'HEADER_IMAGE', '' ); // default: none IMG

	// The height and width of your custom header. You can hook into the theme's own filters to change these values.
	// Add a filter to paramitopia_header_image_width and paramitopia_header_image_height to change these values.
	define( 'HEADER_IMAGE_WIDTH', apply_filters( 'paramitopia_header_image_width', 1000 ) );
	define( 'HEADER_IMAGE_HEIGHT', apply_filters( 'paramitopia_header_image_height', 170 ) );

	// We'll be using post thumbnails for custom header images on posts and pages.
	// We want them to be 950 pixels wide by 180 pixels tall.
	// Larger images will be auto-cropped to fit, smaller ones will be ignored. See header.php.
	set_post_thumbnail_size( HEADER_IMAGE_WIDTH, HEADER_IMAGE_HEIGHT, true );

	// Don't support text inside the header image.
	define( 'NO_HEADER_TEXT', true );

	// Add a way for the custom header to be styled in the admin panel that controls
	// custom headers. See paramitopia_admin_header_style(), below.
	add_custom_image_header( '', 'paramitopia_admin_header_style' );
	if ( ! function_exists( 'paramitopia_admin_header_style' ) ) {
	//Styles the header image displayed on the Appearance > Header admin panel.
		function paramitopia_admin_header_style() {
		?>
			<style type="text/css">
			/* Shows the same border as on front end */
			#headimg { }
			/* If NO_HEADER_TEXT is false, you would style the text with these selectors:
				#headimg #name { }
				#headimg #desc { }
			*/
			</style>
		<?php
		}
	}

} // end of paramitopia_setup()
endif;

// Theme Options
function paramitopia_options_items() {
	$items = array (
		array(
			'id' => 'rss_url',
			'name' => __('RSS URL', 'paramitopia'),
			'desc' => __('Put your full rss subscribe address here.(with http://)', 'paramitopia')
		),
		array(
			'id' => 'twitter_url',
			'name' => __('twitter URL', 'paramitopia'),
			'desc' => __('Put your full twitter address here.(with http:// , leave it blank for display none.)', 'paramitopia')
		),
		array(
			'id' => 'facebook_url',
			'name' => __('facebook URL', 'paramitopia'),
			'desc' => __('Put your full facebook address here.(with http:// , leave it blank for no display none.)', 'paramitopia')
		),
		array(
			'id' => 'excerpt_check',
			'name' => __('Excerpt?', 'paramitopia'),
			'desc' => __('If the home page and archive pages to display excerpt of post, check.', 'paramitopia')
		),
		array(
			'id' => 'comment_notes',
			'name' => __('Disable the comment notes','paramitopia'),
			'desc' => __('Disabling this will remove the note text that displays with more options for adding to comments (html).', 'paramitopia')
		),
		array(
			'id' => 'smilies',
			'name' => __('Disable the comments smilies','paramitopia'),
			'desc' => __('Disabling this will remove the comments smilies.', 'paramitopia')
		)
	);
	return $items;
}

add_action( 'admin_init', 'paramitopia_theme_options_init' );
add_action( 'admin_menu', 'paramitopia_theme_options_add_page' );
function paramitopia_theme_options_init(){
	register_setting( 'paramitopia_options', 'paramitopia_options', 'paramitopia_options_validate' );
}
function paramitopia_theme_options_add_page() {
	add_theme_page( __( 'Theme Options' ), __( 'Theme Options' ), 'edit_theme_options', 'theme_options', 'paramitopia_theme_options_do_page' );
}

function paramitopia_default_options() {
	$options = get_option( 'paramitopia_options' );
	foreach ( paramitopia_options_items() as $item ) {
		if ( ! isset( $options[$item['id']] ) ) {
			$options[$item['id']] = '';
		}
	}
	update_option( 'paramitopia_options', $options );
}
add_action( 'init', 'paramitopia_default_options' );

function paramitopia_theme_options_do_page() {
	if ( ! isset( $_REQUEST['updated'] ) )
		$_REQUEST['updated'] = false;
?>
	<div class="wrap">
		<?php screen_icon(); echo "<h2>" . sprintf( __( '%1$s Theme Options', 'paramitopia' ), get_current_theme() )	 . "</h2>"; ?>
		<?php if ( false !== $_REQUEST['updated'] ) : ?>
		<div class="updated fade"><p><strong><?php _e( 'Options saved', 'paramitopia' ); ?></strong></p></div>
		<?php endif; ?>
		<form method="post" action="options.php">
			<?php settings_fields( 'paramitopia_options' ); ?>
			<?php $options = get_option( 'paramitopia_options' ); ?>
			<table class="form-table">
			<?php foreach (paramitopia_options_items() as $item) { ?>
				<?php if ($item['id'] == 'excerpt_check' || $item['id'] == 'comment_notes' || $item['id'] == 'smilies') { ?>
				<tr valign="top" style="margin:0 10px;border-bottom:1px solid #ddd;">
					<th scope="row"><?php echo $item['name']; ?></th>
					<td>
						<input name="<?php echo 'paramitopia_options['.$item['id'].']'; ?>" type="checkbox" value="true" <?php if ( $options[$item['id']] ) { $checked = "checked=\"checked\""; } else { $checked = ""; } echo $checked; ?> />
						<br/>
						<label class="description" for="<?php echo 'paramitopia_options['.$item['id'].']'; ?>"><?php echo $item['desc']; ?></label>
					</td>
				</tr>
				<?php } else { ?>
				<tr valign="top" style="margin:0 10px;border-bottom:1px solid #ddd;">
					<th scope="row"><?php echo $item['name']; ?></th>
					<td>
						<input name="<?php echo 'paramitopia_options['.$item['id'].']'; ?>" type="text" value="<?php if ( $options[$item['id']] != "") { echo $options[$item['id']]; } else { echo ''; } ?>" size="80" />
						<br/>
						<label class="description" for="<?php echo 'paramitopia_options['.$item['id'].']'; ?>"><?php echo $item['desc']; ?></label>
					</td>
				</tr>
				<?php } ?>
			<?php } ?>
			</table>
			<p class="submit">
				<input type="submit" class="button-primary" value="<?php _e( 'Save Options', 'paramitopia' ); ?>" />
			</p>
		</form>
	</div>
<?php
}
function paramitopia_options_validate($input) {
	foreach ( paramitopia_options_items() as $item ) {
		$input[$item['id']] = wp_filter_nohtml_kses($input[$item['id']]);
	}
	return $input;
}
