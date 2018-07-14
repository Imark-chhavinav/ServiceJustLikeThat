<?php 
class Users_valid
{
	var $validator;
	var $WPmodify;

	public function __construct()
	{
		$this->validator = new Validator();			
		$this->WPmodify = new WPmodify();			
		
	}
	
	public function userRegistrationValidate( $request , $ajax = NULL)
	{
		if( !empty( $ajax ) )
		{
			$parameters = $request;
		}
		else
		{
			$parameters = $request->get_params();
		}
		
		
		$Validation = array(
			'title'    			=> 'required',
			'first_name'   		=> 'required',
			'last_name'    		=> 'required',
			'username'   		=> 'required|alpha_numeric|wp_usernameExists',			
			'password'    		=> 'required|min_len,6',
			'email'      		=> 'required|valid_email|wp_EmailExists',			
			'street_address'    => 'required',			
			'city'      		=> 'required',			
			'state'       		=> 'required',			
			//'postcode'    	=> 'required',			
			'lat'    			=> 'required',
			'longs'    			=> 'required',			
			'dob'    			=> 'required',			
			'phone_number'      => 'required|min_len,10',			
			//'push_noti'      	=> 'required|contains,yes no',			
			//'service_type'    => 'required',						
			'user_type'       	=> "required|contains,1 2|exact_len,1"		
		);

		if( empty( $parameters['reg_nonce'] )  )
		{
			$Validation['device_token'] = 'required';			
			$Validation['device_type'] = 'required';
		}


		if( $parameters['user_type'] == 2 && $parameters['business_type'] == 2 )
		{
			$Validation['business_name'] = 'required';			
			$Validation['service_type'] = 'required';			
			$Validation['overview'] = 'required';			
		}
		$this->validator->validation_rules( $Validation );

		$validated_data = $this->validator->run($parameters);

		if($validated_data === false) 
		{
			$Error = $this->validator->get_errors_array();
			
			 if(isset($parameters['reg_nonce']))
			{
				$this->WPmodify->response( 0, "" , reset($Error) );	
			}
			else
			{
				$this->WPmodify->response( 0, "" , reset($Error) );
			}		
		} 
		else 
		{
			if( isset($parameters['dob']) && !empty( $parameters['dob'] ) ):
				$Now = strtotime( date( 'd-m-Y' ) );
				if( strtotime( $parameters['dob'] ) >= $Now ):
					$this->WPmodify->response( 0, "" , "Invalid Date !" );
				else:
					return true;
				endif;
			else:
				return true;
			endif;
			
		}
	}

	public function WebsocialLoginValidation( $request , $ajax = NULL)
	{
		if( !empty( $ajax ) )
		{
			$parameters = $request;
		}
		else
		{
			$parameters = $request->get_params();
		}
		
		
		$Validation = array(			
			'email'      		=> 'required|valid_email',
			'socialID'       	=> "required"		
		);
	
		$this->validator->validation_rules( $Validation );

		$validated_data = $this->validator->run($parameters);

		if($validated_data === false) 
		{
			$Error = $this->validator->get_errors_array();
			
			 if(isset($parameters['reg_nonce']))
			{
				$this->WPmodify->response( 0, "" , reset($Error) );	
			}
			else
			{
				$this->WPmodify->response( 0, "" , reset($Error) );
			}		
		} 
		else 
		{
			return true;			
		}
	}

