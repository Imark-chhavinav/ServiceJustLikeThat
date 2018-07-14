<?php
/**
 * Twenty Seventeen functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package WordPress
 * @subpackage Twenty_Seventeen
 * @since 1.0
 */

/**
 * Twenty Seventeen only works in WordPress 4.7 or later.
 */
if ( version_compare( $GLOBALS['wp_version'], '4.7-alpha', '<' ) ) {
	require get_template_directory() . '/inc/back-compat.php';
	return;
}

/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which
 * runs before the init hook. The init hook is too late for some features, such
 * as indicating support for post thumbnails.
 */
function twentyseventeen_setup() {
	/*
	 * Make theme available for translation.
	 * Translations can be filed at WordPress.org. See: https://translate.wordpress.org/projects/wp-themes/twentyseventeen
	 * If you're building a theme based on Twenty Seventeen, use a find and replace
	 * to change 'twentyseventeen' to the name of your theme in all the template files.
	 */
	load_theme_textdomain( 'twentyseventeen' );

	// Add default posts and comments RSS feed links to head.
	add_theme_support( 'automatic-feed-links' );

	/*
	 * Let WordPress manage the document title.
	 * By adding theme support, we declare that this theme does not use a
	 * hard-coded <title> tag in the document head, and expect WordPress to
	 * provide it for us.
	 */
	add_theme_support( 'title-tag' );

	/*
	 * Enable support for Post Thumbnails on posts and pages.
	 *
	 * @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
	 */
	add_theme_support( 'post-thumbnails' );

	add_image_size( 'twentyseventeen-featured-image', 2000, 1200, true );

	add_image_size( 'twentyseventeen-thumbnail-avatar', 100, 100, true );


	/*Theme Image sizes*/
	add_image_size( 'themes-image-custom-size', 660, 552, true );
	add_image_size( 'themes-image-custom-size-1', 720, 350, true );
	add_image_size( 'themes-image-custom-size-2', 358, 300, true );
	add_image_size( 'themes-image-custom-size-3', 370, 342, true );
	add_image_size( 'themes-image-custom-size-4', 84, 84, true );
	add_image_size( 'themes-image-custom-size-5', 110, 110, true );

	// Set the default content width.
	$GLOBALS['content_width'] = 525;

	// This theme uses wp_nav_menu() in two locations.
	register_nav_menus( array(
		'top'    => __( 'Top Menu', 'twentyseventeen' ),
		'social' => __( 'Social Links Menu', 'twentyseventeen' ),
	) );

	/*
	 * Switch default core markup for search form, comment form, and comments
	 * to output valid HTML5.
	 */
	add_theme_support( 'html5', array(
		'comment-form',
		'comment-list',
		'gallery',
		'caption',
	) );

	/*
	 * Enable support for Post Formats.
	 *
	 * See: https://codex.wordpress.org/Post_Formats
	 */
	add_theme_support( 'post-formats', array(
		'aside',
		'image',
		'video',
		'quote',
		'link',
		'gallery',
		'audio',
	) );

	// Add theme support for Custom Logo.
	add_theme_support( 'custom-logo', array(
		'width'       => 250,
		'height'      => 250,
		'flex-width'  => true,
	) );

	// Add theme support for selective refresh for widgets.
	add_theme_support( 'customize-selective-refresh-widgets' );

	/*
	 * This theme styles the visual editor to resemble the theme style,
	 * specifically font, colors, and column width.
 	 */
	add_editor_style( array( 'assets/css/editor-style.css', twentyseventeen_fonts_url() ) );

	// Define and register starter content to showcase the theme on new sites.
	$starter_content = array(
		'widgets' => array(
			// Place three core-defined widgets in the sidebar area.
			'sidebar-1' => array(
				'text_business_info',
				'search',
				'text_about',
			),

			// Add the core-defined business info widget to the footer 1 area.
			'sidebar-2' => array(
				'text_business_info',
			),

			// Put two core-defined widgets in the footer 2 area.
			'sidebar-3' => array(
				'text_about',
				'search',
			),
		),

		// Specify the core-defined pages to create and add custom thumbnails to some of them.
		'posts' => array(
			'home',
			'about' => array(
				'thumbnail' => '{{image-sandwich}}',
			),
			'contact' => array(
				'thumbnail' => '{{image-espresso}}',
			),
			'blog' => array(
				'thumbnail' => '{{image-coffee}}',
			),
			'homepage-section' => array(
				'thumbnail' => '{{image-espresso}}',
			),
		),

		// Create the custom image attachments used as post thumbnails for pages.
		'attachments' => array(
			'image-espresso' => array(
				'post_title' => _x( 'Espresso', 'Theme starter content', 'twentyseventeen' ),
				'file' => 'assets/images/espresso.jpg', // URL relative to the template directory.
			),
			'image-sandwich' => array(
				'post_title' => _x( 'Sandwich', 'Theme starter content', 'twentyseventeen' ),
				'file' => 'assets/images/sandwich.jpg',
			),
			'image-coffee' => array(
				'post_title' => _x( 'Coffee', 'Theme starter content', 'twentyseventeen' ),
				'file' => 'assets/images/coffee.jpg',
			),
		),

		// Default to a static front page and assign the front and posts pages.
		'options' => array(
			'show_on_front' => 'page',
			'page_on_front' => '{{home}}',
			'page_for_posts' => '{{blog}}',
		),

		// Set the front page section theme mods to the IDs of the core-registered pages.
		'theme_mods' => array(
			'panel_1' => '{{homepage-section}}',
			'panel_2' => '{{about}}',
			'panel_3' => '{{blog}}',
			'panel_4' => '{{contact}}',
		),

		// Set up nav menus for each of the two areas registered in the theme.
		'nav_menus' => array(
			// Assign a menu to the "top" location.
			'top' => array(
				'name' => __( 'Top Menu', 'twentyseventeen' ),
				'items' => array(
					'link_home', // Note that the core "home" page is actually a link in case a static front page is not used.
					'page_about',
					'page_blog',
					'page_contact',
				),
			),

			// Assign a menu to the "social" location.
			'social' => array(
				'name' => __( 'Social Links Menu', 'twentyseventeen' ),
				'items' => array(
					'link_yelp',
					'link_facebook',
					'link_twitter',
					'link_instagram',
					'link_email',
				),
			),
		),
	);

	/**
	 * Filters Twenty Seventeen array of starter content.
	 *
	 * @since Twenty Seventeen 1.1
	 *
	 * @param array $starter_content Array of starter content.
	 */
	$starter_content = apply_filters( 'twentyseventeen_starter_content', $starter_content );

	add_theme_support( 'starter-content', $starter_content );
}
add_action( 'after_setup_theme', 'twentyseventeen_setup' );

