<?php 
/**
 * Template Name: Create Job Template
 *
 * @author Chhavinav Sehgal
 * @copyright 
 * @link 
 */
get_header(); ?>
<link href="<?php echo get_template_directory_uri(); ?>/assets/css/star-rating.css" rel="stylesheet">
<?php  $fields = get_fields(); if ( have_posts() ) : while ( have_posts() ) : the_post();   ?>
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
    <!-- banner end-->
    <div class="inner-pages create-job-page">
        <div id="form_details" class="container">
            <div class="text-center">
            <h2>Create job</h2> </div>
            <div class="create-job-cover">
                <form id="createJob" method="POST">
                    <?php wp_nonce_field( 'Create_Job' , 'CreateJob_nonce' ); ?>
                    <input type="hidden" name="lat" class="lat" value="" >
                    <input type="hidden" name="longs" class="longs" value="" >
                    <input type="hidden" name="user_id" value="<?php echo get_current_user_id(); ?>" >
                    <input type="hidden" name="user_type" value="1" >
                    <div class="row">
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <div class="form-group slct">
                            <?php  
                                    $drop = "";                          
                                    $categories = get_terms(array('taxonomy' => 'jobs_category','hide_empty' => false) );
                                    $drop .= '<select name="service_type[]" class="selectpicker form-control" multiple>';
                                    $drop .= "<option value=''>Select Category</option>";
                                    foreach( $categories as $catgs )
                                    {    
                                        $drop .= "<option value='".$catgs->term_id."'>".$catgs->name."</option>";
                                    }   
                                    $drop .= '</select>';

                                    echo $drop;     

                            ?>
                            </div>
                        </div>
                        
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <div class="form-group">
                            <input type="text" name="job_title" placeholder="Title" class="form-control">
                            </div>
                        </div>
                        
                         <div class="col-md-6 col-sm-6 col-xs-12">
                            <div class="form-group">
                            <input type="text" name="job_date" placeholder="Date" class="form-control datepicker">
                            </div>
                        </div>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <div class="form-group">
                            <input type="text" placeholder="Time" name="job_time" class="form-control time">
                                </div>
                        </div>
                        
                        
                       
                        
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <div class="form-group">
                            <input type="text" id="autocomplete" name="street_address" placeholder="Address" class="form-control">
                                </div>
                        </div>
                        
                         <div class="col-md-6 col-sm-6 col-xs-12">
                             <div class="form-group">
                            <input type="text" name="city" placeholder="City" class="form-control">
                                 </div>
                        </div>
                        
                         <div class="col-md-6 col-sm-6 col-xs-12">
                             <div class="form-group slct">
                                    <select name="state" id="state" class="placeholder1 form-control">
                                        <option value="none" selected>State</option>
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
                            <input type="text" name="postcode" placeholder="PostCode" class="form-control">
                                </div>
                        </div>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <div class="input-group form-group">
                                  <span class="input-group-addon">$</span>
                                  <input id="autoNumericEventInput" type="text" name="job_price" class="form-control" placeholder="Price">
                            </div>
                        </div>

                        <div class="col-md-12 col-sm-12 col-xs-12">
                            <div class="form-group">
                            <textarea placeholder="Description" name="job_content" class="form-control"></textarea>
                                </div>
                        </div>
                        <div class="col-md-12 col-sm-12 col-xs-12">
                            <div class="form-group flie-in">
                             <input type="file" id="attachment" class="input-file">
                            </div>
                        </div>
                        
                        <div class="col-md-12 col-sm-12 col-xs-12">
                            <input type="submit" class="blue_btn create-job" value="Create job">
                        </div>
                        
                        
                    </div>
                </form>
            </div>
        </div>
        <div style="display:none"  id="invite" class="container">
            <div class="text-center">
                <h2>Service Provider Listing</h2> </div>
            <div class="row">
                <div class="col-md-3">
                    <div class="sidebar">
                        <form id="search_provider">
                            <input type="hidden" id="lat2" name="lat2" class="lat" value="" >
                            <input type="hidden" id="longs2" name="longs2" class="longs" value="" >
                            <input type="hidden" name="page" value="1" >
                            <div class="form-group slct">
                                <label>Search By Name </label>
                                <input name="service_search" placeholder="Search by name" class="form-control group-required">
                            </div>
                             <div class="form-group slct">
                                 <label>Search By Address </label>
                                <input name="autocomplete2" id="autocomplete2" placeholder="Address" class="form-control group-required">
                            </div>
                            <div class="form-group">
                                <label>Rating </label>
                                <select name="rating" id="star-rating-1" class="group-required">
                                    <option value="">Select a rating</option>
                                    <option value="5">5</option>
                                    <option value="4">4</option>
                                    <option value="3">3</option>
                                    <option value="2">2</option>
                                    <option value="1">1</option>
                                </select>
                            </div>
                            <div class="form-group range-group">
                                <label>Choose Miles</label>
                                <input type="range" class="group-required" name="distance" min="0" max="100" step="1" value="0" data-rangeslider>
                                <output id="js-output" class="left-side-output"></output>
                                <output class="right-side-output">100</output>
                            </div>
                            <div class="form-group">
                                 <label>Sort By </label>
                                 <div class="checkbox">
                                  <label class="checkbox-inline">
                                    <input type="checkbox" name="sortBy" class="group-required" name="sortBy" value="1">Success Rate</label>
                                </div>
                            </div>                            
                            <div class="text-center">
                             <input type="submit" class="blue_btn filter-service" value="Filter results"> </div>
                        </form>
                        <div class="text-center">
                         <button class="blue_btn back-job"> <span class='glyphicon glyphicon-chevron-left'></span> Back </button>
                     </div>
                    </div>
                </div>
                <div class="col-md-9 received-bid-cover">
                    <div id="service-provider-listing">
                    </div>
                    <div class="button-public">
                        <div class="col-sm-4">
                             <button class="btn btn-primary create-job-send" data-toggle="tooltip" title="Job will be Open for All User" value="0" > Create Publicly </button>
                            
                        </div> 
                        <div class="col-sm-offset-1 col-sm-4">
                              <button class="btn btn-primary create-job-send" data-toggle="tooltip" title="Job will be Open for Selected User" value="1" > Invite Only </button>
                            
                        </div>
                       
                    </div>
                    
                </div>
            </div>
        </div>
    </div>
