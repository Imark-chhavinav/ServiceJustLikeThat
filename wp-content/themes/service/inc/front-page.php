<?php
/**
 * The front page template file
 *
 * If the user has selected a static page for their homepage, this is what will
 * appear.
 * Learn more: https://codex.wordpress.org/Template_Hierarchy
 *
 * @package WordPress
 * @subpackage Twenty_Seventeen
 * @since 1.0
 * @version 1.0
 */

get_header(); ?>
<?php  $Options = get_fields('option'); if ( have_posts() ) : while ( have_posts() ) : the_post(); //pre($Options);	
?>

	
	 <!--header end-->
    <section class="banner">
        <div id="carousel-example-generic" class="carousel slide" data-ride="carousel">
            <!-- Wrapper for slides -->
            <div class="carousel-inner" role="listbox">   
           
               <?php 
            	if( !empty($Options['slider']) ):
            		$slide = '';
            		$x = 0;
            		foreach( $Options['slider'] as  $keys ):            			
            			if( $x == 0 )
            			{
            				$slide .='<div class="item active" style="background-image:url('.$keys["slider_image"]["url"].');">';
            			}
            			else
            			{
            				$slide .='<div class="item" style="background-image:url('.$keys["slider_image"]["url"].');">';
            			}

	                $slide .='<div class="container">
	                        	<div class="row">
	                            	<div class="col-sm-12">';

									if( !empty( $keys['slider_text'] ) )
									{
										$slide .='<div class="banner_cntnt">';
										$slide .= $keys['slider_text'];

										if( !empty( $keys['slider_button_text'] ) && !empty( $keys['slider_button_url'] ) )
										{
											$slide .='<a href="'.$keys["slider_button_url"].'" title="" class="blue_btn">'.$keys["slider_button_text"].'</a>';	
										}

										$slide .='</div>';
									}	                              
	                                   

	               	$slide .='		</div>
	                        	</div>
	                    	</div>
	            	</div>';

            		$x++; endforeach;
	            	echo  $slide;
            	endif;	
            ?>	
            </div>        
        </div>
    </section>
    <!-- banner end-->
    <div class="home_search">
        <div class="container">
            <div class="row">
                <div class="col-sm-12">
                    <div class="home_search_main">
                        <div class="search_bx">
                            <div class="search_inpt">
                                <input type="text" placeholder="Search" class="formcontrol1"> </div>
                            <!--search_inpt end-->
                            <div class="search_cat slct">
                                <select class="placeholder1 formcontrol1">
                                    <option>Category</option>
                                    <option>Category1</option>
                                    <option>Category2</option>
                                </select>
                            </div>
                        </div>
                        <!--search_bx end-->
                        <div class="search_bx search_location ">
                            <div class="search_inpt">
                                <input type="text" placeholder="Location" class="formcontrol1"> </div>
                            <!--search_inpt end-->
                            <div class="search_city slct">
                                <select class="placeholder1 formcontrol1">
                                    <option>city</option>
                                    <option>city1</option>
                                    <option>city2</option>
                                </select>
                            </div>
                        </div>
                        <!--search_location end-->
                        <div class="search_bx zip_code">
                            <input type="text" placeholder="zip code" class="formcontrol1"> </div>
                        <div class="srch_btn">
                            <input type="submit" class="blue_btn" value="Search"> </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="clearfix"></div>
    <!--home_search end-->
    <section class="home_service">
        <div class="container">
            <div class="row">
                <div class="col-sm-12">
                    <h2>Service ssssssssss</h2>
                    <?php echo do_shortcode( '[BRAIN]' ); ?>
                    <div class="home_service_list">

                        <ul>
                            <?php echo do_shortcode('[CATG]'); ?>                           
                        </ul> <a href="<?php echo site_url( 'category' ); ?>"  class="blue_btn blue_btn_nrml">See All Categories</a> </div>
                </div>
            </div>
        </div>
    </section>
    <div class="clearfix"></div>
    <!-- home_service end-->
    <?php $howitwork = get_post( 85 );  //pre($howitwork); ?>
    <section class="how_it_works" style="background:url(<?php echo get_template_directory_uri();	 ?>/assets/images/how_it_works_bg.jpg) no-repeat;">
        <div class="container">
            <div class="row">
                <div class="col-sm-6 video_sec"> <?php echo get_the_post_thumbnail(  $howitwork->ID , 'themes-image-custom-size', array( 'class' => 'right-align' ) ); ?> </div>
                <div class="col-sm-6 how_works_cntnt">
                    <h2><?php echo  get_the_title( $howitwork->ID ); ?></h2>
                    <?php 
                    $excerpt = substr( $howitwork->post_content , 0, 550); 
                    echo wpautop( $excerpt ).'...'; ?>
                    <a href="<?php echo get_permalink( $howitwork->ID ); ?>" title="">Read More <i class="fa fa-angle-right" aria-hidden="true"></i></a> </div>
            </div>
        </div>
    </section>
    <!--how_it_works end -->
    <section class="companies_service">
        <div class="container">
            <div class="row">
                <div class="col-sm-12">
                    <h2>Companies Utilizing Service</h2>
                    <div class="companies_service_list ri-grid ri-grid-size-1 ri-shadow" id="ri-grid">
                        <ul>
                        	<?php  foreach( $Options['companies_utilizing_service'] as $keys ):  ?>
                        	<li>
                                <a href="<?php echo $keys['link'] ?>" target="_blank" title="<?php echo $keys['logo']['title'] ?>">
                                    <figure><img src="<?php echo $keys['logo']['url'] ?>" alt="<?php echo $keys['logo']['title'] ?>"></figure>
                                </a>
                            </li>
                        	<?php  endforeach; ?>                          
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!--companies_service end -->
    <section class="testimonial">
        <div class="container">
            <div class="row">
                <div class="col-sm-11">
                    <h2>Testimonials</h2>
                    <div id="testimonialSlider" class="owl-carousel owl-theme testimonial-slider">
                    <?php 
                    $the_query = new WP_Query( array( 'post_type' => 'testimonials' ) );

					// The Loop
					if ( $the_query->have_posts() ) 
					{
						while ( $the_query->have_posts() ) 
						{
							$the_query->the_post();
							$stars = get_fields( get_the_ID() );
							//pre( $stars );
							$rating = '';
							for( $x = 1; $x <= $stars['rating']; $x++ )
							{
								$rating .= '<i class="fa fa-star" aria-hidden="true"></i>';
							}

							echo '<div class="item">
                            <figure>
                           		<img src="'.get_the_post_thumbnail_url().'" alt="">
                            </figure>
                            <h6>'.get_the_title().'</h6>
                            <p class="rating">                            
                            '.$rating.'
                            </p>
                            '.get_the_content().'                           
                        </div>';
							
						}						
						/* Restore original Post Data */
						wp_reset_postdata();
					} 
					else 
					{
						echo "<h3> Coming Soon </h3>";
					}

                    ?>                      
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!--tetsimonial end -->
    <section class="home_newsletter">
        <div class="container">
            <div class="newsletter_sec">
                <h6>News Letter</h6>
                <form>
                    <div class="form-group">
                        <input type="text" placeholder="NAME" class="formcontrol2"> </div>
                    <div class="form-group">
                        <input type="text" placeholder="EMAIL" class="formcontrol2"> </div>
                    <div class="form-group form-submit-group">
                        <input type="submit" class="newsletter-btn "> </div>
                </form>
            </div>
            <div class="social-icon-cover">
                <h6>Follow us</h6>
                <ul>
                	<?php foreach( $Options['social_links'] as $keys ){ ?>
                		 <li><a href="<?php echo $keys['social_link']; ?>" target="_blank"><?php echo $keys['social_icon']; ?></a></li>
                	<?php } ?>                   
                </ul>
            </div>
        </div>
    </section>
    <div class="clearfix"></div>
    

<?php	endwhile; endif;  ?>
<?php get_footer();
