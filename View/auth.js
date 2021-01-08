
$( document ).ready(function() {
    $("#log_btn").click(
		function(){
			sendLogForm('log_result_form', 'log_form', 'AJAX/signin.php');
			return false; 
		}
	);
});

 $( document ).ready(function() {
    $("#reg_btn").click(
		function(){
			sendRegForm('reg_result_form', 'reg_form', 'AJAX/reg.php');
			return false; 
		}
	);
});

function sendLogForm(result_form, ajax_form, url) {
    $.ajax({
        url:     url,
        headers: {
            'Cookie': document.cookie
         }
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

function sendRegForm(result_form, ajax_form, url) {
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

        	if(result['fields'] == true){
        		$('#fields_result').html('All fields are required. ');
        	}

        	if(result['no_coincidence'] == true){
        		$('#coincidence_result').html('Password is not equal to confirm. ');
        	}
        	
        	if(result['login_error'] == true){
        		$('#login_result').html('Login is not correct. Min 6 symbols, only letters and numbers');
        	}
        	
        	if(result['password_error'] == true){
        		$('#password_result').html('Password is not correct. Min 6 symbols, password must have at least one large letter, small letter and number');
        	}
        	
        	if(result['email_error'] == true){
        		$('#email_result').html('Email is not correct. ');
        	}
        	
        	if(result['name_error'] == true){
        		$('#name_result').html('Name is not correct. Min 2 symbols, only letters and numbers');
        	}
        	
        	if(result['login_repeat'] == true){
        		$('#login_repeat').html('It is another user with this login. ');
        	}
        	
        	if(result['email_repeat'] == true){
        		$('#email_repeat').html('It is another user with this email. ');
        	}
        	
    	},
    	error: function(response) {
            $('#reg_result_form').html('error');
    	}
 	});
}