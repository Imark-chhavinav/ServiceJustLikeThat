<?php 
/*
*	Get Category Listing of Taxanomy jobs_category
*/

function categoryListing( $attr )
{
	$categories = get_terms(array(
    'taxonomy' => 'jobs_category',
    'hide_empty' => false,
		) );
    
    $listing = '';
    $x = 0 ;
    foreach( $categories  as $keys ):
    	$ID 	= 'jobs_category_'.$keys->term_id;
    	$img 	= get_field('image', $ID );
    	$url 	=   site_url( "services/".$keys->slug );  	
    	if( is_front_page() )
    	{
	    	if( $x < 6):
		   		$listing .= '<li>
		                    <div class="home_service_bx">
		                        <figure><i class="architect_icon"><img src="'.$img["url"].'" alt="'.$img["name"].'"></i></figure>
		                        <h6><a href="'.$url.'" title="'.$keys->slug.'">'.$keys->name.'</a></h6>
		                        '.wpautop($keys->description).'
		                    </div>
		                </li>';
		    $x++;
		    endif;
         }
         else
         {
         	$listing .= '<li>
	                    <div class="home_service_bx">
	                        <figure><i class="architect_icon"><img src="'.$img["url"].'" alt="'.$img["name"].'"></i></figure>
	                        <h6><a href="'.$url.'" title="'.$keys->slug.'">'.$keys->name.'</a></h6>
	                        '.wpautop($keys->description).'
	                    </div>
	                </li>';
         }
    endforeach;
    echo $listing;

}
add_shortcode( 'CATG', 'categoryListing' );

function DropdownCategory()
{

    $drop = "";                          
    $categories = get_terms(array('taxonomy' => 'jobs_category','hide_empty' => false) );
    $drop .= '<select name="service_type" class="placeholder1 form-control">';
  	$drop .= "<option value=''>Select Category</option>";
      foreach( $categories as $catgs )
      {	 
  	  	$drop .= "<option value='".$catgs->term_id."'>".$catgs->name."</option>";
  	}	
      $drop .= '</select>';

    echo $drop;                                
}
add_shortcode( 'CATG_DROP', 'DropdownCategory' );

/* Edit Profile PopUp */
function Edit_profile()
{
    $html = '';
    $html .='<div id="EditProfile" class="modal fade" role="dialog">
      <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title">Edit Profile</h4>
          </div>
          <div class="modal-body">
                         
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Update Profile</button>
          </div>
        </div>

      </div>
    </div>';

    echo $html;
}
add_shortcode( 'EDIT_PROF', 'Edit_profile' );

/* View FeedBack PopUp */
function View_feedback()
{
    $html = '';
    $html .='<div id="View-feedback" class="modal fade" role="dialog">
      <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h3 class="modal-title text-center"> Job FeedBack </h3>
          </div>
          <div class="modal-body profile-des">
            <article id="result-view">
            </article>               
          </div>
        </div>
      </div>
    </div>';

    echo $html;
}
add_shortcode( 'VIEW_FEEDBACK', 'View_feedback' );

