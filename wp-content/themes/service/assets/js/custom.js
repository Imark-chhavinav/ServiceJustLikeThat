//console.log( User.url );
//console.log( Job.url );
// Preloader 
jQuery(function ($) {
    //Preloader
    $(window).load(function () {
        $('body').css('overflow-y', 'visible');
        $('#preloader').fadeOut('slow', function () {
            $(this).remove();
        });
    });
});
// Wow 
wow = new WOW({
    boxClass: 'wow', // default
    animateClass: 'animated', // default
    offset: 0, // default
    mobile: false, // default
    live: true // default
})
wow.init();
// Bootstrap Slider 

/**** Select Box ****/
jQuery('.slct select').change(function () {
    if (jQuery(this).children('option:first-child').is(':selected')) {
        jQuery(this).addClass('placeholder1');
    }
    else {
        jQuery(this).removeClass('placeholder1');
    }
});


jQuery(window).scroll(function() {    
    var scroll = jQuery(window).scrollTop();

    if (scroll >= 200) {
        jQuery("header").addClass("sticky");
    } else {
        jQuery("header").removeClass("sticky");
    }
});


/**** owl carousel ****/
jQuery(document).ready(function () {
    var owl = jQuery("#testimonialSlider");
    owl.owlCarousel({
        margin: 0
        , loop: true
        , autoplay: true
        , responsiveClass: true
        , responsive: {
            0: {
                items: 1
                , nav: false
            }
            , 468: {
                items: 2
                , nav: false
            }
            , 768: {
                items: 3
                , nav: false
            }
            , 1000: {
                items: 3
                , nav: false
                , loop: false
            }
        }
    });

    $('.owl-carousel').owlCarousel({
    loop:false,
    margin:10,
    nav:false,
    responsive:{
        0:{
            items:1
        },
        600:{
            items:3
        },
        1000:{
            items:4
        }
    }
})
	
	
	/* Card Selection */
	if( $( '#credit-card' ).length > 0 )
	{
		$('#credit-card').cardcheck({
			iconLocation: '#accepted-cards-images',
			allowSpaces: false
		});
	}



	/*  Dropzone Js */
	if( $( '#id_dropzone' ).length > 0 )
	{
		Dropzone.autoDiscover = false;
		var myDropzone = new Dropzone("#id_dropzone", { 
	    url: User.url+'Portfolio' ,
	    autoProcessQueue:true,
		maxFilesize:5,
		addRemoveLinks: true,
		parallelUploads: 100,
		//maxFiles: 1,
		acceptedFiles: "image/*",
		clickable: ".buttonText,#id_dropzone,.dz-clickable",
		queuecomplete:function()
		{
			$(".dz-remove").hide();			
		},	
		init: function ()
		{
	        this.on("sending", function (file, xhr, formData, e) {
				formData.append("user_id", CurrentUser.ID); 
				//formData.append("action", "lessonplan"); 			
				//formData.append("_wpnonce", $("#_wpnonce").val());                
			});	

	        this.on("maxfilesexceeded", function(file) 
	        {
	            this.removeAllFiles();
	            this.addFile(file);
	     	});
	     }
	  });
	}
	
	
	/* Auto Numeric Price */
	if( $( '#autoNumericEventInput' ).length > 0 )
	{
		new AutoNumeric('#autoNumericEventInput',{ minimumValue: '0.01' });
//      .update({ valuesToStrings : AutoNumeric.options.valuesToStrings.zeroDash }); //DEBUG
		const autoNumericEventInput = document.querySelector('#autoNumericEventInput');
		//autoNumericEventInput.addEventListener(AutoNumeric.events.formatted, e => 
		//{
			//console.log(`'${AutoNumeric.events.formatted}' sent with`, e); //DEBUG
		//});

		//autoNumericEventInput.addEventListener(AutoNumeric.events.rawValueModified, e => 
		//{
			   // console.log(`'${AutoNumeric.events.rawValueModified}' sent with`, e); //DEBUG
		//});
	}
    
	
	
	/* Bid For Job Question Add more Fields functions */
	var max_fields      = 5; //maximum input boxes allowed
    var wrapper         = $(".input_fields_wrap"); //Fields wrapper
    var add_button      = $(".add_field_button"); //Add button ID
    
	if( typeof(QuesNo) != "undefined" &&  QuesNo !== null )
	{
		var cx = QuesNo;
	}
	else
	{
		var cx = 1;
	}
     //initlal text box count
    $(add_button).click(function(e){ //on add input button click
        e.preventDefault();
        if(cx < max_fields){ //max input box allowed
            cx++; //text box increment
            $(wrapper).append('<div class="input-group form-group"> <input type="text" name="questions[]" class="form-control" placeholder="Questions"> <span class="input-group-btn remove_field"><button class="btn btn-default" type="button"><span class="glyphicon glyphicon-glyphicon glyphicon-minus"></span></button> </span><\/div>'); //add input box
        }
    });
    
    $(wrapper).on("click",".remove_field", function(e){ //user click on remove text
        e.preventDefault(); $(this).parent('div').remove(); cx--;
    })
    
    
    //$(".ServiceProviderForm").slideUp();
    
    /* jQuery("input[name='abc']").click(function () {
		
        if ($("#CustomerForm").is(":checked")) 
		{
			document.getElementById("ServiceProvider_Form").reset();			
            $(".CustomerForm").slideDown();
            $(".ServiceProviderForm").slideUp();
        }
        else if( $("#ServiceProviderForm").is(":checked") ) 
		{
			document.getElementById("Customer_Form").reset();			
            $(".ServiceProviderForm").slideDown();
            $(".CustomerForm").slideUp();
        }
    }); */
    
    
   jQuery(".inbox-cover .view-recent a").click(function () {
       jQuery(".inbox-cover .view-recent").toggleClass("clicked")
       jQuery(".recent-chat-sidebar-cover").toggleClass("closeSidebar");
       jQuery(".inbox-cover .inbox-main-chat").toggleClass("fullWidth");
       
		jQuery(".inbox-cover .view-recent a").html(jQuery(".inbox-cover .view-recent a").text() == 'Show Recent' ? 'Hide Recent' : 'Show Recent');
       
   });
   
   
  /*  if($("#ServiceProvider_Form").length > 0)
	{
		$("#ServiceProvider_Form").validate({
		   rules: {
			oldpassword:	{  required:true },
			new_pass: 	 	{  required:true },       
			new_pass_check: {  required:true, equalTo :"#new_pass" }       
		   },
		   messages: {
			new_pass_check: { equalTo: 'Confirm Password Does not Match !' }
				 
		   }
		});
	}  */
	
	
   /*	Registration Form */
	if($("#Customer_Form").length > 0)
	{
		// Customer Form
		$("#Customer_Form").validate({
			   rules: {
				user_type:			{  required:true },
				title:			{  required:true },
				first_name:			{  required:true },
				last_name: 	 		{  required:true },       
				email: 	 			{  required:true,email:true },       
				username: 	 		{  required:true },       
				street_address: 	{  required:true },       
				city: 	 			{  required:true },       
				postcode: 	 		{  required:true },       
				state: 	 			{  required:true },       
				//phone_number: 	{  required:true,digits:true },       
				phone_number: 		{  required:true },       
				password: 	 		{  required:true },       
				terms: 	 			{  required:true },       
				Confirm_password: 	{  required:true, equalTo :"#Confirm_password" } ,
				business_name: 		{  required:'#ServiceProviderForm:checked' },
				service_type: 		{  required:'#ServiceProviderForm:checked' },
				business_type: 		{  required:'#ServiceProviderForm:checked' },				
				overview: 			{  required:'#ServiceProviderForm:checked' }				
			   }			   
		});
		
		
		
		$('.reg_me').on('click',function(event)
		{
			if (event.preventDefault) 
			{	event.preventDefault();		} 
			else 
			{	event.returnValue = false;	}
		
		if( $("#Customer_Form").valid() )
		{
			var FormDate = $("#Customer_Form").serializeArray();
			
			//FormDate.push({name:'reg_nonce',value: $( '#reg_nonce' ).val()});
			$.ajax({
					type:"POST",
					url: User.url+'userRegistration',
					data:FormDate,				
					success:function(res)
					{						
						if( res.success == 1 )
						{
							toastr.success(res.result, 'Success');
							setTimeout(function(){ window.location = Site.url+'/sign-in/';  },3000);
						}
						else
						{
							toastr.error( res.error, 'Error !')
						}						
					}			
			})	
		}
		});
	}
	
	/* Login User  */
	if( $('#sign-in').length > 0 )
	{
		/* Login Validation */
		$("#sign-in").validate({
			 // specify rules
			ignore: [],
			rules: {				    
			username_email: 	 	{  required:true },			
			password: 	 			{  required:true },       
			user_type: 	 			{  required:true }
		   },
		errorPlacement: function(error, element) {
			if (element.attr("name") == "user_type" )
				error.insertAfter(".User-type");
			else
				error.insertAfter(element);
		}		   
		});
		
		$('.sign-in').click(function(event)
		{
			if( event.preventDefault )
			{
				event.preventDefault();
			}
			else
			{
				event.returnValue = false;
			}
			
			if( $('#sign-in').valid() )
			{
				/* Ajax Login */
				$.ajax
				({
					type:"POST",
					url: User.url+'login',
					data:$('#sign-in').serializeArray(),				
					success:function(res)
					{
						if( res.status == 'success' )
						{
							toastr.success( res.message, 'Success');
							window.location = res.extra;
						}
						else
						{
							toastr.error( res.message, 'Error !');
						}
					}			
				})	
			}
			
		});
	}
	
	/* Reset Password */
	if( $('#forgotPassword').length > 0 )
	{
		$('#forgotPassword').validate({
			rules:{
				email: {required:true,email:true}
			}
		});
		
		$('.reset-password').click(function(event)
		{
			
			if( event.preventDefault )
			{
				event.preventDefault();
			}
			else
			{
				event.returnValue = false;
			}			
			
			if( $('#forgotPassword').valid() )
			{
				
				$.ajax({
					type:'POST',
					url:User.url+'forgotPassword',
					data:$('#forgotPassword').serializeArray(),
					success:function(res)
					{
						if( res.status == 'success' )
						{
							toastr.success( res.message, 'Success');
							setTimeout(function(){ location.reload(); },5000);
						}
						else
						{
							toastr.error( res.message, 'Error !');
						}
					}
				});
			}
			
		});
	}
	
	/* Job Page Filter */
	if( $('#filter').length > 0 )
	{
		/* Validation */
		$("#filter").validate({
			   rules: {
				first_name:			{  required:true },
				last_name: 	 		{  required:true },       
				email: 	 			{  required:true,email:true },       
				username: 	 		{  required:true },       
				street_address: 	{  required:true },       
				city: 	 			{  required:true },       
				postcode: 	 		{  required:true },       
				state: 	 			{  required:true },       
				//phone_number: 		{  required:true,digits:true },       
				phone_number: 		{  required:true },       
				password: 	 		{  required:true },       
				Confirm_password: 	{  required:true, equalTo :"#Confirm_password" }       
			   }					   
		});
		
		$('.filter_btn').on('click',function(event)
		{
			if (event.preventDefault) 
			{	event.preventDefault();		} 
			else 
			{	event.returnValue = false;	}
		
			var Filter_data = $("#filter").serializeArray();
			console.log( Filter_data );
			$( '.loader_team' ).show();
			$.ajax({					
					type:"GET",
					url: Job.url+'getjoblisting',
					data:Filter_data,				
					success:function(res)
					{
						$( '.loader_team' ).hide();						
						if( res.success == 1 )
						{
							$('.job-listing-cover').html( "" );
							$.each( res.result , function( index, value ) 
							{
								if( CurrentUser.Role == ""  )
								{
									$('.job-listing-cover').append( '<article><h3>'+ value.job_title +'</h3> <ul class="time-details"> <li><i class="fa fa-calendar-o" aria-hidden="true"></i> '+ value.job_date +'</li><li><i class="fa fa-clock-o" aria-hidden="true"></i> '+ value.job_time +'</li></ul> <p>'+ value.job_content +' </p><div class="custom-table-btn-group"> <ul><li><a href="'+ value.job_permalink +'" class="white-btn">View job</a></li></ul> </div></article>' );
								}
								else if(  CurrentUser.Role == 2 )
								{
									$('.job-listing-cover').append( '<article><h3>'+ value.job_title +'</h3> <ul class="time-details"> <li><i class="fa fa-calendar-o" aria-hidden="true"></i> '+ value.job_date +'</li><li><i class="fa fa-clock-o" aria-hidden="true"></i> '+ value.job_time +'</li></ul> <p>'+ value.job_content +' </p><div class="custom-table-btn-group"> <ul> <li><a href="'+ Site.url +'/submit-bid/'+ value.job_slug +'" class="blue_btn">Apply now</a></li><li><a href="'+ value.job_permalink +'" class="white-btn">View job</a></li></ul> </div></article>' );
								}
								else
								{
									$('.job-listing-cover').append( '<article><h3>'+ value.job_title +'</h3> <ul class="time-details"> <li><i class="fa fa-calendar-o" aria-hidden="true"></i> '+ value.job_date +'</li><li><i class="fa fa-clock-o" aria-hidden="true"></i> '+ value.job_time +'</li></ul> <p>'+ value.job_content +' </p><div class="custom-table-btn-group"> <ul><li><a href="'+ value.job_permalink +'" class="white-btn">View job</a></li></ul> </div></article>' );
								}
								
							 
							});
							$('.pagination').html(res.total_page);
						}
						else
						{
							toastr.error( res.error, 'Error !')
						}						
					}			
			})	
		
		});
		/* Trigger Filter Button On click on li of Pagination  */
		$(document).on('click','.page',function(){
			var ID = $( this ).attr( 'data-val' );
			$('input[name="page"]').val( ID );
			$('.filter_btn').trigger('click');
			
		});	
	
	}
	
	/* Create Job */
	if( $('#createJob').length > 0 )
	{
		var ScrollID = 0 ;
		$("#createJob").validate({
			rules:{
				'service_type[]'	:	{ required:true },
				job_title			:	{ required:true },
				job_date			:	{ required:true },
				job_time			:	{ required:true },
				street_addess		:	{ required:true },
				city				:	{ required:true },
				state				:	{ required:true },				
				postcode			:	{ required:true },
				job_price			:	{ required:true,number:true },
				job_content			:	{ required:true }
			}
		});
		
		$('.create-job').click(function(event){
			
			if (event.preventDefault) 
			{	event.preventDefault();		} 
			else 
			{	event.returnValue = false;	}
			
			if($('#createJob').valid())
			{
				$('#form_details').hide( "slow");
				$( '#invite' ).show(); 
				
				$( '.back-job' ).click(function()
				{
					$('#invite').hide( "slow");
					$( '#form_details' ).show(); 					
				});	
				scrollAjax( ScrollID );
				
				$( '.create-job-send' ).click(function()
				{
					$( '.loader_team' ).show();
					$(this).unbind('click');
					var fd = new FormData();
					$.each($("#createJob").serializeArray(), function(i, field) {
					  fd.append(field.name, field.value);
					});
					var myArray = new Array();
					$('input[name="invite_ids[]"]:checked').each(function(i) {						
					  myArray.push($(this).val());
					  
					});					
				
					fd.append('attachment', $('#attachment')[0].files[0]);
					fd.append('invite_ids', myArray);
					fd.append('publicly', $(this).attr('value'));
				
					$.ajax({
					  type: "POST",
					  url: Job.url+'createJob',
					  data: fd,
					  processData: false,
					  contentType: false,
					  success: function(res) 
					  {				
						if( res.success == 1 )
						{
							toastr.success( res.result, 'Success');
							window.location = Site.url+'/my-jobs';
							$( '.loader_team' ).hide();
						}
						else
						{
							toastr.error( res.error, 'Error !');
							$( '.loader_team' ).hide();
						}
					  }
					});					
					
				});				
			}
			
		});	
		
	}
	
	/* Submit Bid */
	if( $('#applyforjob').length > 0 )
	{
		var ScrollID = 0 ;
		$("#applyforjob").validate({
			rules:{				
				quoted_price	:	{ required:true },
				attachment			:   { extension: "png|jpeg|jpg|doc|docx|pdf|xls|xlsx|ppt|pptx|txt"	}				
			}
		});
		
		$('.submit-bid').click(function(event){
			
			if (event.preventDefault) 
			{	event.preventDefault();		} 
			else 
			{	event.returnValue = false;	}
			
			if($('#applyforjob').valid())
			{
				if(  CurrentUser.ID != "" ||  CurrentUser.ID != 0 )
				{
					$( '.loader_team' ).show();
					var fd = new FormData();
					$.each($("#applyforjob").serializeArray(), function(i, field) {
					  fd.append(field.name, field.value);
					});										
				
					fd.append('attachment', $('#attachment')[0].files[0]);
					fd.append('user_id', CurrentUser.ID);
					fd.append('user_type', 2);
					$.ajax({
					  type: "POST",
					  url: Job.url+'applyForJob',
					  data: fd,
					  processData: false,
					  contentType: false,
					  success: function(res) 
					  {						
						$( '.loader_team' ).hide();
						if( res.success == 1 )
						{
							toastr.success( 'Job Applied Successfully', 'Success');
							window.location = Site.url+'/my-jobs  ';
						}
						else
						{
							toastr.error( res.error, 'Error !');
						}
					  }
					});	
				}
				else
				{
					toastr.error( "Request Can't be process", 'Error !');
				}					
			}			
		});	
		
	}
	
	/* Update Bid */
	if( $('#UpdateBid').length > 0 )
	{		
		$("#UpdateBid").validate({
			rules:{				
				quoted_price	:	{ required:true },
				attachment			:   { extension: "png|jpeg|jpg|doc|docx|pdf|xls|xlsx|ppt|pptx|txt"	}				
			}
		});
		
		$('.update-bid').click(function(event){
			
			if (event.preventDefault) 
			{	event.preventDefault();		} 
			else 
			{	event.returnValue = false;	}
			
			if($('#UpdateBid').valid())
			{
				if(  CurrentUser.ID != "" ||  CurrentUser.ID != 0 )
				{
					$( '.loader_team' ).show();
					var fd = new FormData();
					$.each($("#UpdateBid").serializeArray(), function(i, field) {
					  fd.append(field.name, field.value);
					});										
				
					fd.append('attachment', $('#attachment')[0].files[0]);
					fd.append('user_id', CurrentUser.ID);
					fd.append('user_type', CurrentUser.Role);
					$.ajax({
					  type: "POST",
					  url: Job.url+'updateBid',
					  data: fd,
					  processData: false,
					  contentType: false,
					  success: function(res) 
					  {					  
						$( '.loader_team' ).hide();
						if( res.success == 1 )
						{
							toastr.success( res.result, 'Success');
							location.reload();
						}
						else
						{
							toastr.error( res.error, 'Error !');
						} 
					  }
					});	
				}
				else
				{
					toastr.error( "Request Can't be process", 'Error !');
				}					
			}			
		});	
		
	}
	
	/* Attachment Delete */
	if( $('.removeattchment').length > 0 )
	{
		$( '.removeattchment' ).click(function()
		{
			var AttachmentID = $(this).attr( 'data-id' );
			if (confirm('Are you sure you want to Delete this Attachment, You cannot revert this action?')) 
			{
				$.ajax({
				type: "POST",
				url : Job.url+'removeAttachment',
				data:{ 'attachment_id' : AttachmentID , "user_id" : CurrentUser.ID , "user_type" : CurrentUser.Role  },
				success:function(res)
					{
						console.log(res);
						if( res.success == 1 )
						{
							toastr.success( res.result, 'Success');
							$('.removeattchment').remove();
							$('.Linkattachment').remove();
							$( "#attachment" ).show();	
						}
						else
						{
							toastr.error( res.error, 'Error !');
						}
					}
				});
			} 						
		});
	}
	
	/* Submit Answers */
	if( $('#SubmitQues').length > 0 )
	{
		$('.submit-ques').click(function(event){
			
			if (event.preventDefault) 
			{	event.preventDefault();		} 
			else 
			{	event.returnValue = false;	}			
		
			$( '.loader_team' ).show();
			
			var fd = new FormData();
			$.each($("#SubmitQues").serializeArray(), function(i, field) {
			  fd.append(field.name, field.value);
			});
			
			$.ajax({
					  type: "POST",
					  url: Job.url+'submitAnswer',
					  data: fd,
					  processData: false,
					  contentType: false,
					  success: function(res) 
					  {						
						$( '.loader_team' ).hide();
						if( res.success == 1 )
						{
							toastr.success( res.result, 'Success');
							window.location = Site.url+'/my-jobs  ';
						}
						else
						{
							toastr.error( res.error, 'Error !');
						}
					  }
			});
			
		});
	}
	
	/* Accept Bid */
	$( '.accept-bid' ).click(function()
	{
		var Bid_ID 		= $(this).attr('data-BidId');
		var Job_ID 		= $(this).attr('data-jobId');
		var BidStatus 	= $(this).attr('data-status');
		
		$( '.loader_team' ).show();
		
		$.ajax({
			type : 'POST',
			url  : Job.url+'acceptBid',
			data :{ 'bid_id': Bid_ID ,'job_id': Job_ID ,'user_id': CurrentUser.ID,'user_type': CurrentUser.Role,'status': BidStatus },
			success:function(res)
			{
				$( '.loader_team' ).hide();
				if( res.success == 1 )
				{
					toastr.success( res.result, 'Success');
					window.location = Site.url+'/my-jobs';
				}
				else
				{
					toastr.error( res.error, 'Error !');
				}
			}
		});		
		
	});
	
	
	function scrollAjax( ScrollID )
	{
		$(window).bind('scroll', function() 
		{	
			if( ScrollID == 0 )
			{		
				if($(window).scrollTop() >= $('#service-provider-listing').offset().top + $('#service-provider-listing').outerHeight() - window.innerHeight) {
					ScrollID = 1;
				var PageVal = parseInt( $('input[name="page"]').val() );
				$('input[name="page"]').val( PageVal+1 );
				
				$.ajax({
					type:"GET",
					url:User.url+'getServiceProvider',
					data:$('#search_provider').serializeArray(),
					success:function(res)
					{
						if( res.result.length != 0 )
						{
							ScrollID = 0;							
						}
						$( res.result ).each(function(index, value){							
							$('#service-provider-listing').append( '<article><figure style="background-image:url('+value.profile_image+');"></figure><div class="user-main-details"><div class="user-name"><p>'+value.first_name+' '+value.last_name+'</p></div><div class="location"><p>'+value.street_address+'</p><p>'+value.service_type+'</p></div></div><div class="pd-btn-group"><ul><li><a href="'+Site.url+'/profile/'+value.first_name+'" class="pd-btn pd-blue-btn">View</a></li><li class="user-selected"><input type="checkbox" name="invite_ids[]" class="user-checkbox" value="'+value.user_id+'" ></li></ul></div></article>' );
						});
					}
				});
					
				}
			}
		});
		
	}
	
	
	
	if( $('#search_provider').length > 0)
	{
		$.get( User.url+'getServiceProvider', function( res ){
			
		  $( res.result ).each(function(index, value){		  
			$('#service-provider-listing').append( '<article><figure style="background-image:url('+value.profile_image+');"></figure><div class="user-main-details"><div class="user-name"><p>'+value.first_name+' '+value.last_name+'</p></div><div class="location"><p>'+value.street_address+'</p><p>'+value.service_type+'</p></div></div><div class="pd-btn-group"><ul><li><a href="'+Site.url+'/profile/'+value.username+'" class="pd-btn pd-blue-btn">View</a></li><li class="user-selected"><input type="checkbox" name="invite_ids[]" value="'+value.user_id+'" class="user-checkbox"></li></ul></div></article>' );
		});
		  
		});
		
		
		/* Validation */
		$('#search_provider').validate({
			rules:{
				service_search: {
				  require_from_group: [1, ".group-required"]
				},
				autocomplete2: {
				  require_from_group: [1, ".group-required"]
				},
				sortBy: {
				  require_from_group: [1, ".group-required"]
				},
				rating: {
				  require_from_group: [1, ".group-required"]
				},
				distance: {
				  require_from_group: [1, ".group-required"]
				}				
			},
			submitHandler:function()
			{
				var PageVal = parseInt( $('input[name="page"]').val() );
				if( PageVal != 1 )
				{					
					$('input[name="page"]').val( 1 );
					ScrollID = 0;
					
				}	
				
				$.ajax({
					type:"GET",
					url:User.url+'getServiceProvider',
					data:$('#search_provider').serializeArray(),
					success:function(res)
					{
						console.log( res );
						$('#service-provider-listing').html( "" );
						$( res.result ).each(function(index, value){							
							$('#service-provider-listing').append( '<article><figure style="background-image:url('+value.profile_image+');"></figure><div class="user-main-details"><div class="user-name"><p>'+value.first_name+' '+value.last_name+'</p></div><div class="location"><p>'+value.street_address+'</p><p>'+value.service_type+'</p></div></div><div class="pd-btn-group"><ul><li><a href="'+Site.url+'/profile/'+value.first_name+'" class="pd-btn pd-blue-btn">View</a></li><li class="user-selected"><input type="checkbox" name="invite_ids[]" class="user-checkbox" value="'+value.user_id+'"></li></ul></div></article>' );
						});
						//scrollAjax( ScrollID );
					}
				});
			}
			
		});
		
		
		/* $('.filter-service').click(function()
		{
			
		}); */
	}

	/* Remove invite ( When Invitation get expired ) */
	$( '.remove-invite' ).click(function(){
		var InviteID = $(this).attr( 'date-inviteID' );
		var UserID = CurrentUser.ID;
		if( InviteID != "" )
		{
			$.ajax({
				type:'POST',
				url :Job.url+'removeInvite',
				data : { 'invite_id': InviteID , 'user_id' : UserID , 'user_type':'2' },
				success:function(res)
				{
					if( res.success == 1 )
						{
							toastr.success( res.result, 'Success');
							window.location = Site.url+'/my-jobs  ';
						}
					else
						{
							toastr.error( res.error, 'Error !');
						}						
					
				}
			})	
		}		
	})
	
	/* Decline invite */
	$( '.decline-invite' ).click(function(){
		var InviteID = $(this).attr( 'date-inviteID' );
		var UserID = CurrentUser.ID;
		if( InviteID != "" )
		{
			$.ajax({
				type:'POST',
				url :Job.url+'declineInvite',
				data : { 'invite_id': InviteID , 'user_id' : UserID , 'user_type':'2' },
				success:function(res)
				{
					if( res.success == 1 )
						{
							toastr.success( res.result, 'Success');
							window.location = Site.url+'/my-jobs  ';
						}
					else
						{
							toastr.error( res.error, 'Error !');
						}						
					
				}
			})	
		}		
	})
	
	/* Send Message */	
	$("#text-message").validate({
		rules: {
		Touser_id: {required: true},
		Fromuser_id: {required: true}		
		},
		submitHandler: function() {
			$.ajax({
				type: "POST",
				url: User.url+'sendMessage',
				data: $("#text-message").serialize(),
				timeout: 3000,
				success: function(res) 
				{
					if( res.success == 1 )
					{
						toastr.success( 'Message Send Successfully', 'Success');
						$("#clear-message").val( "" );
						
					}
					else
					{
						toastr.error( res.error, 'Error !');
					}
				}				
			});

			return false;
		}
	});
	

	
	/* Remove Bid */
	$('.Remove-bid').click(function(){
		var Bid_ID = $(this).attr( 'data-bid' );
		var JobID = $(this).attr( 'data-JobID' );
		$.ajax({
			type:'POST',
			url:Job.url+'acceptBid',
			data:{ 'user_id': CurrentUser.ID , 'user_type': '2', 'bid_id' : Bid_ID , 'job_id':JobID , 'status':2  },
			success:function(res)
			{
				if( res.success == 1 )
						{
							toastr.success( res.result, 'Success');
							window.location = Site.url+'/my-jobs  ';
						}
					else
						{
							toastr.error( res.error, 'Error !');
						}
			}
		});
	});

	/* View Feedback Button */
	$('.view-feedback').click(function()
	{
		var JobID = $(this).attr( 'data-jobID' );
		//console.log( $('.feedback_'+JobID).html() );
		$('#result-view').html( $('.feedback_'+JobID).html() );
		$('#View-feedback').modal( 'show' );	
		
	})
	
	/* Leave Feedback PopUp  */	
	
	$('.leave-feedback').click(function()
	{
		var JobID = $( this ).attr( 'data-jobID' );
		var Name = $( this ).attr( 'data-name' );
		var Img = $( this ).attr( 'data-image' );
		
		$( '#submit-review input[name="job_id"] ' ).val( JobID );
		$( '#submit-review figure' ).css( 'background-image' , 'url('+Img+')'  );
		$( '#submit-review h5' ).text( Name );
		$('#Leave-feedback').modal( 'show' );		
		
	});
	
	if($("#submit-review").length > 0)
	{
		
		$("#submit-review").validate({
			   rules: {
				rating_by_provider:			{  required:true },
				feedback_by_provider: 	 	{  required:true }
			   }
		});
		
		$('.submit-review').on('click',function(event)
		{
			$( '.loader_team' ).show();	
		if (event.preventDefault) 
			{	
				event.preventDefault();		
			} 
		else 
			{	
				event.returnValue = false;	
			}
		
			if( $("#submit-review").valid() )
			{				
				var fd = $("#submit-review").serializeArray();				
				fd.push({name: 'user_id', value: CurrentUser.ID});
				fd.push({name: 'user_type', value: CurrentUser.Role});					
				
				$.ajax({
						type:"POST",
						url: Job.url+'submitCustomerReview',
						data:fd,				
						success:function(res)
						{						
							if( res.success == 1 )
							{
								toastr.success(res.result, 'Success');								
								$.ajax({
								  type: "POST",
								  url: Job.url+'updateJobProgress',
								  data: fd,			 
								  success: function(res) 
								  {																  
									if( res.success == 1 )
									{
										toastr.success( res.result, 'Success');
										$( '.loader_team' ).hide();	
										location.reload();
									}
									else
									{
										toastr.error( res.error, 'Error !');
										$( '.loader_team' ).hide();	
									}
								  }
								});	
							}
							else
							{
								toastr.error( res.error, 'Error !');
								$( '.loader_team' ).hide();	
							}						
						}			
				})	
			}
		});
	} 

	/* Select Card */
	if( $('.card').length > 0 )
	{
		$('.card').click(function()
		{
			if( !$(this).hasClass( 'default' ))
			{
				$('.card').removeClass( 'default' );
				$(this).addClass( 'default' );
			}			
		});
	}
	
	if($("#saveCard").length > 0)
	{
		// Customer Form
		$("#saveCard").validate({
			   rules: {
				number			:			{  required:true,  maxlength: 16 },
				ExpYear			:			{  required:true },
				ExpMonth		:			{  required:true },
				cvv				:			{  required:true },
				cardholderName	: 	 		{  required:true }
			   }
		});
		
		$('.save-card').on('click',function(event)
		{
			if (event.preventDefault) 
			{	
				event.preventDefault();		
			} 
			else 
			{	
				event.returnValue = false;	
			}
			
			var Cardnumber 	= $('#credit-card').val();
			var ExpMonth 	= $('#ExpMonth').val();
			
			/* if( ExpMonth.length == 1 )
			{
				ExpMonth = '0'+ExpMonth;
			}	 */
			
			var ExpYear 	= $('#ExpYear').val();
			var cvv 		= $('#cvv').val();		
			var customerId 	= $('input[name="customerId"]').val();
			var CardName 	= $('input[name="cardholderName"]').val();
			var ExpDate 	= ExpMonth+'/'+ExpYear
		
			if( $("#saveCard").valid() )
			{
			$( '.loader_team' ).show();								
				$.ajax({
						type:"POST",
						url: User.url+'createCard',
						data:{ "user_type" : CurrentUser.Role, "user_id" : CurrentUser.ID, "cardholderName" : CardName ,'number': Cardnumber , "customerId" : customerId , "expirationDate" : ExpDate , "cvv" : cvv },				
						success:function(res)
						{	
							$( '.loader_team' ).hide();					
							if( res.success == 1 )
							{
								toastr.success(res.result, 'Success');
								location.reload();
							}
							else
							{
								toastr.error( res.error, 'Error !')
							}						
						}			
				})	
			}
		});
	}	
	
	/* Remove Card */	
		$( '.remove-card' ).click(function()
		{
			var Token = $(this).attr( 'data-id' );
			$( '.loader_team' ).show();
			$.ajax({
					type:"POST",
					url: User.url+'DeleteCard',
					data:{ "user_type" : CurrentUser.Role, "user_id" : CurrentUser.ID, "card_token" : Token },				
					success:function(res)
					{	
						$( '.loader_team' ).hide();
						if( res.success == 1 )
						{
							toastr.success(res.result, 'Success');
							location.reload();
						}
						else
						{
							toastr.error( res.error, 'Error !')
						}					
					}			
			});	
		});
	
		/* UpdateCard/EditCard */
	
	if( $('.edit-card').length > 0 )
	{
		$( '.edit-card').click(function()
		{
			var Cardnumber 	= $(this).attr( 'data-card_number' );
			var CardToken 	= $(this).attr( 'data-card_Token' );
			var CardName 	= $(this).attr( 'data-cardholderName' );
			var ExpYear 	= $(this).attr( 'data-ExpYear' );
			var ExpMonth 	= $(this).attr( 'data-ExpMonth' );
			
			
			
			$('#credit-card').val( Cardnumber );
			$('#ExpMonth').val( ExpMonth );
			$('#ExpYear').val( ExpYear );		
			$('input[name="cardholderName"]').val( CardName );
			/* $( "input[name='customerId']" ).after( "<input type='hidden' name='card_token' value='"+CardToken+"' >" ); */
			$( '.card-form h3' ).text( 'Update Card' );
			$( '.card-form form' ).attr( 'id','Update-Card' );
			$( '.card-form form' ).attr( 'data-token', CardToken);
			$( '.blue_btn' ).removeClass( 'save-card' );
			$( '.blue_btn' ).addClass( 'update-card' );
			
			 $('html, body').animate({
				scrollTop: $(".card-form").offset().top - 100
			}, 2000);
			//$("#Update-Card").initValidation();
			$(document).on('click','.update-card',function(event)
			{
				
				
				$("#Update-Card").validate({
					   rules: {
						number			:			{  required:true },
						ExpYear			:			{  required:true },
						ExpMonth		:			{  required:true },
						cvv				:			{  required:true },
						cardholderName	: 	 		{  required:true }
					   }
				});				
				
				if (event.preventDefault) 
				{	
					event.preventDefault();		
				} 
				else 
				{	
					event.returnValue = false;	
				}
				
				var Cardnumber 	= $('#credit-card').val();
				var ExpMonth 	= $('#ExpMonth').val();
				var card_token 	= $('#Update-Card').attr( 'data-token' );
				
				var ExpYear 	= $('#ExpYear').val();
				var cvv 		= $('#cvv').val();		
				var customerId 	= $('input[name="customerId"]').val();
				var CardName 	= $('input[name="cardholderName"]').val();
				var ExpDate 	= ExpMonth+'/'+ExpYear
				
				
				if( $("#Update-Card").valid() )
				{	
					$( '.loader_team' ).show();
					$.ajax({
							type:"POST",
							url: User.url+'UpdateCard',
							data:{ "user_type" : CurrentUser.Role, "user_id" : CurrentUser.ID, "cardholderName" : CardName ,'number': Cardnumber , "customerId" : customerId , "expirationDate" : ExpDate , "cvv" : cvv , "card_token" : card_token },				
							success:function(res)
							{	
								$( '.loader_team' ).hide();
								if( res.success == 1 )
								{
									toastr.success(res.result, 'Success');
									location.reload();
								}
								else
								{
									toastr.error( res.error, 'Error !')
								}					
							}			
					})	
				}
			});		
			
		});
		
	}
	
	
	/* Create Contract */
	if( $( '#Payment' ).length > 0 )
	{
		$('.pay').click(function()
		{
			if (event.preventDefault) 
			{	
				event.preventDefault();		
			} 
			else 
			{	
				event.returnValue = false;	
			}
				$( '.loader_team' ).show();
				
				var fd = new FormData();
				$.each($("#Payment").serializeArray(), function(i, field) {
				  fd.append(field.name, field.value);
				});
				
				fd.append('user_id', CurrentUser.ID);
				fd.append('user_type', CurrentUser.Role);
				fd.append('card_token', $('.default').attr('data-id'));
				
				
				$.ajax({
				  type: "POST",
				  url: Job.url+'jobPayment',
				  data: fd,
				  processData: false,
				  contentType: false,
				  success: function(res) 
				  {				
					if( res.success == 1 )
					{
						toastr.success( res.result, 'Success');
						toastr.success('Creating Contract' , 'Processing');
						//window.location = Site.url+'/my-jobs  ';
						
						fd.append('status', 1);
						
						$.ajax({
							  type: "POST",
							  url: Job.url+'acceptBid',
							  data: fd,
							  processData: false,
							  contentType: false,
							  success: function(res) 
							  {
								$( '.loader_team' ).hide();								  
								if( res.success == 1 )
								{
									toastr.success( res.result, 'Success');
									window.location = Site.url+'/my-jobs  ';
								}
								else
								{
									toastr.error( res.error, 'Error !');
								}
							  }
							});
					}
					else
					{
						$( '.loader_team' ).hide();	
						toastr.error( res.error, 'Error !');
						location.reload();
					}
				  }
				});
		});
	}
	
	/* Reject Bid */
	$( '.reject-bid' ).click(function()
	{
		$( '.loader_team' ).show();	
		var Bidstatus 	=  $(this).attr( 'data-status' );
		var JobID 		=  $(this).attr( 'data-jobId' );
		var BidId 		=  $(this).attr( 'data-BidId' );
		console.log(  CurrentUser.ID );
		console.log(  CurrentUser.Role );
		$.ajax({
			  type: "POST",
			  url: Job.url+'acceptBid',
			  data: { 'status' : Bidstatus , 'bid_id' : BidId , 'job_id' : JobID , 'user_id' : CurrentUser.ID , 'user_type' : CurrentUser.Role },			 
			  success: function(res) 
			  {
				$( '.loader_team' ).hide();								  
				if( res.success == 1 )
				{
					toastr.success( res.result, 'Success');
					location.reload();
				}
				else
				{
					toastr.error( res.error, 'Error !');
				}
			  }
			});		
		
	});
	
	
	/* Job Progress */
	
	if( $('.job_progress').length > 0 )
	{
		$('.job_progress').click(function()
		{ 
			var JobID = $(this).attr( 'data-jobID' );
			var Status = $(this).attr( 'data-status' );
			$( '.loader_team' ).show();	
			
			if( Status == 3 )
			{
				$( '#submit-review input[name="job_id"]' ).val( JobID );
				$( '#submit-review input[name="job_progress"]' ).val( Status );
				$('#Leave-feedback').modal('show');
				$( '.loader_team' ).hide();				
			}
			else
			{			
				$.ajax({
				  type: "POST",
				  url: Job.url+'updateJobProgress',
				  data: { 'job_progress' : Status , 'job_id' : JobID , 'user_id' : CurrentUser.ID , 'user_type' : CurrentUser.Role },			 
				  success: function(res) 
				  {
					$( '.loader_team' ).hide();								  
					if( res.success == 1 )
					{
						toastr.success( res.result, 'Success');
						location.reload();
					}
					else
					{
						toastr.error( res.error, 'Error !');
					}
				  }
				});	
			}
		});
	}
	
	/* MarkJobComplete */
	$( '.complete-job' ).on('click',function()
	{
		var getJobID = $( this ).attr( 'data-jobid' );
		console.log(getJobID);
		
		$( '#CustLeave-feedback' ).modal( 'show' ) ;
		
		$( '#cstsubmit-review input[name="job_id"]' ).val(getJobID);
		
		$("#cstsubmit-review").validate({
			   rules: {
				rating_by_cust			:			{  required:true },
				feedback_by_cust		:			{  required:true }				
			   }
		});
		
		
		$('.cstsubmit-review').click(function(event)
		{
			if (event.preventDefault) 
			{	event.preventDefault();		} 
			else 
			{	event.returnValue = false;	}
		
			$( '.loader_team' ).show();
			if( $("#cstsubmit-review").valid() )
			{
				var JobID 		= $('#cstsubmit-review input[name="job_id"]').val();
				var JobStatus 	= $('#cstsubmit-review input[name="job_status"]').val();
				var Rating 		= $('#star-rating-12').val();
				var Feedback 	= $('textarea[name="feedback_by_cust"]').val();				
				$.ajax({
					type: 'POST',
					url :Job.url+'markJobComplete',
					data:{ 'user_id' : CurrentUser.ID , 'user_type': CurrentUser.Role, 'job_id' : JobID , 'job_status' : JobStatus , 'rating_by_cust' : Rating , 'feedback_by_cust' : Feedback },
					success:function(res)
					{
						$( '.loader_team' ).hide();
						if( res.success == 1 )
							{
								toastr.success(res.result, 'Success');
								location.reload();
							}
							else
							{
								toastr.error( res.error, 'Error !');
							}	
					}
				}); 
			}
			
		});	
		
	})
	
	/* Google Login Button */
	if( $( '#login-button' ).length > 0 )
	{
				// Click on login button
		$("#login-button").on('click', function() 
		{
			var UserType = $( 'input[name="user_type"]:checked' ).val();
			
			if( typeof UserType != 'undefined' || UserType != null )
			{
				$("#login-button").attr('disabled', 'disabled');
					
					// API call for Google login
					gapi.auth2.getAuthInstance().signIn().then(
						// On success
						function(success) {
							// API call to get user information
							gapi.client.request({ path: 'https://www.googleapis.com/plus/v1/people/me' }).then(
								// On success
								function(success) {
									var user_info = JSON.parse(success.body);
									
									$.ajax({
											type:'GET',
											url:User.url+'getEmailExists',
											data:{ 'email_exists':user_info.emails[0].value},
											success:function(res)
											{
												if( res.success == 1 )
												{													
													if( res.result == 'yes' )
													{
														toastr.error( 'Email Already Exists! Please Login Redirecting...', 'Email Exists !');
														setTimeout(function(){ window.location = Site.url+'/sign-in/'; },3000);						
													}
													else if( res.result == 'no' )
													{
														$( '.sign-in-btn-group' ).hide();
														$( '.or' ).hide();
														//console.log(success);
														var validator = $( "#Customer_Form" ).validate();
														validator.destroy();
														 
														/* Removing Form Fields */
														$( '#Confirm_password' ).parent().parent().remove();
														$( 'input[name="Confirm_password"]' ).parent().parent().remove();
														$( 'input[name="username"]' ).parent().parent().remove();									
														$( '#Customer_Form' ).attr( 'id' , 'SocialSignUp' );
														$( '.reg_me' ).addClass( 'social-signup' );
														$( '.reg_me' ).removeClass( 'reg_me' );
														toastr.success('Please Fill rest information', 'Success');
														$('html, body').animate({
															scrollTop: $(".select-sign-up").offset().top - 100
														}, 2000);
														
														
														/* Filling Fields */
														$( 'input[name="first_name"]' ).val( user_info.name.givenName );
														$( 'input[name="last_name"]' ).val( user_info.name.familyName );
														$( 'input[name="email"]' ).val( user_info.emails[0].value );
														jQuery('#SocialSignUp').append('<input type="hidden" value="google" name="socialtype">	');
														jQuery('#SocialSignUp').append('<input type="hidden" value="1" name="ajax">	');
														jQuery('#SocialSignUp').append('<input type="hidden" value="'+user_info.image.url+'" name="photo_url">	');
														jQuery('#SocialSignUp').append('<input type="hidden" value="'+user_info.id+'" name="socialLoginId">	');
											
													}

												}
												else
												{
													toastr.error( res.error, 'Error !');
												}
											}
									});
									
								},
								// On error
								function(error) {
									$("#login-button").removeAttr('disabled');
									toastr.error( 'Failed to get user user information', 'Error !');							
								}
							);
						},
						// On error
						function(error) {
							$("#login-button").removeAttr('disabled');
							toastr.error( 'Login Failed ! Please register ', 'Error !');
						}
					);
			}
			else
			{
				toastr.error( 'Please Select User Type ', 'Error !');
				$('html, body').animate({
					scrollTop: $(".select-sign-up").offset().top - 100
				}, 2000);
			}
		});

	}

	/* Google Login Web Page */
	
	if( $( '#Signin-button' ).length > 0 )
	{
				// Click on login button
		$("#Signin-button").on('click', function() 
		{
			var UserType = $( 'input[name="user_type"]:checked' ).val();
			
			if( typeof UserType != 'undefined' || UserType != null )
			{
				$("#Signin-button").attr('disabled', 'disabled');
					
					// API call for Google login
					gapi.auth2.getAuthInstance().signIn().then(
						// On success
						function(success) {
							// API call to get user information
							gapi.client.request({ path: 'https://www.googleapis.com/plus/v1/people/me' }).then(
								// On success
								function(success) 
								{
									var user_info = JSON.parse(success.body);
									$.ajax({
										type:'POST',
										url:User.url+'WebsocialLogin',
										data:{ 'email': user_info.emails[0].value , 'socialID': user_info.id , 'user_type' : UserType },
										success:function(res)
										{
											if( res.success == 1 )
												{
													toastr.success(res.result, 'Success');
													window.location = Site.url+'/my-jobs/';
												}
												else
												{
													toastr.error( res.error, 'Error !');
												}	
										}
									});									
								},
								// On error
								function(error) {
									$("#Signin-button").removeAttr('disabled');
									toastr.error( 'Failed to get user user information', 'Error !');							
								}
							);
						},
						// On error
						function(error) {
							$("#Signin-button").removeAttr('disabled');
							toastr.error( 'Login Failed ! Please register ', 'Error !');
						}
					);
			}
			else
			{
				toastr.error( 'Please Select User Type ', 'Error !');
				$('html, body').animate({
					scrollTop: $(".select-sign-up").offset().top - 100
				}, 2000);
			}
		});

	}

	
	$(document).on( 'click','.social-signup',function(event)
	{
		if (event.preventDefault) 
		{	event.preventDefault();		} 
		else 
		{	event.returnValue = false;	}
		
		// Customer Form
		$("#SocialSignUp").validate({
			   rules: {
				user_type:			{  required:true },				
				first_name:			{  required:true },				
				email: 	 			{  required:true,email:true },
				street_address: 	{  required:true },       
				city: 	 			{  required:true },       
				postcode: 	 		{  required:true },       
				state: 	 			{  required:true },
				terms: 	 			{  required:true }, 
				business_name: 		{  required:'#ServiceProviderForm:checked' },
				service_type: 		{  required:'#ServiceProviderForm:checked' },
				business_type: 		{  required:'#ServiceProviderForm:checked' },				
				overview: 			{  required:'#ServiceProviderForm:checked' }				
			   }			   
		});		
		
		
		if( $("#SocialSignUp").valid() )
		{			
			var FormDate = $("#SocialSignUp").serializeArray();		
			 $.ajax({
					type:"POST",
					url: User.url+'socialLogin',
					data:FormDate,				
					success:function(res)
					{						
						if( res.success == 1 )
						{
							toastr.success(res.result, 'Success');
							window.location = Site.url+'/my-jobs/';
						}
						else
						{
							toastr.error( res.error, 'Error !');
						}		
					}			
			})	
		}
	});

	/* Notification Section */
	$( '.noti-alert' ).click(function()
	{
		var notiID = $(this).attr( 'data-notiID' );
		$.ajax({
			type : 'POST',
			url  : User.url+'NotificationRead',
			data :{"user_id":CurrentUser.ID,"user_type":CurrentUser.Role,"Noti_ID":notiID},
			success:function(res)
			{
				if( res.success == 1 )
					{
						toastr.success(res.result, 'Success');						
					}
				else
					{
						toastr.error( res.error, 'Error !');
					}	
			} 
		});
	});

	/* Alert JS */
	$( '.alert-read' ).click(function(event)
	{
		/*if (event.preventDefault) 
		{	event.preventDefault();		} 
		else 
		{	event.returnValue = false;	}*/

		var RealtedID = $(this).attr( 'data-relatedID' );
		var REDURL = $(this).attr( 'href' );
		$.ajax({
			type : 'POST',
			url  : User.url+'updateRead',
			data : { 'user_type' : CurrentUser.Role , 'user_id' : CurrentUser.ID , 'realted_id' : RealtedID },
			success : function(res)
			{
				console.log( res );
				//window.location = REDURL;
			}
		});
	});

	
	/* Get Message */
	var TotalCount = 0;
	var Pagination = 0;
});	
	
	function getMessage( ConvID , page )
	{	
		setInterval(function()
		{
			console.log( page );		
			$.ajax({
				type:'GET',
				url:User.url+'getMessage',
				data:{ page:page, conv_IDs: ConvID , ajax:'1' , 'user_id' : CurrentUser.ID},
				success:function(res)
				{
					var obj = jQuery.parseJSON(res);
					if( obj.article != "" && obj.TotalCount == 20  )
						{
							console.log(TotalCount);
							Pagination = Pagination + 1;
							if( page != 1 )
							{
								TotalCount = 0;							
							}
							page = Pagination;
						}
					if( obj.TotalCount != TotalCount )
					{						
						TotalCount = obj.TotalCount;			
						console.log( TotalCount );
						$(".inboxContent").mCustomScrollbar('destroy');
						$('.inbox-chat-cover').append(obj.article);
						$('.chat-list').html(obj.Thread);
						$(".inboxContent").mCustomScrollbar({
							axis: "y"
						}); 
						$(".inbox-chat-cover").mCustomScrollbar("scrollTo", "bottom");
					}
				}				
			});

		}, 2000);
	}


	(function($){   
		$.fn.loaddata = function(options) {// Settings
			var settings = $.extend({ 
				loading_gif_url : "https://digitalsynopsis.com/wp-content/uploads/2016/06/loading-animations-preloader-gifs-ui-ux-effects-10.gif", //url to loading gif
				end_record_text : 'No more records found!', //no more records to load
				data_url        : User.url+'getServiceProvider', //url to PHP page
				start_page      : 1 //initial page
			}, options);
			
			var el = this;  
			loading  = false; 
			end_record = false;
			contents(el, settings); //initial data load
			
			$(window).scroll(function() { //detact scroll
				if($(window).scrollTop() + $(window).height() >= $(document).height()){ //scrolled to bottom of the page
					contents(el, settings); //load content chunk 
				}
			});     
		}; 
		//Ajax load function
		function contents(el, settings){
			var load_img = $('<img/>').attr('src',settings.loading_gif_url).addClass('loading-image'); //create load image
			var record_end_txt = $('<div/>').text(settings.end_record_text).addClass('end-record-info'); //end record text
			
			if(loading == false && end_record == false){
				loading = true; //set loading flag on
				el.append(load_img); //append loading image
				$.get( settings.data_url, {'page': settings.start_page}, function(data){ //jQuery Ajax post
					console.log(data);
					/* if(data.trim().length == 0){ //no more records
						el.append(record_end_txt); //show end record text
						load_img.remove(); //remove loading img
						end_record = true; //set end record flag on
						return; //exit
					}*/ 
					loading = false;  //set loading flag off
					load_img.remove(); //remove loading img 
					el.append(data.result);  //append content 
					settings.start_page ++; //page increment
				})
			}
		}

	})(jQuery);
	
	/* Google Login JS */
	// Called when Google Javascript API Javascript is loaded
