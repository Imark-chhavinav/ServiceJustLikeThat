<?php 

function vb_reg_new_user() 
{
	$Valid  =  new Users_valid();
	$Valid->userRegistrationValidate( $_POST , 1 );
	
		
}
 
add_action('wp_ajax_register_user', 'vb_reg_new_user');
add_action('wp_ajax_nopriv_register_user', 'vb_reg_new_user');
