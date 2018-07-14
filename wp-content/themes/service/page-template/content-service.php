<?php 
 //pre( $Details );
 $ProfileImage = ( empty($Details->photo_url) )? $Details->profile_image : $Details->photo_url; 
?>
<div class="container">
            <div class="row">
                <div class="col-md-3">
                    <div class="profile-sidebar">
                        <div class="profile-pic" style="background-image:url('<?php echo $ProfileImage; ?>');"></div>
                        <!-- <div class="availability">
                            <h3>availability</h3> <img src="images/availability-cal.jpg"> </div>
                        <div class="text-center"> <a href="#" class="blue_btn">book now</a> </div> -->
                        <div class="text-center"> <a href="javascript:void(0)" class="blue_btn">Edit Profile</a> </div>
                    </div>
                </div>
                <div class="col-md-9">
                    <div class="profile-des">
                        <div class="row">
                            <div class="col-md-8">
                                <article>
                                    <h3> <?php echo  $Details->first_name.' '.$Details->last_name; ?> </h3>
                                    <div class="rating">
                                    <ul>
                                    <?php
                                         for( $x = 1; $x <= 5; $x++ )
                                         {
                                            if( $x <= $Details->rating)
                                            {
                                                echo '<li class="rate"><i class="fa fa-star" aria-hidden="true"></i></li>';
                                            }
                                            else
                                            {
                                                echo '<li ><i class="fa fa-star" aria-hidden="true"></i></li>';
                                            }
                                         }
                                    ?>
                                    </ul>
                                        <p>(<?php echo count( $Details->work_history ) ?> reviews)</p>
                                </div>
                                    <div class="profile-detail-list">
                                    <p><strong>Location:</strong> <?php echo $Details->street_address; ?></p>
                                    <p><strong>Email:</strong> <a href="mailto:<?php echo $Details->email; ?>"><?php echo $Details->email; ?></a></p>
                                    <p><strong>Success Rate</strong> <?php if( !empty( $Details->success_rate ) ){ echo $Details->success_rate; }else{ echo "Not Available!"; } ?></p>
                                </div>
                                </article>
                                
                                <article>
                                    <h3>Company Overview</h3>
                                    <p><?php if( !empty($Details->overview) ){ echo $Details->overview; }else{ echo"No Information Available !"; } ?></p>
                                </article>
                                
                                
                            </div>
                            <div class="col-md-4">
                                <div class="profile-form">
                                    <div class="profile-form-heading">
                                        <h2>get in touch</h2> </div>
                                    <div class="profile-form-body">
                                        <form>
                                            <div class="form-group">
                                                <input type="text" placeholder="Name" class="form-control"> 
                                            </div>
                                            <div class="form-group">
                                                <input type="text" placeholder="Email" class="form-control"> 
                                            </div>
                                            <div class="form-group">
                                                <input type="text" placeholder="Phone" class="form-control"> 
                                            </div>
                                            <div class="form-group">
                                                <textarea placeholder="Message" class="form-control"></textarea>
                                            </div>
                                            <div class="text-center">
                                                <input type="submit" value="Send" class="blue_btn profile-form-btn">
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <article>
                            <h3>location</h3>
                            <div id="googleMap" class="location-map">
                                
                            </div>
                        </article>
                        
                        <article class="port-gallery-article">
                            <h3>Portfolio <span class="pull-right"> <button class="btn btn-primary" data-toggle="modal" data-target="#myPort" >Add</button> </span> </h3> 
                            <?php if( !empty( $Details->portfolio ) ): ?>
                                <div class="owl-carousel owl-theme">
                                <?php foreach( $Details->portfolio as $keys ): ?>                                    
                                        <div data-id="<?php echo $keys['id'] ?>" class="item"><img src="<?php echo $keys['url'] ?>" ></div>                                    
                                <?php endforeach; ?>
                                </div>
                        <?php endif; ?>
                        </article>
                        
                        <div class="feedback-section">
                             <?php if( !empty($Details->work_history) ): foreach( $Details->work_history as $keys ): ?>
                            <h3>Feedback</h3>
                           
                            <?php $WPmodify = new WPmodify(); ?>
                            <article>
                                <?php if( $keys['user_type'] == 1 ): ?>
                                    <?php $Profile = $WPmodify->getProfileDetails( $keys['service_providerID'] ); $Rating = $keys['rating_by_provider']; ?>
                                        <?php echo $keys['feedback_by_provider']; ?>
                                <?php elseif( $keys['user_type'] == 2 ): ?>
                                    <?php $Profile = $WPmodify->getProfileDetails( $keys['customer_id'] ); $Rating = $keys['rating_by_cust'];  ?>
                                        <?php echo $keys['feedback_by_cust']; ?>
                                <?php endif; ?> 
                                
                                <ul class="post-by-details">

                                    <li>Posted by  - <?php echo $Profile[0]->first_name; ?></li>
                                    <li><?php echo $keys['start_date']; ?> - <?php echo $keys['end_date']; ?></li>
                                </ul>
                                
                                <div class="rating">
                                    <ul >
                                       <?php
                                         for( $x = 1; $x <= 5; $x++ )
                                         {
                                            if( $x <= $Details->rating)
                                            {
                                                echo '<li class="rate"><i class="fa fa-star" aria-hidden="true"></i></li>';
                                            }
                                            else
                                            {
                                                echo '<li ><i class="fa fa-star" aria-hidden="true"></i></li>';
                                            }
                                         }
                                        ?>
                                    </ul>
                                        <p><?php echo $Rating; ?></p>
                                </div>
                            </article>

                            <?php endforeach; else: echo "<article> <h2> No Feedback as Yet ! </h2></article>";endif; ?>                           
                        </div>
                    </div>
                </div>
            </div>
        </div>
<script>
function myMap() {
    var myCenter = new google.maps.LatLng(<?php echo $Details->lat; ?>,<?php echo $Details->longs; ?>);
  var mapCanvas = document.getElementById("googleMap");
  var mapOptions = {center: myCenter, zoom: 18};
  var map = new google.maps.Map(mapCanvas, mapOptions);
  var marker = new google.maps.Marker({position:myCenter,animation: google.maps.Animation.BOUNCE});
  marker.setMap(map);

  var infowindow = new google.maps.InfoWindow({
    content:"<h4><?php echo $Details->business_name; ?></h4><p> <strong> Address: </strong><?php echo $Details->street_address; ?><br><strong> City: </strong><?php echo $Details->city; ?><br><strong> State: </strong><?php echo $Details->state; ?><br><strong> Postcode: </strong><?php echo $Details->postcode; ?><br><strong> Mobile: </strong><?php echo $Details->phone_number; ?></p>"
  });

    google.maps.event.addListener(marker, 'click', function() {
        infowindow.open(map,marker);
    });

};

</script>
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyARla0y98tn7MoyOXhVmdoEROmXeNPE_qw&callback=myMap"></script>