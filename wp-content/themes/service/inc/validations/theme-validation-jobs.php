<?php 
class Jobs_valid
{
	var $validator;
	var $WPmodify;

	public function __construct()
	{
		$this->validator = new Validator();			
		$this->WPmodify = new WPmodify();			
		
	}

	public function createJobValidation( $request , $ajax = NULL )
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
			'user_id'    				=> 'required',
			'user_type'    				=> 'required|contains,1',
			'job_title'    				=> 'required',
			'job_content'   			=> 'required',
			'job_price'    				=> 'required',
			'job_date'   				=> 'required',			
			'job_time'    				=> 'required',			
			'street_address'    		=> 'required',			
			'city'      				=> 'required',			
			'state'       				=> 'required',			
			//'postcode'    				=> 'required',			
			'lat'    					=> 'required',
			'longs'    					=> 'required',						
			'publicly'     				=> 'required|contains,0 1',			
			'service_type'     			=> 'required',			
		);


		$this->validator->validation_rules( $Validation );

		$validated_data = $this->validator->run($parameters);

		if($validated_data === false) 
		{
			
			 if(isset($parameters['CreateJob_wpnonce']))
			{
				return new WP_Error( 'error',$this->validator->get_errors_array(),array( 'status' => 200) );	
			}
			else
			{
				$Error = $this->validator->get_errors_array();
				$this->WPmodify->response( 0, "" , reset($Error) );
			}		
		} 
		else 
		{
			return true;
		}
	}
	
	public function updateJobValidation( $request , $ajax = NULL )
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
			'job_id'    				=> 'required',
			'user_id'    				=> 'required',
			'user_type'    				=> 'required|contains,1',
			'job_title'    				=> 'required',
			'job_content'   			=> 'required',
			'job_price'    				=> 'required',
			'job_date'   				=> 'required',			
			'job_time'    				=> 'required',			
			'street_address'    		=> 'required',			
			'city'      				=> 'required',			
			'state'       				=> 'required',			
			//'postcode'    				=> 'required',			
			'lat'    					=> 'required',
			'longs'    					=> 'required',						
			'publicly'     				=> 'required|contains,0 1',			
			'service_type'     			=> 'required',			
		);


		$this->validator->validation_rules( $Validation );

		$validated_data = $this->validator->run($parameters);

		if($validated_data === false) 
		{
			
			 if(isset($parameters['updateJob_wpnonce']))
			{
				return new WP_Error( 'error',$this->validator->get_errors_array(),array( 'status' => 200) );	
			}
			else
			{
				$Error = $this->validator->get_errors_array();
				$this->WPmodify->response( 0, "" , reset($Error) );
			}		
		} 
		else 
		{
			return true;
		}

	}

	public function applyForJobValidation( $request , $ajax = NULL )
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
			'user_id'    				=> 'required',
			'user_type'    				=> 'required|contains,2',
			'job_id'    				=> 'required',
			'job_title'   				=> 'required',
			'quoted_price'    			=> 'required'			
		);


		$this->validator->validation_rules( $Validation );

		$validated_data = $this->validator->run($parameters);

		if($validated_data === false) 
		{
			
			 if(isset($parameters['CreateJob_wpnonce']))
			{
				return new WP_Error( 'error',$this->validator->get_errors_array(),array( 'status' => 200) );	
			}
			else
			{
				$Error = $this->validator->get_errors_array();
				$this->WPmodify->response( 0, "" , reset($Error) );
			}		
		} 
		else 
		{
			return true;
		}
	}

	public function applyForJobFileValidation( $request , $ajax = NULL )
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
			'user_id'    				=> 'required',
			'bid_id'    				=> 'required',
			'user_type'    				=> 'required|contains,2'
			//'file'    					=> 'max_len,1',					
		);


		$this->validator->validation_rules( $Validation );

		$validated_data = $this->validator->run($parameters);

		if($validated_data === false) 
		{
			
			 if(isset($parameters['CreateJob_wpnonce']))
			{
				return new WP_Error( 'error',$this->validator->get_errors_array(),array( 'status' => 200) );	
			}
			else
			{
				$Error = $this->validator->get_errors_array();
				$this->WPmodify->response( 0, "" , reset($Error) );
			}		
		} 
		else 
		{
			return true;
		}
	}

	public function updateBidJobValidation( $request , $ajax = NULL )
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
			'user_id'    				=> 'required',
			'user_type'    				=> 'required|contains,2',
			'job_id'    				=> 'required',			
			'quoted_price'    			=> 'required',
			'bid_id'					=> 'required'
		);


		$this->validator->validation_rules( $Validation );

		$validated_data = $this->validator->run($parameters);

		if($validated_data === false) 
		{
			
			 if(isset($parameters['CreateJob_wpnonce']))
			{
				return new WP_Error( 'error',$this->validator->get_errors_array(),array( 'status' => 200) );	
			}
			else
			{
				$Error = $this->validator->get_errors_array();
				$this->WPmodify->response( 0, "" , reset($Error) );
			}		
		} 
		else 
		{
			return true;
		}
	}
	public function removeAttachmentValidation( $request , $ajax = NULL )
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
			'user_id'    				=> 'required',
			'user_type'    				=> 'required|contains,1 2',
			'attachment_id'    			=> 'required'			
		);
		$this->validator->validation_rules( $Validation );		$validated_data = $this->validator->run($parameters);
		if($validated_data === false) 
		{			if(isset($parameters['CreateJob_wpnonce']))
			{
				return new WP_Error( 'error',$this->validator->get_errors_array(),array( 'status' => 200) );
			}
			else
			{
				$Error = $this->validator->get_errors_array();
				$this->WPmodify->response( 0, "" , reset($Error) );
			}
		} 
		else 
		{
			return true;		}
	}

	public function serviceJobListingValidation( $request , $ajax = NULL )
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
			'user_id'    				=> 'required',
			'user_type'    				=> 'required|contains,2',
			'status'      			 	=> "required|contains,0 1 2 3|exact_len,1"	
					
		);


		$this->validator->validation_rules( $Validation );

		$validated_data = $this->validator->run($parameters);

		if($validated_data === false) 
		{
			
			 if(isset($parameters['CreateJob_wpnonce']))
			{
				return new WP_Error( 'error',$this->validator->get_errors_array(),array( 'status' => 200) );	
			}
			else
			{
				$Error = $this->validator->get_errors_array();
				$this->WPmodify->response( 0, "" , reset($Error) );
			}		
		} 
		else 
		{
			return true;
		}
	}

	public function acceptBidJobValidation( $request , $ajax = NULL )
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
			'bid_id'    				=> 'required',
			'job_id'    				=> 'required',
			'user_id'    				=> 'required',
			'user_type'    				=> 'required|contains,1 2',
			'status'      			 	=> "required|contains,1 2|exact_len,1"				
		);


		$this->validator->validation_rules( $Validation );

		$validated_data = $this->validator->run($parameters);

		if($validated_data === false) 
		{
			
			 if(isset($parameters['CreateJob_wpnonce']))
			{
				return new WP_Error( 'error',$this->validator->get_errors_array(),array( 'status' => 200) );	
			}
			else
			{
				$Error = $this->validator->get_errors_array();
				$this->WPmodify->response( 0, "" , reset($Error) );
			}		
		} 
		else 
		{
			return true;
		}

	}

	public function updateJobProgressValidation( $request , $ajax = NULL )
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
			'job_id'    				=> 'required',
			'user_id'    				=> 'required',
			'job_progress'    			=> 'required|contains,0 1 2 3',
			'user_type'    				=> 'required|contains,2'			
					
		);


		$this->validator->validation_rules( $Validation );

		$validated_data = $this->validator->run($parameters);

		if($validated_data === false) 
		{
			
			 if(isset($parameters['CreateJob_wpnonce']))
			{
				return new WP_Error( 'error',$this->validator->get_errors_array(),array( 'status' => 200) );	
			}
			else
			{
				$Error = $this->validator->get_errors_array();
				$this->WPmodify->response( 0, "" , reset($Error) );
			}		
		} 
		else 
		{
			return true;
		}
	}

	public function markJobCompleteValidation( $request , $ajax = NULL )
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
			'user_id'    				=> 'required',
			'user_type'    				=> 'required|contains,1',
			'job_id'    				=> 'required',			
			'job_status'    			=> 'required|contains,2',
			'rating_by_cust'			=> 'numeric'
			//'feedback_by_cust'			=> 'required',
			//'rating_by_provider'			=> 'required',
			//'feedback_by_provider'		=> 'required'
					
		);


		$this->validator->validation_rules( $Validation );

		$validated_data = $this->validator->run($parameters);

		if($validated_data === false) 
		{
			
			 if(isset($parameters['CreateJob_wpnonce']))
			{
				return new WP_Error( 'error',$this->validator->get_errors_array(),array( 'status' => 200) );	
			}
			else
			{
				$Error = $this->validator->get_errors_array();
				$this->WPmodify->response( 0, "" , reset($Error) );
			}		
		} 
		else 
		{
			return true;
		}
	}

	public function submitCustomerReviewValidation( $request , $ajax = NULL )
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
			'user_id'    				=> 'required',
			'user_type'    				=> 'required|contains,2',
			'job_id'    				=> 'required',
			'rating_by_provider'		=> 'numeric'	
					
		);


		$this->validator->validation_rules( $Validation );

		$validated_data = $this->validator->run($parameters);

		if($validated_data === false) 
		{
			
			 if(isset($parameters['CreateJob_wpnonce']))
			{
				return new WP_Error( 'error',$this->validator->get_errors_array(),array( 'status' => 200) );	
			}
			else
			{
				$Error = $this->validator->get_errors_array();
				$this->WPmodify->response( 0, "" , reset($Error) );
			}		
		} 
		else 
		{
			return true;
		}
	}

	public function getBidDetailValidation( $request , $ajax = NULL )
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
			//'user_id'    				=> 'required',
			//'user_type'    				=> 'required|contains,1 2',
			'bid_id'    				=> 'required'					
		);


		$this->validator->validation_rules( $Validation );

		$validated_data = $this->validator->run($parameters);

		if($validated_data === false) 
		{
			
			 if(isset($parameters['CreateJob_wpnonce']))
			{
				return new WP_Error( 'error',$this->validator->get_errors_array(),array( 'status' => 200) );	
			}
			else
			{
				$Error = $this->validator->get_errors_array();
				$this->WPmodify->response( 0, "" , reset($Error) );
			}		
		} 
		else 
		{
			return true;
		}
	}

	public function declineInviteValidation( $request , $ajax = NULL )
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
			'user_id'    				=> 'required',
			'user_type'    				=> 'required|contains,2',
			'invite_id'    				=> 'required'					
		);


		$this->validator->validation_rules( $Validation );

		$validated_data = $this->validator->run($parameters);

		if($validated_data === false) 
		{
			
			 if(isset($parameters['CreateJob_wpnonce']))
			{
				return new WP_Error( 'error',$this->validator->get_errors_array(),array( 'status' => 200) );	
			}
			else
			{
				$Error = $this->validator->get_errors_array();
				$this->WPmodify->response( 0, "" , reset($Error) );
			}		
		} 
		else 
		{
			return true;
		}
	}	

	public function removeInviteValidation( $request , $ajax = NULL )
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
			'user_id'    				=> 'required',
			'user_type'    				=> 'required|contains,2',
			'invite_id'    				=> 'required'					
		);


		$this->validator->validation_rules( $Validation );

		$validated_data = $this->validator->run($parameters);

		if($validated_data === false) 
		{
			
			 if(isset($parameters['CreateJob_wpnonce']))
			{
				return new WP_Error( 'error',$this->validator->get_errors_array(),array( 'status' => 200) );	
			}
			else
			{
				$Error = $this->validator->get_errors_array();
				$this->WPmodify->response( 0, "" , reset($Error) );
			}		
		} 
		else 
		{
			return true;
		}
	}

	public function submitAnswerValidation( $request , $ajax = NULL )
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
			'bid_id'    				=> 'required',
			'user_id'    				=> 'required',					
			'user_type'    				=> 'required|contains,1'
		);


		$this->validator->validation_rules( $Validation );

		$validated_data = $this->validator->run($parameters);

		if($validated_data === false) 
		{
			
			 if(isset($parameters['CreateJob_wpnonce']))
			{
				return new WP_Error( 'error',$this->validator->get_errors_array(),array( 'status' => 200) );	
			}
			else
			{
				$Error = $this->validator->get_errors_array();
				$this->WPmodify->response( 0, "" , reset($Error) );
			}		
		} 
		else 
		{
			return true;
		}
	}

	public function getWorkHistoryValidation( $request , $ajax = NULL )
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
			'user_id'    				=> 'required',					
			'user_type'    				=> 'required|contains,1 2'
		);


		$this->validator->validation_rules( $Validation );

		$validated_data = $this->validator->run($parameters);

		if($validated_data === false) 
		{
			
			 if(isset($parameters['CreateJob_wpnonce']))
			{
				return new WP_Error( 'error',$this->validator->get_errors_array(),array( 'status' => 200) );	
			}
			else
			{
				$Error = $this->validator->get_errors_array();
				$this->WPmodify->response( 0, "" , reset($Error) );
			}		
		} 
		else 
		{
			return true;
		}
	}

	public function requestBidValidation( $request , $ajax = NULL )
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
			'user_id'    				=> 'required',					
			'user_type'    				=> 'required|contains,1 2',
			'job_id'    				=> 'required',
			'invite_ids'    			=> 'required'
		);


		$this->validator->validation_rules( $Validation );

		$validated_data = $this->validator->run($parameters);

		if($validated_data === false) 
		{
			
			 if(isset($parameters['CreateJob_wpnonce']))
			{
				return new WP_Error( 'error',$this->validator->get_errors_array(),array( 'status' => 200) );	
			}
			else
			{
				$Error = $this->validator->get_errors_array();
				$this->WPmodify->response( 0, "" , reset($Error) );
			}		
		} 
		else 
		{
			return true;
		}
	}

	public function deleteJobValidation( $request , $ajax = NULL )
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
			'user_id'    				=> 'required',					
			'user_type'    				=> 'required|contains,1 2',
			'job_id'    				=> 'required'			
		);


		$this->validator->validation_rules( $Validation );

		$validated_data = $this->validator->run($parameters);

		if($validated_data === false) 
		{
			
			 if(isset($parameters['CreateJob_wpnonce']))
			{
				return new WP_Error( 'error',$this->validator->get_errors_array(),array( 'status' => 200) );	
			}
			else
			{
				$Error = $this->validator->get_errors_array();
				$this->WPmodify->response( 0, "" , reset($Error) );
			}		
		} 
		else 
		{
			return true;
		}
	}

	public function jobPaymentValidation( $request , $ajax = NULL )
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
			'user_id'    				=> 'required',					
			'user_type'    				=> 'required|contains,1',
			'job_id'    				=> 'required',			
			'bid_id'    				=> 'required',			
			'amount'    				=> 'required',			
			'card_token'    			=> 'required'			
		);


		$this->validator->validation_rules( $Validation );

		$validated_data = $this->validator->run($parameters);

		if($validated_data === false) 
		{
			
			 if(isset($parameters['CreateJob_wpnonce']))
			{
				return new WP_Error( 'error',$this->validator->get_errors_array(),array( 'status' => 200) );	
			}
			else
			{
				$Error = $this->validator->get_errors_array();
				$this->WPmodify->response( 0, "" , reset($Error) );
			}		
		} 
		else 
		{
			return true;
		}
	}

	public function giveTipValidation( $request , $ajax = NULL )
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
			'user_id'    				=> 'required',					
			'user_type'    				=> 'required|contains,1',
			'job_id'    				=> 'required',			
			'service_Provider_ID'    	=> 'required',			
			'amount'    				=> 'required',			
			'card_token'    			=> 'required'			
		);


		$this->validator->validation_rules( $Validation );

		$validated_data = $this->validator->run($parameters);

		if($validated_data === false) 
		{
			
			 if(isset($parameters['CreateJob_wpnonce']))
			{
				return new WP_Error( 'error',$this->validator->get_errors_array(),array( 'status' => 200) );	
			}
			else
			{
				$Error = $this->validator->get_errors_array();
				$this->WPmodify->response( 0, "" , reset($Error) );
			}		
		} 
		else 
		{
			return true;
		}
	}


	public function getPaymentHistoryValidation( $request , $ajax = NULL )
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
			'user_id'    				=> 'required',					
			'user_type'    				=> 'required|contains,1 2',						
		);


		$this->validator->validation_rules( $Validation );

		$validated_data = $this->validator->run($parameters);

		if($validated_data === false) 
		{
			
			 if(isset($parameters['CreateJob_wpnonce']))
			{
				return new WP_Error( 'error',$this->validator->get_errors_array(),array( 'status' => 200) );	
			}
			else
			{
				$Error = $this->validator->get_errors_array();
				$this->WPmodify->response( 0, "" , reset($Error) );
			}		
		} 
		else 
		{
			return true;
		}
	}

	/*public function sendInvitesJobValidation( $request , $ajax = NULL )
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
			'job_id'    				=> 'required',
			'user_id'    				=> 'required',
			'user_type'    				=> 'required|contains,1 2',
			'invite_ids'      			=> "required"			
					
		);


		$this->validator->validation_rules( $Validation );

		$validated_data = $this->validator->run($parameters);

		if($validated_data === false) 
		{
			
			 if(isset($parameters['CreateJob_wpnonce']))
			{
				return new WP_Error( 'error',$this->validator->get_errors_array(),array( 'status' => 200) );	
			}
			else
			{
				$Error = $this->validator->get_errors_array();
				$this->WPmodify->response( 0, "" , reset($Error) );
			}		
		} 
		else 
		{
			return true;
		}
	}
	*/
}
new Jobs_valid();