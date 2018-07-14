<?php 
/*
*   Template Name: Profile Page
*/
get_header();?>
<?php  $fields = get_fields(); if ( have_posts() ) : while ( have_posts() ) : the_post();   ?>
<section class="banner inner-banner">
        <div id="carousel-example-generic" class="carousel slide" data-ride="carousel">
            <!-- Wrapper for slides -->
            <div class="carousel-inner" role="listbox">
                <div class="item active" style="background-image:url(images/inner-page-banner2.jpg);">
                    <div class="container">
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="banner_cntnt">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- banner end-->
    <div class="inner-pages categorie-profile-page">
        <?php  
        global $post;
        // Showing the profile if any user slug is present
        $link_array = array_filter ( explode('/',$_SERVER['REQUEST_URI']) ); 
        /*pre($post); pre( $link_array );*/ 
        $Slugpage = end($link_array); 
        if( $post->post_name == $Slugpage )
        { 
            // Current User Profile 
            $current_user = wp_get_current_user();
            $UserId = $current_user->ID;
            $Role = $current_user->roles[0];

            // Getting User Details           
            $DataUser = new Users();
            $request = new WP_REST_Request( 'GET', '/users/getprofile' );
            $request->set_query_params(array(
                    'user_id' => $UserId,                 
                    'return' => 1,                 
            ));         
            $Details =  $DataUser->getprofile($request);           
            
            // As per username
            if( $Role == 'customer' )
            {
                $Card_DataUser = new Users();
                $Card_request = new WP_REST_Request( 'GET', '/users/getCards' );
                $Card_request->set_query_params(array(
                    'user_id' => $UserId,                 
                    'return' => 1,                 
                ));         
                $Cards =  $Card_DataUser->getCards($Card_request);                
                $Details->cards = $Cards;
                $Details->user_loggin = true;
                set_query_var( 'Details', $Details );                
                get_template_part( 'page-template/content-customer');   // Customer Details
            }
            elseif( $Role == 'service' )
            {
                set_query_var( 'Details', $Details );
                get_template_part( 'page-template/content-service');     // Service Provider Details
            }
        }
        else
        {
            // As per username
            if( $Role == 'customer' )
            {
               get_template_part( 'page-template/content-customer');// Customer Details
            }
            elseif( $Role == 'service' )
            {
               get_template_part( 'page-template/content-service');     // Service Provider Details
            }
        }

         

        ?>
    </div>
<?php   endwhile; endif;  ?>
<?php get_footer(); ?>

<?php if( $post->post_name == $Slugpage ){ echo do_shortcode('[EDIT_PROF]'); } ?>
<?php echo do_shortcode('[Port_PopUp]'); ?>