/* Leave a FeedBack */
function Leave_feedback()
{
    $html = '';
    $html .='<div id="Leave-feedback" class="modal fade" role="dialog">
      <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h3 class="modal-title text-center"> Feedback </h3>
          </div>
          <form id="submit-review" method="POST">
          <div class="modal-body profile-des">
          <article>
              <div class="customer-profile-pic">
                    <figure style="background-image: url();"></figure>
                    <h5></h5>
                    <input type="hidden" name="job_id">
                    <input type="hidden" name="job_progress">
                    <div class="form-group">
                      <label> Rating: </label>
                      <select name="rating_by_provider" id="star-rating-1" class="group-required">
                        <option value="">Select a rating</option>
                        <option value="5">5</option>
                        <option value="4">4</option>
                        <option value="3">3</option>
                        <option value="2">2</option>
                        <option value="1">1</option>
                      </select>
                    </div>

                    <div class="form-group">
                      <label> Feedback: </label>                    
                      <textarea class="form-control" placeholder="Feedback Comment" name="feedback_by_provider"></textarea> 
                    </div> 
                    <input type="submit" class="btn btn-primary submit-review" value="Submit Feedback">                  
              </div>
          </article>             
          </div>
          </form>
        </div>
      </div>
    </div>';

    echo $html;
}
add_shortcode( 'LEAVE_FEEDBACK', 'Leave_feedback' );
/* Leave a FeedBack */
function Customer_LEAVE_FEEDBACK()
{
    $html = '';
    $html .='<div id="CustLeave-feedback" class="modal fade" role="dialog">
      <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h3 class="modal-title text-center"> Feedback </h3>
          </div>
          <form id="cstsubmit-review" method="POST">
          <div class="modal-body profile-des">
          <article>
              <div class="customer-profile-pic">
                    <figure style="background-image: url();"></figure>
                    <h5></h5>
                    <input type="hidden" name="job_id">                    
                    <input type="hidden" name="job_status" value="2">                    
                    <div class="form-group">
                      <label> Rating: </label>
                      <select name="rating_by_cust" id="star-rating-12" class="group-required">
                        <option value="">Select a rating</option>
                        <option value="5">5</option>
                        <option value="4">4</option>
                        <option value="3">3</option>
                        <option value="2">2</option>
                        <option value="1">1</option>
                      </select>
                    </div>

                    <div class="form-group">
                      <label> Feedback: </label>                    
                      <textarea class="form-control" placeholder="Feedback Comment" name="feedback_by_cust"></textarea> 
                    </div> 
                    <input type="submit" class="btn btn-primary cstsubmit-review" value="Submit Feedback">                  
              </div>
          </article>             
          </div>
          </form>
        </div>
      </div>
    </div>';

    echo $html;
}
add_shortcode( 'Customer_LEAVE_FEEDBACK', 'Customer_LEAVE_FEEDBACK' );
/* Submit Bid Filter Checking various conditions */
function submit_bid_filter( $uriSegments ) 
{
  if( $uriSegments[2] == 'submit-bid' )
  {
      $GetUri = $uriSegments[3];
  }
  else
  {
    $GetUri = $uriSegments[2];
  }	
   

		// pre($uriSegments);  
	if( empty( $GetUri ) || !is_user_logged_in() )	
	{		
		wp_redirect( site_url() ); 		
		exit;	
	}	
	else	
	{		
		$DatePost = get_posts( array( 'post_type' => 'job' , 'name' => $GetUri , 'post_status' => 'publish' ));
    //pre($DatePost);
		if( !empty( $DatePost ) ):					
			$Fields = get_fields( $DatePost[0]->ID );
			//pre($Fields);			
			if( $Fields['job_status'] == 0 )			
			{				
				return array( 'job_title' => $DatePost[0]->post_title , 'job_date' => $Fields['job_date'] , 'price' => $Fields['job_price'] , 'job_time' => $Fields['job_time'] , 'job_id' => $DatePost[0]->ID );			
			}			
			else			
			{	
				wp_redirect( site_url() ); 				
				exit; 			
			}		
		endif;	
	}
}
add_filter( 'AllowBid', 'submit_bid_filter' );

function  get_bid_details( $uriSegments )
{	
	//$uriSegments = explode("/", parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));
  if( $uriSegments[2] == 'submit-bid' )
  {
      $GetUri = $uriSegments[3];
  }
  else
  {
    $GetUri = $uriSegments[2];
  } 



	$DatePost = get_posts( array( 'post_type' => 'job' , 'name' => $GetUri , 'post_status' => 'publish' ));	
	if( !empty( $DatePost ) ):							
		$args = array(            
		'post_type' => 'bid',           
		'orderby' => 'modified',           
		'order' => 'DESC',            
		'posts_per_page' => 1,          			
		'meta_query'	=>    				
				array(
					array(					
						'key' => 'select_job',					
						'value' => '"'.$DatePost[0]->ID.'"',
						'compare' => 'LIKE'	
						),					
					array(		
					'key' => 'select_service_provider',	
					'value' => get_current_user_id(),		
					'compare' => '='	
					),		
				)		
		);					
		$BidData = get_posts($args);					
		if( !empty($BidData) ):
			$BidFields = get_fields( $BidData[0]->ID );
			$BidFields['Post_Data'] = $BidData[0]; 
			return $BidFields;			
		endif;	
	endif;
}
add_filter( 'BIDDETAILS' , 'get_bid_details' );