/**
 * Set the content width in pixels, based on the theme's design and stylesheet.
 *
 * Priority 0 to make it available to lower priority callbacks.
 *
 * @global int $content_width
 */
function twentyseventeen_content_width() {

	$content_width = $GLOBALS['content_width'];

	// Get layout.
	$page_layout = get_theme_mod( 'page_layout' );

	// Check if layout is one column.
	if ( 'one-column' === $page_layout ) {
		if ( twentyseventeen_is_frontpage() ) {
			$content_width = 644;
		} elseif ( is_page() ) {
			$content_width = 740;
		}
	}

	// Check if is single post and there is no sidebar.
	if ( is_single() && ! is_active_sidebar( 'sidebar-1' ) ) {
		$content_width = 740;
	}

	/**
	 * Filter Twenty Seventeen content width of the theme.
	 *
	 * @since Twenty Seventeen 1.0
	 *
	 * @param int $content_width Content width in pixels.
	 */
	$GLOBALS['content_width'] = apply_filters( 'twentyseventeen_content_width', $content_width );
}
add_action( 'template_redirect', 'twentyseventeen_content_width', 0 );

/**
 * Register custom fonts.
 */
function twentyseventeen_fonts_url() {
	$fonts_url = '';

	/*
	 * Translators: If there are characters in your language that are not
	 * supported by Libre Franklin, translate this to 'off'. Do not translate
	 * into your own language.
	 */
	$libre_franklin = _x( 'on', 'Libre Franklin font: on or off', 'twentyseventeen' );

	if ( 'off' !== $libre_franklin ) {
		$font_families = array();

		$font_families[] = 'Libre Franklin:300,300i,400,400i,600,600i,800,800i';

		$query_args = array(
			'family' => urlencode( implode( '|', $font_families ) ),
			'subset' => urlencode( 'latin,latin-ext' ),
		);

		$fonts_url = add_query_arg( $query_args, 'https://fonts.googleapis.com/css' );
	}

	return esc_url_raw( $fonts_url );
}

/**
 * Add preconnect for Google Fonts.
 *
 * @since Twenty Seventeen 1.0
 *
 * @param array  $urls           URLs to print for resource hints.
 * @param string $relation_type  The relation type the URLs are printed.
 * @return array $urls           URLs to print for resource hints.
 */
