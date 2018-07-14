<?php
 add_action( 'init', 'Custom_Posts_init' );
/**
 * Register a Custom Post type.
 *
 * @link http://codex.wordpress.org/Function_Reference/register_post_type
 */
function Custom_Posts_init() {
	
	/* 
	*	Job Custom Post Type
	*/
	$Job_labels = array(
		'name'               => _x( 'Job', 'post type general name', 'your-plugin-textdomain' ),
		'singular_name'      => _x( 'Job', 'post type singular name', 'your-plugin-textdomain' ),
		'menu_name'          => _x( 'Job', 'admin menu', 'your-plugin-textdomain' ),
		'name_admin_bar'     => _x( 'Job', 'add new on admin bar', 'your-plugin-textdomain' ),
		'add_new'            => _x( 'Add New', 'Job', 'your-plugin-textdomain' ),
		'add_new_item'       => __( 'Add New Job', 'your-plugin-textdomain' ),
		'new_item'           => __( 'New Job', 'your-plugin-textdomain' ),
		'edit_item'          => __( 'Edit Job', 'your-plugin-textdomain' ),
		'view_item'          => __( 'View Job', 'your-plugin-textdomain' ),
		'all_items'          => __( 'All Job', 'your-plugin-textdomain' ),
		'search_items'       => __( 'Search Job', 'your-plugin-textdomain' ),
		'parent_item_colon'  => __( 'Parent Job:', 'your-plugin-textdomain' ),
		'not_found'          => __( 'No Job found.', 'your-plugin-textdomain' ),
		'not_found_in_trash' => __( 'No Job found in Trash.', 'your-plugin-textdomain' )
	);

	/* 
	*	Job Custom Post Type
	*/

	$Job_args = array(
		'labels'             => $Job_labels,
        'description'        => __( 'Description.', 'Job' ),
		'public'             => true,
		'publicly_queryable' => true,
		'show_ui'            => true,
		'show_in_menu'       => true,
		'query_var'          => true,
		'rewrite'            => array( 'slug' => 'jobs' ),
		'capability_type'    => 'post',
		'has_archive'        => true,
		'hierarchical'       => true,
		'menu_position'      => null,
		'supports'           => array( 'title', 'editor', 'thumbnail')
	);

	register_post_type( 'job', $Job_args );
	
	
	$Category_labels = array(
		'name'              => _x( 'Category', 'taxonomy general name', 'textdomain' ),
		'singular_name'     => _x( 'Category', 'taxonomy singular name', 'textdomain' ),
		'search_items'      => __( 'Search Category', 'textdomain' ),
		'all_items'         => __( 'All Category', 'textdomain' ),
		'parent_item'       => __( 'Parent Category', 'textdomain' ),
		'parent_item_colon' => __( 'Parent Category:', 'textdomain' ),
		'edit_item'         => __( 'Edit Category', 'textdomain' ),
		'update_item'       => __( 'Update Category', 'textdomain' ),
		'add_new_item'      => __( 'Add New Category', 'textdomain' ),
		'new_item_name'     => __( 'New Genre Category', 'textdomain' ),
		'menu_name'         => __( 'Category', 'textdomain' ),
	);

	$args = array(
		'hierarchical'      => true,
		'labels'            => $Category_labels,
		'show_ui'           => true,
		'show_admin_column' => true,
		'query_var'         => true,
		'show_in_rest'      => true,
		'rewrite'           => array( 'slug' => 'services' , 'with_front' => false )
	);

	register_taxonomy( 'jobs_category', array( 'job' ), $args );

	/* 
	*	Testimonials Custom Post Type
	*/

	$Testimonials_labels = array(
		'name'               => _x( 'Testimonials', 'post type general name', 'your-plugin-textdomain' ),
		'singular_name'      => _x( 'Testimonials', 'post type singular name', 'your-plugin-textdomain' ),
		'menu_name'          => _x( 'Testimonials', 'admin menu', 'your-plugin-textdomain' ),
		'name_admin_bar'     => _x( 'Testimonials', 'add new on admin bar', 'your-plugin-textdomain' ),
		'add_new'            => _x( 'Add New', 'Testimonials', 'your-plugin-textdomain' ),
		'add_new_item'       => __( 'Add New Testimonials', 'your-plugin-textdomain' ),
		'new_item'           => __( 'New Testimonials', 'your-plugin-textdomain' ),
		'edit_item'          => __( 'Edit Testimonials', 'your-plugin-textdomain' ),
		'view_item'          => __( 'View Testimonials', 'your-plugin-textdomain' ),
		'all_items'          => __( 'All Testimonials', 'your-plugin-textdomain' ),
		'search_items'       => __( 'Search Testimonials', 'your-plugin-textdomain' ),
		'parent_item_colon'  => __( 'Parent Testimonials:', 'your-plugin-textdomain' ),
		'not_found'          => __( 'No Testimonials found.', 'your-plugin-textdomain' ),
		'not_found_in_trash' => __( 'No Testimonials found in Trash.', 'your-plugin-textdomain' )
	);

	$Testimonials_args = array(
		'labels'             => $Testimonials_labels,
        'description'        => __( 'Description.', 'Testimonials' ),
		'public'             => true,
		'publicly_queryable' => false,
		'show_ui'            => true,
		'show_in_menu'       => true,
		'query_var'          => true,
		'rewrite'            => array( 'slug' => 'testimonials' ),
		'capability_type'    => 'post',
		'has_archive'        => false,
		'hierarchical'       => false,
		'menu_position'      => null,
		'supports'           => array( 'title', 'editor', 'thumbnail')
	);

	register_post_type( 'Testimonials', $Testimonials_args );



	/* 
	*	Bids Custom Post Type
	*/
	$Bid_labels = array(
		'name'               => _x( 'Bids', 'post type general name', 'your-plugin-textdomain' ),
		'singular_name'      => _x( 'Bids', 'post type singular name', 'your-plugin-textdomain' ),
		'menu_name'          => _x( 'Bids', 'admin menu', 'your-plugin-textdomain' ),
		'name_admin_bar'     => _x( 'Bids', 'add new on admin bar', 'your-plugin-textdomain' ),
		'add_new'            => _x( 'Add New', 'Bids', 'your-plugin-textdomain' ),
		'add_new_item'       => __( 'Add New Bids', 'your-plugin-textdomain' ),
		'new_item'           => __( 'New Bids', 'your-plugin-textdomain' ),
		'edit_item'          => __( 'Edit Bids', 'your-plugin-textdomain' ),
		'view_item'          => __( 'View Bids', 'your-plugin-textdomain' ),
		'all_items'          => __( 'All Bids', 'your-plugin-textdomain' ),
		'search_items'       => __( 'Search Bids', 'your-plugin-textdomain' ),
		'parent_item_colon'  => __( 'Parent Bids:', 'your-plugin-textdomain' ),
		'not_found'          => __( 'No Bids found.', 'your-plugin-textdomain' ),
		'not_found_in_trash' => __( 'No Bids found in Trash.', 'your-plugin-textdomain' )
	);

	/* 
	*	Bids Custom Post Type
	*/

	$Bids_args = array(
		'labels'             => $Bid_labels,
        'description'        => __( 'Description.', 'Bids' ),
		'public'             => true,
		'publicly_queryable' => true,
		'show_ui'            => true,
		'show_in_menu'       => true,
		'query_var'          => true,
		'rewrite'            => array( 'slug' => 'bids' ),
		'capability_type'    => 'post',
		'has_archive'        => false,
		'hierarchical'       => false,
		'menu_position'      => null,
		'supports'           => array( 'title')
	);

	register_post_type( 'bid', $Bids_args );
	


	
	
}