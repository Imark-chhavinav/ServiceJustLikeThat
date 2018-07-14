<?php 
/**
 * Template Name: Bids Received
 *
 * @author Chhavinav Sehgal
 * @copyright 
 * @link 
 */
global $post;
$link_array = array_filter ( explode('/',$_SERVER['REQUEST_URI']) ); /*pre($post); pre( $link_array );*/ $Slugpage = end($link_array); //if( $post->post_name == $page ) { wp_safe_redirect( site_url( '/my-jobs/' ), 302 );exit; } 

get_header();  ?>
<link href="<?php echo get_template_directory_uri(); ?>/assets/css/lightbox.css" rel="stylesheet">
<?php  
    $Pagefields = get_fields(); if ( have_posts() ) : while ( have_posts() ) : the_post();
   
    $my_posts = get_posts( array( 'name' => $Slugpage, 'post_type' => 'job' , 'post_status' => 'publish' , 'numberposts' => 1  ) );
    if( empty($my_posts) )
    {
        echo "<script> window.location='".site_url( '/my-jobs/' )."'; </script>";
    }
    $GetPostFields = get_fields( $my_posts[0]->ID );
    //pre(  $GetPostFields );
    $Obj = new WPmodify();  
    $Profile = $Obj->profileImage( $GetPostFields['customer_name']['ID'] );

    $Image = ( empty($Profile) )? get_avatar_url( $GetPostFields['customer_name']['ID'] ) : $Profile ; 

    if( isset($GetPostFields['attachment']) )
    {
        $AttchmentType = $GetPostFields['attachment']['type'];
    }
    


?>