function  get_bid_questions( $uriSegments )
{	
	
  if( $uriSegments[2] == 'view-questions' )
  {
      $GetUri = $uriSegments[3];
  }
  else
  {
    $GetUri = $uriSegments[2];
  } 
	
	$DatePost = get_posts( array( 'post_type' => 'bid' , 'name' => $GetUri , 'post_status' => 'publish' ));		
	if( !empty( $DatePost ) ):		
		$BidFields = get_fields( $DatePost[0]->ID );			
		$JobFields = get_fields( $BidFields['select_job'][0]->ID );			
		$BidFields['Post_Data'] = $DatePost[0];
		//pre($JobFields);
			if( $JobFields['customer_name']['ID'] != get_current_user_id()  )
			{
				wp_redirect( site_url() ); 				
				exit;
			}
			else
			{
				return $BidFields;
			}
		
	else:
		wp_redirect( site_url( 'my-jobs' ) ); 				
		exit; 
	endif;
}
add_filter( 'BIDQUESTIONS' , 'get_bid_questions' );

function ServiceProviderAccess()
{
	
}
add_filter( 'ProviderAccess' , 'ServiceProviderAccess' );

function CustomerAccess()
{
	
}
add_filter( 'CustomerAccess' , 'CustomerAccess' );

function GetBidDetails($uriSegments)
{

  if( $uriSegments[2] == 'cards' )
  {
      $GetUri = $uriSegments[3];
  }
  else
  {
    $GetUri = $uriSegments[2];
  } 
	
	if( !empty( $GetUri ) ): 
	$args = array(            
		'post_type' => 'bid',           
		'orderby' => 'modified',           
		'order' => 'DESC',
		'name' => $GetUri, 		
		'posts_per_page' => 1
		);					
		$BidData = get_posts($args);
		
		if( !empty($BidData) ):
			$BidFields = get_fields( $BidData[0]->ID );
			$BidFields['Post_Data'] = $BidData[0]; 
			return $BidFields;			
		endif;
	endif;
}
add_filter( 'BIDDETAIL' , 'GetBidDetails' );


function FilterLogin()
{
	/*if ( is_user_logged_in() ) 
	{ 
		wp_redirect( site_url( '/my-jobs/' ) );
		exit;
	}*/
}
add_filter( 'ISLOGIN' , 'FilterLogin' );


