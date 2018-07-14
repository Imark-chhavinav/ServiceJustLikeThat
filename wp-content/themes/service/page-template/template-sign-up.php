<?php 
/**
 * Template Name: Sign Up Template
 *
 * @author Chhavinav Sehgal
 * @copyright chhavinav.in
 * @link shadow_chhavi @skype
 */
 apply_filters( 'ISLOGIN' ,'USER' ); 
get_header(); ?>
<link src="<?php echo  get_template_directory_uri().'/assets/css/bootstrap-datepicker.css' ?>">
<?php  $fields = get_fields(); if ( have_posts() ) : while ( have_posts() ) : the_post();   ?>
 <div class="inner-pages sign-up-page">
        <div class="container">
            <div class="text-center">
                <h2>sign up</h2> </div>
            <div class="sign-up-cover">
                <form id="Customer_Form">
                <div class="select-sign-up">
                    <ul>
                    	  <?php wp_nonce_field( 'Registration', 'reg_nonce' ); ?>
                        <li>
                            <label>
                                <input type="radio" value="1" name="user_type" id="CustomerForm"> <span class="custom-radio"></span> Customer </label>
                        </li>
                        <li>
                            <label>
                                <input type="radio" value="2" name="user_type" id="ServiceProviderForm"> <span class="custom-radio"></span> Service Provider </label>
                        </li>
                    </ul>
                </div>
                <div class="sign-up-form CustomerForm">                   
					<input type="hidden" name="lat" class="lat" value="" >
					<input type="hidden" name="longs" class="longs" value="" >
                        <div class="row">
                        	<div class="col-md-6 col-sm-6 col-xs-12">
                                <div class="form-group slct">
                                    <select name="title" class="placeholder1 form-control">
                                    	<option value=""> Select Title </option>
                                    	<option value="Mr"> Mr. </option>
                                    	<option value="Mrs"> Mrs. </option>
                                    	<option value="Miss"> Miss. </option>                                       
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <div class="form-group">
                                    <input type="text" name="first_name" class="form-control" placeholder="First Name*"> </div>
                            </div>
							<div class="col-md-6 col-sm-6 col-xs-12">
                                <div class="form-group">
                                    <input type="text" name="last_name" class="form-control" placeholder="Last Name*"> </div>
                            </div>
                            
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <div class="form-group">
                                    <input type="email" name="email" class="form-control" placeholder="Email Address*"> </div>
                            </div>
                             <div class="col-md-6 col-sm-6 col-xs-12">
                                <div class="form-group">
                                    <input type="text" name="username" class="form-control" placeholder="Username*"> </div>
                            </div>                            
                           
							
							<div class="col-md-6 col-sm-6 col-xs-12">
                                <div class="form-group">
                                    <input type="text" name="street_address" class="form-control" id="autocomplete" placeholder="Street Address*"> </div>
                            </div>
							
							<div class="col-md-6 col-sm-6 col-xs-12">
                                <div class="form-group">
                                    <input type="text" name="city" class="form-control" id="city" placeholder="City* "> 
                                </div>
                            </div>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <div class="form-group">
                                   <input class="form-control datepicker" placeholder="Date Of Birthday" name="dob">
                                </div>
                            </div>
                            
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <div class="form-group">
                                    <input type="number" name="postcode" class="form-control" id="postcode" placeholder="Postal Code*"> </div>
                            </div>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <div class="form-group slct">
                                    <select name="state" id="state" class="placeholder1 form-control state">
                                        <option value="">State</option>
                                        <option value="AL">Alabama</option>
                                        <option value="AK">Alaska</option>
                                        <option value="AZ">Arizona</option>
                                        <option value="AR">Arkansas</option>
                                        <option value="CA">California</option>
                                        <option value="CO">Colorado</option>
                                        <option value="CT">Connecticut</option>
                                        <option value="DE">Delaware</option>
                                        <option value="DC">District of Columbia</option>
                                        <option value="FL">Florida</option>
                                        <option value="GA">Georgia</option>
                                        <option value="HI">Hawaii</option>
                                        <option value="ID">Idaho</option>
                                        <option value="IL">Illinois</option>
                                        <option value="IN">Indiana</option>
                                        <option value="IA">Iowa</option>
                                        <option value="KS">Kansas</option>
                                        <option value="KY">Kentucky</option>
                                        <option value="LA">Louisiana</option>
                                        <option value="ME">Maine</option>
                                        <option value="MD">Maryland</option>
                                        <option value="MA">Massachusetts</option>
                                        <option value="MI">Michigan</option>
                                        <option value="MN">Minnesota</option>
                                        <option value="MS">Mississippi</option>
                                        <option value="MO">Missouri</option>
                                        <option value="MT">Montana</option>
                                        <option value="NE">Nebraska</option>
                                        <option value="NV">Nevada</option>
                                        <option value="NH">New Hampshire</option>
                                        <option value="NJ">New Jersey</option>
                                        <option value="NM">New Mexico</option>
                                        <option value="NY">New York</option>
                                        <option value="NC">North Carolina</option>
                                        <option value="ND">North Dakota</option>
                                        <option value="OH">Ohio</option>
                                        <option value="OK">Oklahoma</option>
                                        <option value="OR">Oregon</option>
                                        <option value="PA">Pennsylvania</option>
                                        <option value="RI">Rhode Island</option>
                                        <option value="SC">South Carolina</option>
                                        <option value="SD">South Dakota</option>
                                        <option value="TN">Tennessee</option>
                                        <option value="TX">Texas</option>
                                        <option value="UT">Utah</option>
                                        <option value="VT">Vermont</option>
                                        <option value="VA">Virginia</option>
                                        <option value="WA">Washington</option>
                                        <option value="WV">West Virginia</option>
                                        <option value="WI">Wisconsin</option>
                                        <option value="WY">Wyoming</option>
                                    </select>
                                </div>
                            </div>
                            
                             <div class="col-md-6 col-sm-6 col-xs-12">
                                <div class="form-group">
                                    <input type="text" name="phone_number" id="usNum" class="form-control" id="" placeholder="Phone Number*"> 
                                </div>
                            </div>
                            
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <div class="form-group">
                                    <input type="password" id="Confirm_password" name="password" class="form-control" placeholder="Password*"> </div>
                            </div>
                            
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <div class="form-group">
                                    <input type="password" name="Confirm_password" class="form-control" placeholder="Confirm Password*"> </div>
                            </div>
                            <span id="service-fields" class="hidden">
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <div class="form-group">
                                        <select class="placeholder1 form-control" name="business_type">
                                            <option value="">Select Business Type</option>
                                            <option value="1">Individual</option>
                                            <option value="2">Business</option>
                                        </select> 
                                    </div>
                                </div>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <div class="form-group">
                                        <input type="text" name="business_name" class="form-control" placeholder="Business Name"> 
                                    </div>
                                </div>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <div class="form-group">
                                        <?php echo do_shortcode('[CATG_DROP]'); ?>
                                    </div>
                                </div>																<div class="col-md-12 col-sm-12 col-xs-12">                                    <div class="form-group">										<textarea name="overview" placeholder="Company Overview" class="form-control" ></textarea>									</div>                                </div>								
                            </span>
                            <div class="text-center col-md-12 col-sm-12 col-xs-12 mb20">
                                <div class="form-group">
                                    <label>
                                        <input name="terms" type="checkbox"> <span class="custom-checkbox"></span> I accept Lorem Ipsum is simply dummy <a href="#">terms and condition</a> and typesetting industry. </label>
                                </div>
                            </div>
                            <div class="text-center col-md-12 col-sm-12 col-xs-12">
                                <button type="submit" class="blue_btn reg_me">
                                <span id="btn-sign-up"> Sign Up  </span>
                                <span class="hidden" id="loader-btn"><i class="fa fa-spinner fa-spin"></i>Loading</span>
                                </button>
                               
                                <div class="or">
                                    <p>Or</p>
                                </div>
                                <ul class="sign-in-btn-group">
                                    <li><a href="javascript:void(0)" id="login-button" class="sign-in-btn btn-google"><i class="fa fa-google" aria-hidden="true"></i> Sign in with Google </a></li>
                                    <li>
                                        <a href="javascript:void(0);" onclick="fblogin()"  class="sign-in-btn btn-facebook"> <i class="fa fa-facebook" aria-hidden="true"></i> Sign in with Facebook</a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                </div> 
                </form>               
            </div>
        </div>
    </div>
