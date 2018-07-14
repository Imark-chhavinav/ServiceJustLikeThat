<?php 
/**
 * Template Name: Blog Template
 *
 * @author Chhavinav Sehgal
 * @copyright chhavinav.in
 * @link shadow_chhavi @skype
 */ 
get_header(); ?>
<?php  $fields = get_fields(103); /*if ( have_posts() ) : while ( have_posts() ) : the_post();*/   ?>

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
   <div class="inner-pages blog-page">
        <div class="container">
            <div class="text-center">
                <h2>Blog</h2> </div>
            <div class="add-border-cover blog-cover">
                
                <div class="row">
                    <div class="col-md-8">
                        <div class="blog-post">

                            <?php 
                          
                            if(have_posts())
                            {
                                while(have_posts())
                                {   
                                	$ID = get_the_ID();
                                    the_post();
                                    $featured_img_url = get_the_post_thumbnail_url( $ID, 'full');   
                                    if (empty($featured_img_url)) 
                                    {
                                          /*$output = preg_match_all('/<img.+src=[\'"]([^\'"]+)[\'"].*>/i', get_the_content($ID), $matches );
                                        $featured_img_url = $matches [1] [0];  */    
                    $featured_img_url = get_template_directory_uri().'/assets/images/about-us-img.jpg';

                                    }
                                    
                            ?>
                            <article>
                                <figure style="background-image:url('<?php echo $featured_img_url; ?>');"></figure>
                                <h4><a href="<?php echo get_the_permalink(); ?>"><?php echo get_the_title(); ?></a></h4>
                                <?php 
                                        
                                        $excerpt = get_the_excerpt();
                                        $excerpt = substr( $excerpt , 0, 200); 
                                        echo wpautop($excerpt);

                                        ?>
                                <a href="<?php echo get_the_permalink(); ?>" class="read-more-link">Read More</a>
                                
                                <div class="blog-info">
                                    <div class="blog-info-left">
                                        <ul>
                                            <li><a href="#">By <?php echo get_the_author(); ?></a></li>
                                            <li><?php echo  get_the_date('M d, Y'); ?></li>
                                        </ul>
                                    </div>
                                    
                                    <div class="blog-info-right">
                                        <ul>
                                            <li><a href="#"><i class="fa fa-thumbs-o-up" aria-hidden="true"></i> Like</a></li>
                                            <li><a href="#"><i class="fa fa-share-alt" aria-hidden="true"></i> Share</a></li>
                                            <li><a href="<?php echo get_the_permalink(); ?>"><i class="fa fa-comments-o" aria-hidden="true"></i> <?php echo  get_comments_number(); ?> Comments</a></li>
                                        </ul>
                                    </div>
                                    
                                    
                                </div>
                                
                                
                            </article>



             <?php    }   wp_reset_postdata(); }else {  echo "<p>No Result Found !  </p>";  }

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

                        <div class="text-left">
                            <div data="english" class="nav-previous alignleft">
                                <?php  previous_posts_link( '&laquo; PREV' ); ?>                                
                            </div>
                            <div data="english" class="nav-next alignright">
                                <?php  next_posts_link( 'NEXT &raquo;' ); ?>
                            </div>
                        </div>


        </div>
    </div>
<?php  /* endwhile; endif;*/  ?>
<?php get_footer(); ?>