function HandleGoogleApiLibrary() {
	// Load "client" & "auth2" libraries
	gapi.load('client:auth2', {
		callback: function() {
			// Initialize client library
			// clientId & scope is provided => automatically initializes auth2 library
			gapi.client.init({
		    	apiKey: 'Id6B8zo6jVF7Nqrl5WPtIbmt',
		    	clientId: '905643289637-oo0l85adlv0gu2b33is5nk3gc7f1uem1.apps.googleusercontent.com',
		    	scope: 'https://www.googleapis.com/auth/userinfo.profile https://www.googleapis.com/auth/userinfo.email https://www.googleapis.com/auth/plus.me'
			}).then(
				// On success
				function(success) {
			  		// After library is successfully loaded then enable the login button
			  		$("#login-button").removeAttr('disabled');
				}, 
				// On error
				function(error) {
					alert('Error : Failed to Load Library');
			  	}
			);
		},
		onerror: function() {
			// Failed to load libraries
		}
	});
}

/* Facebook SignIn */

window.fbAsyncInit = function() {
    // FB JavaScript SDK configuration and setup
    FB.init({
      appId      : '217207088900645', // FB App ID
      cookie     : true,  // enable cookies to allow the server to access the session
      xfbml      : true,  // parse social plugins on this page
      version    : 'v2.8' // use graph api version 2.8
    });
    
    // Check whether the user already logged in
    FB.getLoginStatus(function(response) {
        if (response.status === 'connected') {
            //display user data
            //getFbUserData();
            fbLogout();
        }
    });
};

