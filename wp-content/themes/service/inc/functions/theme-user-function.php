<?php 
class Users
{

	var $WPmodify;
	public function __construct()
	{
		$this->WPmodify = new WPmodify();		
	}
	
	
	public function user_Registration( $request , $ajax = NULL )
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
		
		$more_img = NULL;
		if( isset($_FILES) )
		{
			$more_img = $this->WPmodify->UploadImg( $_FILES );			
		}
		/* print_R($more_img);
		die(); */

		$role = '';
		if( isset($parameters['user_type']) && $parameters['user_type'] == 1 )
		{
			$role = 'customer';
		}
		if( isset($parameters['user_type']) && $parameters['user_type'] == 2 )
		{
			$role = 'service';
		}

		
		$title 					= (isset($parameters['title']))?$parameters['title']:'';
		$first_name 			= $parameters['first_name'];
		$last_name 				= $parameters['last_name'];
		$password 				= $parameters['password'];
		$email 					= $parameters['email'];
		$username 				= $parameters['username'];
		$phone_number 			= $parameters['phone_number'];
		$street_address 		= $parameters['street_address'];
		$city 					= $parameters['city'];
		$state 					= $parameters['state'];
		$postcode 				= (isset($parameters['postcode']))?$parameters['postcode']:'';
		$facebook_id 			= (isset($parameters['facebook_id']))? $parameters['facebook_id'] : '';
		$google_id 				= (isset($parameters['google_id']))? $parameters['google_id'] : '';
		$device_type 			= (isset($parameters['device_type']))?$parameters['device_type']:'';
		$device_token 			= (isset($parameters['device_token']))?$parameters['device_token']:'';
		$user_type 				= $parameters['user_type'];
		$overview 				= (isset($parameters['overview']))?$parameters['overview']:'';
		$longs 					= $parameters['longs'];
		$lat 					= $parameters['lat'];
		$dob 					= (isset($parameters['dob']))?$parameters['dob']:'';
		$overview 				= $parameters['overview'];
		$push_noti 				= (isset($parameters['push_noti']))?$parameters['push_noti']:'';
		$business_type 			= $parameters['business_type'];
		$user_activation_key	= time().base64_encode($email);
	
		if( $parameters['user_type'] == 1 )
		{
			$business_name			= NULL;
			$service_type 			= NULL;
		}
		if( $parameters['user_type'] == 2 )
		{
			$business_name			= $parameters['business_name'];			
			$service_type			= $parameters['service_type'];			
		}


		/* Creating Wordpress User  */
		$userdata = array(
		'user_pass'  			=> $password ,	
		'user_login'   			=> $username ,
		'first_name' 			=> $first_name ,  
		'last_name' 			=> $last_name ,  
		'user_email'   			=> $email ,  
		'display_name'  		=> $first_name ,  
		'user_registered' 	 	=> date('Y-m-d H:i:s') ,  
		'role'  				=> $role  		
		);

		
		$user_id = wp_insert_user( $userdata ) ; 
		wp_set_password( $password, $user_id );

		if( isset($parameters['user_type']) && $parameters['user_type'] == 1 )
		{
			$braintree_ID = create_BrainTreecustomer( $first_name , $email );
		}		
		
		/* Inserting Data into registartion Table of Profile Use */
		$Data = array( 
		'user_id'=>$user_id,
		'user_type'=>$user_type,
		'first_name'=>$first_name,
		'last_name'=>$last_name,
		'username'=>$username,		
		'street_address'=>$street_address,
		'city'=>$city,
		'phone_number'=>$phone_number,
		'state'=>$state,
		'postcode'=>$postcode,		
		'email'=>$email,
		'title'=>$title,
		'business_name'=>$business_name,
		'lat'=>$lat,
		'longs'=>$longs,
		'dob'=>$dob,
		'service_type'=>$service_type,
		'profile_image'		=>	$more_img,
		'facebook_id'=>$facebook_id,
		'google_id'=>$google_id,
		'device_token'=>$device_token,
		'device_type'=>$device_type,
		'overview'			=>	$overview,
		'push_noti'			=>	'yes',
		'braintree_ID'		=>	(isset($braintree_ID))?$braintree_ID:'' ,
		'business_type'		=>	$business_type ,
		'user_activation_key'=>$user_activation_key,
		'modified_on'=>date( 'd-m-Y h:i:s' ),
		'created_on'=>date( 'd-m-Y h:i:s' )

		);

		if( $parameters['user_type'] == 1 )
		{
			$Data['user_activated'] = 1;
		}
		if( $parameters['user_type'] == 2 )
		{
			$Data['user_activated'] = 1;
		}
		
		$wpdb->insert( $wpdb->prefix.'profiledetails', $Data );
		$wpdb->flush();		
	
