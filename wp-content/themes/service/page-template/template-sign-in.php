<?php 
/**
 * Template Name: Sign In Template
 *
 * @author Chhavinav Sehgal
 * @copyright chhavinav.in
 * @link shadow_chhavi @skype
 */ 
 if( is_user_logged_in())
    {
        if ( ! current_user_can('administrator') ) 
        {
            echo "<script>window.location = '".site_url('/my-jobs/')."'</script>";
        }         
    }
get_header(); ?>
<?php  $fields = get_fields(); if ( have_posts() ) : while ( have_posts() ) : the_post();   ?>
 <div class="inner-pages sign-up-page sign-in-page">
        <div class="container">
            <div class="text-center">
                <h2>sign in</h2> </div>
            <div class="sign-up-cover">
             <form id="sign-in">
            <?php wp_nonce_field( 'Sign-in', 'SignIn_wpnonce'); ?> 
                <div class="select-sign-up">
                    <ul class="User-type">
                        <li>
                            <label>
                                <input type="radio" name="user_type" value="1" > <span class="custom-radio"></span> Customer </label>
                        </li>
                        <li>
                            <label>
                                <input type="radio" name="user_type" value="2" > <span class="custom-radio"></span> Service Provider </label>
                        </li>
                    </ul>
                </div>
                <div class="sign-up-form CustomerForm sign-in-form">                   
                        <div class="row">
                            <div class="col-md-12 col-sm-12 col-xs-12">
                                <div class="form-group">
                                    <input type="text" name="username_email" class="form-control" placeholder="Email"> </div>
                            </div>
                            <div class="col-md-12 col-sm-12 col-xs-12">
                                <div class="form-group">
                                    <input type="password" name="password" class="form-control" placeholder="Password"> </div>
                            </div>
                            
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <input type="submit" class="blue_btn sign-in" value="Sign in">
                            </div>
                            
                            <div class="col-md-6 col-sm-6 col-xs-12 forgot-text">
                                <p><a href="#"  data-toggle="modal" data-target="#forgotModal">Forgot Password?</a></p>
                            </div>
                           
                            <div class="text-center col-md-12 col-sm-12 col-xs-12">
                                
                                <div class="or">
                                    <p>Or</p>
                                </div>
                                <ul class="sign-in-btn-group">
                                    <li><a href="javascript:void(0)" id="Signin-button" class="sign-in-btn btn-google"><i class="fa fa-google" aria-hidden="true"></i> Sign in with Google </a></li>
                                    <li>
                                        <a href="javascript:void(0);" onclick="fbSignIn()" class="sign-in-btn btn-facebook"> <i class="fa fa-facebook" aria-hidden="true"></i> Sign in with Facebook</a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </form>
                </div>                
            </div>
        </div>
    </div>
<?php   endwhile; endif;  ?>
<?php get_footer(); ?>
<script async defer src="https://apis.google.com/js/api.js" onload="this.onload=function(){};HandleGoogleApiLibrary()" onreadystatechange="if (this.readyState === 'complete') this.onload()"></script>
<!-- Password Reset Modal -->
<div id="forgotModal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
        <form id="forgotPassword" method="POST"> 
        <?php wp_nonce_field( 'ForgotPassword' ,'ForgotPassword_wpnonce' ); ?>    
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h3 class="modal-title text-center">Reset Password</h3>
            </div>
            <div class="modal-body">            
                <div class="form-group">
                    <input type="email" required name="email" class="form-control" placeholder="Email"> 
                </div> 
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary reset-password">Reset</button>
            </div>
        </form>
    </div>
  </div>
</div>