<?php   endwhile; endif;  ?>
<?php get_footer(); ?>
<script src="<?php echo  get_template_directory_uri().'/assets/js/AutoNumeric.js'; ?>"></script>
<script src="<?php echo  get_template_directory_uri().'/assets/js/star-rating.js'; ?>"></script>
<script src="<?php echo  get_template_directory_uri().'/assets/js/star-rating.js'; ?>"></script>
    <script>
    	var date = new Date();    	
		var today = new Date(date.getFullYear(), date.getMonth(), date.getDate());
    	$('.datepicker').datepicker({
    		 startDate: '-0d',
    		//changeMonth: true
    	});
        $('[data-toggle="tooltip"]').tooltip(); 
        var starrating1 = new StarRating( document.getElementById( 'star-rating-1' ),{          
            showText: false           
        });     
    </script>
 <script>

    //new AutoNumeric('#autoNumericEventInput',{ minimumValue: '0.01' });
//      .update({ valuesToStrings : AutoNumeric.options.valuesToStrings.zeroDash }); //DEBUG
//    const autoNumericEventInput = document.querySelector('#autoNumericEventInput');
//    autoNumericEventInput.addEventListener(AutoNumeric.events.formatted, e => {
        //console.log(`'${AutoNumeric.events.formatted}' sent with`, e); //DEBUG
  //  });
    //autoNumericEventInput.addEventListener(AutoNumeric.events.rawValueModified, e => {
       // console.log(`'${AutoNumeric.events.rawValueModified}' sent with`, e); //DEBUG
    //});

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

            autocomplete2 = new google.maps.places.Autocomplete(
            /** @type {!HTMLInputElement} */(document.getElementById('autocomplete2')),
            {types: ['geocode']});
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

    <!-- Scroll Loading Data -->
<script type="text/javascript">
//$("#service-provider-listing").loaddata(); //load the results into element
</script>
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