function twentyseventeen_resource_hints( $urls, $relation_type ) {
	if ( wp_style_is( 'twentyseventeen-fonts', 'queue' ) && 'preconnect' === $relation_type ) {
		$urls[] = array(
			'href' => 'https://fonts.gstatic.com',
			'crossorigin',
		);
	}

	return $urls;
}
add_filter( 'wp_resource_hints', 'twentyseventeen_resource_hints', 10, 2 );

/**
 * Register widget area.
 *
 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
 */
function twentyseventeen_widgets_init() {
	register_sidebar( array(
		'name'          => __( 'Blog Sidebar', 'twentyseventeen' ),
		'id'            => 'sidebar-1',
		'description'   => __( 'Add widgets here to appear in your sidebar on blog posts and archive pages.', 'twentyseventeen' ),
		'before_widget' => '<section id="%1$s" class="widget %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<h2 class="widget-title">',
		'after_title'   => '</h2>',
	) );

	register_sidebar( array(
		'name'          => __( 'Footer 1', 'twentyseventeen' ),
		'id'            => 'sidebar-2',
		'description'   => __( 'Add widgets here to appear in your footer.', 'twentyseventeen' ),
		'before_widget' => '<section id="%1$s" class="widget %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<h2 class="widget-title">',
		'after_title'   => '</h2>',
	) );

	register_sidebar( array(
		'name'          => __( 'Footer 2', 'twentyseventeen' ),
		'id'            => 'sidebar-3',
		'description'   => __( 'Add widgets here to appear in your footer.', 'twentyseventeen' ),
		'before_widget' => '<section id="%1$s" class="widget %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<h2 class="widget-title">',
		'after_title'   => '</h2>',
	) );
}
add_action( 'widgets_init', 'twentyseventeen_widgets_init' );

/**
 * Replaces "[...]" (appended to automatically generated excerpts) with ... and
 * a 'Continue reading' link.
 *
 * @since Twenty Seventeen 1.0
 *
 * @param string $link Link to single post/page.
 * @return string 'Continue reading' link prepended with an ellipsis.
 */
function twentyseventeen_excerpt_more( $link ) {
	if ( is_admin() ) {
		return $link;
	}

	$link = sprintf( '<p class="link-more"><a href="%1$s" class="more-link">%2$s</a></p>',
		esc_url( get_permalink( get_the_ID() ) ),
		/* translators: %s: Name of current post */
		sprintf( __( 'Continue reading<span class="screen-reader-text"> "%s"</span>', 'twentyseventeen' ), get_the_title( get_the_ID() ) )
	);
	return ' &hellip; ' . $link;
}
add_filter( 'excerpt_more', 'twentyseventeen_excerpt_more' );

/**
 * Handles JavaScript detection.
 *
 * Adds a `js` class to the root `<html>` element when JavaScript is detected.
 *
 * @since Twenty Seventeen 1.0
 */
function twentyseventeen_javascript_detection() {
	echo "<script>(function(html){html.className = html.className.replace(/\bno-js\b/,'js')})(document.documentElement);</script>\n";
}
add_action( 'wp_head', 'twentyseventeen_javascript_detection', 0 );

/**
 * Add a pingback url auto-discovery header for singularly identifiable articles.
 */
function twentyseventeen_pingback_header() {
	if ( is_singular() && pings_open() ) {
		printf( '<link rel="pingback" href="%s">' . "\n", get_bloginfo( 'pingback_url' ) );
	}
}
add_action( 'wp_head', 'twentyseventeen_pingback_header' );

/**
 * Display custom color CSS.
 */
function twentyseventeen_colors_css_wrap() {
	if ( 'custom' !== get_theme_mod( 'colorscheme' ) && ! is_customize_preview() ) {
		return;
	}

	require_once( get_parent_theme_file_path( '/inc/color-patterns.php' ) );
	$hue = absint( get_theme_mod( 'colorscheme_hue', 250 ) );
?>
	<style type="text/css" id="custom-theme-colors" <?php if ( is_customize_preview() ) { echo 'data-hue="' . $hue . '"'; } ?>>
		<?php echo twentyseventeen_custom_colors_css(); ?>
	</style>
<?php }
add_action( 'wp_head', 'twentyseventeen_colors_css_wrap' );

/**
 * Enqueue scripts and styles.
 */