// Load the JavaScript SDK asynchronously
(function(d, s, id) {
    var js, fjs = d.getElementsByTagName(s)[0];
    if (d.getElementById(id)) return;
    js = d.createElement(s); js.id = id;
    js.src = "//connect.facebook.net/en_US/sdk.js";
    fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));

// Facebook login with JavaScript SDK
function fblogin() 
{
	var UserType = $( 'input[name="user_type"]:checked' ).val();
	console.log(UserType);
	if( typeof UserType != 'undefined' || UserType != null )
	{
		FB.login(function (response) 
	    {    	
	    	
		 if (response.authResponse) 
	        {            
	            getFbUserData();
	        }
	    else 
	        {
	        	toastr.error( 'User cancelled login or did not fully authorize.', 'Error !');			           
	        }

	    }, {scope: 'email'});
	}
	else
	{		
		toastr.error( 'Please Select User Type ', 'Error !');
		$('html, body').animate({
			scrollTop: $(".select-sign-up").offset().top - 100
		}, 2000);
	}

}

function fbSignIn() // Web Login Page
{
	var UserType = $( 'input[name="user_type"]:checked' ).val();
	console.log(UserType);
	if( typeof UserType != 'undefined' || UserType != null )
	{
		FB.login(function (response) 
	    {    	
	    	
		 if (response.authResponse) 
	        {            
	            getFbUserDataSignIn(UserType);
	        }
	    else 
	        {
	        	toastr.error( 'User cancelled login or did not fully authorize.', 'Error !');			           
	        }

	    }, {scope: 'email'});
	}
	else
	{		
		toastr.error( 'Please Select User Type ', 'Error !');
		$('html, body').animate({
			scrollTop: $(".select-sign-up").offset().top - 100
		}, 2000);
	}

}


	/* Fb Login Get User Data */
	// Fetch the user profile data from facebook