	public function socialLoginValidation( $request , $ajax = NULL)
	{
		if( !empty( $ajax ) )
		{
			$parameters = $request;
		}
		else
		{
			$parameters = $request->get_params();
		}

		$Validation = array(			
			'first_name'   		=> 'required',		
			'email'      		=> 'required|valid_email',			
			'street_address'    => 'required',			
			'city'      		=> 'required',			
			'state'       		=> 'required',
			'lat'    			=> 'required',
			'longs'    			=> 'required',
			'socialLoginId'    	=> 'required',
			'socialtype'    	=> 'required',
			'user_type'       	=> "required|contains,1 2|exact_len,1"		
		);

		if( $parameters['user_type'] == 2 )
		{
			$Validation['business_type'] = 'required';			
		}


		if( $parameters['user_type'] == 2 && $parameters['business_type'] == 2 )
		{
			$Validation['business_name'] = 'required';
			$Validation['service_type'] = 'required';			
		}
		elseif( $parameters['user_type'] == 2 && $parameters['business_type'] == 1 )
		{					
			$Validation['service_type'] = 'required';			
		}

		if( empty( $parameters['ajax'] ) )
		{
			$Validation['device_token'] = 'required';			
			$Validation['device_type'] = 'required';
		}


		$this->validator->validation_rules( $Validation );

		$validated_data = $this->validator->run($parameters);

		if($validated_data === false) 
		{
			
			 if(isset($parameters['SignUp_wpnonce']))
			{
				return new WP_Error( 'error',$this->validator->get_errors_array(),array( 'status' => 200) );	
			}
			else
			{
				$Error = $this->validator->get_errors_array();
				$this->WPmodify->response( 0, NULL, reset($Error) );
				//$Error = $this->validator->get_errors_array();
				//$this->WPmodify->response( 0, "" , $Error );
			}		
		} 
		else 
		{
			return true;
		}
	}

	public function userSignValidation( $request , $ajax = NULL )
	{
		if( !empty( $ajax ) )
		{
			$parameters = $request;
		}
		else
		{
			$parameters = $request->get_params();
		}

		$parameters = $this->validator->sanitize($parameters); // You don't have to sanitize, but it's safest to do so.
		$ArrayRules = array();

		$ArrayRules = array(
			'username_email'    => 'required',
			'password'   		=> 'required',					
			'user_type'       	=> "required|contains,1 2|exact_len,1"					
		);		

		if( empty( $parameters['SignIn_wpnonce'] ) )
		{
			$ArrayRules['device_type']='required';
			$ArrayRules['device_token']='required';
		}
		
		$this->validator->filter_rules(array(
			'username_email' => 'trim',
			'password' => 'trim'			
		));

		$this->validator->validation_rules( $ArrayRules );

		$validated_data = $this->validator->run($parameters);

		if($validated_data === false) 
		{
			
			$Error = $this->validator->get_errors_array();				
			$this->WPmodify->response( 0, "" , reset($Error) );	
		} 
		else 
		{
			return true;
		}
	}

	public function getprofileValidate( WP_REST_Request $request )
	{
		
		$parameters = $request->get_params();
		$userID 	= $parameters['user_id'];

		//$this->WPmodify->verifyToken();
		return true;
	} 

	public function ValidationProfile( WP_REST_Request $request)
	{
		//$this->WPmodify->verifyToken();
		if( !empty( $ajax ) )
		{
			$parameters = $request;
		}
		else
		{
			$parameters = $request->get_params();
		}			

		$ArrayRules = array(
			'user_id'    		=> 'required|wp_userExists',
			'title'    			=> 'required',
			'first_name'   		=> 'required',
			'last_name'    		=> 'required',								
			'phone_number'      => 'required|min_len,10',						
			'user_type'       	=> "required|contains,1 2|exact_len,1"			
		);
		
		if( isset($parameters['user_type']) && $parameters['user_type'] == 2 )
		{
			//$ArrayRules['business_name'] = 'required';
			//$ArrayRules['overview'] = 'required';	
			$ArrayRules['dob'] = 'required';	
		}

		if( isset($parameters['user_type']) && $parameters['user_type'] == 1 )
		{
			$ArrayRules['street_address'] = 'required';
			$ArrayRules['city'] = 'required';	
			$ArrayRules['state'] = 'required';	
			$ArrayRules['lat'] = 'required';	
			$ArrayRules['longs'] = 'required';	
		}
		
		$this->validator->validation_rules( $ArrayRules );

		$validated_data = $this->validator->run($parameters);

		if($validated_data === false) 
		{			
			 if(isset($parameters['UpdateProfile_wpnonce']))
			{
				return new WP_Error( 'error',$this->validator->get_errors_array(),array( 'status' => 200) );	
			}
			else
			{
				$Error = $this->validator->get_errors_array();				
				$this->WPmodify->response( 0, "" , reset($Error)  );
			}		
		} 
		else 
		{
			return true;
		}
	}

