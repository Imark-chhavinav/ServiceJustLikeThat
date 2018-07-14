<?php 
    $user = wp_get_current_user();
    $Alert = apply_filters( 'WEBALERTS' , $user );
?>

<div class="add-border-cover my-job-cover">
                <div class="my-job-list add-create-job-btn">
                    <ul class="tabs-nav" role="tablist">                        
                        <li role="presentation" class="active" ><a href="#invite" aria-controls="aa" role="tab" data-toggle="tab">Invite <?php if( $Alert['Invite_for_job'] > 0 ){ echo '<span class="dot dot-sm dot-primary"></span>'; } ?> </a></li>
                        <li role="presentation"><a href="#Applied" aria-controls="aa" role="tab" data-toggle="tab">Applied <?php if( $Alert['Pending'] > 0 ){ echo '<span class="dot dot-sm dot-primary"></span>'; } ?> </a></li>
                        <li role="presentation"><a href="#In-Progress" aria-controls="aa" role="tab" data-toggle="tab">In Progress <?php if( $Alert['In_progress'] > 0 ){ echo '<span class="dot dot-sm dot-primary"></span>'; } ?> </a></li>
                        <li role="presentation"><a href="#Complete" aria-controls="ep" role="tab" data-toggle="tab">Complete <?php if( $Alert['CompleteJob'] > 0 ){ echo '<span class="dot dot-sm dot-primary"></span>'; } ?> </a></li>
                    </ul>
                </div>
                <div class="tab-content">                   
                    <div id="invite" class="tab-pane fade active in">
                        <div class="job-listing-cover">
                        	<?php
                        	$user_ID = get_current_user_id();
							$Jobs = new Jobs();
							$Values = $Jobs->getInvites( $user_ID );
			                 //pre( $Values );
							if( !empty($Values) ):
								foreach( $Values as $Ikeys ):
								//pre($Ikeys);
									$CheckDate = strtotime( $Ikeys['job_date']);
                                    $CurrrentDate = strtotime( date( 'Y-m-d' ) );
                                  
									if( $CurrrentDate > $CheckDate  )
									{
                                       
										$JobExpired = '<li><span class="label label-danger"> Job Expired</span></li>';
									}
                                    else
                                    {
                                        $JobExpired ='';
                                    }
							?>							  
							<article>
                                <h3><?php echo $Ikeys['job_title']; ?></h3>
                                <ul class="time-details">
                                    <li><i class="fa fa-calendar-o" aria-hidden="true"></i> <?php echo $Ikeys['job_date']; ?></li>
                                    <li><i class="fa fa-clock-o" aria-hidden="true"></i> <?php echo $Ikeys['job_time']; ?></li>
                                    <?php if( isset( $JobExpired  ) ){ echo $JobExpired;  } ?>
                                </ul>
                               	<?php echo wpautop( $Ikeys['job_content'] ); ?>
                                <ul class="pd-btn-group">
                                	<?php if( $CheckDate >= $CurrrentDate ): ?>
 
                                    <li><a data-relatedID = "<?php echo $Ikeys['job_id']; ?>" href="<?php echo $Ikeys['job_permalink']; ?>" class="white-btn alert-read" target="_blank" >View  job</a></li>

                                    <li><a href="<?php echo site_url( '/submit-bid/'.$Ikeys['job_slug'].'?invite_id='.$Ikeys['invite_id'] ); ?>" class="white-btn">Accept Invite</a></li>

                                    <li><a href="#" date-inviteID="<?php echo $Ikeys['invite_id']; ?>" class="white-btn decline-invite">Reject Invite</a></li>
                                <?php else: ?>
                                	<li><a data-relatedID = "<?php echo $Ikeys['job_id']; ?>" href="<?php echo $Ikeys['job_permalink']; ?>" class="white-btn alert-read" target="_blank">View  job</a></li>
                                	<li><a href="javascript:void(0)" date-inviteID="<?php echo $Ikeys['invite_id']; ?>" class="white-btn remove-invite">Remove Invite</a></li>
                                <?php endif; ?>
                                </ul>
                            </article>
                            <?php endforeach; 

                        else:
                            echo '<article><h3> No Invites !</h3></article>';         
                        endif; ?>      
                        </div>
                    </div>
                
                    <div id="Applied" class="tab-pane fade">
                        <div class="job-listing-cover">
                        <?php    
                        $request = new WP_REST_Request( 'GET', '/jobs/serviceJobListing' );
                    	$request->set_query_params(array( 'page' => 1 , 'user_id' => $user_ID , 'user_type' => 2 , 'status' => 0 , 'ajax' => 1 ));

                        $Applied = $Jobs->serviceJobListing( $request );
                         //pre($Applied);   
                        if( !empty( $Applied ) ):
                        foreach( $Applied as $Inkeys ):  //pre($Inkeys); 

                            $JobExpired = '';
                            if( $Inkeys['bid_status'] == 2 )
                            {
                                $JobExpired = '<li><span class="label label-danger"> Bid Rejected</span></li>';
                            }

                            ?>
                            <article>
                                <h3><?php echo $Inkeys['job_title']; ?></h3>
                                <ul class="time-details">
                                    <li><i class="fa fa-calendar-o" aria-hidden="true"></i> <?php echo $Inkeys['job_date']; ?></li>
                                    <li><i class="fa fa-clock-o" aria-hidden="true"></i> <?php echo $Inkeys['job_time']; ?></li> 
                                      <?php if( isset( $JobExpired  ) && !empty( $JobExpired ) ){ echo $JobExpired;  } ?>   
                                </ul>
                                <?php echo wpautop($Inkeys['job_content']); ?>
                                <ul class="pd-btn-group">
                                    <?php if( $Inkeys['bid_status'] != 2 ): ?>
                                        <li><a href="javascript:void(0)" data-JobID="<?php echo $Inkeys['job_id']; ?>" data-bid="<?php echo $Inkeys['bid_id']; ?>" class="white-btn Remove-bid">Remove Bid</a></li>
                                    <?php endif; ?>
                                    <li><a data-relatedID = "<?php echo $Inkeys['job_id']; ?>" href="<?php echo get_the_permalink( $Inkeys['job_id']  ); ?>" class="white-btn  alert-read">View  job</a></li>
                                    <?php if( $Inkeys['conv_started'] == 1 && $Inkeys['bid_status'] != 2 ): ?>
                                    <li>
                                    	<form action="<?php echo site_url('/message/'); ?>" method="POST">
                                    		<input type="hidden" name="Touser_id" value="<?php echo $Inkeys['user_id']; ?>">
                                    		<input type="hidden" name="Fromuser_id" value="<?php echo $user_ID; ?>">
                                    		<input type="hidden" name="conv_id" value="<?php echo $Inkeys['conv_id']; ?>">
                                    		<input type="submit" value="Message" class="white-btn">
                                    	</form>                                    	
                                    </li>
                                <?php endif; ?>
                                </ul>
                            </article>
                        <?php    endforeach; else: echo'<article><h3>No Job Applied!</h3></article>';  endif;     ?>                        
                        </div>
                    </div>

                    <div id="In-Progress" class="tab-pane fade">
                        <div class="job-listing-cover">
                        <?php    
                        $request = new WP_REST_Request( 'GET', '/jobs/serviceJobListing' );
                        $request->set_query_params(array( 'page' => 1 , 'user_id' => $user_ID , 'user_type' => 2 , 'status' => 1 , 'ajax' => 1 ));

                        $InProgress = $Jobs->serviceJobListing( $request );
                        //pre($InProgress);
                        if( !empty( $InProgress ) ):
                        foreach( $InProgress as $Inkeys ): //pre($Inkeys);
						
						if( empty($Inkeys['job_progress']) )
						{
							$Status = '<span class="glyphicon glyphicon-home" aria-hidden="true"></span> Start From Home';
							$StatusValue = 0;
						}
						
						if( $Inkeys['job_progress'] == 0 )
						{
							$Status = '<span class="glyphicon glyphicon-home" aria-hidden="true"></span> Reached Home';
							$StatusValue = 1;
							
						}
						if( $Inkeys['job_progress'] == 1 )
						{
							$Status = '<span class="glyphicon glyphicon-off" aria-hidden="true"></span> Start Task';
							$StatusValue = 2;
						}
						if( $Inkeys['job_progress'] == 2 )
						{
							$Status = '<span class="glyphicon glyphicon-ok" aria-hidden="true"></span> Completed';
							$StatusValue = 3;
						}

						if( $Inkeys['job_progress'] == 3 )
						{
							$Status = '<span class="glyphicon glyphicon-align-left" aria-hidden="true"></span>Waiting For Customer Feedback';
							$StatusValue = '';
						}						
						
						
						
						?>
                            <article>
                                <h3><?php echo $Inkeys['job_title']; ?></h3>
                                <ul class="time-details">
                                    <li><i class="fa fa-calendar-o" aria-hidden="true"></i> <?php echo $Inkeys['job_date']; ?></li>
                                    <li><i class="fa fa-clock-o" aria-hidden="true"></i> <?php echo $Inkeys['job_time']; ?></li>                       
                                </ul>
                                <?php echo wpautop($Inkeys['job_content']); ?>
                                <ul class="pd-btn-group">
                                    <!-- <li><a href="#" class="white-btn">Leave feedback</a></li> -->
									<?php if( !empty($StatusValue) ): ?>
                                    <li><a href="javascript:void(0)" data-status="<?php echo $StatusValue; ?>" data-jobID="<?php echo $Inkeys['job_id']; ?>"  class="white-btn job_progress"><?php echo $Status; ?></a></li>
									<?php endif; ?>
                                    <li><a href="<?php echo get_the_permalink( $Inkeys['job_id']  ); ?>" class="white-btn">View  job</a></li>
                                    <li>
                                        <form action="<?php echo site_url('/message/'); ?>" method="POST">
                                            <input type="hidden" name="Touser_id" value="<?php echo $Inkeys['user_id']; ?>">
                                            <input type="hidden" name="Fromuser_id" value="<?php echo $user_ID; ?>">
                                            <input type="hidden" name="conv_id" value="<?php echo $Inkeys['conv_id']; ?>">
                                            <input type="submit" value="Message" class="white-btn">
                                        </form>
                                        
                                    </li>
                                </ul>
                            </article>
                        <?php    endforeach; else: echo "<article><h3>No Job InProgress ! </h3></article>";  endif;     ?>                        
                        </div>
                    </div>
                    
                    <div id="Complete" class="tab-pane fade">
                        <div class="job-listing-cover">
                        <?php       

                        $request = new WP_REST_Request( 'GET', '/jobs/serviceJobListing' );
                        $request->set_query_params(array( 'page' => 1 , 'user_id' => $user_ID , 'user_type' => 2 , 'status' => 2 , 'ajax' => 1 ));

                        $Completed = $Jobs->serviceJobListing( $request );

                        if( !empty( $Completed ) ):
                        foreach( $Completed as $Inkeys ): //pre($Inkeys); ?>
                            <article>
                                <h3><?php echo $Inkeys['job_title']; ?></h3>
                                <ul class="time-details">
                                    <li><i class="fa fa-calendar-o" aria-hidden="true"></i> <?php echo $Inkeys['job_date']; ?></li>
                                    <li><i class="fa fa-clock-o" aria-hidden="true"></i> <?php echo $Inkeys['job_time']; ?></li>                       
                                </ul>
                                <?php echo wpautop($Inkeys['job_content']); ?>
                                <ul class="pd-btn-group">                                    
                                    <li>
                                        <a data-jobID="<?php echo $Inkeys['job_id']; ?>" href="javascript:void(0)" class="white-btn view-feedback">View feedback</a>
                                    </li>                                    
                                    <li><a data-relatedID = "<?php echo $Inkeys['job_id']; ?>" href="<?php echo get_the_permalink( $Inkeys['job_id']  ); ?>" class="white-btn alert-read">View  job</a></li>
                                    <!-- <li>
                                        <form action="<?php echo site_url('/message/'); ?>" method="POST">
                                            <input type="hidden" name="Touser_id" value="<?php echo $Inkeys['user_id']; ?>">
                                            <input type="hidden" name="Fromuser_id" value="<?php echo $user_ID; ?>">
                                            <input type="hidden" name="conv_id" value="<?php echo $Inkeys['conv_id']; ?>">
                                            <input type="submit" value="Message" class="white-btn">
                                        </form>                                        
                                    </li>-->
                                </ul>
                                <article class="feedback_<?php echo $Inkeys['job_id']; ?>" style="display:none">
                                  <div class="customer-profile-pic">
                                    <figure style="background-image: url(<?php echo $Inkeys['customer_image']; ?>);"></figure>
                                      <h5><?php echo $Inkeys['customer_name']; ?></h5>                    
                                        <div class="rating">
                                          <ul>
                                          <?php 
                                        for ($x = 1; $x <= 5; $x++) 
                                        {
                                            if( $x <= $Inkeys['job_rating'] )
                                            {
                                                echo '<li class="rate"><i class="fa fa-star" aria-hidden="true"></i></li>';
                                            }
                                            else
                                            {
                                                echo '<li><i class="fa fa-star" aria-hidden="true"></i></li>';
                                            }
                                        } 
                                          
                                          ?>                             
                                          </ul>
                                          <p><?php echo $Inkeys['job_rating']; ?></p>
                                        </div>
                                      <p><?php echo $Inkeys['job_feedback']; ?></p>
                                  </div>                    
                                </article> 


                            </article>
                        <?php    endforeach; else: echo "<article><h3>No Job Completed ! </h3></article>";  endif;     ?>           
                        </div>
                    </div>

                </div>
            </div>