function GetJobBid( $JobID ) // Get Bid Details For Job Open For Current User 
{
  global $wpdb;
	$user = wp_get_current_user();
	$UserID = $user->ID;	

	$args = array(
            'post_type' => 'bid',
            'orderby' => 'modified',
            'order' => 'DESC',
            'posts_per_page' => 10,
            //'paged' => $paged,
      			'meta_query'	=> 
         				array(
      					array(
      					'key' => 'select_job',
      					'value' => '"'.$JobID.'"',
      					'compare' => 'LIKE'
      					),
      					array(
      					'key' => 'select_service_provider',
      					'value' => $UserID,
      					'compare' => '='
					),
				)
			);
      $the_query = new WP_Query($args);
        

        if( !empty( $the_query ) ):
    		while( $the_query->have_posts() ) : $the_query->the_post();
    			$BidFields = get_fields( get_the_ID() );
         // pre($BidFields);
    			if( $BidFields['status'] == 0 ){ $BidStatus = 'Initiated'; }elseif( $BidFields['status'] == 1 ){ $BidStatus = 'Accepted';  }elseif( $BidFields['status'] == 2 ){ $BidStatus = 'Rejected'; }
    			
    			$Ques = '';
    			if( isset( $BidFields['questions'] ) && !empty($BidFields['questions']) ):
    			foreach( $BidFields['questions'] as $keys ):
    				$Ans = (empty($keys["answer"]))? "No Answer Submitted as Yet !" : $keys["answer"];
    				$Ques .= '<tr><td colspan="2">
    				 	<h5>Ques : '.$keys["question"].'</h5>
                        <p>Answer : '.$Ans.'</p>
                    </td></tr>';

    			endforeach;
    		endif;

    			echo "<div class='custom-table'>
    			<table>
    					<tr colspan='2'><th> <h3><u>Bid Description</u> :</h3></th></tr>
                         <tr>                            
                            <td>
                                <h5>Quoted Price</h5>
                                <p>$".$BidFields['quoted_price']."</p>
                            </td>
                             
                             <td>
                                <h5>Bid Status</h5>
                                <p>".$BidStatus."</p>
                            </td>
                        </tr>                        
                    	".$Ques."
                </table>
                </div><script>
            window.onload = function() 
            {
              SendAlert( '".get_the_ID()."' );             
              SendAlert( '".$BidFields['select_job'][0]->ID."' );             
            };
            </script>";

    			//pre($BidFields);
    		endwhile;
    	endif;

}
add_filter( 'BIDUSERDETAILS' , 'GetJobBid' );

function GetBIDCONTRACT( $JobID )
{
  global $wpdb;
  $Contract = $wpdb->get_results( "SELECT * FROM ".$wpdb->prefix."contracts WHERE job_id = '".$JobID."'" ); 
       // pre($the_query);
        if( !empty( $Contract ))
        {     
                
                $args = array(
                    'p' => $Contract[0]->bid_id,
                    'post_type' => 'bid',
                    'orderby' => 'modified',
                    'order' => 'DESC',
                    'posts_per_page' => 10,
                    //'paged' => $paged,
                    'meta_query'  => 
                        array(
                          array(
                          'key' => 'select_job',
                          'value' => '"'.$Contract[0]->job_id.'"',
                          'compare' => 'LIKE'
                        )
                  )
              );
              $the_query = new WP_Query($args);           
        }


        if( !empty( $the_query ) ):
        while( $the_query->have_posts() ) : $the_query->the_post();
          $BidFields = get_fields( get_the_ID() );
         // pre($BidFields);
          if( $BidFields['status'] == 0 ){ $BidStatus = 'Initiated'; }elseif( $BidFields['status'] == 1 ){ $BidStatus = 'Accepted';  }elseif( $BidFields['status'] == 2 ){ $BidStatus = 'Rejected'; }
          
          $Ques = '';
          if( isset( $BidFields['questions'] ) && !empty($BidFields['questions']) ):
          foreach( $BidFields['questions'] as $keys ):
            $Ans = (empty($keys["answer"]))? "No Answer Submitted as Yet !" : $keys["answer"];
            $Ques .= '<tr><td colspan="2">
              <h5>Ques : '.$keys["question"].'</h5>
                        <p>Answer : '.$Ans.'</p>
                    </td></tr>';

          endforeach;
        endif;

          echo "<div class='custom-table'>
          <table>
              <tr colspan='2'><th> <h3><u>Bid Description</u> :</h3></th></tr>
                         <tr>                            
                            <td>
                                <h5>Quoted Price</h5>
                                <p>$".$BidFields['quoted_price']."</p>
                            </td>
                             
                             <td>
                                <h5>Bid Status</h5>
                                <p>".$BidStatus."</p>
                            </td>
                        </tr>                        
                      ".$Ques."
                </table>
                </div><script>
            window.onload = function() 
            {
              SendAlert( '".get_the_ID()."' );             
              SendAlert( '".$BidFields['select_job'][0]->ID."' );             
            };
            </script>";

          //pre($BidFields);
        endwhile;
      endif;

}
add_filter( 'BIDCONTRACTDETAILS' , 'GetBIDCONTRACT' );


