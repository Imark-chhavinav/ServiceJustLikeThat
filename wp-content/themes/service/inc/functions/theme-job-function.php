<?php 
class Jobs
{
	var $WPmodify;
	
	public function __construct()
	{
		$this->WPmodify = new WPmodify();				
	}


	public function getservicetype()
	{
		$categories = get_terms(array(
	    'taxonomy' => 'jobs_category',
	    'hide_empty' => false,
			) );
	    
	    $listing = array();
	    $x = 0 ;
	    foreach( $categories  as $keys ):
	    	$listing[] = array( "id" => $keys->term_id, "name" =>$keys->name );			
	    endforeach;
	   $this->WPmodify->response( 1, $listing, 'No Error Found' );	
	}
	
	public function createJob( $request , $ajax = NULL )
	{
		global $wpdb;		
		if( !empty( $ajax ) )
		{
			$parameters = $request;
		}
		else
		{
			$parameters = $request->get_params();
		}

		if( isset( $parameters['job_date'] ) )
		{
			if( date( 'Y-m-d' ) > date( 'Y-m-d' , strtotime( $parameters['job_date'] ) ) )
			{
				 $this->WPmodify->response( 0, "", "Job Date Can't be in Past");	
			}
		}
		

		$this->WPmodify->checkUserType( $parameters['user_id'] , 1  );
		
		$FILES = $request->get_file_params();	

		
		$my_post = array(
			'post_title'	=> $parameters['job_title'],
			'post_content'	=> $parameters['job_content'],
			'post_type'		=> 'job',		
			'post_status'	=> 'publish'		
		);
		
		if( isset( $parameters['CreateJob_nonce'] ) )
		{
			$cat_ids = $parameters['service_type'];
			$cat_ids = array_map( 'intval', $cat_ids );
			$cat_ids = array_unique( $cat_ids );
		}
		else
		{
			$cat_ids = explode( ',' , $parameters['service_type'] );
			$cat_ids = array_map( 'intval', $cat_ids );
			$cat_ids = array_unique( $cat_ids );
		}

	//pre( $parameters );
		
			// insert the post into the database
			$post_id = wp_insert_post( $my_post );

			if( !empty( $FILES ) )
			{
				foreach( $FILES as $keys  ):
					$this->WPmodify->Add_attachment( $keys , $post_id );
				endforeach;
			}

			$term_taxonomy_ids = wp_set_object_terms( $post_id , $cat_ids, 'jobs_category' );

			$job_date 			= "job_date";
			$job_price 			= "job_price";
			$city 				= "city";
			$postcode 			= "postcode";
			$street_addess		= "street_addess";
			$job_time 			= "job_time";
			$state 				= "state";
			$job_status 		= "job_status";
			$customer_name 		= "customer_name";
			$publicly 			= "publicly";
			

				//invite_ids
			if( isset( $parameters['invite_ids'] ) && !empty($parameters['invite_ids']))
			{
				$Ids = explode( ',' , $parameters['invite_ids'] );
				//pre($Ids);
				foreach( $Ids as $keys  ):
					$this->WPmodify->checkUserType( $keys , 2  );
					global $wpdb;
					$Invite = $wpdb->get_results( "Select * from ".$wpdb->prefix."invites where job_id = ".$post_id." AND invite_id =".$keys );
					if( empty( $Invite) && !empty( $post_id ) )
					{
						// Notification && Push Notification
						$getJobDetails = $this->WPmodify->getJobDetails( $post_id );				 
						$getProfileDetails = $this->WPmodify->getProfileDetails( $parameters['user_id'] );
						//pre( $getJobDetails );
						// Sending Device Push Notification
						$message = $getProfileDetails[0]->first_name." has invited you for Job ".$getJobDetails['job_details']->post_title;	
						$this->WPmodify->sendPushNotification( $keys , $message , 'invite_for_job' , $post_id );				

						// Inserting Notification in the table 
						 $this->WPmodify->insertNotification( $parameters['user_id'] , $keys , 'invite_for_job' , $post_id , $message  );//$FromuserID , $TouserID , $notiType , $for_ID ,

						$save = $wpdb->insert($wpdb->prefix.'invites' , array( 'job_id' =>$post_id , 'invite_id' =>$keys, 'created' =>  date("F j, Y")  ));			
					}
				endforeach;
			}


			update_field( $job_date, $parameters['job_date'], $post_id ); 	
			update_field( $job_price, $parameters['job_price'], $post_id );	
			update_field( $city, $parameters['city'], $post_id );	
			update_field( $postcode, $parameters['postcode'], $post_id );	
			update_field( $street_addess,array("address" => $parameters['street_address'], "lat" => $parameters['lat'], "lng" => $parameters['longs'], "zoom" => "17"), $post_id );	
			update_field( $job_time, $parameters['job_time'], $post_id );	
			update_field( $state, array( $parameters['state'] ), $post_id );	
			update_field( $job_status, 0, $post_id );	
			update_field( $customer_name, $parameters['user_id'], $post_id );
			update_field( $publicly, ( isset( $parameters['publicly'] )? $parameters['publicly'] : 0 ), $post_id );
			

		if( $post_id )
		{
			 $this->WPmodify->response( 1, "Job Posted Successfully", 'No Error Found' );	
		}
		else
		{
			 $this->WPmodify->response( 0, "", "Job not Posted");	
		}

	}


	public function updateJob( $request , $ajax = NULL )
	{
		global $wpdb;		
		if( !empty( $ajax ) )
		{
			$parameters = $request;
		}
		else
		{
			$parameters = $request->get_params();
		}

		$this->WPmodify->checkUserType( $parameters['user_id'] , 1  );
		
		$FILES = $request->get_file_params();	

		$post_id = $parameters['job_id'];

		$job_status = get_field( 'job_status' , $post_id );	

		
			if( $job_status == 1 || $job_status == 2 )
			{
				$this->WPmodify->response( 0, "", "You cannot update the Job as job is Inprogress or Completed");	
			}

			$my_post = array(
				'ID'            => $post_id,
				'post_title'	=> $parameters['job_title'],
				'post_content'	=> $parameters['job_content'],
				'post_type'		=> 'job',		
				//'post_status'	=> 'publish'		
			);
	

			$cat_ids = explode( ',' , $parameters['service_type'] );
			$cat_ids = array_map( 'intval', $cat_ids );
			$cat_ids = array_unique( $cat_ids );
			
			// insert the post into the database
			$update = wp_update_post( $my_post );

			if( !empty( $FILES ) )
			{
				foreach( $FILES as $keys  ):
					$this->WPmodify->Add_attachment( $keys , $post_id );
				endforeach;
			}

			$term_taxonomy_ids = wp_set_object_terms( $post_id , $cat_ids, 'jobs_category' );

			$job_date 			= "job_date";
			$job_price 			= "job_price";
			$city 				= "city";
			$postcode 			= "postcode";
			$street_addess		= "street_addess";
			$job_time 			= "job_time";
			$state 				= "state";
			$job_status 		= "job_status";
			$customer_name 		= "customer_name";
			$publicly 			= "publicly";
				

			update_field( $job_date, $parameters['job_date'], $post_id ); 	
			update_field( $job_price, $parameters['job_price'], $post_id );	
			update_field( $city, $parameters['city'], $post_id );	
			update_field( $postcode, $parameters['postcode'], $post_id );	
			update_field( $street_addess,array("address" => $parameters['street_address'], "lat" => $parameters['lat'], "lng" => $parameters['longs'], "zoom" => "17"), $post_id );	
			update_field( $job_time, $parameters['job_time'], $post_id );	
			update_field( $state, array( $parameters['state'] ), $post_id );	
			//update_field( $job_status, array( 1 ), $post_id );	
			update_field( $customer_name, $parameters['user_id'], $post_id );

			update_field( $publicly, ( isset( $parameters['publicly'] )? $parameters['publicly'] : 0 ), $post_id );


			$IDs = $this->WPmodify->getServiceProviderIdForBids( $post_id );
			if( !empty( $IDs ) )
			{
				foreach( $IDs as $keys ):

				// Notification && Push Notification
				$getJobDetails = $this->WPmodify->getJobDetails( $post_id );				 
				$getContratsDetails = $this->WPmodify->getContratsDetails( $post_id );	

				$ProfileDetails = $this->WPmodify->getProfileDetails( $keys );			 
				
				// Sending Device Push Notification
				$message = $getJobDetails['customer_details']['display_name']." has Updated the Job ".$getJobDetails['job_details']->post_title." details  ";	

				$this->WPmodify->sendPushNotification( $getContratsDetails->service_providerID , $message , 'update_job' , $parameters['job_id'] );
				

				// Inserting Notification in the table 
				 $this->WPmodify->insertNotification( $parameters['user_id'] , $getContratsDetails->service_providerID , 'update_job' , $parameters['job_id'] , $message  );//$FromuserID , $TouserID , $notiType , $for_ID , 


				endforeach;
			}


		if( $update )
		{
			 $this->WPmodify->response( 1, "Job Updated Successfully", 'No Error Found' );	
		}
		else
		{
			 $this->WPmodify->response( 0, "", "Job not Updated");	
		}

	}	
	
	public function getJobDetail( $request , $ajax = NULL )
	{
		//$this->WPmodify->verifyToken();
		
		global $wpdb;		
		if( !empty( $ajax ) )
		{
			$parameters = $request;
		}
		else
		{
			$parameters = $request->get_params();
		}
		
		if( empty( $parameters['job_id'] ) )
		{
			$this->WPmodify->response( 0, "", 'Job ID Require' );
		}
		
		$JobID = $parameters['job_id'];
		$postData = get_post( $JobID ); 
		//pre( $postData );
		$fields = get_fields( $JobID );
		//pre( $fields );
		$categories = get_the_terms( $postData->ID, 'jobs_category' );
		//pre( $categories );
		$ServiceType = array();
		foreach( $categories as $keys )
		{
			$ServiceType[] = array( 'id'=>$keys->term_id , 'name'=>$keys->name );
		}
		
		$CustomerID =  $fields['customer_name']['ID'];
		$results = $wpdb->get_results( 'SELECT * FROM '.$wpdb->prefix.'profiledetails WHERE user_id ='.$CustomerID, OBJECT );
		//pre( $results );
		
		$profileImage = (empty($results[0]->profile_image))? "":site_url().'/'.$results[0]->profile_image;
		 
		$rating = $this->WPmodify->getUserRating( $CustomerID ,'1' );

		$Details = array( 
		'job_id' 			=> $postData->ID,
		'job_title' 		=> $postData->post_title,
		'job_content' 		=> $postData->post_content,
		'job_postedOn' 		=> date("F j, Y", strtotime($postData->post_date)) ,
		'job_date' 			=> $fields['job_date'] ,
		'job_price'	 		=> $fields['job_price'],
		'job_status'	 	=> $fields['job_status'],
		'location' 			=> $fields['street_addess']['address'],
		'lat' 				=> $fields['street_addess']['lat'],
		'publicly'			=> $fields['publicly'],
		'longs' 			=> $fields['street_addess']['lng'],
		'job_time' 			=> $fields['job_time'],
		'state' 			=> $fields['state'][0]['label'],
		'city' 				=> $fields['city'],
		'postcode' 			=> $fields['postcode'],
		'attachment' 		=> ( empty($fields['attachment']['url']) )? "":$fields['attachment']['url'],
		'service_type' 		=> $ServiceType,
		'customer_name'		=> $results[0]->first_name,
		'profile_image'		=> $profileImage,
		'street_address'	=> $results[0]->street_address,
		'member_since'		=> date("F j, Y", strtotime($results[0]->created_on)),
		'rating'			=> ( empty($rating) )? 0 : $rating
		);
		
		
		$this->WPmodify->response( 1, $Details);
	}

