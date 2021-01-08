
$( document ).ready(function() {
    $("#log_btn").click(
		function(){
			sendLogForm('log_form', 'AJAX/signin.php');
			return false; 
		}
	);
});

 $( document ).ready(function() {
    $("#reg_btn").click(
		function(){
			sendRegForm('reg_form', 'AJAX/reg.php');
			return false; 
		}
	);
});

function sendLogForm(ajax_form, url) {
    $.ajax({
        url:     url,

        type:     "POST", 
        dataType: "html", 
        data: $("#"+ajax_form).serialize(), 
        success: function(response) { 
        	console.log(response);
        	result = $.parseJSON(response);
			if(result['ok'] == true){
				window.location = '/index.php';
			}
			if(result['password_error'] == true){
        		$('#log_result_form').html('Wrong password');
        	}
        	if(result['login_error'] == true){
        		$('#log_result_form').html('Login is not exist');
        	}
        	
    	},
    	error: function(response) {
            $('#result_form').html('Error.');
    	}
 	});
}

function sendRegForm(ajax_form, url) {
    $.ajax({
        url:     url,
        type:     "POST", 
        dataType: "html", 
        data: $("#"+ajax_form).serialize(), 
        success: function(response) { 
        console.log(response);
        	result = $.parseJSON(response);
  
			if(result['ok'] == true){
				window.location = '/index.php';
			}
			message(result['fields'], '#fields_result', 'All fields are required. ');
			message(result['no_coincidence'], '#coincidence_result', 'Passwords are not equal.');
			message(result['login_error'], '#login_result', 'Login is not correct. Min 6 symbols, only letters and numbers.');
        	message(result['password_error'], '#password_result', 'Password is not correct. Min 6 symbols, password must have at least one large letter, small letter and number');        	
        	message(result['email_error'], '#email_result', 'Email is not correct.');
        	message(result['name_error'], '#name_result', 'Name is not correct. Min 2 symbols, only letters and numbers');        	
        	message(result['login_repeat'], '#login_repeat', 'It is another user with this login.');
        	message(result['email_repeat'], '#email_repeat', 'It is another user with this email. ');
        	
    	},
    	error: function(response) {
            $('#reg_result_form').html('error');
    	}
 	});
}

function message(result, form, text){
	if(result == true){
        $(form).html(text);
    } else {
		$(form).html('');
	}
}