	public function ResetPasswordValidate( $request , $ajax = NULL )
	{
		//$this->WPmodify->verifyToken();
		if( !empty( $ajax ) )
		{
			$parameters = $request;
		}
		else
		{
			$parameters = $request->get_params();
		}


			$this->validator->validation_rules(array(
				'previous_pass'     => 'required',
				'new_pass'   		=> 'required',					
				'confirm_pass'   	=> 'required',					
				'user_id'   		=> 'required|wp_userExists'					
				));		
		
				$this->validator->filter_rules(array(
				'previous_pass'     => 'trim',
				'new_pass'   		=> 'trim',					
				'confirm_pass'   	=> 'trim',					
				'user_id'   		=> 'trim'	
				));

				$validated_data = $this->validator->run($parameters);

				if($validated_data === false) 
				{
					if( isset( $parameters["UpdatePassword_wpnonce"] ) )
					{
						return new WP_Error( 'error',$this->validator->get_errors_array(),array( 'status' => 200) );	
					}
					else
					{
						$Error = $this->validator->get_errors_array();	
						$this->WPmodify->response( 0, "" , reset($Error));
					}
						
				}
				else
				{
					return true;
				}
	}

	public function UpdateBusinessProfileValidate(  $request , $ajax = NULL )
	{
		//$this->WPmodify->verifyToken();
		if( !empty( $ajax ) )
		{
			$parameters = $request;
		}
		else
		{
			$parameters = $request->get_params();
		}

		$ArrayRules = array(								
			"business_name"		=> "required",	
			//"phone_number"		=> "required",	
			"service_type"		=> "required",	
			"street_address"	=> "required",	
			"city"				=> "required",	
			"state"				=> "required",				
			"lat"				=> "required",	
			"longs"				=> "required",	
			"overview"			=> "required",	
			"user_type"       	=> "required|contains,2|exact_len,1",
			"user_id"			=> "required",	
		);		
		
		
		$this->validator->validation_rules( $ArrayRules );

		$validated_data = $this->validator->run($parameters);

		if($validated_data === false) 
		{			
			 if(isset($parameters['UpdateService_wpnonce']))
			{
				return new WP_Error( 'error',$this->validator->get_errors_array(),array( 'status' => 200) );	
			}
			else
			{
				$Error = $this->validator->get_errors_array();				
				$this->WPmodify->response( 0, "" , reset($Error)  );
			}		
		} 
		else 
		{
			return true;
		}
	}

	public function sendMessageValidate(  $request , $ajax = NULL )
	{
		//$this->WPmodify->verifyToken();
		if( !empty( $ajax ) )
		{
			$parameters = $request;
		}
		else
		{
			$parameters = $request->get_params();
		}

		$ArrayRules = array(								
			"Fromuser_id"		=> "required",		
			"Touser_id"			=> "required",			
			//"message"			=> "required"			
		);	

		$this->validator->validation_rules( $ArrayRules );

		$validated_data = $this->validator->run($parameters);

		if($validated_data === false) 
		{			
			 if(isset($parameters['UpdateService_wpnonce']))
			{
				return new WP_Error( 'error',$this->validator->get_errors_array(),array( 'status' => 200) );	
			}
			else
			{
				$Error = $this->validator->get_errors_array();				
				$this->WPmodify->response( 0, "" , reset($Error)  );
			}		
		} 
		else 
		{
			return true;
		}
	}

	public function getMessageValidate(  $request , $ajax = NULL )
	{
		//$this->WPmodify->verifyToken();
		if( !empty( $ajax ) )
		{
			$parameters = $request;
		}
		else
		{
			$parameters = $request->get_params();
		}

		$ArrayRules = array(								
			"conv_IDs"		=> "required"					
		);	

		$this->validator->validation_rules( $ArrayRules );

		$validated_data = $this->validator->run($parameters);

		if($validated_data === false) 
		{			
			 if(isset($parameters['UpdateService_wpnonce']))
			{
				return new WP_Error( 'error',$this->validator->get_errors_array(),array( 'status' => 200) );	
			}
			else
			{
				$Error = $this->validator->get_errors_array();				
				$this->WPmodify->response( 0, "" , reset($Error)  );
			}		
		} 
		else 
		{
			return true;
		}
	}

