<?php 

/**

 * Template Name: Apply Job Template

 *

 * @author Chhavinav Sehgal

 * @copyright 

 * @link 

 */
$uriSegments = explode("/", parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));
$JobsDetails = apply_filters( 'AllowBid' , $uriSegments );
$AppliedBId = apply_filters( 'BIDDETAILS' , $uriSegments );
//pre($JobsDetails);
get_header(); ?>
<?php  
$fields = get_fields(); if ( have_posts() ) : while ( have_posts() ) : the_post();
?>
<div class="clearfix"></div>
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
    <div class="inner-pages create-job-page">
        <div class="container">
            <div class="text-center">
                <h2><?php if(isset($AppliedBId) && !empty($AppliedBId)){ echo "Update Your Bid"; }else{ echo "Apply For Job"; } ?></h2> 
			</div>
            <div class="create-job-cover">			
                <form <?php if(isset($AppliedBId) && !empty($AppliedBId) && $AppliedBId['status'] == 0){ echo "id='UpdateBid'"; }elseif(empty($AppliedBId)){ echo "id='applyforjob'"; } ?> method="POST">
				<?php if(isset($AppliedBId) && !empty($AppliedBId)){ wp_nonce_field( 'UpdateBid_Nonce' , 'BidUpdateForm_nonce' ); }else{ wp_nonce_field( 'ApplyForJob_Nonce' , 'BidForm_nonce' ); } ?>
				<?php 
				if(isset( $_GET['invite_id'] ) && !empty($_GET['invite_id']) )
				{ 
					echo "<input type='hidden' name='invite_id' value='".$_GET['invite_id']."'>";
				} 
				?>
				<input name="job_id" value="<?php echo  $JobsDetails['job_id']; ?>" type="hidden">
				<input name="job_title" value="<?php echo  $JobsDetails['job_title']; ?>" type="hidden">
				<?php 
				if( isset($AppliedBId) && !empty( $AppliedBId ) ):
				echo "<input type='hidden' name='bid_id' value='".$AppliedBId['Post_Data']->ID."'>";
				endif;					
				?>
                    <div class="row">
						
                        <div class="col-md-6 col-sm-6 col-xs-12">
                             <div class="form-group">
								<label class="col-sm-4 control-label">Job Title</label>
								<div class="col-sm-8">
								  <p class="form-control-static"><a href="<?php echo get_permalink( $JobsDetails['job_id'] ); ?>" target="_blank"><?php echo  $JobsDetails['job_title']; ?></a></p>
								</div>
							  </div>
                        </div>
						<div class="col-md-6 col-sm-6 col-xs-12">
                             <div class="form-group">
								<label class="col-sm-4 control-label">Date</label>
								<div class="col-sm-8">
								  <p class="form-control-static"><?php echo  $JobsDetails['job_date']; ?></p>
								</div>
							  </div>
                        </div>
						<div class="col-md-6 col-sm-6 col-xs-12">
                             <div class="form-group">
								<label class="col-sm-4 control-label">Time</label>
								<div class="col-sm-8">
								  <p class="form-control-static"><?php echo  $JobsDetails['job_time']; ?></p>
								</div>
							  </div>
                        </div>
						
						<div class="col-md-6 col-sm-6 col-xs-12">
                             <div class="form-group">
								<label class="col-sm-4 control-label">Price</label>
								<div class="col-sm-8">
								  <p class="form-control-static">$<?php echo  $JobsDetails['price']; ?></p>
								</div>
							  </div>
                        </div>
						
						<div class="col-md-6 col-sm-6 col-xs-12">
                            <div class="input-group form-group">
							<span class="input-group-addon"><span class="glyphicon glyphicon-usd"></span></span>
								<input value="<?php if( isset($AppliedBId['quoted_price']) ){ echo $AppliedBId['quoted_price']; } ?>" id="autoNumericEventInput" type="text" name="quoted_price" class="form-control" placeholder="Quote a Price">
                            </div>
                        </div> 
						
						<div class="col-md-6 col-sm-6 col-xs-12">
                            <div class="form-group">
							<label class="control-label">Attachment</label>
							<?php 
							if( isset($AppliedBId['attachment']) && !empty( $AppliedBId['attachment'] ) )
							{
								echo "<a class='Linkattachment' target='_blank' href='".$AppliedBId['attachment']['url']."'><img src='".get_template_directory_uri()."/assets/images/attachment-512.png' ></a><span data-id='".$AppliedBId['attachment']['ID']."' class='glyphicon glyphicon-remove removeattchment'></span>";
								echo '<input style="display:none" type="file" id="attachment" name="attachment" >';
							}
							else
							{
								echo '<input type="file" id="attachment" name="attachment" >';
							}							

							?>
								
                            </div>
                        </div>  	
						
						<div class="col-md-12 col-sm-12 col-xs-12 input_fields_wrap">							
							<?php 
							
							if(isset($AppliedBId['questions']) && !empty($AppliedBId['questions']))
							{
								$xs = 0;
								$JsAdd = count( $AppliedBId['questions'] );
								echo "<script>var QuesNo = '".$JsAdd."';</script>";
								foreach( $AppliedBId['questions'] as $keys ):
									if( $xs == 0 )
									{
										echo ' <div class="input-group form-group"><input value="'.$keys["question"].'" type="text" name="questions[]" class="form-control" placeholder="Questions"><span class="input-group-btn">
										<button class="btn btn-default add_field_button" type="button"><span class="glyphicon glyphicon-plus"></span></button>
										</span></div>';
									}
									else
									{
										echo '<div class="input-group form-group"><input value="'.$keys["question"].'" type="text" name="questions[]" class="form-control" placeholder="Questions"><span class="input-group-btn remove_field"><button class="btn btn-default" type="button"><span class="glyphicon glyphicon-glyphicon glyphicon-minus"></span></button> </span></div>';
									}
								$xs++;
									
								endforeach;
							}
							else
							{
								echo ' <div class="input-group form-group"><input type="text" name="questions[]" class="form-control" placeholder="Questions"><span class="input-group-btn">
								<button class="btn btn-default add_field_button" type="button"><span class="glyphicon glyphicon-plus"></span></button>
							  </span></div>';
							}
								?>
							                       
                        </div>  
                        
                        <div class="col-md-12 col-sm-12 col-xs-12">
                            <div class="form-group">
								<textarea name="additional_information" placeholder="Additional Information" class="form-control" rows="5"><?php if(isset($AppliedBId['additional_information'])){ echo $AppliedBId['additional_information']; } ?></textarea>
                            </div>
                        </div>               
                      
                        <div class="col-md-12 col-sm-12 col-xs-12">
						<?php if($AppliedBId['status'] == 0)
						{ ?>
							 <input type="submit" class="blue_btn <?php if(isset($AppliedBId) && !empty($AppliedBId)){ echo "update-bid"; }else{ echo "submit-bid"; } ?>" value="<?php if(isset($AppliedBId) && !empty($AppliedBId)){ echo "Update Bid"; }else{ echo "Submit Bid"; } ?>">
						<?php 
						}
						else
						{
							echo  '<button disabled=disabled class="btn btn-default disable">Bid Rejected</button>';
						}							
						
						
						?>
                           
                        </div>
                    </div>					
					
                </form>
            </div>
        </div>
    </div>	
<?php   endwhile; endif;  ?>
<?php get_footer(); ?>
<script src="<?php echo  get_template_directory_uri().'/assets/js/AutoNumeric.js'; ?>"></script>