<?php   endwhile; endif;  ?>
<?php get_footer(); ?>
<script async defer src="https://apis.google.com/js/api.js" onload="this.onload=function(){};HandleGoogleApiLibrary()" onreadystatechange="if (this.readyState === 'complete') this.onload()"></script>
<script src="<?php echo  get_template_directory_uri().'/assets/js/check-number.js'; ?>">  </script>
<script src="<?php echo  get_template_directory_uri().'/assets/js/bootstrap-datepicker.min.js'; ?>">  </script>
<script type="text/javascript">
  $(document).ready(function() {

    $(document).on( 'click' ,'input[name="user_type"]' ,function()
    {
        var Type = $(this).val();
        if( Type == 2 )
        {
            $( '#service-fields' ).removeClass( "hidden" );
        }
        else
        {
             $( '#service-fields' ).addClass( "hidden" );
        }

    });

  	$('.datepicker').datepicker({
	    format: 'MM dd,yyyy'
	   
	});
   /* $('.multiselect').multiselect({
      includeSelectAllOption: true
    });*/
  });
</script>
    <script>
      // This example displays an address form, using the autocomplete feature
      // of the Google Places API to help users fill in the information.

      // This example requires the Places library. Include the libraries=places
      // parameter when you first load the API. For example:
      // <script src="https://maps.googleapis.com/maps/api/js?key=YOUR_API_KEY&libraries=places">

     var placeSearch, autocomplete , autocomplete2;
     var componentForm = {
        street_number: 'short_name',
        route: 'long_name',
        locality: 'long_name',
        administrative_area_level_1: 'short_name',
        country: 'long_name',
        postal_code: 'short_name'
      };

      function initAutocomplete() {
        // Create the autocomplete object, restricting the search to geographical
        // location types.
        autocomplete = new google.maps.places.Autocomplete(
            /** @type {!HTMLInputElement} */(document.getElementById('autocomplete')),
            {types: ['geocode']}); 
		autocomplete2 = new google.maps.places.Autocomplete(
            /** @type {!HTMLInputElement} */(document.getElementById('autocomplete2')),
            {types: ['geocode']});

            
        // When the user selects an address from the dropdown, populate the address
        // fields in the form.
        autocomplete.addListener('place_changed', fillInAddress);
        autocomplete2.addListener('place_changed', fillInAddress2);
      }

      function fillInAddress() 
	  {
        
        var place = autocomplete.getPlace();
       	$( 'input[name="city"]' ).val("");
		$("#state").val("");
		$( 'input[name="postcode"]' ).val("");
		$( 'input[name="lat"]' ).val("");
		$( 'input[name="longs"]' ).val("");

        $( place.address_components ).each(function( item , value )
        	{
        		
					/* City */
        		if( value.types[0] == 'administrative_area_level_2' )
        		{
        			$( 'input[name="city"]' ).val(value.long_name);
        		}

					/* State */	
        		 if( value.types[0] == 'administrative_area_level_1' )
        		{
        			$("#state").val(value.short_name);
        		}

        		/* postcode */	

        		if( value.types[0] == 'postal_code' )        		
        		{
        			$( 'input[name="postcode"]' ).val(value.long_name);
        		}        		


        	});

		$( 'input[name="lat"]' ).val(place.geometry.location.lat());
		$( 'input[name="longs"]' ).val(place.geometry.location.lng());
		
		
      }
	  function fillInAddress2() 
	  {
        
        var place = autocomplete2.getPlace();
        
		$( '#lat2' ).val("");
		$( '#longs2' ).val("");
		$( '#city2' ).val("");
		$( '#state2' ).val("");
		$( '#postcode2' ).val("");	


        $( place.address_components ).each(function( item , value )
        	{
        		
					/* City */
        		if( value.types[0] == 'administrative_area_level_2' )
        		{
        			$( '#city2' ).val(value.long_name);
        		}

					/* State */	
        		 if( value.types[0] == 'administrative_area_level_1' )
        		{
        			$("#state2").val(value.short_name);
        		}

        		/* postcode */	

        		if( value.types[0] == 'postal_code' )        		
        		{
        			$( '#postcode2' ).val(value.long_name);
        		}

    	});

    	$( '#lat2' ).val(place.geometry.location.lat());
		$( '#longs2' ).val(place.geometry.location.lng());  

      }

      // Bias the autocomplete object to the user's geographical location,
      // as supplied by the browser's 'navigator.geolocation' object.
      function geolocate() {
        if (navigator.geolocation) {
          navigator.geolocation.getCurrentPosition(function(position) {
            var geolocation = {
              lat: position.coords.latitude,
              lng: position.coords.longitude
            };
            var circle = new google.maps.Circle({
              center: geolocation,
              radius: position.coords.accuracy
            });
            autocomplete.setBounds(circle.getBounds());
          });
        }
      }
    </script>
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAZHrvncNkxI08KsBZUrH-9GkIi3WDfzlc&libraries=places&callback=initAutocomplete"></script>