	public function getJobListing( $request , $ajax = NULL )
	{
		global $wpdb;
		$parameters = $request->get_params();		
		//pre( $parameters );
		//$paged = ( !empty( $parameters['page'] ) ) ? $parameters['page'] : 1;

		if( isset($parameters['service_type'])  && !empty( $parameters['service_type'] ))
		{
			if( isset($parameters['filter_nonce']) )
			{
				$services = $parameters['service_type'];
			}
			else
			{
				$services = explode( ',' , $parameters['service_type'] );
			}
			
			$args = array(
	            'post_type' => 'job',
	            'orderby' => 'date',
	            'order' => 'DESC',
	            'posts_per_page' => -1,
	            'tax_query' => array(
			        array(
			            'taxonomy' => 'jobs_category',			            
			            'terms' => $services,
			        )
			    ),
			    'meta_query'	=> array(
				'relation'		=> 'AND',					
					array(
						'key'	  	=> 'job_status',
						'value'	  	=> array( 0 )						
					),
					array(
						'key'	  	=> 'publicly',
						'value'	  	=> array( 0 )						
					)
				)
	            //'paged' => $paged
	        );
		}
		else
		{
			
			$args = array(
	            'post_type' 		=> 'job',
	            'orderby' 			=> 'date',
	            'order' 			=> 'DESC',	         
	            'posts_per_page' 	=> -1,
	            'meta_query'		=> array(
				'relation'			=> 'AND',					
					array(
						'key'	  	=> 'job_status',
						'value'	  	=> array( 0 )						
					),
					array(
						'key'	  	=> 'publicly',
						'value'	  	=> array( 0 )						
					)
				)
	            //'paged' => $paged
	        );
	        if( isset( $parameters['job_search'] ) && !empty( trim( $parameters['job_search'] ) ) )
	        {
	        	$args['s'] = $parameters['job_search'];
	        }
		}
	    
        $the_query = new WP_Query($args);
        

        if( isset( $parameters['user_id'] ) && !empty( $parameters['user_id'] ) )
		{
			$this->WPmodify->checkUserType( $parameters['user_id'] ,2 );
			$user_ID = $parameters['user_id'];
			$Current_User = $wpdb->get_results( "SELECT lat, longs FROM ".$wpdb->prefix."profiledetails WHERE user_id = '".$user_ID."' " );
			
		}

        $Details = array();

        if( !empty( $the_query ) ):
	        while( $the_query->have_posts() ) : $the_query->the_post();
			$post = get_post(get_the_ID());
			$slug = $post->post_name;
	        $fields = get_fields( get_the_ID() );
			
			if( isset( $parameters['user_id'] ) && !empty( $parameters['user_id'] ) )
				{				
					$postID = (string) get_the_ID();
					$Count = $this->WPmodify->checkAlreadyBid( $postID , $parameters['user_id'] );
				}
	       
	        $rating = $this->WPmodify->getUserRating( $fields['customer_name']['ID'] ,'1' );	      

	      	if( isset( $parameters[ 'distance' ]) && !empty( $parameters[ 'distance' ] ) && isset( $parameters[ 'lat' ] ) && isset( $parameters[ 'longs' ] ))
				{
					$miles = $this->WPmodify->distance($fields['street_addess']['lat'], $fields['street_addess']['lng'] , $parameters[ 'lat' ] , $parameters[ 'longs' ], 'M');

					if( $miles < $parameters[ 'distance' ] )
					{
						 $Details[] = array(
				        	'job_id'			=> get_the_ID(),
				        	'job_slug'			=> $slug,
				        	'job_title'			=> get_the_title(),
				        	'job_permalink'		=> get_the_permalink(),
				        	'job_content'		=> get_the_content(),
				        	'job_status'		=> $fields['job_status'][0],
				        	'job_postedOn'		=> get_the_date( 'F j, Y' , get_the_ID() ),
				        	'street_address'	=> $fields['street_addess']['address'],
				        	'price'				=> $fields['job_price'],
				        	'publicly'			=> $fields['publicly'],
				        	'job_date'			=> $fields['job_date'],
				        	'job_time'			=> $fields['job_time'],
				        	'customer_name'		=> ( empty($fields['customer_name']['user_firstname']) )? "" : $fields['customer_name']['user_firstname'],
				        	'rating'			=> ( empty($rating) )? 0 : $rating,
				        	'customer_image'	=> ( empty($fields['customer_name']['ID']) )? "" : $this->WPmodify->profileImage( $fields['customer_name']['ID'] ),
				        	'lat' => $fields['street_addess']['lat'], 
				        	'longs' => $fields['street_addess']['lng'],
							'already_applied' => $Count	
				        );
					}
					
					
				}
				elseif( isset( $parameters[ 'distance' ]) && !empty( $parameters[ 'distance' ] ) && isset( $Current_User[0]->lat ) && isset( $Current_User[0]->longs ) && !empty( $Current_User[0]->lat ) && !empty( $Current_User[0]->longs )  )
				{
					$miles = $this->WPmodify->distance($fields['street_addess']['lat'], $fields['street_addess']['lng'] , $parameters[ 'lat' ] , $parameters[ 'longs' ], 'M');

					if( $miles < $parameters[ 'distance' ] )
					{
						 $Details[] = array(
				        	'job_id'			=> get_the_ID(),
							'job_slug'			=> $slug,
				        	'job_title'			=> get_the_title(),
				        	'job_permalink'		=> get_the_permalink(),
				        	'job_content'		=> get_the_content(),
				        	'job_status'		=> $fields['job_status'][0],
				        	'job_postedOn'		=> get_the_date( 'F j, Y' , get_the_ID() ),
				        	'street_address'	=> $fields['street_addess']['address'],
				        	'price'				=> $fields['job_price'],
				        	'publicly'			=> $fields['publicly'],
				        	'job_date'			=> $fields['job_date'],
				        	'job_time'			=> $fields['job_time'],
				        	'customer_name'		=> ( empty($fields['customer_name']['user_firstname']) )? "" : $fields['customer_name']['user_firstname'],
				        	'rating'			=> ( empty($rating) )? 0 : $rating,
				        	'customer_image'	=> ( empty($fields['customer_name']['ID']) )? "" : $this->WPmodify->profileImage( $fields['customer_name']['ID'] ),
				        	'lat' => $fields['street_addess']['lat'], 
				        	'longs' => $fields['street_addess']['lng'],
							'already_applied' => $Count							
				        );
					}
					
					
				}
				else
				{
					 $Details[] = array(
				        	'job_id'			=> get_the_ID(),
							'job_slug'			=> $slug,
				        	'job_title'			=> get_the_title(),
				        	'job_permalink'		=> get_the_permalink(),
				        	'job_content'		=> get_the_content(),
				        	'job_status'		=> $fields['job_status'][0],
				        	'job_postedOn'		=> get_the_date( 'F j, Y' , get_the_ID() ),
				        	'street_address'	=> $fields['street_addess']['address'],
				        	'price'				=> $fields['job_price'],
				        	'publicly'			=> $fields['publicly'],
				        	'job_date'			=> $fields['job_date'],
				        	'job_time'			=> $fields['job_time'],
				        	'customer_name'		=> ( empty($fields['customer_name']['user_firstname']) )? "" : $fields['customer_name']['user_firstname'],
				        	'rating'			=> ( empty($rating) )? 0 : $rating,
				        	'customer_image'	=> ( empty($fields['customer_name']['ID']) )? "" : $this->WPmodify->profileImage( $fields['customer_name']['ID'] ),
				        	'lat' => $fields['street_addess']['lat'], 
				        	'longs' => $fields['street_addess']['lng'],
							'already_applied' => $Count
				        );
				}	       
	        endwhile;       
        if( !empty($Details) )
        {
        	$count = count( $Details);
        	$default = 10;
        	if(isset($parameters['page']) && $parameters['page'] >= 1)
			{
				$Pcount = $parameters['page'].'0';
				//$limit = $count;
				$Offset = $Pcount - $default; 
			}
			else
			{
				$Offset = 0;
			}

			$count_pages = ceil($count / $default);
			$Pagination = '<ul>';
			for( $x = 1 ; $x <= $count_pages; $x++ )
			{
				if( $x == $parameters['page'] )
				{
					$Pagination .= '<li disable data-val="'.$x.'" class="page disable">'.$x.' </li>';
				}
				else
				{
					$Pagination .= '<li data-val="'.$x.'" class="page">'.$x.' </li>';
				}
				
			}
			$Pagination .= '</ul>';

			$Details = array_slice( $Details,$Offset, $default );


        }
			 


	    endif;
	    $this->WPmodify->response( 1, $Details, ( count($Details) > 0 )? "No Error Found !" : " No Job Found !",  $count ,$Pagination );

	}