function twentyseventeen_scripts() {
	// Add custom fonts, used in the main stylesheet.
	wp_enqueue_style( 'twentyseventeen-fonts', twentyseventeen_fonts_url(), array(), null );

	// Theme stylesheet.
	wp_enqueue_style( 'twentyseventeen-style', get_stylesheet_uri() );

	// Load the dark colorscheme.
	if ( 'dark' === get_theme_mod( 'colorscheme', 'light' ) || is_customize_preview() ) {
		wp_enqueue_style( 'twentyseventeen-colors-dark', get_theme_file_uri( '/assets/css/colors-dark.css' ), array( 'twentyseventeen-style' ), '1.0' );
	}

	// Custom Theme Style

	wp_enqueue_style( 'bootstrap.min.css', get_theme_file_uri( '/assets/css/bootstrap.min.css' ), array( 'twentyseventeen-style' ), '1.0' );
	wp_enqueue_style( 'lightbox', get_theme_file_uri( '/assets/css/lightbox.css' ), array( 'twentyseventeen-style' ), '1.0' );
	wp_enqueue_style( 'rangeslider', get_theme_file_uri( '/assets/css/rangeslider.css' ), array( 'twentyseventeen-style' ), '1.0' );
	wp_enqueue_style( 'Themestyle', get_theme_file_uri( '/assets/css/style.css' ), array( 'twentyseventeen-style' ), '1.0' );
	wp_enqueue_style( 'prettify', get_theme_file_uri( '/assets/css/prettify.css' ), array( 'twentyseventeen-style' ), '1.0' );

	wp_enqueue_style( 'bootstrap-datepicker.css', get_theme_file_uri( '/assets/css/bootstrap-datepicker3.css' ), array( 'twentyseventeen-style' ), '1.0' );

	wp_enqueue_style( 'toastr.min.css', get_theme_file_uri( '/assets/css/toastr.min.css' ), array( 'twentyseventeen-style' ), '1.0' );
	
	wp_enqueue_style( 'bootstrap-select.min.css', get_theme_file_uri( '/assets/css/bootstrap-select.min.css' ), array( 'twentyseventeen-style' ), '1.0' );
	wp_enqueue_style( 'dropzone.css', get_theme_file_uri( '/assets/css/dropzone.css' ), array( 'twentyseventeen-style' ), '1.0' );

	wp_enqueue_style( 'mCustomScrollbar.min.css', 'http://malihu.github.io/custom-scrollbar/jquery.mCustomScrollbar.min.css', array( 'twentyseventeen-style' ), '1.0' );



	// Load the Internet Explorer 9 specific stylesheet, to fix display issues in the Customizer.
	if ( is_customize_preview() ) {
		wp_enqueue_style( 'twentyseventeen-ie9', get_theme_file_uri( '/assets/css/ie9.css' ), array( 'twentyseventeen-style' ), '1.0' );
		wp_style_add_data( 'twentyseventeen-ie9', 'conditional', 'IE 9' );
	}

	// Load the Internet Explorer 8 specific stylesheet.
	wp_enqueue_style( 'twentyseventeen-ie8', get_theme_file_uri( '/assets/css/ie8.css' ), array( 'twentyseventeen-style' ), '1.0' );
	wp_style_add_data( 'twentyseventeen-ie8', 'conditional', 'lt IE 9' );

	// Load the html5 shiv.
	wp_enqueue_script( 'html5', get_theme_file_uri( '/assets/js/html5.js' ), array(), '3.7.3' );
	wp_script_add_data( 'html5', 'conditional', 'lt IE 9' );

	wp_enqueue_script( 'twentyseventeen-skip-link-focus-fix', get_theme_file_uri( '/assets/js/skip-link-focus-fix.js' ), array(), '1.0', true );

	$twentyseventeen_l10n = array(
		'quote'          => twentyseventeen_get_svg( array( 'icon' => 'quote-right' ) ),
	);

	if ( has_nav_menu( 'top' ) ) {
		wp_enqueue_script( 'twentyseventeen-navigation', get_theme_file_uri( '/assets/js/navigation.js' ), array( 'jquery' ), '1.0', true );
		$twentyseventeen_l10n['expand']         = __( 'Expand child menu', 'twentyseventeen' );
		$twentyseventeen_l10n['collapse']       = __( 'Collapse child menu', 'twentyseventeen' );
		$twentyseventeen_l10n['icon']           = twentyseventeen_get_svg( array( 'icon' => 'angle-down', 'fallback' => true ) );
	}

	wp_enqueue_script( 'twentyseventeen-global', get_theme_file_uri( '/assets/js/global.js' ), array( 'jquery' ), '1.0', true );

	wp_enqueue_script( 'jquery-scrollto', get_theme_file_uri( '/assets/js/jquery.scrollTo.js' ), array( 'jquery' ), '2.1.2', true );

	wp_localize_script( 'twentyseventeen-skip-link-focus-fix', 'twentyseventeenScreenReaderText', $twentyseventeen_l10n );


	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}

	// Theme Custom Scripts

	if( !is_admin() )
	{
		 wp_deregister_script('jquery');
  		 wp_register_script('jquery', get_theme_file_uri( '/assets/js/jquery-2.1.4.min.js' ), false, true);
  		 wp_enqueue_script('jquery');
	}
	/* Define URL FOR AJAX */
	
	$user = wp_get_current_user();
    $role = ( array ) $user->roles;
	if( !empty($role[0]) )
	{
		if( $role[0] == 'service' )
		{
			$userRole = 2;
		}
		if( $role[0] == 'customer' )
		{
			$userRole = 1;
		}		
	}
	
	
	wp_register_script('ajax_urls','', array('jquery'), null, false);
	wp_localize_script( 'ajax_urls', 'Site', array( 'url' => site_url() ));
	wp_localize_script( 'ajax_urls', 'User', array( 'url' => site_url('/wp-json/users/') ));
	wp_localize_script( 'ajax_urls', 'CurrentUser', array( 'ID' => get_current_user_id() , 'Role' =>(isset($userRole))? $userRole : "" ));
	wp_localize_script( 'ajax_urls', 'Job', array( 'url' => site_url( '/wp-json/jobs/' ) ));
	wp_enqueue_script( 'ajax_urls' );

	wp_enqueue_script( 'jquery-bootstrap.min', get_theme_file_uri( '/assets/js/bootstrap.min.js' ), array( 'jquery' ), false, true );
	wp_enqueue_script( 'jquery-validate', get_theme_file_uri( '/assets/js/jquery.validate.min.js' ), array( 'jquery' ), false, true );
	wp_enqueue_script( 'additional-methods', get_theme_file_uri( '/assets/js/additional-methods.min.js' ), array( 'jquery' ), false, true );
	wp_enqueue_script( 'jquery-wow.min', get_theme_file_uri( '/assets/js/wow.min.js' ), array( 'jquery' ), false, true );
	wp_enqueue_script( 'jquery-owl.carousel', get_theme_file_uri( '/assets/js/owl.carousel.js' ), array( 'jquery' ), false, true );
	wp_enqueue_script( 'jquery-mCustomScrollbar', "http://malihu.github.io/custom-scrollbar/jquery.mCustomScrollbar.concat.min.js" , array( 'jquery' ), false, true );

	wp_enqueue_script( 'jquery.toaster.js', get_theme_file_uri( '/assets/js/jquery.toaster.js' ) , array( 'jquery' ), false, true );
	wp_enqueue_script( 'rangeslider.min.js', get_theme_file_uri( '/assets/js/rangeslider.min.js' ) , array( 'jquery' ), false, true );
	wp_enqueue_script( 'bootstrap-select.min.js', get_theme_file_uri( '/assets/js/bootstrap-select.min.js' ) , array( 'jquery' ), false, true );
	wp_enqueue_script( 'bootstrap-datepicker.min', get_theme_file_uri( '/assets/js/bootstrap-datepicker.min.js' ) , array( 'jquery' ), false, true );
	wp_enqueue_script( 'cardcheck.min', get_theme_file_uri( '/assets/js/cardcheck.min.js' ) , array( 'jquery' ), false, true );
	wp_enqueue_script( 'dropzone', get_theme_file_uri( '/assets/js/dropzone.js' ) , array( 'jquery' ), false, true );

	wp_add_inline_script( 'jquery-mCustomScrollbar', 'try{Typekit.load({ async: true });}catch(e){}' );
	wp_enqueue_script( 'custom-js', get_theme_file_uri( '/assets/js/custom.js' ), array( 'jquery' ), false, true );
	wp_enqueue_script( 'prettify-js', get_theme_file_uri( '/assets/js/prettify.js	' ), array( 'jquery' ), false, true );




}
add_action( 'wp_enqueue_scripts', 'twentyseventeen_scripts' );