<section class="banner inner-banner">
    <div id="carousel-example-generic" class="carousel slide" data-ride="carousel">
        <!-- Wrapper for slides -->
        <div class="carousel-inner" role="listbox">
            <div class="item active" style="background:url(images/inner-page-banner.jpg) no-repeat;">
                <div class="container">
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="banner_cntnt">
                                <h1>Opportunities At <strong> Your Fingertips</strong></h1> </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
    <!-- banner end-->
    <div class="inner-pages job-description-page">
        <div class="container">
            <div class="text-center">
                <h2><?php echo $my_posts[0]->post_title; ?></h2> </div>
            <div class="add-border-cover received-bid-cover job-description-cover">
                
                <div class="customer-profile-pic">
                    
                    <figure style="background-image: url('<?php echo $Image; ?>');"></figure>
                    <h5><?php echo $GetPostFields['customer_name']['user_firstname']; ?></h5>
                    <div class="price">
                        <p>$<?php echo $GetPostFields['job_price']; ?></p>
                    </div>
                </div>
                
                <div class="customer-profile-description">
                    <h5>Job Description</h5>
                    <?php echo wpautop($my_posts[0]->post_content); ?> 
                </div>
                
                <div class="custom-table">
                <table>
                    <tr>
                        <td>
                            <h5>Date</h5>
                            <p><?php echo $GetPostFields['job_date']; ?></p>
                        </td>
                        <td>
                        <h5>Time</h5>
                        <p><?php echo $GetPostFields['job_time']; ?></p>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <h5>Address</h5>
                            <p><?php echo $GetPostFields['street_addess']['address']; ?></p>
                        </td>
                        <td>
                            <h5>City</h5>
                            <p><?php echo $GetPostFields['city']; ?></p>
                        </td>
                    </tr>                    
                    <tr>
                        <td>
                            <h5>State</h5>
                            <p><?php echo $GetPostFields['state'][0]['label']; ?></p>
                        </td>
                        <td>
                            <h5>Zip Code</h5>
                            <p><?php echo $GetPostFields['postcode']; ?></p>
                        </td>
                        </tr>
                        <?php  if( isset( $AttchmentType ) ):  ?>
                        <tr>
                            <td>
                                <h5>Attachment</h5>
                                <?php                               
                                if( $AttchmentType == 'image' )
                                    {
                                        echo '<a class="example-image-link" href="'.$GetPostFields["attachment"]["url"].'" data-lightbox="example-1"><img class="example-image" src="'.$GetPostFields["attachment"]["sizes"]["thumbnail"].'" alt="image-1" /></a>';
                                    }
                                    else
                                    {
                                        echo '<a class="example-image-link" target="_blank" href="'.$GetPostFields["attachment"]["url"].'" data-lightbox="example-1"><img class="example-image" src="'.get_template_directory_uri().'/assets/images/attachment-pin-icon.png'.'" alt="image-1" /></a>';
                                    }
                                ?>
                            </td>
                        </tr>
                    <?php endif; ?>
                </table>
                   
                    
                </div>
                
                <div class="custom-divider"></div>
            <div class="add-border-cover received-bid-cover">
                <?php                
                  
                    $JobID = $my_posts[0]->ID;

                    $args = array(
                    'post_type' => 'bid',
                    'orderby' => 'date',
                    'order' => 'DESC',
                    'posts_per_page' => 10,
                    'paged' => $paged,
                    'meta_query'    => 
                        array(
                            array(
                            'key' => 'select_job',
                            'value' => '"'.$JobID.'"',
                            'compare' => 'LIKE'
                            )/*,
                            array(
                            'key' => 'status',
                            'value' => array( 0 ),
                            'compare' => 'IN'
                            )*/
                        )
                    );
                    //pre( $args );
                    $the_query = new WP_Query($args);
                    $UserDetails = array();
                  
                    if( $the_query->post_count == 0 )
                    {
                         echo "<p>No Bids Found !</p>";
                    }

                    if( !empty( $the_query ) ):
                        while( $the_query->have_posts() ) : $the_query->the_post();
						
						$postData 		= get_post( get_the_ID() );
                        $fields         = get_fields( get_the_ID() ); //pre( $fields );
                        $jobFields      = get_fields( $fields['select_job'][0]->ID );

                        $WPmodify       = new WPmodify();
                        $Profile        = $WPmodify->getProfileDetails( $fields['select_service_provider']['ID'] );
						$PImage			= ( empty( $Profile[0]->profile_image ) )? get_avatar_url( $Profile[0]->user_id ) : $Profile[0]->profile_image ;
                        $custID         = $jobFields['customer_name']['ID'];           
                        $serv_ID        = $Profile[0]->user_id;

                        $ConvID         = $WPmodify->getConversionID( $custID , $serv_ID);
                        $rating         = $WPmodify->getUserRating( $serv_ID ,'2' );
                        $successrate    = $WPmodify->getSuccessRate( $serv_ID );
                        ?>
                            <article>                   
                                <figure style="background-image:url('<?php echo $PImage; ?>')"></figure>
                                <div class="user-main-details">
                                    <div class="user-name">
                                        <p><?php echo $Profile[0]->first_name; ?></p>
                                    </div>
                                    <div class="location">
                                        <p><?php echo $Profile[0]->street_address; ?></p>
                                    </div>
                                </div>
                                <div class="price">
                                    <p>$<?php echo $fields['quoted_price']; ?></p>
                                </div>
                                <div class="received-bid-info">
                                    <?php echo $fields['additional_information']; ?>
                                </div> <a href="<?php echo site_url( 'view-questions/'.$postData->post_name ); ?>" class="blue-btn-link">View Question<small>(s)</small></a>
                                <div class="pd-btn-group">
                                    <ul>
                                        <?php if( $fields['status'] == 2 ): ?>
                                             <li><a href="javascript:void(0)" class="pd-btn pd-red-btn">Bid Rejected</a></li>
                                        <?php else: ?>
                                        <li>
										<a href="<?php echo  site_url( 'cards/'.$postData->post_name );  ?>" class="pd-btn pd-blue-btn">accept</a></li>
										</li>
                                        <li><a href="javascript:void(0)" data-status="2" data-jobId="<?php echo $fields['select_job'][0]->ID; ?>" data-BidId="<?php echo get_the_ID(); ?>" class="pd-btn pd-red-btn reject-bid">decline</a></li>
                                        <li>
                                        <form action="<?php echo site_url('/message/'); ?>" method="POST">
                                            <input type="hidden" name="Touser_id" value="<?php echo $serv_ID; ?>">
                                             <input type="hidden" name="Fromuser_id" value="<?php echo get_current_user_id(); ?>">
                                            <input type="hidden" name="conv_id" value="<?php echo $ConvID; ?>">
                                            <input type="submit" value="Message" class="white-btn">
                                        </form>                                          
                                        </li>
                                    <?php endif; ?>
                                    </ul>
                                </div>
                            </article>

                       <?php

                        endwhile;       
                    endif;
                    ?>                
            </div>
        </div>
    </div>
    </div>
 
<?php   endwhile; endif;  ?>
<?php get_footer(); ?>
<script src="<?php echo  get_template_directory_uri().'/assets/js/lightbox.js'; ?>"></script>