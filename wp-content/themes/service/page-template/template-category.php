<?php 
/*
*	Template Name: Category Page
*/
get_header();?>
<?php  $fields = get_fields(); if ( have_posts() ) : while ( have_posts() ) : the_post();	?>

 <section class="banner inner-banner">
        <div id="carousel-example-generic" class="carousel slide" data-ride="carousel">
            <!-- Wrapper for slides -->
            <div class="carousel-inner" role="listbox">
                <div class="item active" style="background-image:url(<?php echo $fields['banner_image']['url']; ?>);">
                    <div class="container">
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="banner_cntnt">
                                    <h1><?php echo $fields['banner_text']; ?></h1> 
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- banner end-->
    <div class="inner-pages faq-page">
        <div class="container">
        	<div class="row">
        	<div class="col-sm-12">
                    <h2><?php the_title(); ?></h2>
                    <div class="home_service_list">
                        <ul>                                 
             			  <?php echo do_shortcode('[CATG]'); ?>                       
                        </ul> 
                    </div>
                </div>
                </div>           
        </div>
    </div>
<?php	endwhile; endif;  ?>
<?php get_footer(); ?>