	public function customerJobListing( $request , $ajax = NULL )
	{
		$parameters = $request->get_params();	
		//pre( $parameters );
		if( empty( $parameters['user_id'] ))
		{
			$this->WPmodify->response( 0, array(), "Invalid Parameters");			
		}	

		$paged = ( !empty( $parameters['page'] ) ) ? $parameters['page'] : 1;
	
		//pre( $parameters ); ( $parameters['status'] == 1  )? serialize (array ( $parameters['status'] )) : $parameters['status']

        if( isset($parameters['status']))
        {
        	$args = array(
	            'post_type' => 'job',
	            'orderby' => 'modified',
	            'order' => 'DESC',
	            'posts_per_page' => 10,
				'paged' => $paged,
	            'meta_query'	=> array(
				'relation'		=> 'AND',
					array(
						'key'	 	=> 'customer_name',
						'value'	  	=> array( $parameters['user_id'])						
					),
					array(
						'key'	  	=> 'job_status',
						'value'	  	=> array( $parameters['status'] )						
					)
				)				
			);
        }
        else
        {
        	$args = array(
	            'post_type' => 'job',
	            'orderby' => 'modified',
	            'order' => 'DESC',
	            'posts_per_page' => 10,
				'paged' => $paged,
	            'meta_query'	=> array(
				'relation'		=> 'AND',
					array(
						'key'	 	=> 'customer_name',
						'value'	  	=> array( $parameters['user_id'])						
					)
				)	            
			);
        }

        $the_query = new WP_Query($args);
        //pre($the_query );

        $Details = array();

        if( !empty( $the_query ) ):
	        while( $the_query->have_posts() ) : $the_query->the_post();
			
			$Counts = $this->WPmodify->getAlert( get_the_ID() , $parameters['user_id'] );
			//pre( $Counts );
			$is_latest_for_customer = ( $Counts > 0 )? 1 : 0;
			$is_latest_for_bidder  = 0;
	        $fields = get_fields( get_the_ID() );
			
			/* if( isset( $fields['is_latest_for_customer'] ) && !empty( $fields['is_latest_for_customer'] ) )
				{
					$is_latest_for_customer = ( $fields['is_latest_for_customer'][0] == 1  )? 1 : 0 ;
				}
				else
				{
					$is_latest_for_customer  = 0;
				}
				
			if( isset( $fields['is_latest_for_bidder'] ) && !empty( $fields['is_latest_for_bidder'] ) )
				{
					$is_latest_for_bidder = ( $fields['is_latest_for_bidder'][0] == 1  )? 1 : 0 ;
				}
				else
				{
					$is_latest_for_bidder  = 0;
				} */
			//pre($fields);
	        $categories = get_the_terms( get_the_ID(), 'jobs_category' );


	        if( isset($parameters['status']) && ( $parameters['status'] == 1 || $parameters['status'] == 2) )
	        {
	        	global $wpdb;
	        	$Results = $wpdb->get_results( "SELECT service_providerID,modified_on,bid_id FROM ".$wpdb->prefix."contracts WHERE job_id = '".get_the_ID()."' " );

	        	$get_Price = get_field( 'quoted_price' , $Results[0]->bid_id );

	        	$ServiceProvider = $this->WPmodify->getProfileDetails( $Results[0]->service_providerID );
	        	$ServiceProvider_info = get_userdata( $Results[0]->service_providerID );


	        	$ConvID	= $this->WPmodify->getConversionID( $Results[0]->service_providerID , $fields['customer_name']['ID']);

	        }

	        $rating = $this->WPmodify->getUserRating( $Results[0]->service_providerID ,'2' );
		
	
			$ServiceType = array();
			if( !empty($categories) ):
				foreach( $categories as $keys )
				{
					$ServiceType[] = array( 'id'=>$keys->term_id , 'name'=>$keys->name );
				}
			endif;
	      	//pre( $fields );
			$Dat = array(
	        	'job_id'			=> get_the_ID(),
	        	'job_title'			=> get_the_title(),
	        	'job_content'		=> get_the_content(),
	        	'job_status'		=> $fields['job_status'][0],
	        	'job_postedOn'		=> get_the_date( 'F j, Y' , get_the_ID() ),
	        	'job_street_address'=> $fields['street_addess']['address'],
	        	'street_address'	=> ( empty($ServiceProvider[0]->street_address) )? "" : $ServiceProvider[0]->street_address,
	        	'price'				=> ( empty($get_Price) )? $fields['job_price'] : $get_Price,
	        	'job_date'			=> $fields['job_date'],
	        	'job_time'			=> $fields['job_time'],
	        	'customer_name'		=> ( empty($ServiceProvider_info->user_login) )? $ServiceProvider_info->display_name : $ServiceProvider_info->user_login,
	        	'rating'			=>  ( empty($rating) )? 0 : $rating,
	        	'service_type'		=> $ServiceType,
	        	'is_latest_for_customer'		=> $is_latest_for_customer,
	        	'is_latest_for_bidder'		=> $is_latest_for_bidder,
	        	'customer_image'	=> ( empty($Results[0]->service_providerID) )? "" : $this->WPmodify->profileImage( $Results[0]->service_providerID )
	        );
	        if( isset($parameters['status']) && $parameters['status'] == 1 )
	        {
	        	$Dat['conv_id'] = $ConvID;
	        	$Dat['user_id'] = $Results[0]->service_providerID;
	        }

	        if( isset($parameters['status']) &&  $parameters['status'] == 2  )
	        {
	        	
	        	$job_rating = $this->WPmodify->getUserRating( $Results[0]->service_providerID ,'2' , get_the_ID() );

	        	$JobFeed =  $this->WPmodify->getJobFeedBack( $Results[0]->service_providerID , '2' , get_the_ID() );


	        	$Dat['job_rating'] =( empty($job_rating) )? 0 : $job_rating;	        	
	        	$Dat['job_completed'] =( empty($Results[0]->modified_on) )? "" : $Results[0]->modified_on;
	        	$Dat['job_feedback'] = $JobFeed;
	        }	        

	        $Details[] = $Dat;
	        
	        endwhile;
	    endif;
	    $this->WPmodify->response( 1, $Details);

	}

	public function getBidDetail( $request , $ajax = NULL )
	{
		global $wpdb;		
		if( !empty( $ajax ) )
		{
			$parameters = $request;
		}
		else
		{
			$parameters = $request->get_params();
		}


		$Biddetails = get_post( $parameters[ 'bid_id' ] ); 	
		//pre( $Biddetails );
		if( !empty( $Biddetails ) )
		{
			$Fields 		= get_fields( $Biddetails->ID );
			$jobFields	 	= get_fields( $Fields['select_job'][0]->ID );
		//	pre( $Fields );
		//	pre( $jobFields );

			$Details = array(  
				'bid_id' 		=> $Biddetails->ID,
				'job_date'		=> $jobFields['job_date'],
				'job_price' 	=> $jobFields['job_price'],
				'job_time' 		=> $jobFields['job_time'],
				'quoted_price' 	=> $Fields['quoted_price'],				
				'additional_information' => trim( strip_tags ( $Fields['additional_information'] )),				
				'attachment' 	=> ( empty($Fields['attachment']['url']) )? "":$Fields['attachment']['url'],
				'questions' 	=> ( empty( $Fields['questions'] ) )? array(): $Fields['questions']

			);
		}
		else
		{
			$Details = array(  );
		}



		 $this->WPmodify->response( 1, $Details);

	}


