<?php 
/**
 * Template Name: Notification Template
 *
 * @author Chhavinav Sehgal
 * @copyright chhavinav.in
 * @link shadow_chhavi @skype
 */ 
get_header(); ?>
<?php  $fields = get_fields(); if ( have_posts() ) : while ( have_posts() ) : the_post();  //pre($fields); ?>
<?php
    $user = wp_get_current_user();
    $role = ( array ) $user->roles;
    if( !empty($role[0]) )
    {
        if( $role[0] == 'service' )
        {
            $userRole = 2;
        }
        if( $role[0] == 'customer' )
        {
            $userRole = 1;
        }       
    }
    $Notification = new WP_REST_Request( 'GET', '/jobs/getNotification' );
    $Notification->set_query_params(array(
        'user_id'   => get_current_user_id(),                
        'user_type' => $userRole,                
        'is_web'    => 1            
    ));
    $Jobs = new Jobs();
    //header("Authenticate-Token: $accessToken");
    $Noti = $Jobs->getNotification($Notification);

 ?>
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
    <div class="inner-pages notification-page">
        <div class="container">
            <div class="text-center">
                <h2>Notifications</h2> </div>
            <div class="add-border-cover notification-cover">
            	<?php 
            	foreach( $Noti['results'] as $keys ): 
            		//pre($keys); 
            	$Date = date( 'M Y' , strtotime( $keys["date"] ) ); 
            	if( $keys['noti_type'] == 'new_message' )
            	{            		
            		$Url = site_url( '/message/' );
            	}
            	else
            	{
            		$Post = get_post( $keys['related_Id'] );            		
            		$Url = site_url( '/my-jobs/' );
            	}
            	 
            	?>            		
	            <div class="alert alert-dismissable <?php if( $keys['is_read'] == 0 ){ echo "un-read"; } ?>">
	                    <a href="javascript:void(0)" data-notiID = "<?php echo $keys['noti_ID']; ?>" class="close noti-alert" data-dismiss="alert" aria-label="close"></a>
	                    <a class="noti-link" data-related_Id="<?php echo $keys['related_Id']; ?>" data-notiID = "<?php echo $keys['noti_ID']; ?>"  href="<?php echo $Url; ?>"> 
		                    <?php echo  $keys['noti_message']; ?>
		                    <date><?php echo $Date; ?></date>
	                	</a>
	            </div>
            	<?php endforeach; ?>
            </div>
        </div>
    </div>

<?php   endwhile; endif;  ?>
<?php get_footer(); ?>