function GetContractDetails( $JobID )
{
  global $wpdb;
  // Fetch Contract Details
  $Contract = $wpdb->get_results( "SELECT * FROM ".$wpdb->prefix."contracts WHERE job_id = '".$JobID."'" );

  // Get Review or Rating
  $Review = $wpdb->get_results( "SELECT * FROM ".$wpdb->prefix."review WHERE job_id = '".$JobID."'" );

    if( !empty( $Review ) ):
      
      $CRating = '<div class="rating"><ul>';

      for( $x = 1; $x < 6 ; $x++ )
      {
        if( $x <= $Review[0]->rating_by_cust )
        {
          $CRating .='<li class="rate"><i class="fa fa-star" aria-hidden="true"></i></li>';
        }
        else
        {
          $CRating .='<li><i class="fa fa-star" aria-hidden="true"></i></li>';
        }
        
      }
      $CRating .='</ul></div>'; 

      $SRating = '<div class="rating"><ul>';

      for( $x = 1; $x < 6 ; $x++ )
      {
        if( $x <= $Review[0]->rating_by_provider )
        {
          $SRating .='<li class="rate"><i class="fa fa-star" aria-hidden="true"></i></li>';
        }
        else
        {
          $SRating .='<li><i class="fa fa-star" aria-hidden="true"></i></li>';
        }
        
      }
    $SRating .='</ul></div>';

    $JobStatus  = ( $Contract[0]->status == 2 )? 'Completed': 'In-Progress';
    if( $Contract[0]->status == 2 ):
    echo "<div class='custom-table profile-des'>
              <table>
                <tr colspan='2'>
                  <th> <h3><u>Rating & Feedback</u> :</h3></th>
                </tr>
                <tr>                            
                  <td>
                    <h5>Cutomer Rating &nbsp;".$CRating."</h5>
                    <p><strong>Feedback :</strong> ".$Review[0]->feedback_by_cust."</p>
                  </td>
                  <td>
                    <h5>Service Provider Rating &nbsp;".$SRating."</h5>
                    <p><strong>Feedback :</strong>  ".$Review[0]->feedback_by_provider."</p>
                  </td>
                </tr>
               <tr>                            
                    <td>
                        <h5>Job Status</h5>
                        <p>".$JobStatus."</p>
                    </td>
                </tr>    
              </table>
            </div>
            <script>
            window.onload = function() 
            {
              SendAlert( '".$Contract[0]->job_id."' );
              SendAlert( '".$Contract[0]->bid_id."' );
            };
            </script>
            ";
      else:
        echo "<div class='custom-table profile-des'>
              <table>
                <tr colspan='2'>
                  <th> <h3><u>Rating & Feedback</u> :</h3></th>
                </tr>                
               <tr>                            
                    <td>
                        <h5>Job Status</h5>
                        <p>".$JobStatus."</p>
                    </td>
                </tr>    
              </table>
            </div>
            <script>
            window.onload = function() 
            {
              SendAlert( '".$Contract[0]->job_id."' );
              SendAlert( '".$Contract[0]->bid_id."' );
            };
            </script>
            ";

      endif;

  endif;
  //pre($Contract);

}
add_filter( 'CONTRACTDETAILS' , 'GetContractDetails' );


function getAlertWeb( $user )
{   
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
    $Alerts = new WP_REST_Request( 'GET', '/users/getAlerts' );
    $Alerts->set_query_params(array(
        'user_id'   => get_current_user_id(),                
        'user_type' => $userRole,                
        'is_web'    => 1            
    ));
    $User = new Users();
    //header("Authenticate-Token: $accessToken");
    return $User->getAlerts($Alerts);
}

add_filter( 'WEBALERTS' , 'getAlertWeb' );


function Add_PortfolioPop()
{
  echo '<div id="myPort" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Add Portfolio Images</h4>
      </div>
      <div class="modal-body">
       <div id="id_dropzone" class="dropzone">
      </div>      
    </div>

  </div>
</div>';
}
add_shortcode( 'Port_PopUp' , 'Add_PortfolioPop' );