/**
 * Add custom image sizes attribute to enhance responsive image functionality
 * for content images.
 *
 * @since Twenty Seventeen 1.0
 *
 * @param string $sizes A source size value for use in a 'sizes' attribute.
 * @param array  $size  Image size. Accepts an array of width and height
 *                      values in pixels (in that order).
 * @return string A source size value for use in a content image 'sizes' attribute.
 */
function twentyseventeen_content_image_sizes_attr( $sizes, $size ) {
	$width = $size[0];

	if ( 740 <= $width ) {
		$sizes = '(max-width: 706px) 89vw, (max-width: 767px) 82vw, 740px';
	}

	if ( is_active_sidebar( 'sidebar-1' ) || is_archive() || is_search() || is_home() || is_page() ) {
		if ( ! ( is_page() && 'one-column' === get_theme_mod( 'page_options' ) ) && 767 <= $width ) {
			 $sizes = '(max-width: 767px) 89vw, (max-width: 1000px) 54vw, (max-width: 1071px) 543px, 580px';
		}
	}

	return $sizes;
}
add_filter( 'wp_calculate_image_sizes', 'twentyseventeen_content_image_sizes_attr', 10, 2 );

/**
 * Filter the `sizes` value in the header image markup.
 *
 * @since Twenty Seventeen 1.0
 *
 * @param string $html   The HTML image tag markup being filtered.
 * @param object $header The custom header object returned by 'get_custom_header()'.
 * @param array  $attr   Array of the attributes for the image tag.
 * @return string The filtered header image HTML.
 */
