function problemSignin(typeOfError){
	/* 	
	 	 * username - validate_signup_form.php - Someone already has that username!
		 * email - validate_signup_form.php - Someone has taken that email address.
		 * signin_username - validate_signin_form.php - if no match for username or password.
	*/
	switch(typeOfError){
	case "signin_username":
		$('#index_error').html("Username or Password was incorrect!");
		break;
	case "username":
		$('#index_error').html("Username has already been taken!");
	break;
	case "email":
		$('#index_error').html("Email has already been taken!");
	break;	
	default:
	break;
	}
	
}



function signin_form(){
	$('form#signin_page_signin').submit(function(e){
		//e.preventDefault();
		//alert('worked');
		if($('input#username').val() == ''){
			e.preventDefault();
			$('form#signin_page_signin input#username').css('border-color','red');
			$('form#signin_page_signin input#password').css('border-color','');	
			$('#index_error').html("Enter Username!");
		}
		else if ($('input#password').val() == ''){
			e.preventDefault();
			$('form#signin_page_signin input#password').css('border-color','red').siblings().css('border-color','');		
			$('#index_error').html("Enter Password!");
		}
		else{
			$('input#form_sign_in_submit').submit();			
		} 
	});
}

function signup_form(){
	$('form#signin_page_signup').submit(
			function(e){
				if($('form#signin_page_signup input#username').val()==''){
					e.preventDefault();
					$('form#signin_page_signup input#username').css('border-color','red').siblings().css('border-color','');		
					$('#index_error').html("Please enter a desired Username!");
				}
				else if($('form#signin_page_signup input#email').val() == ''){
					e.preventDefault();
					$('form#signin_page_signup input#email').css('border-color','red').siblings().css('border-color','');		
					$('#index_error').html("Please enter a Email Address!");
				}
				else if ($('form#signin_page_signup input#first_password').val()==''){
					e.preventDefault();
					$('form#signin_page_signup input#first_password').css('border-color','red').siblings().css('border-color','');		
					$('#index_error').html("Please enter a desired Password!");
				}
				else if ($('form#signin_page_signup input#verified_password').val()==''){
					e.preventDefault();
					$('form#signin_page_signup input#verified_password').css('border-color','red').siblings().css('border-color','');		
					$('#index_error').html("Please enter type your desired Password again!");
				}
				else if ($('form#signin_page_signup input#verified_password').val() != $('form#signin_page_signup input#first_password').val()){
					e.preventDefault();
					$('form#signin_page_signup input#verified_password').val('');
					$('form#signin_page_signup input#verified_password').css('border-color','red').siblings().css('border-color','');		
					$('#index_error').html("Passwords did not match!");
				}
				else if (!$('form#signin_page_signup input#check_terms').is(":checked")){
					e.preventDefault();
					$('form#signin_page_signup input#check_terms').css('border-color','red').siblings().css('border-color','');		
					$('#index_error').html("Please enter Agree to Terms and Conditions!");
				}
			}
	);
}