function getFbUserData(){
    FB.api('/me', {locale: 'en_US', fields: 'id,first_name,last_name,email,link,gender,locale,picture'},
    function (response) {    	
    	$.ajax({
			type:'GET',
			url:User.url+'getEmailExists',
			data:{ 'email_exists':response.email},
			success:function(res)
			{
				if( res.success == 1 )
				{
					
					if( res.result == 'yes' )
					{
						toastr.error( 'Email Already Exists! Please Login Redirecting...', 'Email Exists !');
						setTimeout(function(){ window.location = Site.url+'/sign-in/'; },3000);						
					}
					else if( res.result == 'no' )
					{
						$( '.sign-in-btn-group' ).hide();
						$( '.or' ).hide();
						//console.log(success);
						var validator = $( "#Customer_Form" ).validate();
						validator.destroy();
						 
						/* Removing Form Fields */
						$( '#Confirm_password' ).parent().parent().remove();
						$( 'input[name="Confirm_password"]' ).parent().parent().remove();
						$( 'input[name="username"]' ).parent().parent().remove();									
						$( '#Customer_Form' ).attr( 'id' , 'SocialSignUp' );
						$( '.reg_me' ).addClass( 'social-signup' );
						$( '.reg_me' ).removeClass( 'reg_me' );
						toastr.success('Please Fill rest information', 'Success');
						$('html, body').animate({
							scrollTop: $(".select-sign-up").offset().top - 100
						}, 2000);		
						console.log(response);
						/* Filling Fields */
						$( 'input[name="first_name"]' ).val( response.first_name );
						$( 'input[name="last_name"]' ).val( response.last_name );
						$( 'input[name="email"]' ).val( response.email );
						jQuery('#SocialSignUp').append('<input type="hidden" value="fb" name="socialtype">	');
						jQuery('#SocialSignUp').append('<input type="hidden" value="1" name="ajax">	');
						jQuery('#SocialSignUp').append('<input type="hidden" value="'+response.picture.data.url+'" name="photo_url">	');
						jQuery('#SocialSignUp').append('<input type="hidden" value="'+response.id+'" name="socialLoginId">	');
					}

				}
				else
				{
					toastr.error( res.error, 'Error !');
				}	
			}
		});
    });
}