function twentyseventeen_header_image_tag( $html, $header, $attr ) {
	if ( isset( $attr['sizes'] ) ) {
		$html = str_replace( $attr['sizes'], '100vw', $html );
	}
	return $html;
}
add_filter( 'get_header_image_tag', 'twentyseventeen_header_image_tag', 10, 3 );

/**
 * Add custom image sizes attribute to enhance responsive image functionality
 * for post thumbnails.
 *
 * @since Twenty Seventeen 1.0
 *
 * @param array $attr       Attributes for the image markup.
 * @param int   $attachment Image attachment ID.
 * @param array $size       Registered image size or flat array of height and width dimensions.
 * @return array The filtered attributes for the image markup.
 */
function twentyseventeen_post_thumbnail_sizes_attr( $attr, $attachment, $size ) {
	if ( is_archive() || is_search() || is_home() ) {
		$attr['sizes'] = '(max-width: 767px) 89vw, (max-width: 1000px) 54vw, (max-width: 1071px) 543px, 580px';
	} else {
		$attr['sizes'] = '100vw';
	}

	return $attr;
}
add_filter( 'wp_get_attachment_image_attributes', 'twentyseventeen_post_thumbnail_sizes_attr', 10, 3 );

/**
 * Use front-page.php when Front page displays is set to a static page.
 *
 * @since Twenty Seventeen 1.0
 *
 * @param string $template front-page.php.
 *
 * @return string The template to be used: blank if is_home() is true (defaults to index.php), else $template.
 */
function twentyseventeen_front_page_template( $template ) {
	return is_home() ? '' : $template;
}
add_filter( 'frontpage_template',  'twentyseventeen_front_page_template' );

/**
 * Modifies tag cloud widget arguments to display all tags in the same font size
 * and use list format for better accessibility.
 *
 * @since Twenty Seventeen 1.4
 *
 * @param array $args Arguments for tag cloud widget.
 * @return array The filtered arguments for tag cloud widget.
 */
function twentyseventeen_widget_tag_cloud_args( $args ) {
	$args['largest']  = 1;
	$args['smallest'] = 1;
	$args['unit']     = 'em';
	$args['format']   = 'list';

	return $args;
}
add_filter( 'widget_tag_cloud_args', 'twentyseventeen_widget_tag_cloud_args' );

/**
 * Implement the Custom Header feature.
 */
require get_parent_theme_file_path( '/inc/custom-header.php' );

/**
 * Custom template tags for this theme.
 */
require get_parent_theme_file_path( '/inc/template-tags.php' );

/**
 * Additional features to allow styling of the templates.
 */
require get_parent_theme_file_path( '/inc/template-functions.php' );

/**
 * Customizer additions.
 */
require get_parent_theme_file_path( '/inc/customizer.php' );

/**
 * SVG icons functions and filters.
 */
require get_parent_theme_file_path( '/inc/icon-functions.php' );
function pre( $value )
{
	echo "<pre>";
	print_R($value);
	echo "</pre>";
}

/*
*	Role Setup
*/
function add_roles_on_plugin_activation() {
       add_role( 'customer', 'Customer', array( 'read' => true, 'level_0' => true ) );
       add_role( 'service', 'Service Provider', array( 'read' => true, 'level_0' => true ) );
   }

