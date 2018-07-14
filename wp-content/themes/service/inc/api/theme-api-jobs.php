<?php 
/* WP_REST_Server::READABLE = ‘GET’

WP_REST_Server::EDITABLE = ‘POST, PUT, PATCH’

WP_REST_Server::DELETABLE = ‘DELETE’

WP_REST_Server::ALLMETHODS = ‘GET, POST, PUT, PATCH, DELETE’
WP_REST_Server::CREATABLE

 */

class Jobs_api extends WP_REST_Controller
{
	var $my_namespace = 'jobs';
	var $validator;
	var $WPmodify;
	var $Jobs_valid;
	var $Jobs;
  	
	public function __construct()
	{
		$this->validator = new Validator();
		$this->Jobs_valid = new Jobs_valid();		
		$this->Jobs = new Jobs();
			
		//$this->WPmodify = new WPmodify();
		add_action( 'rest_api_init', array( $this, 'RegisterRoutes' ) );
	}
	
	public function RegisterRoutes()
	{
	
		
		register_rest_route( $this->my_namespace, '/getservicetype',array(
			'methods'         => WP_REST_Server::READABLE,
			'callback'        => array( $this->Jobs, 'getservicetype' )		
			) 
		);	

		register_rest_route( $this->my_namespace, '/createJob',array(
		'methods'         => WP_REST_Server::CREATABLE,
		'callback'        => array( $this->Jobs, 'createJob' ),	
		'permission_callback'   => array( $this->Jobs_valid, 'createJobValidation' )		
			) 
		);
		
		register_rest_route( $this->my_namespace, '/updateJob',array(
		'methods'         => WP_REST_Server::CREATABLE,
		'callback'        => array( $this->Jobs, 'updateJob' ),	
		'permission_callback'   => array( $this->Jobs_valid, 'updateJobValidation' )		
			) 
		);

		register_rest_route( $this->my_namespace, '/deleteJob',array(
		'methods'         => WP_REST_Server::CREATABLE,
		'callback'        => array( $this->Jobs, 'deleteJob' ),	
		'permission_callback'   => array( $this->Jobs_valid, 'deleteJobValidation' )		
			) 
		);
		
		register_rest_route( $this->my_namespace, '/getJobListing',array(
			'methods'         => WP_REST_Server::READABLE,
			'callback'        => array( $this->Jobs, 'getJobListing' )		
			) 
		);

		register_rest_route( $this->my_namespace, '/getBidDetail',array(
			'methods'         => WP_REST_Server::READABLE,
			'callback'        => array( $this->Jobs, 'getBidDetail' ),
			'permission_callback'   => array( $this->Jobs_valid, 'getBidDetailValidation' )					
			) 
		);


		register_rest_route( $this->my_namespace, '/customerJobListing',array(
			'methods'         => WP_REST_Server::READABLE,
			'callback'        => array( $this->Jobs, 'customerJobListing' )		
			) 
		);

		register_rest_route( $this->my_namespace, '/serviceJobListing',array(
			'methods'         => WP_REST_Server::READABLE,
			'callback'        => array( $this->Jobs, 'serviceJobListing' ),
			'permission_callback'   => array( $this->Jobs_valid, 'serviceJobListingValidation' )		
			) 
		);
		
		register_rest_route( $this->my_namespace, '/getJobDetail',array(
			'methods'         => WP_REST_Server::READABLE,
			'callback'        => array( $this->Jobs, 'getJobDetail' )			
			) 
		);


		/* Bids */

		register_rest_route( $this->my_namespace, '/getBids',array(
			'methods'         => WP_REST_Server::READABLE,
			'callback'        => array( $this->Jobs, 'getBids' )			
			) 
		);

		register_rest_route( $this->my_namespace, '/requestBid',array(
			'methods'         => WP_REST_Server::CREATABLE,
			'callback'        => array( $this->Jobs, 'requestBid' ),	
			'permission_callback'   => array( $this->Jobs_valid, 'requestBidValidation' )					
			) 
		);

		register_rest_route( $this->my_namespace, '/applyForJob',array(
		'methods'         => WP_REST_Server::CREATABLE,
		'callback'        => array( $this->Jobs, 'applyForJob' ),	
		'permission_callback'   => array( $this->Jobs_valid, 'applyForJobValidation' )		
			) 
		);

		register_rest_route( $this->my_namespace, '/applyForJobFile',array(
		'methods'         => WP_REST_Server::CREATABLE,
		'callback'        => array( $this->Jobs, 'applyForJobFile' ),	
		'permission_callback'   => array( $this->Jobs_valid, 'applyForJobFileValidation' )		
			) 
		);

		register_rest_route( $this->my_namespace, '/updateBid',array(
		'methods'         => WP_REST_Server::EDITABLE,
		'callback'        => array( $this->Jobs, 'updateBid' ),	
		'permission_callback'   => array( $this->Jobs_valid, 'updateBidJobValidation' )		
			) 
		);

		register_rest_route( $this->my_namespace, '/acceptBid',array(
		'methods'         => WP_REST_Server::CREATABLE,
		'callback'        => array( $this->Jobs, 'acceptBid' ),	
		'permission_callback'   => array( $this->Jobs_valid, 'acceptBidJobValidation' )		
			) 
		);

		register_rest_route( $this->my_namespace, '/declineInvite',array(
		'methods'         => WP_REST_Server::CREATABLE,
		'callback'        => array( $this->Jobs, 'declineInvite' ),	
		'permission_callback'   => array( $this->Jobs_valid, 'declineInviteValidation' )
		) 
		);

		register_rest_route( $this->my_namespace, '/removeInvite',array(
		'methods'         => WP_REST_Server::CREATABLE,
		'callback'        => array( $this->Jobs, 'removeInvite' ),	
		'permission_callback'   => array( $this->Jobs_valid, 'removeInviteValidation' )
		) 
		);

		register_rest_route( $this->my_namespace, '/submitAnswer',array(
		'methods'         => WP_REST_Server::CREATABLE,
		'callback'        => array( $this->Jobs, 'submitAnswer' ),	
		'permission_callback'   => array( $this->Jobs_valid, 'submitAnswerValidation' )		
			) 
		);

		/*register_rest_route( $this->my_namespace, '/sendInvites',array(
		'methods'         => WP_REST_Server::CREATABLE,
		'callback'        => array( $this->Jobs, 'sendInvites' ),	
		'permission_callback'   => array( $this->Jobs_valid, 'sendInvitesJobValidation' )		
			) 
		);*/

		register_rest_route( $this->my_namespace, '/updateJobProgress',array(
		'methods'         => WP_REST_Server::CREATABLE,
		'callback'        => array( $this->Jobs, 'updateJobProgress' ),	
		'permission_callback'   => array( $this->Jobs_valid, 'updateJobProgressValidation' )		
			) 
		);

		register_rest_route( $this->my_namespace, '/markJobComplete',array(
		'methods'         => WP_REST_Server::CREATABLE,
		'callback'        => array( $this->Jobs, 'markJobComplete' ),	
		'permission_callback'   => array( $this->Jobs_valid, 'markJobCompleteValidation' )		
			) 
		);	

		register_rest_route( $this->my_namespace, '/submitCustomerReview',array(
		'methods'         => WP_REST_Server::CREATABLE,
		'callback'        => array( $this->Jobs, 'submitCustomerReview' ),	
		'permission_callback'   => array( $this->Jobs_valid, 'submitCustomerReviewValidation' )		
			) 
		);

		register_rest_route( $this->my_namespace, '/getWorkHistory',array(
			'methods'         => WP_REST_Server::READABLE,
			'callback'        => array( $this->Jobs, 'getWorkHistory' ),
			'permission_callback'   => array( $this->Jobs_valid, 'getWorkHistoryValidation' )			
			) 
		);

		register_rest_route( $this->my_namespace, '/jobPayment',array(
		'methods'         => WP_REST_Server::CREATABLE,
		'callback'        => array( $this->Jobs, 'jobPayment' ),	
		'permission_callback'   => array( $this->Jobs_valid, 'jobPaymentValidation' )		
			) 
		);				register_rest_route( $this->my_namespace, '/removeAttachment',array(		'methods'         => WP_REST_Server::CREATABLE,		'callback'        => array( $this->Jobs, 'removeAttachment' ),			'permission_callback'   => array( $this->Jobs_valid, 'removeAttachmentValidation' )					) 		);

		register_rest_route( $this->my_namespace, '/giveTip',array(
		'methods'         => WP_REST_Server::CREATABLE,
		'callback'        => array( $this->Jobs, 'giveTip' ),	
		'permission_callback'   => array( $this->Jobs_valid, 'giveTipValidation' )		
			) 
		);

		register_rest_route( $this->my_namespace, '/getPaymentHistory',array(
			'methods'         => WP_REST_Server::READABLE,
			'callback'        => array( $this->Jobs, 'getPaymentHistory' ),
			'permission_callback'   => array( $this->Jobs_valid, 'getPaymentHistoryValidation' )			
			) 
		);


		register_rest_route( $this->my_namespace, '/getNotification',array(
			'methods'         => WP_REST_Server::READABLE,
			'callback'        => array( $this->Jobs, 'getNotification' )
			//'permission_callback'   => array( $this->Jobs_valid, 'getWorkHistoryValidation' )			
			) 
		);
	
	}
	
	
}
new Jobs_api();