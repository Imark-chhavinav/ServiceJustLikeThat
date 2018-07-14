<?php 
/* WP_REST_Server::READABLE = ‘GET’

WP_REST_Server::EDITABLE = ‘POST, PUT, PATCH’

WP_REST_Server::DELETABLE = ‘DELETE’

WP_REST_Server::ALLMETHODS = ‘GET, POST, PUT, PATCH, DELETE’
WP_REST_Server::CREATABLE

 */

class Users extends WP_REST_Controller
{
	var $my_namespace = 'users';
	var $validator;
	var $WPmodify;
  	
	public function __construct()
	{
		$this->validator = new Validator();
			
		//$this->WPmodify = new WPmodify();
		add_action( 'rest_api_init', array( $this, 'RegisterRoutes' ) );
	}
	
	public function RegisterRoutes()
	{
	
		
	register_rest_route( $this->my_namespace, '/userRegistration',array(
		'methods'         => WP_REST_Server::CREATABLE,
		'callback'        => array( $this, 'user_Registration' ),	
		'permission_callback'   => array( $this, 'userRegistrationValidate' )		
		) 
	);	
	
	
	}
	
	public function user_Registration( WP_REST_Request $request )
	{
		$parameters = $request->get_params();
		
		$role = '';
		if( $parameters['user_type'] == 1 )
		{
			$role = 'customer';
		}
		if( $parameters['user_type'] == 2 )
		{
			$role = 'provider';
		}
		$title 					= $parameters['title'];
		$first_name 			= $parameters['first_name'];
		$last_name 				= $parameters['last_name'];
		$password 				= md5($parameters['password']);
		$email 					= $parameters['email'];
		$username 				= $parameters['username'];
		$phone_number 			= $parameters['phone_number'];
		$unit_apartment_number 	= $parameters['unit_apartment_number'];
		$street_address 		= $parameters['street_address'];
		$suburb 				= $parameters['suburb'];
		$state 					= $parameters['state'];
		$postcode 				= $parameters['postcode'];
		$country 				= $parameters['country'];
		$business_name			= $parameters['business_name'];
		$abn 					= $parameters['abn'];
		$facebook_id 			= $parameters['facebook_id'];
		$google_id 				= $parameters['google_id'];
		$device_type 			= $parameters['device_type'];
		$device_token 			= $parameters['device_token'];
		$user_type 				= $parameters['user_type'];
		$user_activation_key	= time().wp_generate_password( 8, false ).base64_encode($email);
	
		/* Creating Wordpress User  */
		$userdata = array(
		'user_pass'  			=> $password ,	
		'user_login'   			=> $username ,
		'first_name' 			=> $first_name ,  
		'last_name' 			=> $last_name ,  
		'user_email'   			=> $email ,  
		'display_name'  		=> $first_name ,  
		'user_registered' 	 	=> date('Y-m-d H:i:s') ,  
		'role'  				=> $role ,  
		'user_activation_key'  	=> $user_activation_key   
		);
		$user_id = wp_insert_user( $userdata ) ; 
		/* Inserting Data into registartion Table of Profile Use */
		$Data = array( 
		'user_id'=>$user_id,
		'user_type'=>$user_type,
		'first_name'=>$first_name,
		'last_name'=>$last_name,
		'username'=>$username,
		'apartment_number'=>$unit_apartment_number,
		'street_address'=>$street_address,
		'suburb'=>$suburb,
		'state'=>$state,
		'post_code'=>$postcode,
		'country'=>$country,
		'email'=>$email,
		'title'=>$title,
		'business_number'=>$business_name,
		'abn'=>$abn,
		'facebook_id'=>$facebook_id,
		'google_id'=>$google_id,
		'device_token'=>$device_token,
		'device_type'=>$device_type
		);
		global $wpdb;
		$wpdb->insert( $wpdb->prefix.'registration', $Data );
		$wpdb->flush();
		
		$to = $email;
		$subject = 'Account Verification';
		$body = 'The email body content '.$user_activation_key;
		$headers = array('Content-Type: text/html; charset=UTF-8');
		$mail =  wp_mail( $to, $subject, $body, $headers );
		if( $mail )
		{
			$this->WPmodify->response( 1, 'PLease check your email for verification Link' , 'No Error Found' );
		}
		else
		{
			$this->WPmodify->response( 0, 'Please ask Admin to Activate your account as Activation Mail was not sent ' , 'Mail Sent Error' );
		}		
	}
	
	/* Validtion Starts */
	public function userRegistrationValidate( WP_REST_Request $request )
	{
		$parameters = $request->get_params();
				
		$parameters = $this->validator->sanitize($parameters); // You don't have to sanitize, but it's safest to do so.

		$this->validator->validation_rules(array(
			'title'    			=> 'required|alpha_numeric',
			'first_name'   		=> 'required|alpha_numeric',
			'last_name'    		=> 'required|alpha_numeric',
			'username'   		=> 'required|alpha_numeric|wp_usernameExists',			
			'password'    		=> 'required|min_len,6',
			'email'      		=> 'required|valid_email|wp_EmailExists',			
			'street_address'    => 'required',			
			'suburb'      		=> 'required',			
			'state'       		=> 'required',			
			'postcode'    		=> 'required',			
			'phone_number'      => 'required',			
			//'provider'      	=> 'required',			
			'user_type'       	=> "required|contains,1 2|exact_len,1",			
		));
		
		if( $parameters['user_type'] == 2 )
		{
			$this->validator->validation_rules(array(					
			'business_name'  	=> 'required'
			));
		}
		
		$this->validator->filter_rules(array(
			'username' => 'trim|sanitize_string',
			'password' => 'trim',
			'email'    => 'trim|sanitize_email'
			
		));

		$validated_data = $this->validator->run($parameters);

		if($validated_data === false) 
		{
			
			 if(isset($parameters['SignUp_wpnonce']))
			{
				return new WP_Error( 'error',$this->validator->get_errors_array(),array( 'status' => 200) );	
			}
			else
			{
				$this->WPmodify->response( 0, NULL , 'Some Fields were Not Valid !' );
			}		
		} 
		else 
		{
			return true;
		}
	}
}
new Users();