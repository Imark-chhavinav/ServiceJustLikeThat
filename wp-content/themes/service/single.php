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
                                <h1>Opportunities At <strong> Your Fingertips</strong></h1>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- banner end-->

<?php  
	/* Start the Loop */
	while ( have_posts() ) : the_post(); ?>	
    <div class="inner-pages blog-page">
        <div class="container">
            <div class="text-center">
                <h2>Blog</h2> </div>
            <div class="add-border-cover blog-cover">
                
                <div class="row">
                    <div class="col-md-8">
                        <div class="blog-post">
                            <article>
                                <figure style="background-image:url('<?php echo get_the_post_thumbnail_url( get_the_ID(), 'full'); ?>');"></figure>
                                <h4>
                                	<a href="<?php echo get_the_permalink(); ?>">
                                	<?php echo get_the_title();  ?>
                                	</a>
                                </h4>
                                <?php echo wpautop( get_the_content()); ?>                            
                                <div class="blog-info">
                                    <div class="blog-info-left">
                                        <ul>
                                            <li><a href="#">By <?php echo nl2br(get_the_author_meta('display_name')); ?>
											</a></li>
                                            <li><?php echo get_the_date( 'M d, Y' ); ?></li>
                                        </ul>
                                    </div>
                                    
                                    <div class="blog-info-right">
                                        <ul>
                                            <li><a href="#"><i class="fa fa-thumbs-o-up" aria-hidden="true"></i> Like</a></li>
                                            <li><a href="#"><i class="fa fa-share-alt" aria-hidden="true"></i> Share</a></li>
                                            <li><a href="#"><i class="fa fa-comments-o" aria-hidden="true"></i> 0 Comments</a></li>
                                        </ul>
                                    </div>
                                    
                                    
                                </div>
                                
                                
                            </article>
                            
                            <article>
                               <?php 
	                            if ( comments_open() || get_comments_number() ) :
									comments_template();
								endif;
                                ?>
                            </article>
                            <?php
					endwhile;
				?>
                        
                            
                        </div>
                    </div>
                    
                    <div class="col-md-4">
                        <div class="blog-sidebar">
                            <div class="blog-search">
                              <form role="search" method="get" class="search-form" action="<?php echo site_url(); ?>">
                                    <input type="text" name="s" placeholder="Search" class="form-control">
                                    <input type="submit" class="search-btn" value=""> 
                                </form> 
                            </div>
                            
                            <div class="blog-recent-post">
                                <h4>Recent Posts</h4>
                                <ul>
                                <?php
                                    $recent_args = array( 'numberposts' => '5','orderby' => 'post_date','order' => 'DESC','post_status' => 'publish' );
                                    $recent_posts = wp_get_recent_posts( $recent_args );
                                    foreach( $recent_posts as $recent )
                                    {
                                       echo'<li>
                                            <a href="'. get_the_permalink( $recent["ID"]  ).'">
                                                <figure style="background-image:url('.get_the_post_thumbnail_url(  $recent["ID"] ).')"></figure>
                                            </a>
                                            <h5><a href="">'.$recent["post_title"].'</a></h5>
                                            '.substr( $recent["post_content"] ,0, 30).'...
                                        </li>';
                                    }
                                    wp_reset_query();
                                ?> 
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php get_footer();
