<?php 
    $user = wp_get_current_user();
    $Alert = apply_filters( 'WEBALERTS' , $user );
   // pre( $Alert);
?>
<div class="add-border-cover my-job-cover">
                <div class="my-job-list add-create-job-btn">
                    <ul class="tabs-nav" role="tablist">
                        <li role="presentation" class="active"><a href="#All-Jobs" aria-controls="sa" role="tab" data-toggle="tab">All Jobs</a></li>
                        <li role="presentation"><a href="#Pending" aria-controls="aa" role="tab" data-toggle="tab">Pending <?php if( $Alert['Pending'] > 0 ){ echo '<span class="dot dot-sm dot-primary"></span>'; } ?> </a></li>
                        <li role="presentation"><a href="#In-Progress" aria-controls="aa" role="tab" data-toggle="tab">In Progress <?php if( $Alert['In_progress'] > 0 ){ echo '<span class="dot dot-sm dot-primary"></span>'; } ?> </a></li>
                        <li role="presentation"><a href="#Complete" aria-controls="ep" role="tab" data-toggle="tab">Complete <?php if( $Alert['CompleteJob'] > 0 ){ echo '<span class="dot dot-sm dot-primary"></span>'; } ?></a></li>
                    </ul>
                    <div class="create-job-btn-cover"> <a href="<?php echo site_url( '/create-job' ); ?>" class="blue_btn">Create job</a> </div>
                </div>
                <div class="tab-content">
                    <div id="All-Jobs" class="tab-pane fade active in">
                        <div class="job-listing-cover">
                 <?php
                $paged = ( !empty( $parameters['page'] ) ) ? $parameters['page'] : 1;
                    $args = array(
                                'post_type' => 'job',
                                'orderby' => 'date',
                                'order' => 'DESC',
                                'posts_per_page' => 10,
                                'paged' => $paged,
                                'meta_query'    => array(
                                'relation'      => 'AND',
                                    array(
                                        'key'       => 'customer_name',
                                        'value'     => array( get_current_user_id() )                       
                                    )
                                )               
                            );
                $the_query = new WP_Query($args);               

                if( !empty( $the_query ) ):
                    while( $the_query->have_posts() ) : $the_query->the_post();
                    $fields = get_fields( get_the_ID() );
                    $categories = get_the_terms( get_the_ID(), 'jobs_category' );
                    if( $fields['job_status'] == 0 )
                    {
                        $JobStatus = '<span class="label label-default">Pending</span>';
                    }
                    elseif( $fields['job_status'] == 1 )
                    {
                        $JobStatus = '<span class="label label-primary">In-Progress</span>';
                    }
                    elseif( $fields['job_status'] == 2 )
                    {
                        $JobStatus = '<span class="label label-success">Completed</span>';
                    }
           
                ?>
                <article>
                    <h3><?php the_title(); ?></h3>
                    <ul class="time-details">
                        <li><i class="fa fa-calendar-o" aria-hidden="true"></i> <?php echo $fields['job_date']; ?></li>
                        <li><i class="fa fa-clock-o" aria-hidden="true"></i> <?php echo $fields['job_time']; ?></li>
                        <li><?php echo $JobStatus; ?></li>
                    </ul>
                    <?php the_content(); ?>
                    <ul class="pd-btn-group">
                        <!-- <li><a href="#" class="white-btn">Leave feedback</a></li> -->
                        <li><a href="<?php echo get_the_permalink(); ?>" class="white-btn">View  job</a></li>
                    </ul>
                </article>
                      <?php
                     endwhile;
					 else:
					 echo'<article><h3>No Job Found!</h3></article>'; 
                         endif;    
                     ?>
                            
                        </div>
                    </div>

                    <div id="Pending" class="tab-pane fade ">
                        <div class="job-listing-cover">
            <?php
                $paged = ( !empty( $parameters['page'] ) ) ? $parameters['page'] : 1;
                
                $args = array(
                'post_type' => 'job',
                'orderby' => 'date',
                'order' => 'DESC',
                'posts_per_page' => 10,
                'paged' => $paged,
                'meta_query'    => array(
                'relation'      => 'AND',
                    array(
                        'key'       => 'customer_name',
                        'value'     => array( get_current_user_id() )                       
                    ),
                    array(
                        'key'       => 'job_status',
                        'value'     => array( 0 )                       
                    )
                ));
                $the_query = new WP_Query($args);               

        if( !empty( $the_query ) ):
            while( $the_query->have_posts() ) : $the_query->the_post();
             
            $Slug = ( basename(get_permalink()) );   
            $fields = get_fields( get_the_ID() );
            $categories = get_the_terms( get_the_ID(), 'jobs_category' );
            if( $fields['job_status'] == 0 )
            {
                $JobStatus = '<span class="label label-default">Pending</span>';
            }            
           
                ?>
                <article>
                    <h3><?php the_title(); ?></h3>
                    
                    <ul class="time-details">
                        <li><i class="fa fa-calendar-o" aria-hidden="true"></i> <?php echo $fields['job_date']; ?></li>
                        <li><i class="fa fa-clock-o" aria-hidden="true"></i> <?php echo $fields['job_time']; ?></li>                       
                    </ul>
                    <?php the_content(); ?>
                    
                    <ul class="pd-btn-group">
                        <li><a data-relatedID = "<?php echo get_the_ID(); ?>" href="<?php echo site_url( '/bids-received/' ).$Slug;  ?>" class="white-btn alert-read">View Bids</a></li> 
                        <li><a href="<?php echo get_the_permalink(); ?>" class="white-btn">View  job</a></li>
                    </ul>
                </article>
            <?php
            endwhile;
			else:
					 echo'<article><h3>No Job Found!</h3></article>'; 
        endif;    
            ?>
                        </div>
                    </div>
                
                     <div id="In-Progress" class="tab-pane fade">
                        <div class="job-listing-cover">
     <?php
                $paged = ( !empty( $parameters['page'] ) ) ? $parameters['page'] : 1;
                
                $args = array(
                'post_type' => 'job',
                'orderby' => 'date',
                'order' => 'DESC',
                'posts_per_page' => 10,
                'paged' => $paged,
                'meta_query'    => array(
                'relation'      => 'AND',
                    array(
                        'key'       => 'customer_name',
                        'value'     => array( get_current_user_id() )                       
                    ),
                    array(
                        'key'       => 'job_status',
                        'value'     => array( 1 )                       
                    )
                ));
                $the_query = new WP_Query($args);               

        if( !empty( $the_query ) ):
            while( $the_query->have_posts() ) : $the_query->the_post();
            $fields = get_fields( get_the_ID() ); //pre( $fields );
            $categories = get_the_terms( get_the_ID(), 'jobs_category' );
			$JobProgress_Status = '';
            if( isset($fields['job_progress']) ):
    			if( $fields['job_progress'] == 0 )
    			{
    				$JobProgress_Status = '<span class="label label-default"> <span class="glyphicon glyphicon-home" aria-hidden="true"></span> Start From Home</span>';
    			}
    			elseif( $fields['job_progress'] == 1 )
    			{
    				$JobProgress_Status = '<span class="label label-info"> <span class="glyphicon glyphicon-home" aria-hidden="true"></span> Reached Home</span>';
    			}
    			elseif( $fields['job_progress'] == 2 )
    			{
    				$JobProgress_Status = '<span class="label label-primary"> <span class="glyphicon glyphicon-off" aria-hidden="true"></span> Start Task</span>';
    			}
    			elseif( $fields['job_progress'] == 3 )
    			{
    				$JobProgress_Status = '<span class="label label-success"> <span class="glyphicon glyphicon-ok" aria-hidden="true"></span> Task Completed</span>';				
    			}
            endif;
           
                ?>
                <article>
                    <h3><?php the_title(); ?></h3>
                    <ul class="time-details">
                        <li><i class="fa fa-calendar-o" aria-hidden="true"></i> <?php echo $fields['job_date']; ?></li>
                        <li><i class="fa fa-clock-o" aria-hidden="true"></i> <?php echo $fields['job_time']; ?></li>
						<?php if( isset($JobProgress_Status) && !empty($JobProgress_Status) ){ echo $JobProgress_Status; } ?>
                    </ul>
                    <?php the_content(); ?>
                    <ul class="pd-btn-group">
						<?php if( isset($fields['job_progress']) &&  $fields['job_progress'] == 3 ): ?>
							<li><a href="javascript:void(0)" data-jobid="<?php echo get_the_ID(); ?>" class="white-btn complete-job">Mark Job Completed</a></li> 
						<?php endif; ?>
                        <li><a href="<?php echo get_the_permalink(); ?>" class="white-btn">View  job</a></li>
                    </ul>
                </article>
            <?php
            endwhile;
			else:
					 echo'<article><h3>No Job Found!</h3></article>'; 
        endif;    
            ?>                        
                        </div>
                    </div>
                    
                    <div id="Complete" class="tab-pane fade">
                        <div class="job-listing-cover">
         <?php
                $paged = ( !empty( $parameters['page'] ) ) ? $parameters['page'] : 1;
                
                $args = array(
                'post_type' => 'job',
                'orderby' => 'date',
                'order' => 'DESC',
                'posts_per_page' => 10,
                'paged' => $paged,
                'meta_query'    => array(
                'relation'      => 'AND',
                    array(
                        'key'       => 'customer_name',
                        'value'     => array( get_current_user_id() )                       
                    ),
                    array(
                        'key'       => 'job_status',
                        'value'     => array( 2 )                       
                    )
                ));
                $the_query = new WP_Query($args);               

        if( !empty( $the_query ) ):
            while( $the_query->have_posts() ) : $the_query->the_post();
            $fields = get_fields( get_the_ID() );
            $categories = get_the_terms( get_the_ID(), 'jobs_category' ); 
			$WPmodify = new WPmodify();
			$ContractDetails = $WPmodify->getContratsDetails( get_the_ID() ); //pre( $ContractDetails );
			$WPmodify->getUserRating( $ContractDetails->service_providerID , 2 , $ContractDetails->job_id  );
                ?>
                <article>
                    <h3><?php the_title(); ?></h3>
                    <ul class="time-details">
                        <li><i class="fa fa-calendar-o" aria-hidden="true"></i> <?php echo $fields['job_date']; ?></li>
                        <li><i class="fa fa-clock-o" aria-hidden="true"></i> <?php echo $fields['job_time']; ?></li>                       
                    </ul>
                    <?php the_content(); ?>
                    <ul class="pd-btn-group">
                        <!-- <li><a href="#" class="white-btn">Leave feedback</a></li> -->
                        <li><a href="<?php echo get_the_permalink(); ?>" class="white-btn">View  job</a></li>
                    </ul>
					
					
                </article>
            <?php
            endwhile;
			else:
					 echo'<article><h3>No Job Found!</h3></article>'; 
        endif;    
            ?>                    
                        </div>
                    </div>
                </div>
            </div>