add_action('after_setup_theme', 'add_roles_on_plugin_activation');



/* Wp Admin Logo */

function custom_loginlogo() 
{
	$fields = get_fields('option');


echo '<style type="text/css">
h1 a {background-image: url("'.$fields["header_logo"]["url"].'") !important; height: 75px !important; width: 100%!important; background-size: 100% !important }
.login h1 a:focus{box-shadow:none !important;}
</style>';
}
add_action('login_head', 'custom_loginlogo');

function my_login_logo_url() {
    return home_url();
}
add_filter( 'login_headerurl', 'my_login_logo_url' );

/* Acf Pro Google Key */
function my_acf_init() {
	
	acf_update_setting('google_api_key', 'AIzaSyAahT6o0c69st0dS0Z1HtHDgqIN4W0NEOI');
}

add_action('acf/init', 'my_acf_init');



/**
 * Theme Settings page
 */
if( function_exists('acf_add_options_page') ) 
{
	acf_add_options_page('Theme Settings');	
	acf_add_options_page('Payment Settings');
}

/**
 * Custom Post type
 */
require get_parent_theme_file_path( '/inc/theme-custom-post-type.php' ); 

/**
 * Theme shortcode
 */

require get_parent_theme_file_path( '/inc/theme-shortcodes.php' ); 

/* https://developers.google.com/maps/documentation/javascript/places-autocomplete */


 /**
 * Theme Validator
 */
require get_parent_theme_file_path( '/inc/theme-validator.php' ); 
require get_parent_theme_file_path( '/inc/theme-wp-modify.php' ); 



 /**
 * Validations
 */
 require get_parent_theme_file_path( '/inc/validations/theme-validation-jobs.php' ); 
 require get_parent_theme_file_path( '/inc/validations/theme-validation-users.php' ); 

 /**
 * Functions
 */

 require get_parent_theme_file_path( '/inc/functions/theme-job-function.php' ); 
 require get_parent_theme_file_path( '/inc/functions/theme-user-function.php' ); 

  /**
 * API 
 */

require get_parent_theme_file_path( '/inc/api/theme-api-jobs.php' ); 
require get_parent_theme_file_path( '/inc/api/theme-api-users.php' ); 



/* Ajax Functions*/
require get_parent_theme_file_path( '/inc/theme-ajax-function.php' ); 


/* Including FB Library */
require get_parent_theme_file_path( '/Facebook/autoload.php' ); 

