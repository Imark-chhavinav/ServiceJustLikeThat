<?php 
/**
 * Template Name: Cards Template
 *
 * @author Chhavinav Sehgal
 * @copyright 
 * @link 
 */
get_header(); 
$WPmodify = new WPmodify();
$ProfileDetails = $WPmodify->getProfileDetails(get_current_user_id()); 
?>
<?php if ( have_posts() ) : while ( have_posts() ) : the_post();  ?>
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
    <div class="inner-pages job-description-page saved-card-page">
        <div class="container">

            <div class="text-center">
                <h2>Saved Cards</h2> </div>
            <div class="add-border-cover contracts-page categorie-profile-page">
              <div class="text-left">
                <article class="card-form mb-5">
                <h3>Add New Card</h3>
                    <div class="row">
                        <div class="col-md-8">
                            <form id="saveCard" >
							<input type="hidden" name="customerId" value="<?php echo $ProfileDetails[0]->braintree_ID; ?>">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <input type="text" class="form-control" name="cardholderName" placeholder="Card Holder Name">
                                        </div>
                                    </div>
                                   
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <input type="text" id="credit-card" class="form-control" name="number" placeholder="Card Number">											
                                        </div>										
                                    </div>
									
									<div class="col-md-6">
                                        <div class="form-group">
                                            <input type="password" id="cvv" class="form-control" name="cvv" placeholder="CVV Number">											
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="form-group slct">
                                            <select id="ExpMonth" name="ExpMonth" class="form-control placeholder1 formcontrol1">
                                                <option value="">Month</option>
												<?php 
													for( $x = 01; $x <= 12; $x++ )
													{
														$value = str_pad($x,2,"0",STR_PAD_LEFT);
														echo '<option value="'.$value.'">'.$value.'</option>';
													}
												?>
                                            </select>
                                        </div>
                                            </div>

                                            <div class="col-md-4">
                                                <div class="form-group slct">
                                            <select id="ExpYear" name="ExpYear" class="form-control placeholder1 formcontrol1">
                                                <option value="">Year</option>                         
												<?php 
												for( $y = date( 'Y' ); $y <= date( 'Y' ) + 20; $y++  )
												{
													 echo '<option value="'.$y.'">'.$y.'</option>';      
												}
												?>
                                            </select>
                                        </div>
                                            </div>

                                            <div class="col-md-4">
                                                <div class="form-group">
                                                <p>Expiry Date</p>
                                                    </div>
                                            </div>
                                        </div>
                                    </div>
									<div class="col-md-6">
                                        <div class="form-group">                                            
											<span id="accepted-cards-images"></span>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <input type="submit" class="blue_btn save-card" value="Submit">
                                    </div>

                                </div>
                            </form>
                        </div>
                    </div>
            </article>
              </div>
			<div class="text-left">
			<h3>Select Card</h3>
			<?php
			
			$Users = new Users();
			$request = new WP_REST_Request( 'GET', '/users/getCards' );
			$request->set_query_params(array(
					'user_id' => get_current_user_id(),				  
					'user_type' => 1,				  
					'return' => 1			  
			));					
			$Results = $Users->getCards($request);
			
			
			//pre( $ProfileDetails );			
			
			?>
                <div class="row">
				<?php  if(!empty($Results)): foreach( $Results as $keys ):  ?>
					<div class="col-md-5">
                    <div data-id="<?php echo $keys->card_Token; ?>" class="card card-blue <?php if( $keys->default == 1 ){ echo "default"; } ?>">
                      <div class="card-header">
                        <h4><?php echo $keys->card_type; ?></h4>
                        <div>
                          <ul>
                            <li>
                              <a data-id="<?php echo $keys->card_Token; ?>" class="remove-card" href="javascript:void(0)">Remove Card</a>
                            </li>
                            <li>
							<a class="edit-card" data-card_number="<?php echo $keys->card_number; ?>" data-card_Token="<?php echo $keys->card_Token; ?>" data-cardholderName="<?php echo $keys->cardholderName; ?>" data-ExpYear="<?php echo $keys->expirationYear; ?>" data-ExpMonth="<?php echo $keys->expirationMonth; ?>" href="javascript:void(0)">Edit</a>
                            </li>
                          </ul>
                        </div>
                      </div>
                      <div class="card-body">
                        <ul>
                          <li>
                            <strong>Card No.</strong>
                            <?php echo $keys->card_number; ?>
                          </li>
                          <li>
                            <strong>Name on the card:</strong>
                            <?php echo $keys->cardholderName; ?>
                          </li>
                          <li>
                            <strong>Exp Date:</strong>
                            <?php echo $keys->expirationDate; ?>
                          </li>                         
                        </ul>
                      </div>
                    </div>
                  </div>
				<?php 	endforeach;	else: echo "<h2>Please Add Card for the Payment Process !</h2>"; endif;	?>
                </div>
			</div>
			<?php
            $uriSegments = explode("/", parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH)); 
			$BidDetails = apply_filters( 'BIDDETAIL' , $uriSegments ); 
			$JobFields = get_fields( $BidDetails['select_job'][0]->ID );  //pre( $JobFields ); ?>
			<?php if( !empty($BidDetails) ): ?>
			<div class="row">
			<div class="col-lg-12">				
				<form id="Payment">
					<input type="hidden" name="job_id" value="<?php echo $BidDetails['select_job'][0]->ID; ?>">
					<input type="hidden" name="bid_id" value="<?php echo $BidDetails['Post_Data']->ID; ?>">
					<input type="hidden" name="amount" value="<?php echo str_replace(',','',$BidDetails['quoted_price']); ?>">
					<div class="custom-table">				
						<table>
							<thead>
								<th colspan="2"><h3>Create Contract</h3></th>
							</thead>						
							<tbody>
								<tr>
									<td>
										<h5>Job Name</h5>
										<p><?php echo get_the_title( $BidDetails['select_job'][0]->ID ); ?></p>
									</td>
									<td>
										<h5>Final Price</h5>
										<p>$<?php echo $BidDetails['quoted_price']; ?></p>
									</td>
								</tr>
								<tr>
									<td>
										<h5>Address</h5>
										<p><?php echo $JobFields['street_addess']['address']; ?></p>
									</td>
									<td>
                                        <?php if( !empty($Results) ): ?>
										  <button class="blue_btn pay">Pay Now</button>
                                        <?php endif; ?>
									</td>
								</tr>
							</tbody>
						</table>
					</div>
				</form>	
			</div>
		</div>
		<?php endif; ?>
            </div>
        </div>
    </div> 
<?php   endwhile; endif;  ?>
<?php get_footer(); ?>