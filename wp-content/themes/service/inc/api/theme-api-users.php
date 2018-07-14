<?php 
/* 
WP_REST_Server::READABLE = ‘GET’
WP_REST_Server::EDITABLE = ‘POST, PUT, PATCH’
WP_REST_Server::DELETABLE = ‘DELETE’
WP_REST_Server::ALLMETHODS = ‘GET, POST, PUT, PATCH, DELETE’
WP_REST_Server::CREATABLE
 */

class Users_api extends WP_REST_Controller
{
	var $my_namespace = 'users';
	var $validator;
	var $Users_valid;
	var $Users;
	var $WPmodify;
  	
	public function __construct()
	{
		//$this->validator = new Validator();
		$this->Users_valid = new Users_valid();
		
		$this->Users = new Users();
			
		//$this->WPmodify = new WPmodify();
		add_action( 'rest_api_init', array( $this, 'RegisterRoutes' ) );
	}
	
	public function RegisterRoutes()
	{
		
		register_rest_route( $this->my_namespace, '/userRegistration',array(
			'methods'         		=> WP_REST_Server::CREATABLE,
			'callback'        		=> array( $this->Users, 'user_Registration' ),	
			'permission_callback'   => array( $this->Users_valid, 'userRegistrationValidate' )		
			) 
		);

		register_rest_route( $this->my_namespace, '/login',array(
		'methods'         => WP_REST_Server::CREATABLE,
		'callback'        => array( $this->Users, 'user_SignIn' ),	
		'permission_callback'   => array( $this->Users_valid, 'userSignValidation' )		
			) 
		);		

		register_rest_route( $this->my_namespace, '/logout',array(
		'methods'         => WP_REST_Server::CREATABLE,
		'callback'        => array( $this->Users, 'logout' )		
			) 
		);	

		register_rest_route( $this->my_namespace, '/socialLogin',array(
		'methods'         => WP_REST_Server::CREATABLE,
		'callback'        => array( $this->Users, 'socialLogin' ),	
		'permission_callback'   => array( $this->Users_valid, 'socialLoginValidation' )		
			) 
		);	

		register_rest_route( $this->my_namespace, '/UpdateProfile',array(
		'methods'         =>  WP_REST_Server::EDITABLE,
		'callback'        => array( $this->Users, 'UpdateProfile' ),	
		'permission_callback'   => array( $this->Users_valid, 'ValidationProfile' )		
			) 
		);

		register_rest_route( $this->my_namespace, '/PushNotificationSetting',array(
		'methods'         => WP_REST_Server::CREATABLE,
		'callback'        => array( $this->Users, 'PushNotificationSetting' )		
			) 
		);

		register_rest_route( $this->my_namespace, '/UpdateBusinessProfile',array(
		'methods'         =>  WP_REST_Server::EDITABLE,
		'callback'        => array( $this->Users, 'UpdateBusinessProfile' ),	
		'permission_callback'   => array( $this->Users_valid, 'UpdateBusinessProfileValidate' )		
			) 
		);

		register_rest_route( $this->my_namespace, '/ResetPassword',array(
		'methods'         => WP_REST_Server::CREATABLE,
		'callback'        => array( $this->Users, 'ResetPassword' ),
		'permission_callback'   => array( $this->Users_valid, 'ResetPasswordValidate' )		
		) 
		);

		register_rest_route( $this->my_namespace, '/forgotPassword',array(
		'methods'         => WP_REST_Server::EDITABLE,
		'callback'        => array( $this->Users, 'forgotPassword' ),
		'permission_callback'   => array( $this->Users_valid, 'forgotPasswordValidate' )		
		) 
	);
		
		register_rest_route( $this->my_namespace, '/getprofile',array(
		'methods'         => WP_REST_Server::READABLE,
		'callback'        => array( $this->Users, 'getprofile' ),
		//'permission_callback'   => array( $this->Users_valid, 'getprofileValidate' )		
			) 
		);

		register_rest_route( $this->my_namespace, '/getPortfolio',array(
		'methods'         => WP_REST_Server::READABLE,
		'callback'        => array( $this->Users, 'getPortfolio' )
		) 
		);

		register_rest_route( $this->my_namespace, '/Portfolio',array(
		'methods'         =>  WP_REST_Server::CREATABLE,
		'callback'        => array( $this->Users, 'Portfolio' )		
			) 
		);

		register_rest_route( $this->my_namespace, '/deletePortfolio',array(
		'methods'         => WP_REST_Server::DELETABLE,
		'callback'        => array( $this->Users, 'deletePortfolio' ),
		//'permission_callback'   => array( $this->Users_valid, 'getprofileValidate' )		
			) 
		);

		register_rest_route( $this->my_namespace, '/getServiceProvider',array(
		'methods'         => WP_REST_Server::READABLE,
		'callback'        => array( $this->Users, 'getServiceProvider' )
		) 
		);

		/* Inbox Apis */

		register_rest_route( $this->my_namespace, '/getMessageThreads',array(
		'methods'         => WP_REST_Server::READABLE,
		'callback'        => array( $this->Users, 'getMessageThreads' )
		) 
		);

		register_rest_route( $this->my_namespace, '/sendMessage',array(
		'methods'         => WP_REST_Server::CREATABLE,
		'callback'        => array( $this->Users, 'sendMessage' ),
		'permission_callback'   => array( $this->Users_valid, 'sendMessageValidate' )		
		) 
		);

		register_rest_route( $this->my_namespace, '/getMessage',array(
		'methods'         => WP_REST_Server::READABLE,
		'callback'        => array( $this->Users, 'getMessage' ),
		'permission_callback'   => array( $this->Users_valid, 'getMessageValidate' )
		) 
		);

		/* Payment Cards */

		register_rest_route( $this->my_namespace, '/createCard',array(
		'methods'         => WP_REST_Server::CREATABLE,
		'callback'        => array( $this->Users, 'createCard' ),
		'permission_callback'   => array( $this->Users_valid, 'createCardValidate' )		
		) 
		);


		register_rest_route( $this->my_namespace, '/UpdateCard',array(
		'methods'         => WP_REST_Server::CREATABLE,
		'callback'        => array( $this->Users, 'UpdateCard' ),
		'permission_callback'   => array( $this->Users_valid, 'UpdateCardValidate' )		
		) 
		);

		register_rest_route( $this->my_namespace, '/DeleteCard',array(
		'methods'         => WP_REST_Server::CREATABLE,
		'callback'        => array( $this->Users, 'DeleteCard' ),
		'permission_callback'   => array( $this->Users_valid, 'DeleteCardValidate' )		
		) 
		);				register_rest_route( $this->my_namespace, '/NotificationRead',array(
		'methods'         => WP_REST_Server::CREATABLE,
		'callback'        => array( $this->Users, 'NotificationRead' ),
		'permission_callback'   => array( $this->Users_valid, 'NotificationReadValidate' )		
		) 
		);

		register_rest_route( $this->my_namespace, '/getCards',array(
		'methods'         => WP_REST_Server::READABLE,
		'callback'        => array( $this->Users, 'getCards' ),
		'permission_callback'   => array( $this->Users_valid, 'getCardsValidate' )
		) 
		);				register_rest_route( $this->my_namespace, '/getAlerts',array(
		'methods'         => WP_REST_Server::READABLE,
		'callback'        => array( $this->Users, 'getAlerts' ),
		'permission_callback'   => array( $this->Users_valid, 'getAlertsValidate' )
		) 
		);

		register_rest_route( $this->my_namespace, '/refreshToken',array(
		'methods'         => WP_REST_Server::CREATABLE,
		'callback'        => array( $this->Users, 'refreshToken' )
		
		) 
		);
		
		register_rest_route( $this->my_namespace, '/updateRead',array(
		'methods'         => WP_REST_Server::CREATABLE,
		'callback'        => array( $this->Users, 'updateRead' ),
		'permission_callback'   => array( $this->Users_valid, 'updateReadValidate' )
		) 
		);

		register_rest_route( $this->my_namespace, '/getEmailExists',array(
		'methods'         => WP_REST_Server::READABLE,
		'callback'        => array( $this->Users, 'getEmailExists' )		
		) 
		);

		register_rest_route( $this->my_namespace, '/WebsocialLogin',array(
		'methods'         => WP_REST_Server::CREATABLE,
		'callback'        => array( $this->Users, 'WebsocialLogin' ),	
		'permission_callback'   => array( $this->Users_valid, 'WebsocialLoginValidation' )		
			) 
		);	

		/*			
			BrainTree_create_payment			

		*/

		
	}	
	
}
new Users_api();