<?php 
/**
 * Template Name: Message
 *
 * @author Chhavinav Sehgal
 * @copyright chhavinav.in
 * @link shadow_chhavi @skype
 */ 
get_header(); ?>
<?php  
$Pagefields = get_fields(); if ( have_posts() ) : while ( have_posts() ) : the_post();   
    /* if( !is_user_logged_in())
    {
        if ( ! current_user_can('administrator') ) 
        {
            echo "<script>window.location = '".site_url()."'</script>";
        }         
    }
    else
    { */
        $UserID = get_current_user_id();
        // Current User Profile 
        $current_user = wp_get_current_user();
        $Role = $current_user->roles[0];
		$Touser_id = $_POST['Touser_id'];
		$user_info = get_userdata($Touser_id);
		//pre($user_info);
		//pre($_POST);
		$UserClass = new Users();
		if( isset($_POST['conv_id'])  && !empty($_POST['conv_id'])):
		// Get Message
		$request = new WP_REST_Request( 'GET', '/users/getMessage' );
		$request->set_query_params(array( 'page' => 1 , 'conv_IDs' => $_POST['conv_id'] ,'ajax' => 1 , 'user_id' => $UserID  ));
		$MsgData = json_decode($UserClass->getMessage( $request ));
		$Message = $MsgData->article;
		$TotalCount = $MsgData->TotalCount;
		//pre($MsgData);
		endif;
		if( isset($UserID)  && !empty($UserID)):
		// Get Message
		$request = new WP_REST_Request( 'GET', '/users/getMessageThreads' );
		$request->set_query_params(array( 'page' => 1 , 'user_id' => $UserID ,'ajax' => 1 ));
		$MessageThread = $UserClass->getMessageThreads( $request );
		//pre($MessageThread);
		endif;
   // }
?>
<div class="inner-pages inbox-page">
        <div class="container">
            <div class="text-center">
                <h2>Inbox</h2> </div>
            <div class="inbox-cover">
                <div class="row">
                    <div class="col-md-8 inbox-main-chat">
                        <div class="discussion-top">
                            <div class="discussion-left">
                                <h3><?php echo $user_info->user_login; ?></h3>
                            </div>
                        </div>
                        <div class="discussion-heading">
                            <h5>Discussion</h5> </div>
                        <div class="inbox-chat-cover inboxContent">
						<?php echo $Message; ?>
                        </div>
                        <div class="inbox-message-box-cover">
                            <form id="text-message">
								<input type="hidden" name="Touser_id" value="<?php echo $Touser_id; ?>">
								<input type="hidden" name="Fromuser_id" value="<?php echo $UserID; ?>">
                                <textarea id="clear-message" name="message" class="input"></textarea>
								<div class="text-right">
									<input type="submit" class="blue_btn" value="Send">
								</div>
							</form>
                        </div>
                    </div>
                    <div class="col-md-4 recent-chat-sidebar-cover">
                        <div class="recent-chat-sidebar ">
                            <!--<div class="chat-search-box">
                                <input type="text" class="form-control" placeholder="Search">
                                <button type="submit" class="search-btn"></button>
                            </div> -->
                            <!--<div class="select-chat-view">
                                <div class="dropdown">
                                    <button class="dropdown-toggle" type="button" data-toggle="dropdown">Recent <span class="fa fa-angle-down"></span></button>
                                    <ul class="dropdown-menu">
                                        <li><a href="#">HTML</a></li>
                                        <li><a href="#">CSS</a></li>
                                        <li><a href="#">JavaScript</a></li>
                                    </ul>
                                </div>
                            </div>-->
                            <div class="chat-list inboxContent">
                                <ul>
								<?php foreach( $MessageThread as $keys ): ?>
								 <li <?php if( $Touser_id == $keys['user_id'] ){ echo 'class="current"'; }  ?> >
                                    <a href="#">
										<time><i class="fa fa-clock-o" aria-hidden="true"></i><?php echo date( 'H:i a',strtotime($keys['created_on'])); ?></time> <big><?php echo $keys['first_name']; ?></big> <?php echo $keys['message']; ?>
									</a>
                                </li>  
								
								<?php endforeach; ?>               
                                </ul>
                            </div>
                        </div>
                        <div class="view-recent"> <a href="#" class="blue_btn">Hide Recent</a> </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


<?php   endwhile; endif;  ?>
<?php get_footer(); ?>
<script>
$(document).ready(function()
{
	TotalCount = '<?php echo $TotalCount; ?>';
	//console.log(TotalCount);
	getMessage( '<?php echo $_POST['conv_id'] ?>' , '1' );
});

/* (function ($) {
		$(window).on("load", function () {
			$(".inboxContent").mCustomScrollbar({
				axis: "y"
			});
			$(".inbox-chat-cover").mCustomScrollbar("scrollTo", "bottom");
		});
})(jQuery);  */
</script>