	public function serviceJobListing( $request , $ajax = NULL )
	{
		$parameters = $request->get_params();
		//pre( $parameters );		

		$paged = ( !empty( $parameters['page'] ) ) ? $parameters['page'] : 1;

		// Status  0: Applied , 1 : InProgress , 2 : Completed , 3 : Invites

		if( isset( $parameters['status'] ) && $parameters['status'] == 3 )
		{

			$Results = $this->getInvites( $parameters['user_id'] );
			$this->WPmodify->response( 1, $Results);					
		}
		else
		{

			if( isset($parameters['user_type']))
	    	{

	        	$args = array(
		            'post_type' => 'bid',
		            'orderby' => 'date',
		            'order' => 'DESC',
		            'posts_per_page' => 10,
					'paged' => $paged,
		            'meta_query'	=> array(
					'relation'		=> 'AND',
					array(
							'key'	 	=> 'select_service_provider',
							'value'	  	=> array( $parameters['user_id'])						
						)			
					)
				);

				if( isset( $parameters['status'] ) && ( $parameters['status'] == 1)):
					$args['meta_query'][] = array('key' => 'status', 'value' =>  array( $parameters['status'] ));
				endif;

				
	        }      

       //pre( $args );
      
        $the_query = new WP_Query($args);

        $Details = array();

	        if( !empty( $the_query ) ):
	        	$fields = '';
		        while( $the_query->have_posts() ) : $the_query->the_post();
		        $fields = get_fields( get_the_ID() );	
		        //pre( $fields );
		      	$Address = get_field( 'street_addess' , $fields['select_job'][0]->ID );
		      	$customerName = get_field( 'customer_name' , $fields['select_job'][0]->ID );
		      	$jobStatus = get_field( 'job_status' , $fields['select_job'][0]->ID );
		      	$jobFields = get_fields( $fields['select_job'][0]->ID ); //pre($jobFields);
		      	$JobProgress = get_field( 'job_progress' , $fields['select_job'][0]->ID );
		      	$rating = $this->WPmodify->getUserRating( $customerName['ID'] ,'1' );

		      	$CompletedRating = $this->WPmodify->getUserRating( $parameters['user_id'] ,'2' ,$fields['select_job'][0]->ID );
		      	$CompletedFeedBack = $this->WPmodify->getJobFeedBack( $parameters['user_id'] ,'2' ,$fields['select_job'][0]->ID );
				
				$Counts = $this->WPmodify->getAlert( $fields['select_job'][0]->ID , $parameters['user_id'] );
				//pre( $Counts );
				$is_latest_for_bidder = ( $Counts > 0 )? 1 : 0;
				$is_latest_for_customer  = 0;

				/* Get Contract Details */
				if( isset( $jobStatus ) && $jobStatus == 2 )
				{
					$ContractDetails = $this->WPmodify->getContratsDetails( $fields['select_job'][0]->ID );
					//pre( $ContractDetails );
				}
				
				
			/* if( isset( $fields['is_latest_for_customer'] ) && !empty( $fields['is_latest_for_customer'] ) )
				{
					$is_latest_for_customer = ( $fields['is_latest_for_customer'][0] == 1  )? 1 : 0 ;
				}
				else
				{
					$is_latest_for_customer  = 0;
				}
				
			if( isset( $fields['is_latest_for_bidder'] ) && !empty( $fields['is_latest_for_bidder'] ) )
				{
					$is_latest_for_bidder = ( $fields['is_latest_for_bidder'][0] == 1  )? 1 : 0 ;
				}
				else
				{
					$is_latest_for_bidder  = 0;
				} */
		      	
		      	if($JobProgress == NULL  )
		      	{
		      		$JobProgress ="";
		      	}
		      	
		      	if( isset( $parameters['status'] ) && ($parameters['status'] == 2 &&  $jobStatus == 2 ) && $parameters['user_id'] == $ContractDetails->service_providerID )
		      	{
		      		$serv_ID = $parameters['user_id'];
		      		$custID = $customerName['ID'];

	      			$ConvID	= $this->WPmodify->getConversionID( $custID , $serv_ID);
	      			$ConversationStarted = $this->WPmodify->checkConversionStarted( $ConvID );

		      		$Details[] = array(
		        	'job_id'			=> $fields['select_job'][0]->ID,
		        	'job_title'			=> $fields['select_job'][0]->post_title,
		        	'job_content'		=> $fields['select_job'][0]->post_content,
		        	'job_status'		=> $jobStatus,
		        	'job_postedOn'		=> get_the_date( 'F j, Y' ,$fields['select_job'][0]->ID ),
		        	'street_address'	=> $Address['address'],
		        	'quoted_price'		=> $fields['quoted_price'],
	      			'actual_price'		=> $jobFields['job_price'],
		        	'job_progress' 		=> $JobProgress,
		        	'job_date'			=>  get_field( 'job_date' , $fields['select_job'][0]->ID ),
		        	'job_time'			=>  get_field( 'job_time' , $fields['select_job'][0]->ID ),
		        	'customer_name'		=> ( empty($customerName) )? "" : $customerName['display_name'],
		        	'rating'			=> ( empty($rating) )? 0 : $rating,
		        	'job_rating'		=> $CompletedRating,
		        	'job_feedback'		=> $CompletedFeedBack,
		        	'member_since'	    => ( empty($customerName) )? "" : date("F j, Y", strtotime($customerName['user_registered'])),
		        	'customer_image'	=> ( empty($customerName) )? "" : $this->WPmodify->profileImage( $customerName['ID'] ),
		        	'conv_id' => (empty($ConvID))? "" : $ConvID,
	        		'user_id' => $customerName['ID'],
	        		'conv_started' => $ConversationStarted,
	        		'is_latest_for_customer' => $is_latest_for_customer,
	        		'is_latest_for_bidder' => $is_latest_for_bidder
		       		 );
		      	}
		      	elseif( isset( $parameters['status'] ) && $parameters['status'] != 2  )
		      	{
		      		if( $parameters['status'] == 0  &&  $jobStatus == 0 ) // Applied
		      		{
		      			$serv_ID = $parameters['user_id'];
		      			$custID = $customerName['ID'];

		      			$ConvID	= $this->WPmodify->getConversionID( $custID , $serv_ID);
		      			$ConversationStarted = $this->WPmodify->checkConversionStarted( $ConvID );


		      			$Details[] = array(
			        	'job_id'			=> $fields['select_job'][0]->ID,
			        	'job_title'			=> $fields['select_job'][0]->post_title,
			        	'job_content'		=> $fields['select_job'][0]->post_content,
			        	'job_status'		=> $jobStatus,
			        	'job_postedOn'		=> get_the_date( 'F j, Y' ,$fields['select_job'][0]->ID ),
			        	'street_address'	=> $Address['address'],
			        	'quoted_price'		=> $fields['quoted_price'],
	      				'actual_price'		=> $jobFields['job_price'],
			        	'job_progress' 		=> $JobProgress,
			        	'job_date'			=>  get_field( 'job_date' , $fields['select_job'][0]->ID ),
			        	'job_time'			=>  get_field( 'job_time' , $fields['select_job'][0]->ID ),
			        	'customer_name'		=> ( empty($customerName) )? "" : $customerName['display_name'],
			        	'bid_status'		=> $fields['status'],	
			        	'bid_id'			=> get_the_ID(),
			        	'rating' 			=> ( empty($rating) )? 0 : $rating,
			        	'member_since'	    => ( empty($customerName) )? "" : date("F j, Y", strtotime($customerName['user_registered'])),		        	
			        	'customer_image'	=> ( empty($customerName) )? "" : $this->WPmodify->profileImage( $customerName['ID'] ),
			        	'conv_id' => (empty($ConvID))? "" : $ConvID,
		        		'user_id' => $customerName['ID'],
		        		'conv_started' => $ConversationStarted,
						'is_latest_for_customer' => $is_latest_for_customer,
						'is_latest_for_bidder' => $is_latest_for_bidder
			       		 );
		      		}
		      		elseif( $parameters['status'] == 1  && $jobStatus == 1 )
		      		{
		      			$serv_ID = $parameters['user_id'];
		      			$custID = $customerName['ID'];

		      			$ConvID	= $this->WPmodify->getConversionID( $custID , $serv_ID);

		      		$Details[] = array(
		        	'job_id'			=> $fields['select_job'][0]->ID,
		        	'job_title'			=> $fields['select_job'][0]->post_title,
		        	'job_content'		=> $fields['select_job'][0]->post_content,
		        	'job_status'		=> $jobStatus,		        	
		        	'job_postedOn'		=> get_the_date( 'F j, Y' ,$fields['select_job'][0]->ID ),
		        	'street_address'	=> $Address['address'],
		        	'quoted_price'		=> $fields['quoted_price'],
	      			'actual_price'		=> $jobFields['job_price'],
		        	'job_progress' 		=> $JobProgress,
		        	'job_date'			=>  get_field( 'job_date' , $fields['select_job'][0]->ID ),
		        	'job_time'			=>  get_field( 'job_time' , $fields['select_job'][0]->ID ),
		        	'customer_name'		=> ( empty($customerName) )? "" : $customerName['display_name'],
		        	'bid_status'		=> $fields['status'],
		        	'member_since'	    => ( empty($customerName) )? "" : date("F j, Y", strtotime($customerName['user_registered'])),		        	
		        	'customer_image'	=> ( empty($customerName) )? "" : $this->WPmodify->profileImage( $customerName['ID'] ),
		        	'conv_id' => (empty($ConvID))? "" : $ConvID,
		        	'user_id' => $customerName['ID'],
					'is_latest_for_customer' => $is_latest_for_customer,
					'is_latest_for_bidder' => $is_latest_for_bidder
		       		 );

		      		}
		      		
		      	}
		        
		        endwhile;
		    endif;
		    if( isset($parameters['ajax']) && $parameters['ajax'] == 1 )
		    {
		    	return $Details;
		    }
		    else
		    {
		    	$this->WPmodify->response( 1, $Details);
		    }	    
		}
	}

	public function getInvites( $userID )
	{
		global $wpdb;		
		
		$this->WPmodify->checkUserType( $userID , 2  );
		$results = $wpdb->get_results( "Select * from ".$wpdb->prefix."invites WHERE invite_id = ".$userID." AND status = 0 ORDER BY id Desc " );

		$Details = array();

		if( !empty( $results ) ):
		foreach( $results as $keys ):			
		$JobID = $keys->job_id;
		$postData = get_post( $JobID ); 
		if( !empty($postData) ):
		
		$Counts = $this->WPmodify->getAlert( $JobID , $userID );
		//pre( $Counts );
		$is_latest_for_bidder = ( $Counts > 0 )? 1 : 0;
		$is_latest_for_customer  = 0;
		
		$fields = get_fields( $JobID );
		
		$categories = get_the_terms( $postData->ID, 'jobs_category' );
		
		$ServiceType = array();
		if( !empty($categories) ):
			foreach( $categories as $Ckeys )
			{
				$ServiceType[] = array( 'id'=>$Ckeys->term_id , 'name'=>$Ckeys->name );
			}
		endif;
		
		$CustomerID =  $fields['customer_name']['ID'];
		$results = $wpdb->get_results( 'SELECT * FROM '.$wpdb->prefix.'profiledetails WHERE user_id ='.$CustomerID, OBJECT );
		$rating = $this->WPmodify->getUserRating( $fields['customer_name']['ID'] ,'1' );
		//pre( $results );
		
		$profileImage = (empty($results[0]->profile_image))? "":site_url().'/'.$results[0]->profile_image;
		 
			$Details[] = array( 
			'invite_id'			=> $keys->id,
			'job_id' 			=> $postData->ID,
			'job_title' 		=> $postData->post_title,
			'job_slug' 			=> $postData->post_name,
			'job_permalink' 	=> get_the_permalink( $postData->ID ),
			'job_content' 		=> $postData->post_content,
			'job_postedOn' 		=> date("F j, Y", strtotime($postData->post_date)) ,
			'job_date' 			=> $fields['job_date'] ,
			'job_price'	 		=> $fields['job_price'],
			'location' 			=> $fields['street_addess']['address'],
			'lat' 				=> $fields['street_addess']['lat'],
			'longs' 			=> $fields['street_addess']['lng'],
			'job_time' 			=> $fields['job_time'],
			'state' 			=> $fields['state'][0]['label'],
			'city' 				=> $fields['city'],
			'postcode' 			=> $fields['postcode'],
			'attachment' 		=> ( empty( $fields['attachment']['url'] ) ) ? "" : $fields['attachment']['url'],
			'service_type' 		=> $ServiceType,
			'customer_name'		=> $results[0]->first_name,
			'profile_image'		=> $profileImage,
			'street_address'	=> $results[0]->street_address,
			'member_since'		=> date("F j, Y", strtotime($results[0]->created_on)),
			'rating'			=> ( empty($rating) )? 0 : $rating,
			'is_latest_for_customer'			=> $is_latest_for_customer,
			'is_latest_for_bidder'			=> $is_latest_for_bidder
			);
		endif;
		endforeach;
		endif;
		 
		return $Details;
	}

	public function declineInvite( $request , $ajax = NULL )
	{
		global $wpdb;		
		if( !empty( $ajax ) )
		{
			$parameters = $request;
		}
		else
		{
			$parameters = $request->get_params();
		}
		
		$this->WPmodify->checkUserType( $parameters['user_id'] , 2  );

		// Notification && Push Notification

		$InviteDetails = $this->WPmodify->getInviteDetails( $parameters['invite_id'] );		
		
		$Details = $this->WPmodify->getJobDetails( $InviteDetails->job_id );
		$getProfileDetails = $this->WPmodify->getProfileDetails( $parameters['user_id'] );

		$message = $getProfileDetails[0]->username." has rejected your Invite for Job ".$Details['job_details']->post_title;
		

		$this->WPmodify->sendPushNotification( $Details['customer_details']['ID'] , $message , 'invite_rejected' , $InviteDetails->job_id );
		$this->WPmodify->insertNotification( $parameters['user_id'] , $Details['customer_details']['ID'] , 'invite_rejected' , $InviteDetails->job_id , $message  );

		$wpdb->update( $wpdb->prefix.'notification', array( 'is_alert'=> 1 )  ,array( 'to_userID' => $parameters['user_id'], 'noti_type' => 'invite_for_job' , 'for_ID' => $InviteDetails->job_id  ) );

		$Results = $wpdb->update( $wpdb->prefix.'invites', array( 'status'=> 1 )  ,array( 'id' => $parameters['invite_id'] ) );

		if( $Results )
			{
				 $this->WPmodify->response( 1, "Invite Rejected Successfully", 'No Error Found' );	
			}
		else
			{
				 $this->WPmodify->response( 0, "", "Please try again as some things went wrong !");	
			}
	}