	public function forgotPasswordValidate( WP_REST_Request $request )
	{
		//$this->WPmodify->verifyToken();
		if( !empty( $ajax ) )
		{
			$parameters = $request;
		}
		else
		{
			$parameters = $request->get_params();
		}

		$ArrayRules = array(								
			"email"		=> "required|valid_email"					
		);	

		$this->validator->validation_rules( $ArrayRules );

		$validated_data = $this->validator->run($parameters);

		if($validated_data === false) 
		{			
			 if(isset($parameters['UpdateService_wpnonce']))
			{
				return new WP_Error( 'error',$this->validator->get_errors_array(),array( 'status' => 200) );	
			}
			else
			{
				$Error = $this->validator->get_errors_array();				
				$this->WPmodify->response( 0, "" , reset($Error)  );
			}		
		} 
		else 
		{
			return true;
		}
	}

	public function createCardValidate( WP_REST_Request $request )
	{
		//$this->WPmodify->verifyToken();
		if( !empty( $ajax ) )
		{
			$parameters = $request;
		}
		else
		{
			$parameters = $request->get_params();
		}

		$ArrayRules = array(								
			"user_type"       	=> "required|contains,1|exact_len,1",
			"user_id"			=> "required",					
			"cardholderName"	=> "required",					
			"customerId"		=> "required",					
			"number"			=> "required",					
			"expirationDate"	=> "required",					
			"cvv"				=> "required"					
		);	

		$this->validator->validation_rules( $ArrayRules );

		$validated_data = $this->validator->run($parameters);

		if($validated_data === false) 
		{			
			 if(isset($parameters['UpdateService_wpnonce']))
			{
				return new WP_Error( 'error',$this->validator->get_errors_array(),array( 'status' => 200) );	
			}
			else
			{
				$Error = $this->validator->get_errors_array();				
				$this->WPmodify->response( 0, "" , reset($Error)  );
			}		
		} 
		else 
		{
			return true;
		}
	}

	public function UpdateCardValidate( WP_REST_Request $request )
	{
		//$this->WPmodify->verifyToken();
		if( !empty( $ajax ) )
		{
			$parameters = $request;
		}
		else
		{
			$parameters = $request->get_params();
		}

		$ArrayRules = array(
			"user_type"       	=> "required|contains,1|exact_len,1",
			"user_id"			=> "required",									
			"card_token"		=> "required",					
			"cardholderName"	=> "required",					
			"customerId"		=> "required",					
			"number"			=> "required",					
			"expirationDate"	=> "required",					
			"cvv"				=> "required"					
		);	

		$this->validator->validation_rules( $ArrayRules );

		$validated_data = $this->validator->run($parameters);

		if($validated_data === false) 
		{			
			 if(isset($parameters['UpdateService_wpnonce']))
			{
				return new WP_Error( 'error',$this->validator->get_errors_array(),array( 'status' => 200) );	
			}
			else
			{
				$Error = $this->validator->get_errors_array();				
				$this->WPmodify->response( 0, "" , reset($Error)  );
			}		
		} 
		else 
		{
			return true;
		}
	}

	public function DeleteCardValidate( WP_REST_Request $request )
	{
		//$this->WPmodify->verifyToken();
		if( !empty( $ajax ) )
		{
			$parameters = $request;
		}
		else
		{
			$parameters = $request->get_params();
		}

		$ArrayRules = array(
			"user_type"       	=> "required|contains,1|exact_len,1",
			"user_id"			=> "required",									
			"card_token"		=> "required"					
		);	

		$this->validator->validation_rules( $ArrayRules );

		$validated_data = $this->validator->run($parameters);

		if($validated_data === false) 
		{			
			 if(isset($parameters['UpdateService_wpnonce']))
			{
				return new WP_Error( 'error',$this->validator->get_errors_array(),array( 'status' => 200) );	
			}
			else
			{
				$Error = $this->validator->get_errors_array();				
				$this->WPmodify->response( 0, "" , reset($Error)  );
			}		
		} 
		else 
		{
			return true;
		}
	}
	