		/* 	print_R($Data);		die(); */
			$to 		= $email;
			$subject 	= 'Account Verification';
			$body		= 'The email body content '.site_url().'/?verifyAccount='.$user_activation_key;
			$headers 	= array('Content-Type: text/html; charset=UTF-8');
			$mail 		=  wp_mail( $to, $subject, $body, $headers );
			if( $mail )
			{
				$this->WPmodify->response( 1, 'Could you please check inbox for verification link' , 'No Error Found' );
			} 
			else
			{
				$this->WPmodify->response( 0, 'Please ask Admin to Activate your account as Activation Mail was not sent ' , 'Mail Sent Error' );
			}		
	}

	public function socialLogin( $request , $ajax = NULL )
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

		
		if ( email_exists( $parameters['email'] ) ) 
		{
			$user = get_user_by('email', $parameters['email']);

			if (isset($parameters['ajax']) && !empty($user)) 
			{
					clean_user_cache($user->ID);
			        wp_clear_auth_cookie();
			        wp_set_current_user( $user->ID );
			        wp_set_auth_cookie( $user->ID , true, false);

			        update_user_caches($user);

			        if(is_user_logged_in())
			        {
			            $this->WPmodify->web_response( 'success', 'User Login Successfully' , site_url("/my-jobs/") );	
			        }
			}
			else
			{
						// Checking User Role

				if( isset($parameters['user_type']) && $parameters['user_type'] == 1 && $user->roles[0] != 'customer' )
				{
					$this->WPmodify->response( 0, NULL , 'Login from wrong portal ! ' );	
				}
				if( isset($parameters['user_type']) && $parameters['user_type'] == 2 && $user->roles[0] != 'service' )
				{
					$this->WPmodify->response( 0, NULL , 'Login from wrong portal ! ' );
				}
				

				$this->WPmodify->updateDeviceToken( $parameters['device_token'] , $parameters['device_type'] , $user->ID );
				
				$request = new WP_REST_Request( 'GET', '/users/getprofile' );
				$request->set_query_params(array(
			  		'user_id' => $user->ID				  
				));
				//header("Authenticate-Token: $accessToken");
				$this->getprofile($request);	
			}
		}

		if( $parameters['socialtype'] == 'fb' )
		{
			$facebook_id = $parameters['socialLoginId'];
			$google_id = "";
		}
		elseif( $parameters['socialtype'] == 'google' )
		{
			$google_id = $parameters['socialLoginId'];
			$facebook_id = "";
		}

		

		$role = '';
		if( isset($parameters['user_type']) && $parameters['user_type'] == 1 )
		{
			$role = 'customer';
		}
		if( isset($parameters['user_type']) && $parameters['user_type'] == 2 )
		{
			$role = 'service';
		}

		
		$title 					= (empty($parameters['title']))? "" : $parameters['title'];
		$first_name 			= (empty($parameters['first_name']))? "" : $parameters['first_name'];
		$last_name 				= (empty($parameters['last_name']))? "" : $parameters['last_name'];
		$password 				= (empty($parameters['password']))? "" : $parameters['password'];
		$email 					= (empty($parameters['email']))? "" : $parameters['email'];
		$username 				= (empty($parameters['username']))? "" : $parameters['username'];
		$phone_number 			= (empty($parameters['phone_number']))? "" : $parameters['phone_number'];
		$street_address 		= (empty($parameters['street_address']))? "" : $parameters['street_address'];
		$city 					= (empty($parameters['city']))? "" : $parameters['city'];
		$state 					= (empty($parameters['state']))? "" : $parameters['state'];
		$postcode 				= (empty($parameters['postcode']))? "" : $parameters['postcode'];
		$device_type 			= (empty($parameters['device_type']))? "" : $parameters['device_type'];
		$device_token 			= (empty($parameters['device_token']))? "" : $parameters['device_token'];
		$user_type 				= (empty($parameters['user_type']))? "" : $parameters['user_type'];
		$overview 				= (empty($parameters['overview']))? "" : $parameters['overview'];
		$longs 					= (empty($parameters['longs']))? "" : $parameters['longs'];
		$lat 					= (empty($parameters['lat']))? "" : $parameters['lat'];
		$dob 					= (empty($parameters['dob']))? "" : $parameters['dob'];
		$photo_url 				= (empty($parameters['photo_url']))? "" : $parameters['photo_url'];
		$more_img 				= "";
		$overview 				= (empty($parameters['overview']))? "" : $parameters['overview'];
		$push_noti 				= (empty($parameters['push_noti']))? "" : $parameters['push_noti'];
		$user_activation_key	= "";

		if( $parameters['user_type'] == 1 )
		{
			$business_name			= NULL;
			$service_type 			= NULL;
		}
		if( $parameters['user_type'] == 2 )
		{
			$business_name			= (empty( $parameters['business_name'] ))? "" : $parameters['business_name'];	
			$business_type			= (empty( $parameters['business_type'] ))? "" : $parameters['business_type'];	
			$service_type			= (empty( $parameters['service_type'] ))? "" : $parameters['service_type'];			
		}

		$username = $first_name;
    	while( username_exists( $username ) )
    	{
    		$username = $username.rand(0, 9999);
    	}  


		/* Creating Wordpress User  */
		$userdata = array(
		'user_pass'  			=> $password ,	
		'user_login'   			=> $username ,
		'first_name' 			=> $first_name ,
		'user_email'   			=> $email,  
		'display_name'  		=> $first_name ,  
		'user_registered' 	 	=> date('Y-m-d H:i:s') ,  
		'role'  				=> $role  		
		);

		
		$user_id = wp_insert_user( $userdata ) ; 
		wp_set_password( $password, $user_id );

		if( isset($parameters['user_type']) && $parameters['user_type'] == 1 )
		{
			$braintree_ID = create_BrainTreecustomer( $first_name , $email );
		}		
		
		/* Inserting Data into registartion Table of Profile Use */
		$Data = array( 
		'user_id'				=> $user_id,
		'user_type'				=> $user_type,
		'first_name'			=> $first_name,
		'last_name'				=> $last_name,
		'username' 				=> $username,		
		'street_address'		=> $street_address,
		'city'					=> $city,
		'phone_number'			=> $phone_number,
		'state'					=> $state,
		'postcode'				=> $postcode,		
		'email'					=> $email,
		'title'					=> $title,
		'business_name'			=> $business_name,
		'lat'					=> $lat,
		'longs'					=> $longs,
		'dob'					=> $dob,
		'photo_url'				=> $photo_url,
		'service_type'			=> $service_type,
		'profile_image'			=> $more_img,
		'facebook_id'			=> $facebook_id,
		'google_id'				=> $google_id,
		'device_token'			=> $device_token,
		'device_type'			=> $device_type,
		'overview'				=> $overview,
		'push_noti'				=> 'yes',
		'braintree_ID'			=> (isset($braintree_ID))?$braintree_ID:'' ,
		'business_type'			=> $business_type ,
		'user_activation_key'	=> $user_activation_key,
		'user_activated'		=> 1,
		'modified_on'			=> date( 'd-m-Y h:i:s' ),
		'created_on'			=> date( 'd-m-Y h:i:s' )

		);
		
		$wpdb->insert( $wpdb->prefix.'profiledetails', $Data );
		$wpdb->flush();


		if (isset($parameters['ajax']) && !empty($user_id)) 
			{
					$user = get_user_by('email', $parameters['email']);
					clean_user_cache($user_id);
			        wp_clear_auth_cookie();
			        wp_set_current_user( $user_id );
			        wp_set_auth_cookie( $user_id , true, false);

			        update_user_caches($user);

			        if(is_user_logged_in())
			        {

			            $this->WPmodify->response( 1, 'User Login Successfully');	
			        }
			}
			else
			{
				$request = new WP_REST_Request( 'GET', '/users/getprofile' );
				$request->set_query_params(array(
				  		'user_id' => $user_id				  
				));			
				$this->getprofile($request);	
			}
	}

	public function user_SignIn ( $request , $ajax = NULL )
	{
		if( !empty( $ajax ) )
		{
			$parameters = $request;
		}
		else
		{
			$parameters = $request->get_params();
		}

		if(isset($parameters['SignIn_wpnonce']))
			{				
				$info = array();
				if (filter_var($parameters['username_email'], FILTER_VALIDATE_EMAIL)) 
				{
					 $user = get_user_by('email', $parameters['username_email']);	
					 if( empty($user) )
					 {
					 	$this->WPmodify->web_response( 'error', 'Email does\'nt Exists ! ',200 );
					 }				
				} 
				else 
				{
					$user = get_user_by('login', $parameters['username_email']);					
				}

				if( $parameters['user_type'] == 1 && $user->roles[0] != 'customer') // Customer
				{
					$this->WPmodify->web_response( 'error', 'Invalid Login ! ',200 );
				}

				if( $parameters['user_type'] == 2 && $user->roles[0] != 'service') // Customer
				{
					$this->WPmodify->web_response( 'error', 'Invalid Login ! ',200 );
				}

				if( empty( $user ) )
				{
					$this->WPmodify->web_response( 'error', 'Invalid Credentials ! ',200 );
				}

				$info['user_login'] 	= 	$user->data->user_login;
				$info['user_password'] 	= $parameters['password'] ;
				
				 $user_signon = wp_signon( $info );
				if ( is_wp_error($user_signon) )
				{
					$this->WPmodify->web_response( 'error', 'Invalid Credentials ! ',200 );				
				} 
				else 
				{
					$this->WPmodify->web_response( 'success', 'User Login Successfully' , site_url("/my-jobs/") );
				}
				
			}
			else
			{

				if (filter_var($parameters['username_email'], FILTER_VALIDATE_EMAIL)) 
				{
					 $user = get_user_by('email', $parameters['username_email']);					
				} 
				else 
				{
					$user = get_user_by('login', $parameters['username_email']);					
				}

				if ( $user && wp_check_password( $parameters['password'], $user->data->user_pass, $user->ID) )
				{
					if( isset($parameters['user_type']) && $parameters['user_type'] == 1 )	
					{
						$role = 'customer';	
					}
					if( isset($parameters['user_type']) &&  $parameters['user_type'] == 2 )	
					{
						$role = 'service';	

					}

					if( $user->roles[0] != $role )
					{
						$this->WPmodify->response( 0, NULL , 'Invalid Login ! ' );
					}					

					$this->WPmodify->updateDeviceToken( $parameters['device_token'] , $parameters['device_type'] , $user->ID );


					$accessToken = $this->WPmodify->createToken( $user->ID , $user->user_login , $parameters['device_token'] , $parameters['device_type'] );

					$request = new WP_REST_Request( 'GET', '/users/getprofile' );
					$request->set_query_params(array(
				  		'user_id' => $user->ID				  
					));
					header("Authenticate-Token: $accessToken");
					$this->getprofile($request);	

				}			
				else
				{
					$this->WPmodify->response( 0, NULL , 'Invalid Credentials ! ' );
				}
				//user_password
				
			}		
	}

	public function getprofile( WP_REST_Request $request )
	{
		$parameters = $request->get_params();		
		$UserID 	= $parameters['user_id'];

		global $wpdb;
	
		$results = $wpdb->get_results( 'SELECT id,user_id,first_name,last_name,username,street_address,city,state,postcode,email,title,profile_image,business_name,service_type,overview,user_type,phone_number,push_noti,overview,lat,longs,dob,braintree_ID,business_type,photo_url FROM '.$wpdb->prefix.'profiledetails WHERE user_id ='.$UserID, OBJECT );

		if( !empty( $results[0]->service_type ) )
		{
			//$listing = $this->WPmodify->getTermsArray( $results[0]->service_type );			
			$results[0]->service_type = $this->WPmodify->getTermsArray( $results[0]->service_type );	
		}
		else
		{
			$results[0]->service_type = array();
		}

		if( empty(  $results[0]->postcode ) )
		{
			$results[0]->postcode = "";
		}

		if( empty(  $results[0]->business_type ) )
		{
			$results[0]->business_type = "";
		}

		if( empty(  $results[0]->braintree_ID ) )
		{
			$results[0]->braintree_ID = "";
		}

		$Work =  $this->WPmodify->getWorkHistory( $UserID , $results[0]->user_type );
		$rating = $this->WPmodify->getUserRating( $UserID , $results[0]->user_type );
		$successrate = $this->WPmodify->getSuccessRate( $UserID );

		$Port = $wpdb->get_results( 'SELECT * FROM '.$wpdb->prefix.'portfolio WHERE user_id ='.$UserID, OBJECT );		
		
		if( !empty($Port) )
		{
			$Urls = array();
			foreach( $Port as $keys  ):

				$results[0]->portfolio[] = array( 'id'=>$keys->id,'url'=>site_url($keys->image) );

			endforeach;
		}
		else
		{
			$results[0]->portfolio = array();
		}

		if( $results[0]->profile_image != NULL )
		{
			if( $results[0]->user_type == 1 ) //customer
			{
				unset( $results[0]->business_name );
				$results[0]->profile_image = site_url('/').$results[0]->profile_image;
			}
			
			if( $results[0]->user_type == 2 ) // provider
			{
				$results[0]->profile_image = site_url('/').$results[0]->profile_image;
			}			
			
		}
		else
		{
			$results[0]->profile_image = get_avatar_url( $UserID );
		}

		if( empty($results[0]->photo_url) )
		{
			$results[0]->photo_url = "";
		}

			$results[0]->work_history = $Work;
			$results[0]->rating 	  = $rating;
			$results[0]->success_rate = $successrate['success_rate'];

		unset( $results[0]->id );	
		
		if( isset( $parameters['return'] ) && $parameters['return'] == 1  )
		{
			return $results[0];
		}
		else
		{
			$this->WPmodify->response( 1, $results[0] , 'No Error Found' );
		}
			
	}

	public function forgotPassword(  WP_REST_Request $request )
	{
		global $wpdb;
		$parameters = $request->get_params();
				
		if ( ! email_exists( $parameters['email'] ) ) 
		{
			if(isset($parameters['ForgotPassword_wpnonce']))
			{
				$this->WPmodify->web_response( 'error','Email ID does not Exists !');
			}
			else
			{
				$this->WPmodify->response( 0, NULL , 'Email ID does not Exists !' );
			}
		}
		else
		{
		
		/* Generating Reset Tokken */
		$user = get_user_by( 'email', $parameters['email'] );	
		$password = wp_generate_password( 8, false );
		wp_set_password( $password, $user->ID );	
		
		$DirPath = getcwd();	
		$file 			= $DirPath.'/wp-content/themes/service/email-template/EmailTemplate-ForgotPassword.html';
		$logo 			= $DirPath.'/wp-content/themes/service/assets/images/logo.png';
		$message 		= 'Your New Password  : '.$password;
		$search 		= array('[logo]','[username]','[message]');
		$replace_emp 	= array( $logo,$user->firstname,$message);
		$mail = $this->WPmodify->SendEmail($parameters['email'],'Reset Password',$file,$search,$message,$replace_emp);
			if( $mail )
			{
				if(isset($parameters['ForgotPassword_wpnonce']))
				{
					$this->WPmodify->web_response( 'success','Please check your Email for New Password');
				}
				else
				{
					$this->WPmodify->response( 1, NULL , 'Please check your Email for New Password' );
				}
			}
			else
			{
				$this->WPmodify->response( 0, NULL , 'Please contact site Admin as we can\'t send you email !' );
			}
		
		}
	}

	public function UpdateProfile( WP_REST_Request $request )
	{
		
		global $wpdb;
		$parameters = $request->get_params();
		$more_img = NULL;
		if( isset($_FILES) )
		{
			$more_img = $this->WPmodify->UploadImg( $_FILES );			
		}
		
		$title 					= $parameters['title'];
		$first_name 			= $parameters['first_name'];
		$last_name 				= $parameters['last_name'];		
		$phone_number 			= $parameters['phone_number'];
		$street_address 		= $parameters['street_address'];
		$city 					= $parameters['city'];
		$state 					= $parameters['state'];
		$postcode 				= $parameters['postcode'];		
		$overview 				= $parameters['overview'];
		$longs 					= $parameters['longs'];
		$lat 					= $parameters['lat'];		
		$overview 				= $parameters['overview'];
		$user_id 				= $parameters['user_id'];
		$dob 				= $parameters['dob'];
	
		
		/* Updating Data into registartion Table of Profile Use */
		$Data = array( 		
		'title'=>$title,		
		'first_name'=>$first_name,
		'last_name'=>$last_name,						
		'phone_number'=>$phone_number,
		'dob'=>$dob,
		'modified_on'=>date( 'd-m-Y h:i:s' )
		);
		
		if( isset($_FILES) && $more_img != NULL )
		{
			$Data['profile_image'] = $more_img;
		}


		/*if( isset( $parameters['user_type'] ) && $parameters['user_type'] == 2 )
		{
			$Data['business_name']	= $parameters['business_name'];	
			$Data['overview']		= $parameters['overview'];				
		}
*/
		if( isset( $parameters['user_type'] ) && $parameters['user_type'] == 1 )
		{			
			$Data['street_address']	= $street_address;	
			$Data['city']			= $city;	
			$Data['state']			= $state;	
			$Data['postcode']		= $postcode;	
			$Data['lat']			= $lat;	
			$Data['longs']			= $longs;	
		}elseif( isset( $parameters['business_type'] ) && $parameters['business_type'] == 1 && $parameters['user_type'] == 2 )
		{			
			$Data['street_address']	= $street_address;	
			$Data['city']			= $city;	
			$Data['state']			= $state;	
			$Data['postcode']		= $postcode;	
			$Data['lat']			= $lat;	
			$Data['longs']			= $longs;	
		}

		
		update_user_meta($user_id, 'first_name', $first_name);
		update_user_meta($user_id, 'last_name', $last_name);		
		$wpdb->update( $wpdb->prefix.'profiledetails', $Data,	array( 'user_id' => $user_id ));
		$wpdb->flush();

		
		if(isset($parameters['UpdateProfile_wpnonce']))
		{
			$this->WPmodify->web_response( 'success', 'Details Updated Successfully' , 'No Error Found' );
		}
		else
		{
			$this->WPmodify->response( 1, 'Details Updated Successfully' , 'No Error Found' );			
		}	
		
	}

	public function ResetPassword( $request , $ajax = NULL )
	{
		if( !empty( $ajax ) )
		{
			$parameters = $request;
		}
		else
		{
			$parameters = $request->get_params();
		}

		$user = get_user_by( 'id', $parameters['user_id'] );
					if ( $user && wp_check_password( $parameters['previous_pass'], $user->data->user_pass, $user->ID) )
					{
						if( $parameters['new_pass'] == $parameters['confirm_pass'] )
						 {
						 	 wp_set_password( $parameters['new_pass'], $parameters['user_id'] );
						 	
					 		if( isset( $parameters["UpdatePassword_wpnonce"] ) )
								{
									$this->WPmodify->web_response( 'success', 'Password Updated Successfully');	
								}
							else
								{
									$this->WPmodify->response( 1, 'Password Updated Successfully' );
								}								
						 	
						 	
						 }
						 else
						 {
						 	if( isset( $parameters["UpdatePassword_wpnonce"] ) )
								{
									$this->WPmodify->web_response( 'error', 'New and Confirm Password not matched !' , 'Password Not Updated' );	
								}
								else
								{
									$this->WPmodify->response( 0, NULL , 'New and Confirm Password not matched !' );
								}	
						 }
					}
					else
					{
						if( isset( $parameters["UpdatePassword_wpnonce"] ) )
						{
							$this->WPmodify->web_response( 'error', 'Previous Password Does\'nt match' );	
						}
						else
						{
							$this->WPmodify->response( 0, NULL , 'Previous Password Does\'nt match' );
						}

					}
	}

	public function UpdateBusinessProfile( $request , $ajax = NULL )
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

		$this->WPmodify->checkUserType( $parameters['user_id'] ,$parameters['user_type'] );
		

		$Data = array(

			"business_name"		=> $parameters['business_name'],	
			"phone_number"		=> $parameters['phone_number'],	
			"service_type"		=> $parameters['service_type'],	
			"street_address"	=> $parameters['street_address'],	
			"city"				=> $parameters['city'],	
			"state"				=> $parameters['state'],				
			"lat"				=> $parameters['lat'],	
			"longs"				=> $parameters['longs'],	
			"overview"			=> $parameters['overview']			
		);

		$wpdb->update( $wpdb->prefix.'profiledetails', $Data, array( 'user_id' => $parameters['user_id']  ) );

		$this->WPmodify->response( 1, 'Business Information Updated Successfully' );

	}

	public function Portfolio( $request , $ajax = NULL  )
	{

		global $wpdb;
		if( !empty( $ajax ) )
		{
			$parameters = $request;
		}
		else
		{
			//$this->WPmodify->verifyToken();
			$parameters = $request->get_params();
		}

		$FILES = $request->get_file_params();
		
		if( empty($FILES) )
		{
			$this->WPmodify->response( 0, '','Please Select Images to upload in your Portfolio' );
		}

		if( count($FILES) > 4 )
		{
			$this->WPmodify->response( 0, '','Max 4 Images are allowed to Upload' );			
		}

		$Results = $this->WPmodify->PortfolioImage( $FILES ,$parameters['user_id'] );

		$GetVal = json_decode($Results);
		
		if( empty( $GetVal->error ))
		{
			$this->WPmodify->response( 1, $GetVal->success ,'Portfolio Updated Successfully' );
		}
		else
		{
			$ErrCount = count($GetVal->error);
			$this->WPmodify->response( 1, $GetVal->success , "$ErrCount Images were not uploaded");
		}
	}

	public function getPortfolio( $request , $ajax = NULL )
	{
		global $wpdb;
		if( !empty( $ajax ) )
		{
			$parameters = $request;
		}
		else
		{
			//$this->WPmodify->verifyToken();
			$parameters = $request->get_params();
		}

		$UserID = $parameters['user_id'];

		$results = $wpdb->get_results( 'SELECT * FROM '.$wpdb->prefix.'portfolio WHERE user_id ='.$UserID.' ORDER BY id DESC ', OBJECT );
		
		
		if( !empty($results) )
		{
			$Urls = array();
			foreach( $results as $keys  ):

				$Urls[] = array( 'id'=>$keys->id,'url'=>site_url($keys->image) );

			endforeach;
		}
		else
		{
			$Urls = array();
		}
		
		$this->WPmodify->response( 1, $Urls );

	}

	public function deletePortfolio ( $request , $ajax = NULL  )
	{
		global $wpdb;
		if( !empty( $ajax ) )
		{
			$parameters = $request;
		}
		else
		{
			//$this->WPmodify->verifyToken();
			$parameters = $request->get_params();
		}

		$query = "DELETE FROM ".$wpdb->prefix."portfolio WHERE `user_id`= ".$parameters['user_id']." AND `id` IN ( ".$parameters['image_ids']." )";
		
		$results = $wpdb->query( $wpdb->prepare($query) );			
				
			if(!empty($results))
				{
					$this->WPmodify->response( 1, "Image deleted Successfully" );				
				}
			else
				{
					$this->WPmodify->response( 0, ""," Image is not deleted !" );
				}
	}

	public function getServiceProvider( $request , $ajax = NULL )
	{
			global $wpdb;
		if( !empty( $ajax ) )
		{
			$parameters = $request;
		}
		else
		{
			//$this->WPmodify->verifyToken();
			$parameters = $request->get_params();
		}
		//pre( $parameters );
		if( isset( $parameters['user_id'] ) && !empty( $parameters['user_id'] ) )
		{
			$this->WPmodify->checkUserType( $parameters['user_id'] ,1 );
			$user_ID = $parameters['user_id'];
			$Current_User = $wpdb->get_results( "SELECT lat, longs FROM ".$wpdb->prefix."profiledetails WHERE user_id = '".$user_ID."' " );
		}
		
		


		if( !empty ( $parameters ) )
		{

			$where = '';
			if( isset( $parameters[ 'service_type' ]) && !empty( $parameters[ 'service_type' ] ))
				{
					$val = str_replace(',', '|', $parameters[ 'service_type' ]);		

					$where .= ' AND service_type REGEXP "'.$val.'" ';
				}
			if( isset( $parameters[ 'service_search' ]) && !empty( trim($parameters[ 'service_search' ]) ))
				{
					$val = $parameters[ 'service_search' ];		

					$where .= ' AND (username LIKE "%'.$val.'%" OR first_name LIKE "%'.$val.'%")';
				}
			
			$results = $wpdb->get_results( 'SELECT * FROM '.$wpdb->prefix.'profiledetails WHERE user_type = 2 '.$where, OBJECT );
		}
		else
		{
			$results = $wpdb->get_results( 'SELECT * FROM '.$wpdb->prefix.'profiledetails WHERE user_type = 2', OBJECT );
		}
		
		//pre( $results );
		$Details = array();
		if( !empty( $results ) ):
			
			foreach( $results as $keys  ):
				//pre( $keys )
				

				if( isset( $parameters[ 'distance' ]) && !empty( $parameters[ 'distance' ] ) && isset( $parameters[ 'lat' ] ) && isset( $parameters[ 'longs' ] ) && !empty( $parameters[ 'lat' ] ) && !empty( $parameters[ 'longs' ] ))
				{
					$miles = $this->WPmodify->distance($keys->lat, $keys->longs,$parameters[ 'lat' ] , $parameters[ 'longs' ], 'M');
					
					if( $miles < $parameters[ 'distance' ] ):						
						$Details[] = $this->ServiceProvider( $keys );
					endif;
				}
				elseif( isset( $parameters[ 'distance' ]) && !empty( $parameters[ 'distance' ] ) && !empty( $Current_User[0]->lat ) && !empty( $Current_User[0]->longs )  )
				{
					$miles = $this->WPmodify->distance($keys->lat, $keys->longs,$Current_User[0]->lat , $Current_User[0]->longs, 'M');
					
					if( $miles < $parameters[ 'distance' ] ):						
						$Details[] = $this->ServiceProvider( $keys );
					endif;
				}
				else
				{
					$Details[] = $this->ServiceProvider( $keys );
				}

			endforeach;	

			

			if( isset( $parameters['rating'] ) && $parameters['rating']!='')
			{
				$sortArray = array(); 

				foreach($Details as $person){ 
					
						foreach($person as $key=>$value){				  		 
				  		    
				        if(!isset($sortArray[$key])){ 
				            $sortArray[$key] = array(); 
				        }

				        $sortArray[$key][] = $value; 
				    	} 
					
				   
				} 

				$orderby = "rating"; //change this to whatever key you want from the array 

				array_multisort($sortArray[$orderby],SORT_DESC,$Details); 
				$finalArray=array();
				foreach ($Details as $key => $value) 
				{
					if($value['rating']>= $parameters['rating'] )
					{
						$finalArray[]=$value;
					}					
				}

				
			}
			else
			{
				$finalArray = $Details;
			}



			/* Success Rate */
			if( isset( $parameters['sortBy'] ) && $parameters['sortBy']!='')
			{

				$photo = array();
	           foreach ($finalArray as $key => $row)
	           {
	               $photo[$key] = $row['percentage'];                           
	           } 
	           array_multisort($photo, SORT_DESC, $finalArray); 
	       }
			


			$TotalCount = count( $finalArray );
			
			$default = 10;
			if(isset($parameters['page']) && $parameters['page'] >= 1)
			{
				$count = $parameters['page'].'0';
				//$limit = $count;
				$Offset = $count - $default; 
			}
			else
			{
				$Offset = 0;
			}

			$finalArray = array_slice( $finalArray,$Offset, $default ); 
		endif;		
		$this->WPmodify->response( 1, ( empty($finalArray) )? array(): $finalArray  , '' , $TotalCount);
		

	}

	private function ServiceProvider( $Data )
	{
		$TermsID = explode(',',$Data->service_type);
		
		$terms = '';
		foreach( $TermsID as $IDS )
		{
			
			if( end($TermsID) == $IDS )
			{
				$Details = get_term_by('id', $IDS, 'jobs_category');
				if( !empty( $Details ) ):
					$terms .= $Details->name;
				endif;	
			}
			else
			{
				$Details = get_term_by('id', $IDS, 'jobs_category');
				if( !empty( $Details ) ):
					$terms .= $Details->name.', ';
				endif;	
			}
			
		}
		$image = (empty($Data->profile_image)) ? get_avatar_url( $Data->user_id )  : site_url().'/'.$Data->profile_image;
		$rating = $this->WPmodify->getUserRating( $Data->user_id , 2 );
		$successrate = $this->WPmodify->getSuccessRate( $Data->user_id );
		$profile = array(
				'user_id' =>$Data->user_id,
				'first_name' =>$Data->first_name,
				'last_name' =>$Data->last_name,
				'username' =>strtolower( str_replace(' ', '-',$Data->username)),
				'street_address' =>$Data->street_address,
				'profile_image' =>$image,
				'service_type' =>$terms,
				'rating' => $rating,
				'success_rate'=>$successrate['success_rate'],
				'percentage'=>$successrate['percentage']			
		);

		return $profile;
	}

	public function PushNotificationSetting( WP_REST_Request $request )
	{
		//$this->WPmodify->verifyToken();
		$parameters = $request->get_params();

		global $wpdb;

		if( $parameters['push_noti'] == 'yes' || $parameters['push_noti'] == 'no' ):
			$update = $wpdb->update( $wpdb->prefix.'profiledetails', array( 'push_noti' => $parameters['push_noti']),	array( 'user_id' => $parameters['user_id'] ));
				$wpdb->flush();

			
			if( $update )
			{
					$this->WPmodify->response( 1, 'Details Updated Successfully' , 'No Error Found !' );	
			}
			else
			{
					$this->WPmodify->response( 0, '' , 'Please Try Again !' );	
			}
		else:
			$this->WPmodify->response( 0, '' , 'Invalid Parameters !' );
		endif;
		
	}
	
	public function sendMessage( $request , $ajax = NULL )
	{
		global $wpdb;
		if( !empty( $ajax ) )
		{
			$parameters = $request;
		}
		else
		{
			//$this->WPmodify->verifyToken();
			$parameters = $request->get_params();
		}

		$Fromuser_id = $parameters['Fromuser_id'];
		$Touser_id   = $parameters['Touser_id'];

		$FILES = $request->get_file_params();
		trim( $parameters['message'] );

		if(  empty( $FILES ) &&  empty( $parameters['message'] ))
		{
			$this->WPmodify->response( 0,  "", 'Some fields were left blank' );
		}



		if( !empty( $parameters['message'] ) )
		{
			$message     = $parameters['message'];			
		}
		else
		{
			$message     = "";
		}
		
		$Exists_Fromuser_id = get_userdata( $Fromuser_id );
		$Exists_Touser_id = get_userdata( $Touser_id );

		if ( $Exists_Fromuser_id === false ) 
		{
		 	$this->WPmodify->response( 0,  $Exists_Fromuser_id, 'User ID Does\'nt Exists' );  
		}

		if ( $Exists_Touser_id === false ) 
		{
		   $this->WPmodify->response( 0,  $Touser_id, 'User ID Does\'nt Exists' );
		}

		if( empty($FILES) )
		{
			$attachment = "";
		}
		else
		{
			$attachment = $this->WPmodify->UploadFile( $FILES );
		}		

		

		$conv_IDs = $this->getConversionID( $Fromuser_id , $Touser_id );
		$Date = date( 'd-m-Y h:i a' );
		$Data = array( 
				'conv_IDs'		 => $conv_IDs ,
				'from_userID'    => $Fromuser_id,
				'to_userID'  	 => $Touser_id,
				'message'   	 => $message,
				'attachment'  	 => $attachment,
				'is_read'   	 => 0,
				'createdOn'  	 => $Date
			 );
		
		$wpdb->insert( $wpdb->prefix."message" , $Data);
		$InsertID = $wpdb->insert_id;
		
		$Update = $wpdb->update( $wpdb->prefix.'conversationids' , array( 'modified_date' => $Date ) , array( 'id' =>  $conv_IDs ) );
		$wpdb->flush();
		
		// Notification && Push Notification
		$FromuserProfile = $this->WPmodify->getProfileDetails( $Fromuser_id );
		$ToProfile = $this->WPmodify->getProfileDetails( $Touser_id );
		
		if( isset( $FromuserProfile ) && !empty( $FromuserProfile ) )
			{
				$senderDetails = array();
				$senderDetails['user_id'] = $FromuserProfile[0]->user_id;
				$senderDetails['username'] = $FromuserProfile[0]->username;
				$senderDetails['profile_image'] = $FromuserProfile[0]->profile_image;				
			}

		// Sending Device Push Notification
		$Noti_message = "You have received a new message from ".$FromuserProfile[0]->first_name.' '.$FromuserProfile[0]->last_name;	
		
		$this->WPmodify->sendPushNotification( $ToProfile[0]->user_id , $Noti_message , 'new_message' , $conv_IDs, $senderDetails  );				

		// Inserting Notification in the table 
		 $this->WPmodify->insertNotification( $FromuserProfile[0]->user_id , $ToProfile[0]->user_id , 'new_message' , $conv_IDs , $Noti_message  );//$FromuserID , $TouserID , $notiType , $for_ID , 

		if( empty($FILES ) )
		{
			$Return = array( 'attachment' => $attachment , 'message' => $message ,'createdOn'  	 => $Date );
		}
		else
		{
			$link = site_url( $attachment  );

			$Return = array( 'attachment' => $link , 'message' => $message ,'createdOn'  	 => $Date );	
		}		
		
		$this->WPmodify->response( 1, $Return , 'Message Send Successfully' );
	}


	private function getConversionID( $FromUserID , $ToUserID )
	{
		global $wpdb;

		$results = $wpdb->get_results( "SELECT * FROM ".$wpdb->prefix."conversationids WHERE  ( Fromuser_id = '".$FromUserID."' AND Touser_id = '".$ToUserID."' ) OR  ( Fromuser_id = '".$ToUserID."' AND Touser_id = '".$FromUserID."' )  " );
		
		if( empty( $results ) )
		{
			$Data = array( 
				'Fromuser_id' => $FromUserID ,
				'Touser_id'   => $ToUserID,
				'created_on'   => date( 'd-m-Y h:i:s' )
			 );
			$wpdb->insert( $wpdb->prefix."conversationids" , $Data);
			$ConvID = $wpdb->insert_id;
		}
		else
		{
			$ConvID	= $results[0]->id;
		}
		return $ConvID;
	}

	public function getMessageThreads( $request , $ajax = NULL )
	{
		global $wpdb;
		if( !empty( $ajax ) )
		{
			$parameters = $request;
		}
		else
		{
			//$this->WPmodify->verifyToken();
			$parameters = $request->get_params();
		}

		$default = 10;
		$Offset = 0;

		if( isset($parameters['page']) && $parameters['page'] >= 1)
			{
				$count = $parameters['page'].'0';				
				$Offset = $count - $default; 
			}

		$userID = $parameters['user_id'];

		$results = $wpdb->get_results( "SELECT * FROM ".$wpdb->prefix."conversationids WHERE Fromuser_id = '".$userID."' OR Touser_id = '".$userID."' ORDER BY modified_date Desc LIMIT ".$Offset." , ".$default." " );

		if( !empty( $results ) )
		{
			$details = array(  );
			foreach( $results as $keys ):
			
				$Message = $wpdb->get_results( "SELECT *  FROM ".$wpdb->prefix."message WHERE conv_IDs = ".$keys->id." ORDER BY id DESC LIMIT 1"  );
				if( !empty( $Message ) ):
					if( $userID != $keys->Fromuser_id )
					{
						$Profile = $this->WPmodify->getProfileDetails( $keys->Fromuser_id );						
					}
					else
					{
						$Profile = $this->WPmodify->getProfileDetails( $keys->Touser_id );
					}
				

					$details[] = array( 
						'conv_IDs'=> $keys->id, 
						'user_id'=> $Profile[0]->user_id, 
						'first_name'=> $Profile[0]->first_name, 
						'profile_image'=> empty( $Profile[0]->profile_image )? "" : $Profile[0]->profile_image, 
						'message'=> empty( $Message[0]->message )? "" : $Message[0]->message, 
						'created_on' => empty( $Message[0]->createdOn )? "" : $Message[0]->createdOn,
						'modified_date' => empty( $keys->modified_date )? "" : $keys->modified_date
					);			
				endif;	
			endforeach;
			
			if( isset( $parameters['ajax'] ) && $parameters['ajax'] == 1)
			{
				return $details;
			}
			else
			{
				$this->WPmodify->response( 1, $details , 'No Error Found Found' );
			}
		}
		else
		{
			if( isset( $parameters['ajax'] ) && $parameters['ajax'] == 1)
			{
				return $details;
			}
			else
			{
				$this->WPmodify->response( 1, array() , 'No Thread Found' );
			}
		}
	}

	public function getMessage( $request , $ajax = NULL )
	{
		global $wpdb;
		if( !empty( $ajax ) )
		{
			$parameters = $request;
		}
		else
		{
			//$this->WPmodify->verifyToken();
			$parameters = $request->get_params();
		}


		$conv_IDs = $parameters['conv_IDs'];
		$DetailsConv = $wpdb->get_results( "SELECT * FROM ".$wpdb->prefix."conversationids WHERE id=".$conv_IDs );
		$UserInfo = array();
		if( !empty($DetailsConv) )
		{
			if( isset( $parameters['user_id'] ) )
			{
				if( $DetailsConv[0]->Fromuser_id == $parameters['user_id'] )
				{
					$UserDetails = $this->WPmodify->getProfileDetails($DetailsConv[0]->Touser_id);
				}
				else
				{
					$UserDetails = $this->WPmodify->getProfileDetails($DetailsConv[0]->Fromuser_id);
				}
			}
			if( isset( $UserDetails ) && !empty( $UserDetails ) )
			{
				
				$senderDetails = array();
				$senderDetails['user_id'] = $UserDetails[0]->user_id;
				$senderDetails['username'] = $UserDetails[0]->username;
				$senderDetails['profile_image'] = $UserDetails[0]->profile_image;
			}
		}		
		
		$default = 20;
		$Offset = 0;

		if( isset($parameters['page']) && $parameters['page'] > 1)
			{
				$Offset = ($parameters['page'] - 1) * 20 ;	
			}
		if( isset($parameters['ajax']) && $parameters['ajax'] == 1 )
			{
				
				$Messages = $wpdb->get_results( "SELECT *  FROM ".$wpdb->prefix."message WHERE conv_IDs = '".$conv_IDs."' ORDER BY id ASC LIMIT ".$Offset." , ".$default  );
				/*$TMessages = $wpdb->get_results( "SELECT *  FROM ".$wpdb->prefix."message WHERE conv_IDs = '".$conv_IDs."' ORDER BY id ASC" );*/
			}
		else
			{
				$Messages = $wpdb->get_results( "SELECT *  FROM ".$wpdb->prefix."message WHERE conv_IDs = '".$conv_IDs."' ORDER BY id DESC LIMIT ".$Offset." , ".$default  );
			}
		
		$article = '';
		$TotalCount = count( $Messages );
			foreach( $Messages as $Message ):
				if( isset($parameters['ajax']) && $parameters['ajax'] == 1 )
				{	
					$ProfileDetails = $this->WPmodify->getProfileDetails( $Message->from_userID );
					//pre($ProfileDetails[0]);
					if( !empty($Message->message) )
					{
						$msg = wpautop($Message->message);
					}
					elseif( !empty( $Message->attachment ) )
					{
						$msg = '<img src="'.site_url($Message->attachment).'" height="100px" width="100px">';
					}
					
					if( !empty( $msg ) ):
						$article .='<article>
		                    <figure style="background-image:url(\''.$ProfileDetails[0]->profile_image.'\')"> </figure>
		                    <div class="inbox-time">
		                        <time><i class="fa fa-clock-o" aria-hidden="true"></i> '.date( 'H:i a', strtotime( $Message->createdOn )).'</time>
		                        <p>'.date( 'd-m-Y',strtotime( $Message->createdOn)).'</p>
		                    </div>
		                    <div class="inbox-message">
		                        <h4>'.$ProfileDetails[0]->first_name.'</h4>
		                        '.$msg.'
		                    </div>
		                </article>';
	           		endif;
				}
				else
				{
					$details[] = array( 
					'ID'=> $Message->id, 
					'conv_IDs'=> $Message->conv_IDs, 
					'Fromuser_id'=> $Message->from_userID, 
					'Touser_id'=> $Message->to_userID, 					
					'message'=> empty( $Message->message )? "" : $Message->message, 
					'attachment' => ( empty( $Message->attachment ))? 0: site_url( $Message->attachment ),
					'created_on' => $Message->createdOn
					);	
				}							
			endforeach;

			
			if( isset( $parameters['ajax'] ) && $parameters['ajax'] == 1)
			{
				// FOR WEB 
				$request = new WP_REST_Request( 'GET', '/users/getMessageThreads' );
				$request->set_query_params(array( 'page' => 1 , 'user_id' => $parameters['user_id'] ,'ajax' => 1 ));
				$MessageThread = $this->getMessageThreads( $request );
				//pre($MessageThread);
				$ThreadContent = '';
				$ThreadContent .= '<ul>';
					foreach( $MessageThread as $keys ):
						if( get_current_user_id() == $keys['user_id'] )
						{ 
							$ThreadContent .= '<li class="current">'; 
						}
						else
						{
							$ThreadContent .= '<li>';
						}  
					 	
	                    $ThreadContent .= '<a href="#">
							<time><i class="fa fa-clock-o" aria-hidden="true"></i>'.date( "H:i a",strtotime($keys["created_on"])).'</time><big>'.$keys["first_name"].'</big>'.$keys["message"].'</a>';
	               	$ThreadContent .= '<li>';		
					endforeach;              
              	$ThreadContent .= '</ul>';

				return json_encode(array( 'article' => $article , 'TotalCount'=>$TotalCount , 'Thread' => $ThreadContent));
			}
			else
			{
				// FOR MOBILE
				if( !empty( $details ) )
				{
					$this->WPmodify->response( 1, $details , 'No Error Found !' , NULL , NULL , $senderDetails );
				}
				else
				{
					$this->WPmodify->response( 1, array() , 'No Message Found' , NULL , NULL , $senderDetails );
				}			
			}			
	}

	public function getCards( $request , $ajax = NULL )
	{
		global $wpdb;
		if( !empty( $ajax ) )
		{
			$parameters = $request;
		}
		else
		{
			//$this->WPmodify->verifyToken();
			$parameters = $request->get_params();
		}

		$UserID = $parameters['user_id'];

		$Results = $wpdb->get_results( "Select braintree_ID FROM ".$wpdb->prefix."profiledetails WHERE user_id = '$UserID' " );

		if( !empty( $Results ) )
		{
			if( !empty($Results[0]->braintree_ID) )
			{
				if( isset( $parameters['return'] ) && $parameters['return'] == 1 )
				{
					return BrainTree_getCustomer( $Results[0]->braintree_ID , $parameters['return'] );
				}
				else
				{
					BrainTree_getCustomer( $Results[0]->braintree_ID );
				}				
			}
			else
			{
				$this->WPmodify->response( 0, array() , 'Brain Tree ID Does\'nt Exists ! ' );
			}
		}
		else
		{
			$this->WPmodify->response( 0, array() , 'Brain Tree ID Does\'nt Exists ! ' );
		}
	}

	public function DeleteCard( $request , $ajax = NULL )
	{
		global $wpdb;
		if( !empty( $ajax ) )
		{
			$parameters = $request;
		}
		else
		{
			//$this->WPmodify->verifyToken();
			$parameters = $request->get_params();
		}

		$Card_token = $parameters['card_token'];
		BrainTree_delete_card( $Card_token );
	}

	public function UpdateCard( $request , $ajax = NULL )
	{
		global $wpdb;
		if( !empty( $ajax ) )
		{
			$parameters = $request;
		}
		else
		{
			//$this->WPmodify->verifyToken();
			$parameters = $request->get_params();
		}	

		$token 			= $parameters['card_token'];		
		$CardHolder 	= $parameters['cardholderName'];		
		$Cvv 			= $parameters['cvv'];		
		$Cardnumber		= $parameters['number'];		
		$expirationDate = $parameters['expirationDate'];		

		BrainTree_update_card( $token , $CardHolder , $Cvv , $Cardnumber , $expirationDate );
	}

	public function createCard( $request , $ajax = NULL )
	{
		global $wpdb;
		if( !empty( $ajax ) )
		{
			$parameters = $request;
		}
		else
		{
			//$this->WPmodify->verifyToken();
			$parameters = $request->get_params();
		}
		
		$CustomerID 	= $parameters['customerId'];		
		$CardHolder 	= $parameters['cardholderName'];		
		$Cvv 			= $parameters['cvv'];		
		$Cardnumber		= $parameters['number'];		
		$expirationDate = $parameters['expirationDate'];

		BrainTree_create_card( $CardHolder , $Cvv , $Cardnumber , $expirationDate , $CustomerID );		
	}

	public function logout( $request , $ajax = NULL )
	{
		global $wpdb;
		if( !empty( $ajax ) )
		{
			$parameters = $request;
		}
		else
		{
			//$this->WPmodify->verifyToken();
			$parameters = $request->get_params();
		}

		if( empty( $parameters['user_id'] ) )
		{
			$this->WPmodify->response( 0,"" , 'User ID is required !' );
		}

		$wpdb->update( $wpdb->prefix."profiledetails" , array( 'device_token' => '' , 'device_type' => '' ) , array( 'user_id' => $parameters['user_id'] ) );

		$this->WPmodify->response( 1,'User logout Successfully' );

	}	

	public function refreshToken( $request , $ajax = NULL )
	{
		global $wpdb;
		if( !empty( $ajax ) )
		{
			$parameters = $request;
		}
		else
		{
			//$this->WPmodify->verifyToken();
			$parameters = $request->get_params();
		}

		if( empty( $parameters['user_id'] ) )
		{
			$this->WPmodify->response( 0,"" , 'User ID is required !' );
		}

		$wpdb->update( $wpdb->prefix."profiledetails" , array( 'device_token' => $parameters['device_token'] , 'device_type' => $parameters['device_type'] ) , array( 'user_id' => $parameters['user_id'] ) );

		$this->WPmodify->response( 1,'User logout Successfully' );

	}

	public function getAlerts( $request , $ajax = NULL  )
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
		$UserType 	= $parameters['user_type'];
		
		$Results = $wpdb->get_results( "SELECT * FROM ".$wpdb->prefix."notification WHERE to_userID = '".$user_id."' ORDER BY  FIELD(is_read,'0','1'),id DESC " );
		
		//$Requests = $this->getJobRequests( $user_id ,  $UserType );
		
		$JobRequest = array();
		$Inbox = array();	
		$Invite_for_job = array();	
		$In_progress = array();	
		$Pending = array();	
		$CompleteJob = array();	
		
		foreach( $Results as $keys ):
			
			if( $keys->is_read == 0 )
			{
				if( $keys->noti_type == 'new_message' )
				{
					$Inbox[] = 1;
				}
			}
			
			if( $keys->is_alert == 0 )
			{
				if( $keys->noti_type == 'invite_for_job' )
				{
					//pre( $keys );
					$Invite_for_job[] = 1;
				}
				elseif( $keys->noti_type == 'apply_for_job' )
				{
					$Pending[] = 1;
				}
				elseif( $keys->noti_type == 'accept_bid' )
				{
					$In_progress[] = 1;
				}
				elseif( $keys->noti_type == 'job_progress_left_home' )
				{
					$In_progress[] = 1;
				}
				elseif( $keys->noti_type == 'job_progress_reached_home' )
				{
					$In_progress[] = 1;
				}
				elseif( $keys->noti_type == 'job_progress_tast_started' )
				{
					$In_progress[] = 1;
				}
				elseif( $keys->noti_type == 'job_progress_tast_completed' )
				{
					$In_progress[] = 1;
				}
				elseif( $keys->noti_type == 'answer_submit' )
				{
					//pre( $keys );
					$Pending[] = 1;
				}
				elseif( $keys->noti_type == 'rejected_bid' )
				{
					//pre( $keys );
					$Pending[] = 1;
				}
				elseif( $keys->noti_type == 'complete_job' )
				{
					$CompleteJob[] = 1;
				}
				else
				{
					$JobRequest[] = $keys->noti_type;
				}
			}				
			
		
		endforeach;		
		
		$InviteCount = count( $Invite_for_job );
		$In_progressCount = count( $In_progress );
		$PendingCount = count( $Pending );
		$CompleteJobCount = count( $CompleteJob );
		
		//RequestCount = $Requests['Invites']+$Requests['In_Progress']+$Requests['Is_Pending']+$Requests['Complete'];
		$RequestCount = $InviteCount + $In_progressCount + $PendingCount + $CompleteJobCount;
		
		$Notification = array( 
		'inbox' 			=> ( empty(count( $Inbox )) )? 0: count( $Inbox ) , 
		'jobRequest' 		=> $RequestCount , 
		'Invite_for_job' 	=> $InviteCount, 
		'In_progress' 		=> $In_progressCount, 
		'Pending' 			=> $PendingCount, 
		'CompleteJob' 		=> $CompleteJobCount		
		);

		if( isset( $parameters['is_web'] ) ):
			return $Notification;
		else:
			$this->WPmodify->response( 1 , $Notification , "No Error Found !" );
		endif;
		
	}
	
	private function getJobRequests( $UserID ,  $UserType )
	{
		global $wpdb;
		
		if( isset( $UserType ) && !empty( $UserType ) && $UserType == 1  )
		{
			// Customer		
        	$args = array(
	            'post_type' => 'job',
	            'orderby' => 'modified',
	            'order' => 'DESC',
	            'posts_per_page' => -1,
				'paged' => $paged,
	            'meta_query'	=> array(
				'relation'		=> 'AND',
					array(
						'key'	 	=> 'customer_name',
						'value'	  	=> array( $UserID )						
					)
				)	            
			);
        

        $the_query = new WP_Query($args);
        //pre($the_query );

        $Invites 		= array();
		$Is_Pending 	= array(); 
		$In_Progress 	= array(); 
		$Complete 		= array(); 

        if( !empty( $the_query ) ):
	        while( $the_query->have_posts() ) : $the_query->the_post();
	        $fields = get_fields( get_the_ID() );
			
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
				
			if( isset( $fields['job_status'][0] ) && $fields['job_status'][0] == 0 )
			{
				// Pending
				if( $is_latest_for_customer == 1 )
				{
					$Is_Pending[] = 1 ;
				}
				
			}			
			elseif( isset( $fields['job_status'][0] ) && $fields['job_status'][0] == 1 )
			{
				// In Progress
				if( $is_latest_for_customer == 1 )
				{
					$In_Progress[] = 1 ;
				}
			}	
			elseif( isset( $fields['job_status'][0] ) && $fields['job_status'][0] == 2 )
			{
				// Completed
				if( $is_latest_for_customer == 1 )
				{
					$Complete[] = 1 ;
				}
			}
	        endwhile;
	    endif;
		
			$Status = array( 
						'Is_Pending'	=> count( $Is_Pending ),
						'In_Progress'	=> count( $In_Progress ),
						'Complete'		=> count( $Complete ),
						'Invites'		=> count( $Invites )						
				);
		}
		
		if( isset( $UserType ) && !empty( $UserType ) && $UserType == 2  )
		{
				// Service Provider
				// Status  0: Applied , 1 : InProgress , 2 : Completed , 3 : Invites
			$args = array(
		            'post_type' => 'bid',
		            'orderby' => 'modified',
		            'order' => 'DESC',
		            'posts_per_page' => -1,
					'paged' => $paged,
		            'meta_query'	=> array(
					'relation'		=> 'AND',
					array(
							'key'	 	=> 'select_service_provider',
							'value'	  	=> array( $UserID)						
						)			
					)
			);
			
			$Invites 		= array();
			$Is_Pending 	= array(); 
			$In_Progress	= array(); 
			$Complete		= array();
			
			$the_query = new WP_Query($args);
			 if( !empty( $the_query ) ):
	        	$fields = '';
		        while( $the_query->have_posts() ) : $the_query->the_post();
		        $fields = get_fields( get_the_ID() );
		        $Jobfields = get_field( 'is_latest_for_bidder' , $fields['select_job'][0]->ID );;
				$jobStatus = get_field( 'job_status' , $fields['select_job'][0]->ID );				
				
				if( isset( $jobStatus ) && $jobStatus == 0 )
				{
					if( isset( $fields['is_latest_for_bidder'] ) && !empty( $fields['is_latest_for_bidder'] ) )
					{
						$is_latest_for_bidder = ( $fields['is_latest_for_bidder'][0] == 1  )? 1 : 0 ;
					}
					else
					{
						$is_latest_for_bidder  = 0;
					}
				}
				else
				{
					pre( 'JobID -> '.$fields['select_job'][0]->ID );
					pre( 'JObstatus -> '.$jobStatus );
					pre( $Jobfields );
					
					//die();
				}				
					
					
				if( isset( $jobStatus ) && $jobStatus == 0 )
					{
						// Pending
						if( $is_latest_for_bidder == 1 )
						{
							$Is_Pending[] = 1 ;
						}
						
					}			
				elseif( isset( $jobStatus ) && $jobStatus == 1 )
					{
						// In Progress
						if( $is_latest_for_bidder == 1 )
						{
							$In_Progress[] = 1 ;
						}
					}	
				elseif( isset( $jobStatus ) && $jobStatus == 2 )
					{
						// Completed
						if( $is_latest_for_bidder == 1 )
						{
							$Complete[] = 1 ;
						}
					}
				elseif( isset( $jobStatus ) && $jobStatus == 3 )
					{
						// Invite
						if( $is_latest_for_bidder == 1 )
						{
							$Invites[] = 1 ;
						}
					}				
				endwhile;
			endif;
			$Status = array( 
						'Invites'		=> count( $Invites ),
						'Is_Pending'	=> count( $Is_Pending ),
						'In_Progress'	=> count( $In_Progress ),					
						'Complete'		=> count( $Complete )					
				);	
		}
		
		return $Status;		
	}

	public function NotificationRead( $request , $ajax = NULL  )
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
		
		$Noti_ID 	= $parameters['Noti_ID'];
		
		$Update = $wpdb->update( $wpdb->prefix.'notification' , array( 'is_read' => 1 ) , array( 'id' =>  $Noti_ID ) );
		$wpdb->flush();
		
		if( $Update )
		{
			$this->WPmodify->response( 1, "Notification Updated Successfully");
		}
		else
		{
			$this->WPmodify->response( 0, "" , "Some think went wrong ! Please Try Again ");
		}
	}
	
	public function updateRead( $request , $ajax = NULL )
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
		
		$Results = $wpdb->update( $wpdb->prefix."notification" , array( 'is_alert' => 1 ), array( 'for_ID' => $parameters['realted_id'] , 'to_userID' => $parameters['user_id'] ) );
		
		if( $Results )
		{
			$this->WPmodify->response( 1, "Updated Successfully");
		}
		else
		{
			$this->WPmodify->response( 0, "No Entity is Updated");
		}
		
		/* if( isset( $parameters['type'] ) && $parameters['type'] == 'job' )
			{
				// Job
				if( $parameters['read_type'] == 'is_latest_for_customer' )
				{
					update_field('field_5b18e1c26dd16', array(0), $parameters['realted_id']);	// Customer
				}
				if( $parameters['read_type'] == 'is_latest_for_bidder' )
				{
					update_field('field_5b1bb3c9ddb30', array(0), $parameters['realted_id']);	// Bidder
				}
				
				$this->WPmodify->response( 1, "Updated Successfully");
			}
		elseif( isset( $parameters['type'] ) && $parameters['type'] == 'bid' )
			{
				// bid				
				if( $parameters['read_type'] == 'is_latest_for_customer' )
				{
					update_field('field_5b18e18dc352d', array(0), $parameters['realted_id']);// Customer
				}
				if( $parameters['read_type'] == 'is_latest_for_bidder' )
				{
					update_field('field_5b1bb364495ee', array(0), $parameters['realted_id']);// Bidder
				}				
				$this->WPmodify->response( 1, "Updated Successfully");
				
			}
		else
		{
			$this->WPmodify->response( 0, "Please Check your Input");
		}			
	 */
	 }

	public function getEmailExists( $request )
	{
		global $wpdb;
		$parameters = $request->get_params();

		if( isset( $parameters['email_exists'] ) )
		{
			if ( email_exists( $parameters['email_exists'] ) ) 
			{
				$this->WPmodify->response( 1, "yes");
			}
			else
			{
				$this->WPmodify->response( 1, 'no');
			}
		}
		else
		{
			$this->WPmodify->response( 0, "Please Check your Input");
		}

	}

	public function WebsocialLogin( $request )
	{
		global $wpdb;
		$parameters = $request->get_params();

		$Email 		= $parameters['email'];
		$SocialID 	= $parameters['socialID'];
		$UserType 	= $parameters['user_type'];

		$Results = $wpdb->get_results( 'SELECT * FROM '.$wpdb->prefix.'profiledetails WHERE email = "'.$Email.'" AND (facebook_id = "'.$SocialID.'" || google_id = "'.$SocialID.'")' );

		
		if ( email_exists( $parameters['email'] ) ) 
		{
			$user = get_user_by('email', $parameters['email']);

			if( isset($parameters['user_type']) && $parameters['user_type'] == 1 && $user->roles[0] != 'customer' )
				{
					$this->WPmodify->response( 0, NULL , 'Login from wrong User Type ! ' );	
				}
			if( isset($parameters['user_type']) && $parameters['user_type'] == 2 && $user->roles[0] != 'service' )
				{
					$this->WPmodify->response( 0, NULL , 'Login from wrong User Type ! ' );
				}
				

			if (!empty($user) && !empty($Results)) 
				{
					clean_user_cache($user->ID);
			        wp_clear_auth_cookie();
			        wp_set_current_user( $user->ID );
			        wp_set_auth_cookie( $user->ID , true, false);

			        update_user_caches($user);

			        if(is_user_logged_in())
				        {
				           $this->WPmodify->response( 1, 'Login Successfully' );
				        }
				}
		}
		else
		{
			 $this->WPmodify->response( 0, 'Invalid Login' );
		}

	}



}
new Users();