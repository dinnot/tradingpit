	
$(document).ready(function(){

	

	//CUFON
	Cufon.replace('h1'); 
	Cufon.replace('#slidecaption'); 
	

	//SET COUNTER DATE
	
	
	$('#countdown_dashboard').countDown({
		targetDate: {
			'day': 		Day,
			'month': 	Month,
			'year': 	Year,
			'hour': 	Hour,
			'min': 		Min,
			'sec': 		Sec
			}
	});
	
	//PROGRESS BAR
	if(containerStat==1){
		$("#barRight").width(0); 															    //set bar width to 0
		$("#barRight").delay(animationDelay).animate({ width: percentComplete }, barSpeed);   //animate bar  
		$(".counter").html(percentComplete +"&#8204 complete");								//set counter % equal to bar %
		$(".counter").delay(1000).fadeIn( 2500);												//animate % 
	}
	
	//CONTENT SWTICH
	var switchState = 0;
	$('#contentSwitch').click(function() {
		if(switchState==0){
			$('#content2').delay(1000).fadeIn(); 
			$('#content1').fadeOut();;
			$('#mid').slideToggle("slow");
			$('#mid').delay(500).slideToggle("slow");
			$('#contentSwitch').fadeOut('normal', function(){ 
				//switch link title										   
            	 $('#contentSwitch').html('Go back'); 
         	 });
			
			$('#contentSwitch').fadeIn();
			switchState=1;
		}else{
			$('#content1').delay(1000).fadeIn();
			$('#content2').fadeOut();
			$('#mid').slideToggle("slow");
			$('#mid').delay(500).slideToggle("slow");
			$('#contentSwitch').fadeOut('normal', function(){ 
				//switch link title											   
            	 $('#contentSwitch').html('Get in touch');
         	 });
			$('#contentSwitch').fadeIn();
			switchState=0;
		}
											 
	});
	

	//ICON HOVERS
	$(".icon_rollover").animate({ "opacity": "0" });
	
	$(".icon_rollover").hover(
			function() {
				$(this).animate({"opacity": "1"}, fadeSpeedIcons);
		},
			function() {
				$(this).animate({"opacity": "0"}, fadeSpeedIcons);
				
		});
	
	
		
		//SET SOCIAL URLS
		$("a#facebook").attr("href", "http://www.facebook.com/pages/"+facebookPageID)
		$("a#twitter").attr("href", "http://www.twitter.com/"+twitterID)
		$("a#myspace").attr("href", "http://www.myspace.com/"+myspaceID)
		$("a#skype").attr("href", "skype:"+skypeID+"?call")
		
		
		//CONTACT FORM - original code by Farid Hadi -http://www.faridhadi.com
		
		$('#reload').hide();// hide form reload button
		
		$('#contactForm #submit').click(function() {
												 
			// Fade in the prloader
			$('#contactForm #formProgress').hide();
			$('#contactForm #formProgress').html('<img src="images/loader.gif" /> Sending&hellip;');
			$('#contactForm #formProgress').fadeIn();
			
			// Disable the submit button
			$('#contactForm #submit').attr("disabled", "disabled");
			
			// Set temaprary variables for the script
			var isFocus=0;
			var isError=0;
			
			// Get the data from the form
			var name=$('#contactForm #name').val();
			var email=$('#contactForm #email').val();
			var subject=$('#contactForm #subject').val();
			var message=$('#contactForm #message').val();
			
			//Make sure borders are set to gray
			$('#contactForm #name').css({'border':'1px solid #111111'})
			$('#contactForm #email').css({'border':'1px solid #111111'})
			$('#contactForm #message').css({'border':'1px solid #111111'})
			
			// Validate the data
			if(name=='Name*') {
				$('#contactForm #name').css({'border':formBorderVerify});
				$('#contactForm #name').focus();
				isFocus=1;
				isError=1;
			}
			if(email=='E-mail*') {
				$('#contactForm #email').css({'border':formBorderVerify});
				if(isFocus==0) {
					$('#contactForm #email').focus();
					isFocus=1;
				}
				isError=1;
			} else {
				var reg = /^([A-Za-z0-9_\-\.])+\@([A-Za-z0-9_\-\.])+\.([A-Za-z]{2,4})$/;
				if(reg.test(email)==false) {
					$('#contactForm #email').css({'border':formBorderVerify});
	
					if(isFocus==0) {
						$('#contactForm #email').focus();
						isFocus=1;
					}
					isError=1;
					
				}
			}
			if(message=='Message*') {
				$('#contactForm #message').css({'border':formBorderVerify});
				if(isFocus==0) {
					$('#contactForm #message').focus();
					isFocus=1;
				}
				isError=1;
			}
			
			// Terminate the script if an error is found
			if(isError==1) {
				$('#contactForm #formProgress').html(formWarning);
				
				
				// Activate the submit button
				$('#contactForm #submit').attr("disabled", "");
				
				return false;
			}
			
			$.ajaxSetup ({
				cache: false
			});
			
			var dataString = 'name='+ name + '&email=' + email + '&subject=' + subject + '&message=' + message;  
			$.ajax({
				type: "POST",
				url: "php/submit-form-ajax.php",
				data: dataString,
				success: function(msg) {
					
					//alert(msg);
					
					// Check to see if the mail was successfully sent
					if(msg=='Mail sent') {
						
						// Update the loader to a check + message
						$('#sentConfirmMessage').html(formSuccess);
						
						//Change the main title
						$('#sentConfirmTitle').html(formSuccessTitle);
						
						//Display the info
						$('#sentConfirmMessage').fadeIn(1000);
						$('#sentConfirmTitle').fadeIn(1000);
						
						// Reinitialize the fields
						$('#contactForm #name').val('Name*');
						$('#contactForm #email').val('E-mail*');
						$('#contactForm #subject').val('Subject');
						$('#contactForm #message').val('Message*');
						
						// Fade out the contact from, then toggle the height
						$("#contactForm").animate({"opacity": "0"}, 1000);	
						$('#contactForm').delay(200).slideToggle("slow");
						
						//Fade in reload link
						$('#reload').fadeIn();	
						
						
						//Ensure new title is cufoned after sending
						Cufon.replace('h1#sentConfirmTitle');
						
						
					} else {
						$('#contactForm #formProgress').html(formError);
					}
					
					// Activate the submit button
					$('#contactForm #submit').attr("disabled", "");
				},
				error: function(ob,errStr) {
					$('#contactForm #formProgress').html(formError);
					
					// Activate the submit button
					$('#contactForm #submit').attr("disabled", "");
				}
			});
			
			return false;
	});
		
	// Contact form reload but function	
	$('#reload').click(function() {
		$("#contactForm").animate({"opacity": "1"}, 1000);	
		$('#contactForm').animate({ height:'toggle' }, 1000);
		$('#sentConfirmMessage').html(formReload);
		$('#sentConfirmTitle').html(formReloadTitle);
		$('#reload').fadeOut();
		$('#contactForm #formProgress').html('*required');
		//Ensure new title is cufoned
		Cufon.replace('h1#sentConfirmTitle');
		
	});			
		
		
		
		//CONTACT FORM
		$('#notify #submitNotify').click(function() {
												 
			// Fade in the loader
			$('#notify #notifyProgress').hide();
			$('#notify #notifyProgress').html('<img src="images/loader.gif" /> Sending&hellip;');
			$('#notify #notifyProgress').css({color:'#919191'});
			$('#notify #notifyProgress').fadeIn();
			
			// Disable the submitNotify button + field while sending
			$('#notify #submitNotify').attr("disabled", "disabled");
			
			// Set temaprary variables for the script
			var isFocus=0;
			var isError=0;
			
			// Get the data from the notify field
			var emailNotify=$('#notify #emailNotify').val();
			
			// Validate the data
			if(emailNotify=='Your e-mail') {
				if(isFocus==0) {
					$('#notify #emailNotify').focus();
					isFocus=1;
				}
				isError=1;
			} else {
				var reg = /^([A-Za-z0-9_\-\.])+\@([A-Za-z0-9_\-\.])+\.([A-Za-z]{2,4})$/;
				if(reg.test(emailNotify)==false) {
					if(isFocus==0) {
						$('#notify #emailNotify').focus();
						isFocus=1;
					}
					isError=1;
					
				}
			}
			
			
			// Terminate the script if an error is found
			if(isError==1) {
				
				//Fade in progress message
				$('#notify #notifyProgress').fadeIn();
				
				//Display warning message +fade out
				$('#notify #notifyProgress').html(notifyWarning);
				$('#notify #notifyProgress').css({color:'#fff'});
				$('#notify #notifyProgress').delay(1000).fadeOut(1000);
				
				// Activate the submitNotify button
				$('#notify #submitNotify').attr("disabled", "");
				
				return false;
			}
			
			$.ajaxSetup ({
				cache: false
			});
			
			var dataString = 'emailNotify='+ emailNotify;  
			$.ajax({
				type: "POST",
				url: "php/submit-notify-ajax.php",
				data: dataString,
				success: function(msg) {
					
					//alert(msg);
					
					// Check to see if the mail was successfully sent
					if(msg=='Mail sent') {
						
						//Fade in progress message
						$('#notify #notifyProgress').fadeIn();
						
						// Reinitialize the field
						$('#notify #emailNotify').val('Your e-mail');
						
						//Display success message + fade out
						$('#notify #notifyProgress').css({color:'#fff'});
						$('#notify #notifyProgress').html(notifySuccess);
						$('#notify #notifyProgress').delay(1000).fadeOut(1000);
						
					} else {
						
						//Fade in progress message
						$('#notify #notifyProgress').fadeIn();
						
						//Display error message + fade out
						$('#notify #notifyProgress').html(notifyError);
						$('#notify #notifyProgress').css({color:'#fff'});
						$('#notify #notifyProgress').delay(1000).fadeOut(1000);
					}
					
					// Activate the submitNotify button
					$('#notify #submitNotify').attr("disabled", "");
				},
				error: function(ob,errStr) {
					
					//Fade in progress message
					$('#notify #notifyProgress').fadeIn();
					
					//Display error message + fade out
					$('#notify #notifyProgress').html(notifyError);
					$('#notify #notifyProgress').css({color:'#fff'});
					$('#notify #notifyProgress').delay(1000).fadeOut(1000);
					
					// Activate the submitNotify button
					$('#notify #submitNotify').attr("disabled", "");
				}
			});
			
			return false;
	});
});
