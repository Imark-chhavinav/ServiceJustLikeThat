<?php 
 //pre( $Details );
 //pre( $cards );
?>

 <div class="inner-pages categorie-profile-page customer-profile-page">
        <div class="container">
            <div class="text-center">
                <h2>Customer profile</h2> 
            </div>
            
            <div class="add-border-cover customer-profile-cover profile-des">
                <article>
                <div class="customer-profile-pic">
                	<?php $ProfileImage = (empty( $Details->profile_image ))? esc_url( get_avatar_url( $Details->user_id ) ) : $Details->profile_image ; ?>
                    <figure style="background-image: url('<?php echo $ProfileImage; ?>');"></figure>
                    <h5><?php echo $Details->first_name.' '.$Details->last_name;  ?></h5>
                    <ul>
                        <li><a href="mailto:<?php echo $Details->email;  ?>"><i class="fa fa-envelope" aria-hidden="true"></i> <?php echo $Details->email;  ?></a></li>
                        <li><i class="fa fa-building" aria-hidden="true"></i> <?php echo $Details->street_address;  ?></li>
                        <li><a href="tel:<?php echo $Details->phone_number;  ?>"><i class="fa fa-phone" aria-hidden="true"></i> <?php echo $Details->phone_number;  ?></a></li>
                    </ul>
                </div>
                <?php if( isset( $Details->user_loggin ) && $Details->user_loggin ==  true ): ?>
                    <div class="edit-btn">
                        <a href="#" data-toggle="modal" data-target="#EditProfile" class="blue_btn">Edit</a>
                    </div>
                <?php endif; ?>    
                    
                    </article>
                <?php if( isset($Details->cards) && !empty( $Details->cards ) ): ?>                
                <article class="card-form">
                <h3>Credit card</h3>
	               	<?php if( isset( $Details->user_loggin ) && $Details->user_loggin ==  true ): ?>
	                    <div class="edit-btn">
	                        <a href="#" class="blue_btn">Add Card</a>
	                    </div>
	            	<?php endif; ?>  
                    <div class="row">
                        <div class="col-md-8">
	                    <?php foreach( $Details->cards as $Cards ): ?>
	                          	<div class="card-info">
		                          	<ul>	
		                          		<li> Card Numeber - <?php echo $Cards->card_number; ?> </li>
		                          		<li> Exp Date - <?php echo $Cards->expirationDate; ?> </li>
		                          		<li> Card Type - <?php echo $Cards->card_type; ?> </li>
		                          	</ul>
	                          	</div>
	                    <?php endforeach; ?>
                        </div>
                    </div>
           		 </article>
           		 <?php endif; ?>
                
                <div class="feedback-section">
                    <h3>feedback</h3>

                        <?php foreach($Details->work_history as $keys  ): ?>
                        	<article>
                               <?php echo wpautop( $keys['job_name'] ); ?>                                
                               <?php echo wpautop( $keys['job_content'] ); ?>                                
                                <ul class="post-by-details">                                   
                                    <li><?php  echo $keys['start_date'].' - '.$keys['end_date']; ?></li>
                                </ul>                                
                                <div class="rating">
                                    <ul>
                                    	<?php for( $x = 1 ; $x <= 5 ; $x++ ): ?>
                                    		<?php if( $x <= $keys['rating_by_provider'] ): ?>
                                    				<li class="rate"><i class="fa fa-star" aria-hidden="true"></i></li>
                                    		<?php else: ?>
                                    				<li><i class="fa fa-star" aria-hidden="true"></i></li>
                                    		<?php endif; ?>
                                    	<?php endfor; ?> 
                                    </ul>
                                        <p><?php  echo $keys['rating_by_provider']; ?></p>
                                </div> 
                            </article>
                        <?php endforeach; ?>                            
                </div>                
            </div>            
        </div>
    </div>