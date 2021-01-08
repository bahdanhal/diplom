
$( document ).ready(function() {
    $("#log_btn").click(
		function(){
			sendAjaxForm('result_form', 'log_form', 'AJAX/signin.php');
			return false; 
		}
	);
});

 $( document ).ready(function() {
    $("#reg_btn").click(
		function(){
			sendAjaxForm('result_form', 'reg_form', 'AJAX/reg.php');
			return false; 
		}
	);
});

function sendAjaxForm(result_form, ajax_form, url) {
    $.ajax({
        url:     url,
        type:     "POST", 
        dataType: "html", 
        data: $("#"+ajax_form).serialize(), 
        success: function(response) { 
        
        	result = $.parseJSON(response);
  
			if(result['ok'] == true){
				window.location = '/index.php';
			}
        	if(result['password_error'] == true){
        		$('#result_form').html('Password is not correct');
        	}
        	if(result['login_error'] == true){
        		$('#result_form').html('Login is not correct');
        	}
        	
    	},
    	error: function(response) {
            $('#result_form').html('Ошибка. Данные не отправлены.');
    	}
 	});
}