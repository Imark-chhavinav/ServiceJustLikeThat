<?php 
/**
 * Template Name: Contact Us
 *
 * @author Chhavinav Sehgal
 * @copyright chhavinav.in
 * @link shadow_chhavi @skype
 */ 
get_header(); ?>
<?php  $fields = get_fields(); if ( have_posts() ) : while ( have_posts() ) : the_post();   ?>

  <!--header end-->
    <section class="banner inner-banner">
        <div id="carousel-example-generic" class="carousel slide" data-ride="carousel">
            <!-- Wrapper for slides -->
            <div class="carousel-inner" role="listbox">
                <div class="item active" style="background-image:url(<?php echo $fields['banner_image']['url']; ?>);">
                    <div class="container">
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="banner_cntnt">
                                    <h1><?php echo $fields['banner_text']; ?></h1>
                                 </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- banner end-->
    <div class="inner-pages contact-page">
        <div class="container">
            <div class="text-center">
                <h2><?php the_title() ?></h2> 
            </div>
            <div class="row">
                <div class="col-md-7">
                    <div class="contact-form-cover">
                       <?php the_content(); ?> 
                    </div>
                </div>
                <div class="col-md-4 col-md-offset-1 col-sm-5 col-xs-12">
                    <div class="contact-details">
                        <ul>
                            <li><i class="fa fa-mobile" aria-hidden="true"></i> <a href="tel:+16202511934">+1620-251-1934</a></li>
                            <li><i class="fa fa-envelope" aria-hidden="true"></i> <a href="mailto:<?php echo $fields['email']; ?>"><?php echo $fields['email']; ?></a></li>
                            <li><i class="fa fa-map-marker" aria-hidden="true"></i> <?php echo $fields['address']['address']; ?></li>
                        </ul>
                       
                        <div class="map-cover">
                        <?php $location = $fields['address'];
                        if( !empty($location) ):
                        ?>
                        <div class="acf-map">
                            <div class="marker" data-lat="<?php echo $location['lat']; ?>" data-lng="<?php echo $location['lng']; ?>"></div>
                        </div>
                        <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php   endwhile; endif;  ?>
<?php get_footer(); ?>
<!--************************************ START SCRIPT USE FOR ENTER ALPHABETS ONLY IN (NAME) TEXT BOX********************-->
<script type="text/javascript">
jQuery(document).ready(function(){
//jQuery.noConflict();
  jQuery("input[name='name']").keypress(function(event){
      var inputValue = event.which;
      // allow letters and whitespaces only.
      if((inputValue > 33 && inputValue < 64) || (inputValue > 90 && inputValue < 97 ) || (inputValue > 123 && inputValue < 126)
&& (inputValue != 32)){
          event.preventDefault();
      }
  });
});
</script>
<!--************************************ END OF SCRIPT USE FOR ENTER ALPHABETS ONLY IN (NAME) TEXT BOX********************-->



<!--**************************** START SCRIPT USE FOR ENTER NUMBER ONLY IN (PHONENUMBER) TEXT BOX ********************-->

<script type="text/javascript">

jQuery(document).ready(function() {
 jQuery("input[name='tel-680']").keydown(function (e) {
     // Allow: backspace, delete, tab, escape, enter and .
     if (jQuery.inArray(e.keyCode, [46, 8, 9, 27, 13, 110, 190]) !== -1 ||
          // Allow: Ctrl+A, Command+A
         (e.keyCode == 65 && ( e.ctrlKey === true || e.metaKey === true ) ) || 
          // Allow: home, end, left, right, down, up
         (e.keyCode >= 35 && e.keyCode <= 40)) {
              // let it happen, don't do anything
              return;
     }
     // Ensure that it is a number and stop the keypress
     if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
         e.preventDefault();
     }
 });
});

</script>
<!--**************************** END OF SCRIPT USE FOR ENTER NUMBER ONLY IN (PHONENUMBER) TEXT BOX ********************-->
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAahT6o0c69st0dS0Z1HtHDgqIN4W0NEOI"></script>
<script type="text/javascript">
(function($) {

/*
*  new_map
*
*  This function will render a Google Map onto the selected jQuery element
*
*  @type    function
*  @date    8/11/2013
*  @since   4.3.0
*
*  @param   $el (jQuery element)
*  @return  n/a
*/

function new_map( $el ) {
    
    // var
    var $markers = $el.find('.marker');
    
    
    // vars
    var args = {
        zoom        : 16,
        center      : new google.maps.LatLng(0, 0),
        mapTypeId   : google.maps.MapTypeId.ROADMAP
    };
    
    
    // create map               
    var map = new google.maps.Map( $el[0], args);
    
    
    // add a markers reference
    map.markers = [];
    
    
    // add markers
    $markers.each(function(){
        
        add_marker( $(this), map );
        
    });
    
    
    // center map
    center_map( map );
    
    
    // return
    return map;
    
}

/*
*  add_marker
*
*  This function will add a marker to the selected Google Map
*
*  @type    function
*  @date    8/11/2013
*  @since   4.3.0
*
*  @param   $marker (jQuery element)
*  @param   map (Google Map object)
*  @return  n/a
*/

function add_marker( $marker, map ) {

    // var
    var latlng = new google.maps.LatLng( $marker.attr('data-lat'), $marker.attr('data-lng') );

    // create marker
    var marker = new google.maps.Marker({
        position    : latlng,
        map         : map
    });

    // add to array
    map.markers.push( marker );

    // if marker contains HTML, add it to an infoWindow
    if( $marker.html() )
    {
        // create info window
        var infowindow = new google.maps.InfoWindow({
            content     : $marker.html()
        });

        // show info window when marker is clicked
        google.maps.event.addListener(marker, 'click', function() {

            infowindow.open( map, marker );

        });
    }

}

/*
*  center_map
*
*  This function will center the map, showing all markers attached to this map
*
*  @type    function
*  @date    8/11/2013
*  @since   4.3.0
*
*  @param   map (Google Map object)
*  @return  n/a
*/

function center_map( map ) {

    // vars
    var bounds = new google.maps.LatLngBounds();

    // loop through all markers and create bounds
    $.each( map.markers, function( i, marker ){

        var latlng = new google.maps.LatLng( marker.position.lat(), marker.position.lng() );

        bounds.extend( latlng );

    });

    // only 1 marker?
    if( map.markers.length == 1 )
    {
        // set center of map
        map.setCenter( bounds.getCenter() );
        map.setZoom( 16 );
    }
    else
    {
        // fit to bounds
        map.fitBounds( bounds );
    }

}

/*
*  document ready
*
*  This function will render each map when the document is ready (page has loaded)
*
*  @type    function
*  @date    8/11/2013
*  @since   5.0.0
*
*  @param   n/a
*  @return  n/a
*/
// global var
var map = null;

$(document).ready(function(){

    $('.acf-map').each(function(){

        // create map
        map = new_map( $(this) );

    });

});

})(jQuery);
</script>