	public function removeInvite( $request , $ajax = NULL )
	{
		global $wpdb;		
		if( !empty( $ajax ) )
		{
			$parameters = $request;
		}
		else
		{
			$parameters = $request->get_params();
		}

		$this->WPmodify->checkUserType( $parameters['user_id'] , 2  );

		$Results = $wpdb->delete( $wpdb->prefix.'invites', array( 'id' => $parameters['invite_id'] ) );

		if( $Results )
			{
				 $this->WPmodify->response( 1, "Invite Removed Successfully", 'No Error Found' );	
			}
		else
			{
				 $this->WPmodify->response( 0, "", "Please try again as some things went wrong !");	
			}
	}

	public function getBids( $request , $ajax = NULL )
	{
		global $wpdb;
		$parameters = $request->get_params();		
		
		$paged = ( !empty( $parameters['page'] ) ) ? $parameters['page'] : 1;
	
		$JobID = $parameters['job_id'];

        $args = array(
            'post_type' => 'bid',
            'orderby' => 'modified',
            'order' => 'DESC',
            'posts_per_page' => 10,
            'paged' => $paged,
			'meta_query'	=> 
   				array(
					array(
					'key' => 'select_job',
					'value' => '"'.$JobID.'"',
					'compare' => 'LIKE'
					),
					array(
					'key' => 'status',
					'value' => array( 0 ),
					'compare' => 'IN'
					),
				)
			);

        $the_query = new WP_Query($args);
        $UserDetails = array();
        //pre( $the_query );

        if( !empty( $the_query ) ):
	        while( $the_query->have_posts() ) : $the_query->the_post();

	        $fields = get_fields( get_the_ID() );
			
			//pre( $fields );
			
			if( isset( $fields['is_latest_for_customer'] ) && !empty( $fields['is_latest_for_customer'] ) )
				{
					$is_latest_for_customer = ( $fields['is_latest_for_customer'][0] == 1  )? 1 : 0 ;
				}
				else
				{
					$is_latest_for_customer  = 0;
				}
				
			if( isset( $fields['is_latest_for_bidder'] ) && !empty( $fields['is_latest_for_bidder'] ) )
				{
					$is_latest_for_bidder = ( $fields['is_latest_for_bidder'][0] == 1  )? 1 : 0 ;
				}
				else
				{
					$is_latest_for_bidder  = 0;
				}
			
			
	        $jobFields = get_fields( $fields['select_job'][0]->ID );

	      	$Profile = $this->WPmodify->getProfileDetails( $fields['select_service_provider']['ID'] );
	       
	        $custID = $jobFields['customer_name']['ID'];	       
	        $serv_ID = $Profile[0]->user_id;
			/* echo $custID;			echo $serv_ID; */
	        $ConvID	= $this->WPmodify->getConversionID( $custID , $serv_ID);
	     	$rating = $this->WPmodify->getUserRating( $serv_ID ,'2' );
	     	$successrate = $this->WPmodify->getSuccessRate( $serv_ID );

	      	$results = $wpdb->get_results( 'SELECT * FROM '.$wpdb->prefix.'portfolio WHERE user_id ='.$fields['select_service_provider']['ID'].'', OBJECT );
		
			
			if( !empty($results) )
			{
				$Urls = array();
				foreach( $results as $keys  ):

					$Urls[] = site_url($keys->image);

				endforeach;
			}
			else
			{
				$Urls = array();
			}			
			
			$Work = $this->WPmodify->getWorkHistory( $serv_ID , 2 );

	      	$UserDetails[] = array(
	      		'bid_id'		=>	get_the_ID(),
	      		'user_id'		=>	$Profile[0]->user_id,
	      		'user_name'		=>	$Profile[0]->first_name,
	      		'profile_image'	=>	( empty($Profile[0]->profile_image) )? "": $Profile[0]->profile_image,
	      		'street_address'=>	$Profile[0]->street_address,
	      		'member_since'	=>	date( 'F j, Y', strtotime( $fields['select_service_provider']['user_registered'])),
	      		'success_rate'	=>	$successrate['success_rate'],
	      		'rating'		=>	( empty($rating) )? 0 : $rating,
	      		'job_name'		=>	$fields['select_job'][0]->post_title,
	      		'job_id'		=>	$fields['select_job'][0]->ID,
	      		'work_history'  => $Work,
	      		'portfolio'		=> $Urls,
	      		'quoted_price'	=> $fields['quoted_price'],
	      		'actual_price'	=> $jobFields['job_price'],
	      		'conv_id'	=> ( empty($ConvID) )? "" : $ConvID,
	      		'is_latest_for_customer'	=> $is_latest_for_customer,
	      		'is_latest_for_bidder'	=> $is_latest_for_bidder
	      	);
	        endwhile;

	         $this->WPmodify->response( 1, $UserDetails);
	    endif;

	}

	public function applyForJob( $request , $ajax = NULL )
	{
	
		global $wpdb;		
		if( !empty( $ajax ) )
		{
			$parameters = $request;
		}
		else
		{
			$parameters = $request->get_params();
		}
		
		$this->WPmodify->checkUserType( $parameters['user_id'] , 2  );
		$Count = $this->WPmodify->checkAlreadyBid( $parameters['job_id'] , $parameters['user_id'] );

		if( !empty($Count) )
		{
			 $this->WPmodify->response( 0,"", "Already Applied");
		}


		
		$FILES = $request->get_file_params();

		$my_post = array(
			'post_title'	=> $parameters['job_title'].'_'.$parameters['user_id'],		
			'post_type'		=> 'bid',		
			'post_status'	=> 'publish'		
		);
		
		// insert the post into the database
		$post_id = wp_insert_post( $my_post );

		if( !empty( $FILES ) && isset($parameters['BidForm_nonce'] ) )
		{
			foreach( $FILES as $keys  ):
				$this->WPmodify->Add_attachment( $keys , $post_id );
			endforeach;
		}

			
		$select_job 					= "select_job";
		$select_service_provider 		= "select_service_provider";
		$quoted_price 					= "quoted_price";	
		$additional_information			= "additional_information";
		$questions_key 					= "field_5a30dced58ad5";


		
		update_field( $select_job, $parameters['job_id'], $post_id ); 	
		update_field( $select_service_provider, $parameters['user_id'], $post_id );	
		update_field( $quoted_price, $parameters['quoted_price'], $post_id );
		update_field( 'status', 0, $post_id );
		
		if( isset( $parameters['questions'] ) && !empty( $parameters['questions'] ) )
			{
				if( isset($parameters['BidForm_nonce'] ) )
				{
					$Array = array();
					foreach( $parameters['questions'] as $keys )
					{
							$Array[] = array( 'question' => $keys );
					}
					update_field( $questions_key, $Array, $post_id );
				}
				else
				{
					update_field( $questions_key, $parameters['questions'], $post_id );
				}				
			}

		if( isset( $parameters['additional_information'] ) && !empty( $parameters['additional_information'] ) )	
			{
				update_field( $additional_information, $parameters['additional_information'], $post_id );
			}


			if( $post_id )
			{
				// Notification && Push Notification
				$getJobDetails = $this->WPmodify->getJobDetails( $parameters['job_id'] );				 
				
				$FromuserProfile = $this->WPmodify->getProfileDetails( $parameters['user_id'] );
				
				// If Invited then Delete the invite
				
				if( isset( $parameters['invite_id'] ) && !empty( $parameters['invite_id'] ) )
				{
					$wpdb->delete( $wpdb->prefix.'invites' , array( 'id' => $parameters['invite_id'] ));
				}
				
				
				// Sending Device Push Notification
				$message = $FromuserProfile[0]->first_name." has applied For Job ".$getJobDetails['job_details']->post_title;	

				
				$this->WPmodify->sendPushNotification( $getJobDetails['customer_details']['ID'] , $message , 'apply_for_job' , $parameters['job_id'] );		

				// Inserting Notification in the table 
				$this->WPmodify->insertNotification( $parameters['user_id'] , $getJobDetails['customer_details']['ID'] , 'apply_for_job' , $parameters['job_id'] , $message  );//$FromuserID , $TouserID , $notiType , $for_ID ,
				
				//Is New
				// Job Field
				//$this->WPmodify->InsertAlert( $parameters['user_id'] , $getJobDetails['customer_details']['ID'] );//$FromUserID , $ToUserID , $RelatedID , $AlertType
				update_field('field_5b18e1c26dd16', array(1), $parameters['job_id']);
				$this->WPmodify->UpdatePostModifiedDate( $parameters['job_id'] );

				 $this->WPmodify->response( 1, "$post_id", 'No Error Found' );
			}
			else
			{
				 $this->WPmodify->response( 0, "", "Please try again as some things went wrong !");	
			}
	}

	public function applyForJobFile( $request , $ajax = NULL )
	{
	
		global $wpdb;		
		if( !empty( $ajax ) )
		{
			$parameters = $request;
		}
		else
		{
			$parameters = $request->get_params();
		}
		
		$this->WPmodify->checkUserType( $parameters['user_id'] , 2  );
		

		$FILES = $request->get_file_params();
		$bid_id = $parameters['bid_id'];


		if( !empty( $FILES ) )
		{
			foreach( $FILES as $keys  ):
			$result = 	$this->WPmodify->Add_attachment( $keys , $bid_id );
			endforeach;
		}

	
			if( $result )
			{
				 $this->WPmodify->response( 1, "Attachment uploaded successfully", 'No Error Found' );	
			}
			else
			{
				 $this->WPmodify->response( 0, "", "Please try again as some things went wrong !");	
			}
	}


