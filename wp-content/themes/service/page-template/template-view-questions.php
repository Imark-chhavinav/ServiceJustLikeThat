<?php
/**
 * Template Name: View Questions Template
 *
 * @author Chhavinav Sehgal
 * @copyright 
 * @link 
 */
$uriSegments = explode("/", parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));
$BidsFields = apply_filters( 'BIDQUESTIONS' , $uriSegments );
//pre($BidsFields);
get_header();
if ( have_posts() ) : while ( have_posts() ) : the_post(); $fields = get_fields();?>
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
    <div class="inner-pages question-page">
        <div class="container">
            <div class="text-center">
                <h2>View Question</h2> <a class="btn pull-right" href="<?php echo site_url( 'bids-received/'.$BidsFields['select_job'][0]->post_name ); ?>">Back</a></div>
            <div class="add-border-cover question-cover">
            <form id="SubmitQues">
				<input type="hidden" name="bid_id" value="<?php echo $BidsFields['Post_Data']->ID; ?>">
				<input type="hidden" name="user_id" value="<?php echo get_current_user_id(); ?>">
				<input type="hidden" name="user_type" value="1">
				<?php wp_nonce_field( 'SubmitAnswerNonce' , 'Submit_Answer_Nonce' ); ?>
					<?php 
						if( !empty($BidsFields['questions']) ): 
							foreach( $BidsFields['questions'] as $keys ):
							echo "<article>".wpautop($keys['question'])."<div class='form-group'><label>Reply</label><input type='hidden' name='questions[]' value='".$keys['question']."'><textarea name='answers[]' class='form-control' >".$keys['answer']."</textarea></div></article>";
							
							endforeach;
						endif; 
					?> 
				<input type="button" class="blue_btn submit-ques" value="Submit">
            </form>
            </div>
        </div>
    </div>
	<?php   endwhile; endif;  ?>
<?php get_footer(); ?>