function getFbUserDataSignIn(UserType){ // Web SignIn
    FB.api('/me', {locale: 'en_US', fields: 'id,first_name,last_name,email,link,gender,locale,picture'},
    function (response) {    	
    	$.ajax({
			type:'POST',
			url:User.url+'WebsocialLogin',
			data:{ 'email': response.email , 'socialID': response.id , 'user_type' : UserType },
			success:function(res)
			{
				//console.log( res );
				if( res.success == 1 )
				{
					toastr.success( res.result, 'Success');
					window.location = Site.url+'/my-jobs/';
				}
				else
				{
					toastr.error( res.error, 'Error !');
				}
			}
		});
    });
}



// Logout from facebook
function fbLogout() {
    FB.logout(function() {
        /*document.getElementById('fbLink').setAttribute("onclick","fbLogin()");
        document.getElementById('fbLink').innerHTML = '<img src="fblogin.png"/>';
        document.getElementById('userData').innerHTML = '';
        document.getElementById('status').innerHTML = 'You have successfully logout from Facebook.';*/
    });
}

function SendAlert( RealtedID )
{
	$.ajax({
			type : 'POST',
			url  : User.url+'updateRead',
			data : { 'user_type' : CurrentUser.Role , 'user_id' : CurrentUser.ID , 'realted_id' : RealtedID },
			success : function(res)
			{
				console.log( res );
				//window.location = REDURL;
			}
		});
}
	