	public function updateBid( $request , $ajax = NULL )
	{
	
		global $wpdb;		
		if( !empty( $ajax ) )
		{
			$parameters = $request;
		}
		else
		{
			$parameters = $request->get_params();
		}
		
		$this->WPmodify->checkUserType( $parameters['user_id'] , 2  );

		$post_id = $parameters['bid_id'];
		$JobID = $parameters['job_id'];
		
		$job_status = get_field( 'job_status' , $JobID );
		$Bid_status = get_field( 'status' , $post_id );
		
		/* File Upload */
		$FILES = $request->get_file_params();
		
		if( !empty( $FILES ) && isset($parameters['BidUpdateForm_nonce'] ) )
		{
			foreach( $FILES as $keys  ):
				$this->WPmodify->Add_attachment( $keys , $post_id );
			endforeach;
		}
		
		if( $job_status == 1 || $job_status == 2 )
		{
			$this->WPmodify->response( 0, "", "You cannot update the bid as job is Inprogress or Completed");	
		}

		if(  $Bid_status != 0 )
		{
			$this->WPmodify->response( 0, "", "You cannot update the bid as your Bid is Rejected or Accepted ");
		}		
		
		$quoted_price 					= "quoted_price";	
		$additional_information			= "additional_information";
		$questions_key 					= "field_5a30dced58ad5";
		
		update_field( $quoted_price, $parameters['quoted_price'], $post_id );		
		
		if( isset( $parameters['questions'] ) && !empty( $parameters['questions'] ) )
			{
				if( isset($parameters['BidUpdateForm_nonce'] ) )
					{
						$Array = array();
						foreach( $parameters['questions'] as $keys )
						{
								$Array[] = array( 'question' => $keys );
						}
						update_field( $questions_key, $Array, $post_id );
					}
				else
					{
						update_field( $questions_key, $parameters['questions'], $post_id );
					}
			}

		if( isset( $parameters['additional_information'] ) && !empty( $parameters['additional_information'] ) )	
			{
				update_field( $additional_information, $parameters['additional_information'], $post_id );
			}


			if( $post_id )
			{
				// Notification && Push Notification
				$getJobDetails = $this->WPmodify->getJobDetails( $JobID );				 
				
				// Sending Device Push Notification
				$message = $getJobDetails['customer_details']['display_name']." has Updated his Bid for Job ".$getJobDetails['job_details']->post_title;	
				$this->WPmodify->sendPushNotification( $getJobDetails['customer_details']['ID'] , $message , 'update_bid' , $post_id );				

				// Inserting Notification in the table 
				 $this->WPmodify->insertNotification( $parameters['user_id'] , $getJobDetails['customer_details']['ID'] , 'update_bid' , $post_id , $message  );//$FromuserID , $TouserID , $notiType , $for_ID , 
				 
				// Is New 
				// Job Field
				update_field('field_5b18e1c26dd16', array(1), $JobID);				
				// Bid Field
				update_field('field_5b18e18dc352d', array(1), $post_id);				
				
				$this->WPmodify->UpdatePostModifiedDate( $JobID );				
				$this->WPmodify->UpdatePostModifiedDate( $post_id );

				 $this->WPmodify->response( 1, "Bid Updated Successfully", 'No Error Found' );	
			}
			else
			{
				 $this->WPmodify->response( 0, "", "Please try again as some things went wrong !");	
			}

	}

	public function removeAttachment( $request , $ajax = NULL )
	{
	
		global $wpdb;		
		if( !empty( $ajax ) )
		{
			$parameters = $request;
		}
		else
		{
			$parameters = $request->get_params();
		}
		$this->WPmodify->checkUserType( $parameters['user_id'] , $parameters['user_type']  );
		$Results = wp_delete_attachment( $parameters['attachment_id'], true );
		
			if( $Results )
			{
				$this->WPmodify->response( 1, "Attachment Deleted Successfully", 'No Error Found' );	
			}
			else
			{
				 $this->WPmodify->response( 0, "", "Please try again as some things went wrong !");	
			}
	}

	public function acceptBid( $request , $ajax = NULL )
	{
	
		global $wpdb;		
		if( !empty( $ajax ) )
		{
			$parameters = $request;
		}
		else
		{
			$parameters = $request->get_params();
		}
		/* pre($parameters);
		die(); */
		
		$this->WPmodify->checkUserType( $parameters['user_id'] , $parameters['user_type']  );		

		$JobID = $parameters['job_id'];

		if( $parameters['user_type'] == 1 )// Customer 
		{

			if( $parameters['status'] == 1 )	// Accept Bid
			{
				// Check Payment is done for the current bid corresponding to jobid.
				$this->WPmodify->CheckPaymentDone( $parameters['job_id'] , $parameters['bid_id']  );
				
				$service_provider = get_field( 'select_service_provider' ,$parameters['bid_id'] );
				// Change Current Bid Status
				update_field( 'status', $parameters['status'], $parameters['bid_id'] );

				// Change Job Status Also
				update_field( 'job_status', 1, $parameters['job_id'] );

				// Notification && Push Notification
				$getJobDetails = $this->WPmodify->getJobDetails( $parameters['job_id'] );				 
				
				// Sending Device Push Notification
				$message = $getJobDetails['customer_details']['display_name']." has Accepted Your Bid for Job ".$getJobDetails['job_details']->post_title;

				$this->WPmodify->sendPushNotification( $service_provider['ID'] , $message , 'accept_bid' , $parameters['job_id'] );				

				// Inserting Notification in the table 
				 $this->WPmodify->insertNotification( $parameters['user_id'] , $service_provider['ID'] , 'accept_bid' , $parameters['job_id'] , $message  );//$FromuserID , $TouserID , $notiType , $for_ID , 
				 
				 
				// Is New 
				// Job Field
				update_field('field_5b1bb3c9ddb30', array(1), $parameters['job_id']);
				// Bid Field
				update_field('field_5b1bb364495ee', array(1), $parameters['bid_id']);				
				
				$this->WPmodify->UpdatePostModifiedDate( $parameters['job_id'] );				
				$this->WPmodify->UpdatePostModifiedDate( $parameters['bid_id'] );




				$wpdb->insert($wpdb->prefix.'contracts',array(
					'job_id'			=> $parameters['job_id'],
					'bid_id'			=> $parameters['bid_id'],
					'customer_id'		=> $parameters['user_id'],
					'service_providerID'=> $service_provider['ID'],
					'status'			=> $parameters['status'],
					'createdOn'			=> date("F j, Y"),
					'modified_on'		=> date("F j, Y")
				));

				// Change rest All bid Status to Rejected

				$args = array(
	            'post_type' => 'bid',
	            'orderby' => 'date',
	            'order' => 'DESC',
	            'posts_per_page' => -1,	            
				'meta_query'	=> 
	   				array(
						array(
						'key' => 'select_job',
						'value' => '"'.$JobID.'"',
						'compare' => 'LIKE'
						),
						array(
						'key' => 'status',
						'value' => array( 0 ),
						'compare' => 'IN'
						),
					)
				);

       		 $the_query = new WP_Query($args); 
   		  		if( !empty( $the_query ) ):
	        		while( $the_query->have_posts() ) : $the_query->the_post();
	        			update_field( 'status', 2 , get_the_ID() );
	        			// Notification && Push Notification
						$getJobDetails = $this->WPmodify->getJobDetails( $parameters['job_id'] );				 
	        			$service_provider_Rejected = get_field( 'select_service_provider' ,get_the_ID() );
						
						// Sending Device Push Notification
						$message = $getJobDetails['customer_details']['display_name']." has Rejected Your Bid for Job ".$getJobDetails['job_details']->post_title;

						$this->WPmodify->sendPushNotification( $service_provider_Rejected['ID'] , $message , 'rejected_bid' , $parameters['job_id'] );				

						// Inserting Notification in the table 
						 $this->WPmodify->insertNotification( $parameters['user_id'] , $service_provider_Rejected['ID'] , 'rejected_bid' , $parameters['job_id'] , $message  );//$FromuserID , $TouserID , $notiType , $for_ID ,						 

					 endwhile;	         
   				 endif;
   				 $this->WPmodify->response( 1, "Contract Created Successfully");	
			}
			else 				// Reject Bid
			{
				// Change Current Bid Status
				update_field( 'status', $parameters['status'], $parameters['bid_id'] );

				// Notification && Push Notification
				$getJobDetails = $this->WPmodify->getJobDetails( $parameters['job_id'] );				 
    			$service_provider_Rejected = get_field( 'select_service_provider' ,$parameters['bid_id'] );
				
				// Sending Device Push Notification
				$message = $getJobDetails['customer_details']['display_name']." has Rejected Your Bid for Job ".$getJobDetails['job_details']->post_title;

				$this->WPmodify->sendPushNotification( $service_provider_Rejected['ID'] , $message , 'rejected_bid' , $parameters['job_id'] );				

				// Inserting Notification in the table 
				 $this->WPmodify->insertNotification( $parameters['user_id'] , $service_provider_Rejected['ID'] , 'rejected_bid' , $parameters['job_id'] , $message  );//$FromuserID , $TouserID , $notiType , $for_ID ,


				$this->WPmodify->response( 1, "Bid Rejected Successfully");
			}

		}
		else 				// Service Provider
		{
			wp_delete_post( $parameters['bid_id'], true );
			$this->WPmodify->response( 1, "Bid Deleted Successfully");
		}
	}

	public function updateJobProgress( $request , $ajax = NULL )
	{
	
		global $wpdb;		
		if( !empty( $ajax ) )
		{
			$parameters = $request;
		}
		else
		{
			$parameters = $request->get_params();
		}
		$this->WPmodify->checkUserType( $parameters['user_id'] , 2  );
		// 0: Start From Home , 1: Reached Home , 2: Start Task , 3 : Completed	

		$Touser_id = get_field( 'customer_name' , $parameters['job_id'] );
		$getJobDetails = $this->WPmodify->getJobDetails( $parameters['job_id'] );
		
		$NotiType = '';
		
		if( $parameters['job_progress'] == 0 )
		{
			$status = 'has left from Home.';
			$NotiType = 'job_progress_left_home';
		}
		if( $parameters['job_progress'] == 1 )
		{
			$status = 'has reached your home. ';
			$NotiType = 'job_progress_reached_home';
		}
		if( $parameters['job_progress'] == 2 )
		{
			$status = 'has started the task. ';
			$NotiType = 'job_progress_tast_started';
		}
		if( $parameters['job_progress'] == 3 )
		{
			$status = 'has completed the task. ';
			$NotiType = 'job_progress_tast_completed';
		}
		
		

		// Notification && Push Notification
		$FromuserProfile = $this->WPmodify->getProfileDetails( $parameters['user_id'] );
		$ToProfile = $this->WPmodify->getProfileDetails( $Touser_id['ID'] );

		// Sending Device Push Notification
		$message = $FromuserProfile[0]->first_name.' '.$FromuserProfile[0]->last_name." ".$status.' '.$getJobDetails['job_details']->post_title  ;	
		
		$this->WPmodify->sendPushNotification( $Touser_id['ID'] , $message, $NotiType , $parameters['job_id'] );

		// Inserting Notification in the table 
		 $this->WPmodify->insertNotification( $FromuserProfile[0]->user_id , $ToProfile[0]->user_id , $NotiType , $parameters['job_id'] , $message  );//$FromuserID , $TouserID , $notiType , $for_ID 


		$result = update_field( 'job_progress', $parameters['job_progress'], $parameters['job_id'] );
		
		// Is New
		// Job Field
		update_field('field_5b18e1c26dd16', array(1), $parameters['job_id']);		
		$this->WPmodify->UpdatePostModifiedDate( $parameters['job_id'] );

		if( $result )
		{
			$this->WPmodify->response( 1, "Job Progess Status Changed Successfully");
		}
		else
		{
			$this->WPmodify->response( 0, "" , "Some think went wrong ! Please Try Again ");
		}
	}