	public function getCardsValidate( WP_REST_Request $request )
		{
			//$this->WPmodify->verifyToken();
			if( !empty( $ajax ) )
			{
				$parameters = $request;
			}
			else
			{
				$parameters = $request->get_params();
			}

			$ArrayRules = array(
				"user_type"       	=> "required|contains,1|exact_len,1",
				"user_id"			=> "required"
			);	

			$this->validator->validation_rules( $ArrayRules );

			$validated_data = $this->validator->run($parameters);

			if($validated_data === false) 
			{			
				 if(isset($parameters['UpdateService_wpnonce']))
				{
					return new WP_Error( 'error',$this->validator->get_errors_array(),array( 'status' => 200) );	
				}
				else
				{
					$Error = $this->validator->get_errors_array();				
					$this->WPmodify->response( 0, "" , reset($Error)  );
				}		
			} 
			else 
			{
				return true;
			}
		}			public function getAlertsValidate( WP_REST_Request $request )
		{
			//$this->WPmodify->verifyToken();
			if( !empty( $ajax ) )
			{
				$parameters = $request;
			}
			else
			{
				$parameters = $request->get_params();
			}

			$ArrayRules = array(
				"user_type"       	=> "required|contains,1 2|exact_len,1",
				"user_id"			=> "required"
			);	

			$this->validator->validation_rules( $ArrayRules );

			$validated_data = $this->validator->run($parameters);

			if($validated_data === false) 
			{			
				 if(isset($parameters['UpdateService_wpnonce']))
				{
					return new WP_Error( 'error',$this->validator->get_errors_array(),array( 'status' => 200) );	
				}
				else
				{
					$Error = $this->validator->get_errors_array();				
					$this->WPmodify->response( 0, "" , reset($Error)  );
				}		
			} 
			else 
			{
				return true;
			}
		}				public function NotificationReadValidate( WP_REST_Request $request )
		{
			//$this->WPmodify->verifyToken();
			if( !empty( $ajax ) )
			{
				$parameters = $request;
			}
			else
			{
				$parameters = $request->get_params();
			}

			$ArrayRules = array(
				"user_type"       	=> "required|contains,1 2|exact_len,1",
				"user_id"			=> "required",				"Noti_ID"			=> "required",
			);	

			$this->validator->validation_rules( $ArrayRules );

			$validated_data = $this->validator->run($parameters);

			if($validated_data === false) 
			{			
				 if(isset($parameters['UpdateService_wpnonce']))
				{
					return new WP_Error( 'error',$this->validator->get_errors_array(),array( 'status' => 200) );	
				}
				else
				{
					$Error = $this->validator->get_errors_array();				
					$this->WPmodify->response( 0, "" , reset($Error)  );
				}		
			} 
			else 
			{
				return true;
			}
		}
		
	public function updateReadValidate( WP_REST_Request $request )
	{
		//$this->WPmodify->verifyToken();
		if( !empty( $ajax ) )
		{
			$parameters = $request;
		}
		else
		{
			$parameters = $request->get_params();
		}

		$ArrayRules = array(
			"user_type"       	=> "required|contains,1 2|exact_len,1",
			"user_id"			=> "required",									
			//"type"				=> "required",					
			"realted_id"		=> "required",					
			//"read_type"			=> "required"
		);	

		$this->validator->validation_rules( $ArrayRules );

		$validated_data = $this->validator->run($parameters);

		if($validated_data === false) 
		{			
			 if(isset($parameters['UpdateService_wpnonce']))
			{
				return new WP_Error( 'error',$this->validator->get_errors_array(),array( 'status' => 200) );	
			}
			else
			{
				$Error = $this->validator->get_errors_array();				
				$this->WPmodify->response( 0, "" , reset($Error)  );
			}		
		} 
		else 
		{
			return true;
		}
	}


}
new Users_valid();