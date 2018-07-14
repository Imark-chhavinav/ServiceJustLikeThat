<?php 
/**
 * Template Name: Faq Template
 *
 * @author Chhavinav Sehgal
 * @copyright chhavinav.in
 * @link shadow_chhavi @skype
 */ 
get_header(); ?>
<?php  $fields = get_fields(); if ( have_posts() ) : while ( have_posts() ) : the_post();  //pre($fields); ?>

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
   <!-- banner end-->
    <div class="inner-pages faq-page">
        <div class="container">
            <div class="text-center">
                <h2><?php  the_title(); ?></h2> </div>
            <div class="add-border-cover faq-cover">
                <div class="panel-group" id="accordion">
            <?php  $x = 0; foreach( $fields['faq'] as $keys ): ?>

                <?php if( $x == 0 ): ?>

                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h4 class="panel-title">
                            <a data-toggle="collapse" data-parent="#accordion" href="#collapse<?php echo $x; ?>">
                                <?php echo $keys['questions']; ?>
                            <span class="plus-mins"></span>
                            </a>
                          </h4> </div>
                        <div id="collapse<?php echo $x; ?>" class="panel-collapse collapse in">
                            <div class="panel-body">
                               <?php echo wpautop($keys['answers']); ?>
                            </div>
                        </div>
                    </div>
                <?php else: ?>
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h4 class="panel-title">
                            <a data-toggle="collapse" data-parent="#accordion" href="#collapse<?php echo $x; ?>" class="collapsed">
                             <?php echo $keys['questions']; ?>
                              
                                <span class="plus-mins"></span>
                              
                              </a>
                          </h4> </div>
                        <div id="collapse<?php echo $x; ?>" class="panel-collapse collapse">
                            <div class="panel-body">
                                 <?php echo wpautop($keys['answers']); ?>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
            <?php $x++; endforeach; ?>                   
                </div>
            </div>
        </div>
    </div>
<?php   endwhile; endif;  ?>
<?php get_footer(); ?>