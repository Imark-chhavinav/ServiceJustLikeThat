<?php 
/*
*   Template Name: About Us Page
*/
get_header();?>
<?php  $fields = get_fields(); if ( have_posts() ) : while ( have_posts() ) : the_post();   ?>

  <!--header end-->
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
            <div class="text-center">
                <h2><?php  the_title() ?></h2> </div>
            <div class="add-border-cover about-cover">
                <?php echo get_the_post_thumbnail(  get_the_ID() , 'themes-image-custom-size', array( 'class' => 'right-align' ) ); ?>
                
                <?php the_content(); ?>
            </div>
        </div>
    </div>
<?php   endwhile; endif;  ?>
<?php get_footer(); ?>