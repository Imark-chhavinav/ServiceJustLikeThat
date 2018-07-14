<?php
/**
 * The template for displaying archive pages
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package WordPress
 * @subpackage Twenty_Seventeen
 * @since 1.0
 * @version 1.0
 */

get_header(); ?>
<section class="banner inner-banner">
        <div id="carousel-example-generic" class="carousel slide" data-ride="carousel">
            <!-- Wrapper for slides -->
            <div class="carousel-inner" role="listbox">
                <div class="item active" style="background-image:url(images/inner-page-banner.jpg);">
                    <div class="container">
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="banner_cntnt">
                                    <h1>Opportunities At <strong> Your Fingertips</strong></h1> </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
     <div class="inner-pages job-listing-page">
        <div class="container">
            <div class="text-center">
                <h2>Job Listing</h2> </div>
            <div class="row">
                <div class="col-md-3">
                    <div class="sidebar">
                        <form id="filter">
                            <?php wp_nonce_field( 'filter_action' , 'filter_nonce'); ?>
                            <input type="hidden" name="page" class="lat" value="1" >
                            <input type="hidden" value="<?php if( isset( $_GET['lat'] ) ){ echo $_GET['lat']; } ?>" name="lat" class="lat" value="" >
                            <input type="hidden" value="<?php if( isset( $_GET['longs'] ) ){ echo $_GET['longs']; } ?>" name="longs" class="longs" value="" >
                            <div class="form-group slct">
                                <input type="text" value="<?php if( isset( $_GET['job_search'] ) ){ echo $_GET['job_search']; } ?>" name="job_search" placeholder="Keyword" class="form-control">
                            </div>
                            <div class="form-group">
                               <input type="text"  value="<?php if( isset( $_GET['street_address'] ) ){ echo $_GET['street_address']; } ?>" name="street_address" class="form-control" id="autocomplete" placeholder="Location *"> 
                            </div>
                            <div class="form-group">
                        <?php  
                            $drop = "";                          
                            $categories = get_terms(array('taxonomy' => 'jobs_category','hide_empty' => false) );
                            $drop .= '<select name="service_type[]" class="selectpicker" multiple>';
                            $drop .= "<option value=''>Select Category</option>";
                            foreach( $categories as $catgs )
                            {    
								if( isset( $_GET['service_type'] ) )
								{
									if( $_GET['service_type'] == $catgs->term_id )
									{
										$drop .= "<option selected value='".$catgs->term_id."'>".$catgs->name."</option>";
									}
								}
								else
								{
									$drop .= "<option value='".$catgs->term_id."'>".$catgs->name."</option>";
								}
                                
                            }   
                            $drop .= '</select>';

                            echo $drop;     

                        ?>
                            </div>
                            <div class="form-group range-group">
                                <label>Choose Miles</label>
                                <input type="range" name="distance" min="0" max="100" step="1" value="0" data-rangeslider>
                                <output id="js-output" class="left-side-output"></output>
                                <output class="right-side-output">100</output>
                            </div>
                            <div class="text-center">
                                <input type="submit" class="blue_btn filter_btn" value="Filter results"> </div>
                        </form>
                    </div>
                </div>
            <div class="col-md-9 job-listing-cover">
			<?php  
			$x = 0; $fields = get_fields(); 
			if ( have_posts() ) : while ( have_posts() ) : the_post(); 
					
					$post = get_post(get_the_ID()); 
					$slug = $post->post_name;
                	$jobs_fields = get_fields( get_the_ID() );	
					
            if( isset($jobs_fields['job_status']) && $jobs_fields['job_status'] == 0 ):						
				echo '<article>
                        <h3>'.get_the_title().'</h3>
                        <ul class="time-details">
                            <li><i class="fa fa-calendar-o" aria-hidden="true"></i> '.$jobs_fields["job_date"].'</li>
                            <li><i class="fa fa-clock-o" aria-hidden="true"></i> '.$jobs_fields["job_time"].'</li>
                        </ul>
                        '.wpautop( get_the_content() ).'

                        <div class="custom-table-btn-group">
                            <ul>';
                            if ( is_user_logged_in() ) 
                            {
                            	$user = wp_get_current_user();
    							$role = ( array ) $user->roles;
    							if( $role[0] == 'service' )
    							{
    								echo'<li><a href="'.site_url( "submit-bid/".$slug ).'" class="blue_btn">Apply now</a></li>';
    							}
							} 							

                                //<li><a href="#" class="blue_btn">Apply now</a></li>
                                echo '<li><a href="'.get_the_permalink().'" class="white-btn">View job</a></li>
                            </ul>
                        </div>
                    </article>';
                    $x++;
                endif;
							
                	 ?>
                <?php   endwhile; else: echo "<article> <h3> No Job Found ! </h3> </article>"; endif; if( $x==0 ){ echo "<article> <h3> No Job Found ! </h3></article>"; } ?>                
                </div>
                <div class="pagination pull-right"></div>
            </div>
        </div>
    </div>

<?php get_footer(); ?>
<?php if( isset($_GET['job_search']) ){  echo "<script> $('.filter_btn').trigger('click'); </script>"; } ?>
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

      function initAutocomplete() 
      {
            autocomplete = new google.maps.places.Autocomplete(
            (document.getElementById('autocomplete')),
            {types: ['geocode']});    
            autocomplete.addListener('place_changed', fillInAddress);     
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
    <script>
        $(function () {
            var $document = $(document);
            var selector = '[data-rangeslider]';
            var $element = $(selector);
            // For ie8 support
            var textContent = ('textContent' in document) ? 'textContent' : 'innerText';
            // Example functionality to demonstrate a value feedback
            function valueOutput(element) {
                var value = element.value;
                var output = element.parentNode.getElementsByTagName('output')[0] || element.parentNode.parentNode.getElementsByTagName('output')[0];
                output[textContent] = value;
            }
            $document.on('input', 'input[type="range"], ' + selector, function (e) {
                valueOutput(e.target);
            });
            $element.rangeslider({
                // Deactivate the feature detection
                polyfill: false
                , // Callback function
                onInit: function () {
                    valueOutput(this.$element[0]);
                }
                , // Callback function
                onSlide: function (position, value) {
                    //console.log('onSlide');
                    //console.log('position: ' + position, 'value: ' + value);
                }
                , // Callback function
                onSlideEnd: function (position, value) {
                    //console.log('onSlideEnd');
                   // console.log('position: ' + position, 'value: ' + value);
                }
            });
        })
    </script>
