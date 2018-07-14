<?php
/**
 * The header for our theme
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package WordPress
 * @subpackage Twenty_Seventeen
 * @since 1.0
 * @version 1.0
 */
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?> class="no-js no-svg">

<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">
    <title>:: Services Just Like That - Home ::</title>    
    <?php wp_head(); ?>
</head>
<body <?php body_class(); ?> >
<?php $fields = get_fields('option'); //pre($fields); ?>

    <header>
        <nav id="nav" class="navbar navbar-default">
            <div class="container">
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar"> <span class="sr-only">Toggle navigation</span> <span class="icon-bar"></span> <span class="icon-bar"></span> <span class="icon-bar"></span></button>
                    <a class="navbar-brand logo" href="<?php echo site_url(); ?>"><img src="<?php echo $fields['header_logo']['url'] ?>" alt="<?php echo $fields['header_logo']['alt'] ?>" title="<?php echo $fields['header_logo']['title'] ?>"></a>
                </div>
                    <?php wp_nav_menu( array(  'menu' => 'Top Menu' , 'container_class' => 'navbar-collapse collapse' , 'menu_class'=> 'nav navbar-nav' ) ); ?>
               <!-- <div id="navbar" class="navbar-collapse collapse">
                    <ul class="nav navbar-nav">
                        <li class="current-menu-item"><a href="index.html" title="">Home</a></li>
                        <li><a href="#" title="">About Us</a></li>
                        <li><a href="#" title="">How it works</a></li>
                        <li><a href="#" title="">Directory</a></li>
                        <li><a href="job-listing.html" title="">Jobs</a></li>
                        <li><a href="faq.html" title="">FAQ</a></li>
                        <li><a href="#" title="">Blog</a></li>
                        <li><a href="contact.html" title="">Contact Us</a></li>
                    </ul>
                </div>-->
                <div class="header_login">
                    <ul>
                    	<?php

                    	if( is_user_logged_in() )
                    	{	
                        /* Get Notification */
                        $user = wp_get_current_user();
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
                        $Notification = new WP_REST_Request( 'GET', '/jobs/getNotification' );
                        $Notification->set_query_params(array(
                            'user_id'   => get_current_user_id(),                
                            'user_type' => $userRole,                
                            'is_web'    => 1            
                        ));
                        $Jobs = new Jobs();
                        //header("Authenticate-Token: $accessToken");
                        $Noti = $Jobs->getNotification($Notification);

                            ?>

                        <li class="dropdown">
                           <a class="dropdown-toggle"data-toggle="dropdown"><i class="fa fa-bell-o" aria-hidden="true"></i>
                           </a>
                           <ul class="dropdown-menu">
                            <?php 
                            if( !empty( $Noti['results'] ) ): 
                                $ANoti = array_slice($Noti['results'], 0, 3);
                                foreach( $ANoti  as $keys ): //pre($keys);
                                    if( $keys['is_read'] == 0 ):
                                    $Date = date( 'M Y' , strtotime( $keys["date"] ) );
                                     echo '<li><a href="#">'.$keys["noti_message"].' <span class="noti-date">'.$Date.'</span> </a></li>';
                                 endif;
                                endforeach;
                                echo '<li><a href="'.site_url( '/notification/' ).'"> All Notification </a></li>';
                            else:
                                  echo '<li><a href="javascript:void(0)">No Update !</a></li>';
                            endif; ?>
                           </ul>
                        </li>
                    	 <li>
                            <figure><img src="<?php echo  get_template_directory_uri();  ?>/assets/images/user.svg" alt="user"></figure><a href="<?php echo site_url( 'my-jobs' ); ?>" title="">My Jobs</a></li>
                        <li>
                            <figure><img src="<?php echo  get_template_directory_uri();  ?>/assets/images/login.svg" alt="login"></figure><a href="<?php echo wp_logout_url( site_url() ); ?> " title="">Logout</a></li>	
                       	

				<?php  	}
                    	else{
                    	?>



                        <li>
                            <figure><img src="<?php echo  get_template_directory_uri();  ?>/assets/images/user.svg" alt="user"></figure><a href="<?php echo site_url( 'sign-in' ); ?>" title="">log-in</a></li>
                        <li>
                            <figure><img src="<?php echo  get_template_directory_uri();  ?>/assets/images/login.svg" alt="login"></figure><a href="<?php echo site_url( 'sign-up' ); ?>" title="">Sign Up</a></li>
                    <?php } ?>
                    </ul>
                </div>
            </div>
        </nav>
    </header>
    <div class="clearfix"></div>
   