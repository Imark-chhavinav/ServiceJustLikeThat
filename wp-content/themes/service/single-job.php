<?php
/**
 * The template for displaying all single posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package WordPress
 * @subpackage Twenty_Seventeen
 * @since 1.0
 * @version 1.0
 */

get_header(); ?>
<?php  $fields = get_fields(); if ( have_posts() ) : while ( have_posts() ) : the_post();  $JobID = get_the_ID(); //pre($fields); 
?>
 <!--header end-->
    <section class="banner inner-banner">
        <div id="carousel-example-generic" class="carousel slide" data-ride="carousel">
            <!-- Wrapper for slides -->
            <div class="carousel-inner" role="listbox">
                <div class="item active" style="background-image:url(images/inner-page-banner.jpg);">
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
                <h2>Job Description</h2> </div>
            
            <div class="add-border-cover job-description-cover">
                <h3><?php the_title(); ?></h3>
                
                <div class="customer-profile-pic">
                    <figure style="background-image: url(<?php echo esc_url( get_avatar_url( $fields['customer_name']['ID'] ) ); ?>)"></figure>
                    <h5><?php echo $fields['customer_name']['display_name']; ?></h5>
                    <div class="price">
                        <p> $ <?php echo $fields['job_price']; ?></p>
                    </div>
                </div>
                
                <div class="customer-profile-description">
                    <h5>Job Description</h5>
                    <?php wpautop( the_content() ); ?>
                </div>
                
                <div class="custom-table">
                <table>                      
                         <tr>                            
                            <td>
                                <h5>Date</h5>
                                <p><?php echo $fields['job_date']; ?></p>
                            </td>
                             
                             <td>
                                <h5>Time</h5>
                                <p><?php echo $fields['job_time']; ?></p>
                            </td>
                        </tr>
                    
                    
                    <tr>
                        <td>
                                <h5>Address</h5>
                                <p><?php echo $fields['street_addess']['address']; ?></p>
                            </td>
                            
                            <td>
                                <h5>City</h5>
                                <p><?php echo $fields['city']; ?></p>
                            </td>
                        </tr>
                    
                    
                    <tr>
                            <td>
                                <h5>State</h5>
                                <p><?php echo $fields['state'][0]['label']; ?></p>
                            </td>
                            <td>
                               <h5>Zip Code</h5>
                                <p><?php echo $fields['postcode']; ?></p>
                            </td>
                        </tr>
                  
                </table>
                </div>
            </div>
              <?php if( $fields['customer_name']['ID'] == get_current_user_id() ){ apply_filters( 'BIDCONTRACTDETAILS' , $JobID ); } ?>      
              <?php apply_filters( 'BIDUSERDETAILS' , $JobID ); ?>      
              <?php apply_filters( 'CONTRACTDETAILS' , $JobID ); ?>      
        </div>
    </div>  
      
<?php	endwhile; endif;  ?>
<?php get_footer();