	public function markJobComplete( $request , $ajax = NULL )
	{
	
		global $wpdb;		
		if( !empty( $ajax ) )
		{
			$parameters = $request;
		}
		else
		{
			$parameters = $request->get_params();
		}

		$this->WPmodify->checkUserType( $parameters['user_id'] , 1  );


		update_field( 'job_status', $parameters['job_status'], $parameters['job_id'] );
		

		$Data = array(			
			'job_id'    				=> $parameters['job_id'],			
			'rating_by_cust'			=> ( empty( $parameters['rating_by_cust'] ) )? "" : $parameters['rating_by_cust'],
			'feedback_by_cust'			=> ( empty( $parameters['feedback_by_cust'] ) )? "" : $parameters['feedback_by_cust'],
			'createdOn'					=> date( 'F j, Y' )
						
		);
		
		$ReviewResults = $wpdb->get_results( "SELECT * FROM ".$wpdb->prefix."review WHERE job_id = ".$parameters['job_id']);
		

			// Updating the Job status in Contracts Table
		$wpdb->update( $wpdb->prefix.'contracts' , array( 'status' => $parameters['job_status'], 'modified_on' => date( 'F j, Y' )  ) , array( 'job_id' =>  $parameters['job_id'] ) );
		$wpdb->flush();
		
		
		if( empty( $ReviewResults ) )
		{
			// Submitting the review rating and feedback
			$save = $wpdb->insert( $wpdb->prefix.'review', $Data );		
			$wpdb->flush();			
		}
		else
		{
			unset( $Data['job_id'] );
			$save = $wpdb->update( $wpdb->prefix.'review' , $Data , array( 'job_id' =>  $parameters['job_id'] ) );
			$wpdb->flush();
		}
			

		// Notification && Push Notification
		$getJobDetails = $this->WPmodify->getJobDetails( $parameters['job_id'] );				 
		$getContratsDetails = $this->WPmodify->getContratsDetails( $parameters['job_id'] );	

		$ProfileDetails = $this->WPmodify->getProfileDetails( $getContratsDetails->service_providerID );			 
		
		// Sending Device Push Notification
		$message = $getJobDetails['customer_details']['display_name']." has marked the Job ".$getJobDetails['job_details']->post_title." completed  ";	

		$this->WPmodify->sendPushNotification( $getContratsDetails->service_providerID , $message , 'complete_job' , $parameters['job_id'] );
		

		// Inserting Notification in the table 
		 $this->WPmodify->insertNotification( $parameters['user_id'] , $getContratsDetails->service_providerID , 'complete_job' , $parameters['job_id'] , $message  );//$FromuserID , $TouserID , $notiType , $for_ID , 
		
		// Is New
		// Job Field
		update_field('field_5b18e1c26dd16', array(1), $parameters['job_id']);
		$this->WPmodify->UpdatePostModifiedDate( $parameters['job_id'] );
		

		if( $save )
		{
			$this->WPmodify->response( 1, "Job Completed Successfully");
		}
		else
		{
			$this->WPmodify->response( 0, "" , "Some think went wrong ! Please Try Again ");
		}
	}

	public function submitCustomerReview( $request , $ajax = NULL )
	{
	
		global $wpdb;		
		if( !empty( $ajax ) )
		{
			$parameters = $request;
		}
		else
		{
			$parameters = $request->get_params();
		}

		$this->WPmodify->checkUserType( $parameters['user_id'] , 2  );

		$Data = array();
		if( isset( $parameters['rating_by_provider'] ) && !empty( $parameters['rating_by_provider'] ) )
		{
			$Data['rating_by_provider'] = $parameters['rating_by_provider'];			
		}
		if( isset( $parameters['feedback_by_provider'] ) &&  !empty( $parameters['feedback_by_provider'] ) )
		{
			$Data['feedback_by_provider'] = $parameters['feedback_by_provider'];			 
		}
		
		$Results = $wpdb->get_results( "SELECT * FROM ".$wpdb->prefix."review WHERE job_id = ".$parameters['job_id']);
		
		if( empty( $Results ) )
		{
			$Data['job_id'] = $parameters['job_id'];			
			$Update = $wpdb->insert( $wpdb->prefix.'review', $Data );		
			//$wpdb->flush();
		}
		else
		{
			$Update = $wpdb->update( $wpdb->prefix.'review' , $Data , array( 'job_id' =>  $parameters['job_id'] ) );
			///$wpdb->flush();
		}

		
		if( $Update )
		{
			$this->WPmodify->response( 1, "Review Submitted Successfully");
		}
		else
		{
			$this->WPmodify->response( 0, "" , "Some think went wrong ! Please Try Again ");
		}
	}

	public function submitAnswer( $request , $ajax = NULL )
	{
		global $wpdb;		
		if( !empty( $ajax ) )
		{
			$parameters = $request;
		}
		else
		{
			$parameters = $request->get_params();
		}
		

		$this->WPmodify->checkUserType( $parameters['user_id'] , 1  );

		$post_id = $parameters['bid_id'];
		$questions_key 	= "field_5a30dced58ad5";

	 	$Touser_id = get_field( 'select_service_provider' , $post_id );
	 	$Fields 		= get_fields( $post_id );		

		// Notification && Push Notification
		$FromuserProfile = $this->WPmodify->getProfileDetails( $parameters['user_id'] );
		$ToProfile = $this->WPmodify->getProfileDetails( $Touser_id['ID'] );

		// Sending Device Push Notification
		$message = $FromuserProfile[0]->first_name.' '.$FromuserProfile[0]->last_name.' has answer your questions ';
		
		if( isset( $parameters['questions'] ) && !empty( $parameters['questions'] ) )
			{
				if( isset($parameters['Submit_Answer_Nonce'] ) )
					{
						$Array = array();
						foreach( $parameters['answers'] as $keys )
						{
								$Array[] = array( 'answer' => $keys );
						}
						update_field( $questions_key, $Array, $post_id );
					}
				else
					{
						$updated = update_field( $questions_key, $parameters['questions'], $post_id );
					}				
				
				// Is New
				// Bid Field				
				update_field('field_5b1bb3c9ddb30', array(1), $post_id);
				update_field('field_5b1bb364495ee', array(1), $post_id);
				$this->WPmodify->UpdatePostModifiedDate( $post_id );
				
				
				$this->WPmodify->sendPushNotification( $Touser_id['ID'] , $message , 'answer_submit' , $post_id );
				// Inserting Notification in the table 
				 $this->WPmodify->insertNotification( $FromuserProfile[0]->user_id , $ToProfile[0]->user_id , 'answer_submit' , $post_id , $message  );//$FromuserID , $TouserID , $notiType , $for_ID ,
				$this->WPmodify->response( 1, "Answers submitted Successfully");
			}
			else
			{
				$this->WPmodify->response( 0, "Answers not submitted ! Please try again");
			}
			
			/* if( $updated )
			{
				$this->WPmodify->sendPushNotification( $Touser_id['ID'] , $message , 'answer_submit' , $post_id );
				// Inserting Notification in the table 
				 $this->WPmodify->insertNotification( $FromuserProfile[0]->user_id , $ToProfile[0]->user_id , 'answer_submit' , $post_id , $message  );//$FromuserID , $TouserID , $notiType , $for_ID ,
				$this->WPmodify->response( 1, "Answers submitted Successfully");
			}
			else
			{
				$this->WPmodify->response( 0, "Answers not submitted ! Please try again");
			} */
	}


	public function getWorkHistory( $request , $ajax = NULL )
	{
		global $wpdb;		
		if( !empty( $ajax ) )
		{
			$parameters = $request;
		}
		else
		{
			$parameters = $request->get_params();
		}
		
		$UserID = $parameters['user_id'];
		//$this->WPmodify->checkUserType( $parameters['user_id'] , 1  );

		if(  $parameters['user_type'] == 1 )
		{
			$where =  " customer_id =  '".$UserID."' ";
		}

		if(  $parameters['user_type'] == 2 )
		{
			$where =  " service_providerID =  '".$UserID."' ";
		}

		// Job name , rating ,date , 

		$Results = $wpdb->get_results( "SELECT * FROM ".$wpdb->prefix."contracts INNER JOIN ".$wpdb->prefix."review ON ".$wpdb->prefix."review.job_id = ".$wpdb->prefix."contracts.job_id WHERE ".$where );
		
		$Details = array();
		foreach( $Results  as $keys ):
			$JobName 				= get_the_title( $keys->job_id );
			$JobID 					= $keys->job_id;
			$rating_by_cust 		= ( empty($keys->rating_by_cust) )? "": $keys->rating_by_cust;
			$CreateDate		 		= ( empty($keys->createdOn) )? "": date( 'M Y' , strtotime( $keys->createdOn ) );
			$CompletedDate		 	= ( empty($keys->modified_on) )? "": date( 'M Y' , strtotime( $keys->modified_on ));
			$rating_by_provider 		= ( empty($keys->rating_by_provider) )? "": $keys->rating_by_provider;

				$Details[] = array(  
					'user_id' 			=> $UserID ,
					'user_type' 		=> $parameters['user_type'] ,
					'job_name' 			=> $JobName ,
					'job_id' 			=> $JobID,
					'rating_by_cust' 	=> $rating_by_cust ,
					'rating_by_provider'=> $rating_by_provider ,
					'start_date' 		=> $CreateDate ,
					'end_date' 			=> $CompletedDate
				);
		endforeach;

		$this->WPmodify->response( 1, $Details );		

	}


	public function requestBid( $request , $ajax = NULL )
	{
		global $wpdb;		
		if( !empty( $ajax ) )
		{
			$parameters = $request;
		}
		else
		{
			$parameters = $request->get_params();
		}

		$post_id = $parameters['job_id'];

		if( isset( $parameters['invite_ids'] ) && !empty($parameters['invite_ids']))
		{
			$Ids = explode( ',' , $parameters['invite_ids'] );
			foreach( $Ids as $keys  ):
				$this->WPmodify->checkUserType( $keys , 2  );
				global $wpdb;
				$Invite = $wpdb->get_results( "Select * from ".$wpdb->prefix."invites where job_id = ".$post_id." AND invite_id =".$keys );
				if( empty( $Invite) )
				{
					// Notification && Push Notification
					$getJobDetails = $this->WPmodify->getJobDetails( $post_id );				 
					
					// Sending Device Push Notification
					$message = $getJobDetails['customer_details']['display_name']." has invited you for Job ".$getJobDetails['job_details']->post_title;	
					$this->WPmodify->sendPushNotification( $keys , $message , 'invite_for_job' , $post_id );				

					// Inserting Notification in the table 
					 $this->WPmodify->insertNotification( $parameters['user_id'] , $keys , 'invite_for_job' , $post_id , $message  );//$FromuserID , $TouserID , $notiType , $for_ID , 


					$wpdb->insert($wpdb->prefix.'invites' , array( 'job_id' =>$post_id , 'invite_id' =>$keys, 'created' =>  date("F j, Y")  ));	
					$this->WPmodify->response( 1, 'Invite Send Successfully' );		
					
					update_field('field_5b1bb3c9ddb30', array(1), $post_id);
					$this->WPmodify->UpdatePostModifiedDate( $post_id );
					
				}
				else
				{
					$this->WPmodify->response( 0, "", "Invite Already Send !" );
				}
			endforeach;
		}
	}

