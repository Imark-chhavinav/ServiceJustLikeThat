<?php 

/**

 * Template Name: My Jobs

 *

 * @author Chhavinav Sehgal

 * @copyright 

 * @link 

 */ 

get_header(); ?>

<link href="<?php echo get_template_directory_uri(); ?>/assets/css/star-rating.css" rel="stylesheet">

<?php  $Pagefields = get_fields(); if ( have_posts() ) : while ( have_posts() ) : the_post();   ?>

<?php 

    if( !is_user_logged_in())

    {

        if ( ! current_user_can('administrator') ) 

        {

            echo "<script>window.location = '".site_url()."'</script>";

        }         

    }

    else

    {

        $UserID = get_current_user_id();

        // Current User Profile 

        $current_user = wp_get_current_user();

        $Role = $current_user->roles[0];

    }

?>

<div class="inner-pages my-jobs-page">

    <div class="container">

        <div class="text-center">

           <h2>My jobs</h2> 

        </div>

        <?php 

        if( $Role == 'customer' )

            {

               get_template_part( 'page-template/myjobs-customer');// Customer Details

            }

        elseif( $Role == 'service' )

            {

               get_template_part( 'page-template/myjobs-service');     // Service Provider Details

            }

        ?>

    </div>

</div>



<?php   endwhile; endif;  ?>

<?php get_footer(); ?>

<script src="<?php echo  get_template_directory_uri().'/assets/js/star-rating.js'; ?>"></script>
<?php echo do_shortcode('[VIEW_FEEDBACK]'); ?>

<?php echo do_shortcode('[LEAVE_FEEDBACK]'); ?>
<?php echo do_shortcode('[Customer_LEAVE_FEEDBACK]'); ?>

<script>

    var starrating1 = new StarRating( document.getElementById( 'star-rating-1' ),

    {          

        showText: false           

    });
	
	var starrating12 = new StarRating( document.getElementById( 'star-rating-12' ),
    {          

        showText: false           

    });     

</script>