function loginFb(  )
{
	$fb = new Facebook\Facebook([
	  'app_id' => '131117104233972',
	  'app_secret' => 'bcc9dd63f96da8ac42a349d074f78863',
	  'default_graph_version' => 'v2.10',
	  ]);

	$helper = $fb->getRedirectLoginHelper();

	$permissions = ['email']; // Optional permissions
	$loginUrl = $helper->getLoginUrl(site_url(), $permissions);

	echo '<a class="blue_btn" href="' . $loginUrl . '">Log in with Facebook!</a>';
}
add_shortcode( 'FB_LOGIN' , 'loginFb' );


	function Brain_Config()
	{
		require get_parent_theme_file_path( '/inc/braintree/lib/Braintree.php' ); 
		$Fields = get_fields( 'option' );	
		
		$params = array(
		"testmode"   => ( empty($Fields['test_mode']) )? "0" : $Fields['test_mode'],
		"merchantid" => trim( $Fields['merchant_id'] ),
		"publickey"  => trim( $Fields['public_key'] ),
		"privatekey" => trim( $Fields['private_key'] )
		);	
		
		if ($params['testmode'] == "1")
		{
			Braintree_Configuration::environment('sandbox');
		}
		else
		{
			Braintree_Configuration::environment('production');
		}



		Braintree_Configuration::merchantId($params["merchantid"]);
		Braintree_Configuration::publicKey($params["publickey"]);
		Braintree_Configuration::privateKey($params["privatekey"]);
	}	

	function create_BrainTreecustomer( $Firstname , $Email )
	{
		Brain_Config();
		$result = Braintree_Customer::create(array(
		    'firstName' => $Firstname,
		    'email' => $Email		    
			));
			if ($result->success)
			{
				return $braintree_cust_id = $result->customer->id;			
			}
	}

	function BrainTree_create_card( $CardHolder , $Cvv , $Cardnumber , $expirationDate , $CustomerID )
	{
		Brain_Config();
		$result = \Braintree\CreditCard::create(array(
	    'cardholderName' 	=> $CardHolder,
	    'customerId' 		=> $CustomerID,
	    'number' 			=> $Cardnumber,
	    'expirationDate' 	=> $expirationDate,
	    'cvv' 				=> $Cvv 
		));

		if ($result->success == 1)
			{
				$Message = json_encode(array( 'success' => 1, 'result'=> 'Card Created Successfully', 'error' => 'No Error Found !' ));
			}
		else
			{
				$re_error=$result->errors->deepAll();			
				$Message = json_encode(array( 'success' => 0, 'result'=> '', 'error' => $re_error[0]->message ));
			}

		echo $Message;
		exit;
	}

	function BrainTree_update_card( $token , $CardHolder , $Cvv , $Cardnumber , $expirationDate )
	{
		Brain_Config();
		
		try {
	 	 
			 	 $result = \Braintree\CreditCard::update( $token, array(
			    'cardholderName' => $CardHolder,
			    'cvv' => $Cvv,
			    'number' => $Cardnumber,
			    'expirationDate' => $expirationDate   
				));

		 	  if( $result->success )
		 	  {
		 	  	$Message = json_encode(array( 'success' => 1, 'result'=> 'Card update Successfully', 'error' => 'No Error Found !' ));
		 	  }
		} 
		catch (Braintree_Exception_NotFound $e) 
		{
				$Message = json_encode(array( 'success' => 0, 'result'=> '', 'error' => 'Card not updted !' ));
		}
		echo $Message;
		exit;
	}

	function BrainTree_create_payment( $Amount , $CardToken)
	{
		Brain_Config();	
		$result = Braintree_Transaction::sale(array(
	  	'amount' =>  $Amount,
	  	'paymentMethodToken' => $CardToken, 
		));

			if ($result->success == 1)
				{
					//$Message = json_encode(array( 'success' => 1, 'result'=> $result->transaction->id, 'error' => 'No Error Found !' ));
					$Message = $result->transaction->id;
				}
				else
				{
					$re_error=$result->errors->deepAll();
					$message=$re_error[0]->message;
					$Message = json_encode(array( 'success' => 0, 'result'=> '', 'error' => $message ));
				}
		return $Message;
	}


	function BrainTree_delete_card( $creditCardToken )
	{
		Brain_Config();
		try {
	 	  $result = \Braintree\CreditCard::delete($creditCardToken);
	 	  if( $result->success )
	 	  {
	 	  	$Message = json_encode(array( 'success' => 1, 'result'=> 'Card Deleted Successfully', 'error' => 'No Error Found !' ));
	 	  }
		} 
		catch (Braintree_Exception_NotFound $e) 
		{
			$Message = json_encode(array( 'success' => 0, 'result'=> '', 'error' => 'Card not Deleted ' ));
		}
		echo $Message;
		exit;
	}


	function BrainTree_getCustomer( $CustomerID , $return = NULL )
	{
		Brain_Config();
		try {
	 	  $result = Braintree_Customer::find($CustomerID);
	 	  $Cards = array();
	 	  	foreach( $result->creditCards as $keys ):
				//pre($keys);	
	 	  		 $Cards[] = array( 'cardholderName'=> $keys->cardholderName,'card_Token' => $keys->token , 'card_number' => $keys->maskedNumber  , 'expirationDate' => $keys->expirationDate , 'card_type' =>$keys->cardType , 'default' => $keys->default , 'expirationMonth' => $keys->expirationMonth , 'expirationYear' => $keys->expirationYear );		 	
	 	 	 endforeach;
	 	 $Message = json_encode(array( 'success' => 1, 'result'=> $Cards, 'error' => 'No Error Found !' ));

		} 
		catch (Braintree_Exception_NotFound $e) 
		{  		
	  		$Message = json_encode(array( 'success' => 0, 'result'=> '', 'error' => $e->getMessage() ));
		}

		if( !empty( $return ) )
		{
			$DataS = json_decode($Message);
			return 	$DataS->result;		
		}
		else
		{
			echo $Message;			
			exit;
		}		
	}

add_action( 'init', 'wpse12065_init' );
function wpse12065_init()
{
   add_rewrite_rule('^bids-received/([^/]*)/?','index.php?page_id=756','top'); 
   add_rewrite_rule('^submit-bid/([^/]*)/?','index.php?page_id=929','top'); 
   add_rewrite_rule('^view-questions/([^/]*)/?','index.php?page_id=954','top'); 
   add_rewrite_rule('^cards/([^/]*)/?','index.php?page_id=961','top'); 
   //add_rewrite_rule('^profile/([^/]*)/?','index.php?page_id=579','top');  
}