	public function deleteJob( $request , $ajax = NULL )
	{
		global $wpdb;		
		if( !empty( $ajax ) )
		{
			$parameters = $request;
		}
		else
		{
			$parameters = $request->get_params();
		}

		$this->WPmodify->checkUserType( $parameters['user_id'] , 1 );

		$JobID = $parameters['job_id'];

		$job_status = get_field( 'job_status' , $JobID );		
		
		
		if( $job_status == 1 || $job_status == 2 )
		{
			$this->WPmodify->response( 0, "", "You cannot Delete this Job as Job is InProgress or Completed ");	
		}

		wp_delete_post( $JobID , true );
		$this->WPmodify->response( 1, "Job Deleted Successfully");			
		
	}

	public function jobPayment( $request , $ajax = NULL )
	{
	
		global $wpdb;		
		if( !empty( $ajax ) )
		{
			$parameters = $request;
		}
		else
		{
			$parameters = $request->get_params();
		}
		
		$this->WPmodify->checkUserType( $parameters['user_id'] , 1 );
		$this->WPmodify->AlreadyPaid( $parameters['job_id'] , $parameters['bid_id'] );


		$Card_token = $parameters['card_token'];
		$user_id 	= $parameters['user_id'];
		$user_type 	= $parameters['user_type'];
		$job_id 	= $parameters['job_id'];
		$bid_id 	= $parameters['bid_id'];
		$amount 	= $parameters['amount'];

		$ServiceID = get_field( 'select_service_provider' , $bid_id );
	
		$Resp = BrainTree_create_payment( $amount , $Card_token);
		
		
		if( !empty( $Resp ) )
		{
			$Data = array( 'transactionID'=> $Resp , 'bid_id' => $bid_id , 'job_id' => $job_id , 'customer_ID'=>$user_id , 'service_Provider_ID'=>$ServiceID['ID'],'amount'=>$amount, 'payment_type'=>'job_payment' , 'created_on' => date( 'Y-m-d H:i:s' ) );			
			$Insert = $wpdb->insert( $wpdb->prefix."payment" , $Data );
			$wpdb->flush(); 
			if( $Insert )
			{
				$this->WPmodify->response( 1, "Payment Completed");	
			}
			else
			{
				$this->WPmodify->response( 0, "","Please try Again as payment can't be process");		
			}
		}
		else
		{
			$this->WPmodify->response( 0, "","No Response from payment gateway ");
		}
	}

	public function giveTip( $request , $ajax = NULL )
	{
	
		global $wpdb;		
		if( !empty( $ajax ) )
		{
			$parameters = $request;
		}
		else
		{
			$parameters = $request->get_params();
		}
		$this->WPmodify->checkUserType( $parameters['user_id'] , 1 );
		//$this->WPmodify->AlreadyPaid( $parameters['job_id'] , $parameters['bid_id'] );


		$Card_token = $parameters['card_token'];
		$user_id 	= $parameters['user_id'];
		$user_type 	= $parameters['user_type'];
		$job_id 	= $parameters['job_id'];		
		$amount 	= $parameters['amount'];
		$ServiceID 	= $parameters['service_Provider_ID'];
			
		$Resp = BrainTree_create_payment( $amount , $Card_token);

		if( !empty( $Resp ) )
		{
			$Insert = $wpdb->insert( $wpdb->prefix."payment" , array( 'transactionID'=> $Resp , 'job_id' => $job_id , 'customer_ID'=>$user_id , 'service_Provider_ID'=>$ServiceID,'amount'=>$amount, 'payment_type'=>'job_tip' , 'created_on' => date( 'Y-m-d H:i:s' ) ) );
			if( $Insert )
			{
				$this->WPmodify->response( 1, "Payment Completed");	
			}
			else
			{
				$this->WPmodify->response( 0, "","Please try Again as payment can't be process");		
			}
		}
	}


	public function getPaymentHistory( $request , $ajax = NULL )
	{
	
		global $wpdb;		
		if( !empty( $ajax ) )
		{
			$parameters = $request;
		}
		else
		{
			$parameters = $request->get_params();
		}
		
		
		$user_id 	= $parameters['user_id'];
		$user_type 	= $parameters['user_type'];
		
		if( $user_type == 1  )
		{
			// Customer
			$Results = $wpdb->get_results( "SELECT * FROM ".$wpdb->prefix."payment WHERE customer_ID = '".$user_id."' ORDER BY id DESC " );
			$payment = array();
			if( !empty( $Results ) )
			{
				$Total = 0;
				foreach( $Results as $keys ):
				$Job = $wpdb->get_results( "SELECT * FROM ".$wpdb->prefix."review WHERE job_id = '".$keys->job_id."' " );
				$JobTitle = get_the_title( $keys->job_id );
				if( !empty($Job) )
				{
					$payment[] = array( 'transactionID' => $keys->transactionID , 'amount' => $keys->amount , 'job_title' => $JobTitle , 'payment_type'=> $keys->payment_type , 'rating_by_cust'=> ( empty($Job[0]->rating_by_cust) )? "":$Job[0]->rating_by_cust  , 'rating_by_provider' => ( empty($Job[0]->rating_by_provider) )? "":$Job[0]->rating_by_provider , 'date' => date("F j, Y" , strtotime($keys->created_on)) );
				}
				else
				{
					$payment[] = array( 'transactionID' => $keys->transactionID , 'amount' => $keys->amount , 'job_title' => $JobTitle ,'payment_type'=> $keys->payment_type, 'rating_by_cust'=> "", 'rating_by_provider' => "", 'date' => date("F j, Y" , strtotime($keys->created_on)) );
				}

				$Total =  floatval($Total + $keys->amount);
				endforeach;
			
			}
			$this->WPmodify->response( 1, $payment , "No Error Found !" , $Total);
		}
		elseif( $user_type == 2 )
		{
			//Service Provider
			// Customer
			$Results = $wpdb->get_results( "SELECT * FROM ".$wpdb->prefix."payment WHERE service_Provider_ID = '".$user_id."' AND amout_to_SP != '' " );
			$payment = array();
			$Total = 0;
			if( !empty( $Results ) )
			{
				foreach( $Results as $keys ):
				$Job = $wpdb->get_results( "SELECT * FROM ".$wpdb->prefix."review WHERE job_id = '".$keys->job_id."' " );
				$JobTitle = get_the_title( $keys->job_id );
				if( !empty($Job) )
				{
					$payment[] = array( 'transactionID' => $keys->transactionID , 'amount' => $keys->amout_to_SP , 'job_title' => $JobTitle ,'payment_type'=> $keys->payment_type, 'rating_by_cust'=> ( empty($Job[0]->rating_by_cust) )? "":$Job[0]->rating_by_cust  , 'rating_by_provider' => ( empty($Job[0]->rating_by_provider) )? "":$Job[0]->rating_by_provider , 'date' => date("F j, Y" , strtotime($keys->created_on)) );
				}
				else
				{
					$payment[] = array( 'transactionID' => $keys->transactionID , 'amount' => $keys->amout_to_SP , 'job_title' => $JobTitle ,'payment_type'=> $keys->payment_type, 'rating_by_cust'=> "", 'rating_by_provider' => "", 'date' => date("F j, Y" , strtotime($keys->created_on)) );
				}
				$Total =  floatval( $Total + $keys->amout_to_SP );
				endforeach;
				
			}
			
			$this->WPmodify->response( 1,$payment ,"No Error Found !", floatval($Total));
		}
	}

	/*public function sendInvites( $request , $ajax = NULL )
	{
	
		global $wpdb;		
		if( !empty( $ajax ) )
		{
			$parameters = $request;
		}
		else
		{
			$parameters = $request->get_params();
		}
		$this->WPmodify->checkUserType( $parameters['user_id'] , $parameters['user_type']  );
	}*/

	public function getNotification( $request , $ajax = NULL )
	{
		global $wpdb;		
		if( !empty( $ajax ) )
		{
			$parameters = $request;
		}
		else
		{
			$parameters = $request->get_params();
		}
		
		
		$user_id 	= $parameters['user_id'];
		$user_type 	= $parameters['user_type'];

		$Results = $wpdb->get_results( "SELECT * FROM ".$wpdb->prefix."notification WHERE to_userID = '".$user_id."' ORDER BY  FIELD(is_read,'0','1'),id DESC " );
		
		$Notification = array();
		$is_Read = array();

		if( !empty($Results) )
		{
		//{"noti_message":"new bid","profile_image":"","user_name":"3","date":"Jan 2018"}
			foreach( $Results as $keys ):
			 $ProfileDetails = $this->WPmodify->getProfileDetails( $keys->from_userID );
			 
			 if( !empty( $ProfileDetails[0]->profile_image ) )
			 {
			 	$image = $ProfileDetails[0]->profile_image;
			 }
			 elseif( !empty( $ProfileDetails[0]->photo_url ) )
			 {
			 	$image = $ProfileDetails[0]->photo_url;
			 }
			 else
			 {
			 	$image = '';
			 }
		
				$Notification[] = array( 
				'noti_ID' 		=> $keys->id,
				'noti_type' 	=> $keys->noti_type,
				'related_Id' 	=> $keys->for_ID,
				'noti_message' 	=> $keys->noti_message,
				'profile_image' => $image,
				'user_name' 	=> $ProfileDetails[0]->username,
				'date' 			=> $keys->created_on,
				'is_read' 		=> $keys->is_read
				);
				
				if( $keys->is_read == 0 )
				{
					$is_Read[] = 1;
				}

			endforeach;

			if( isset( $parameters['is_web'] ) )
			{
				return array( 'results' => $Notification , 'count' => count($is_Read) );
			}
			else
			{
				$this->WPmodify->response( 1 , $Notification , "No Error Found !" , count($is_Read) );
			}
			
		}
		else
		{
			if( isset( $parameters['is_web'] ) )
			{
				return array( 'results' => $Notification );
			}
			else
			{
				$this->WPmodify->response( 1 , $Notification );
			}
			
		}

	}



}
new Jobs();