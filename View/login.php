<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <title>AuthTask</title>

</head>
<body>
    <div class="container">
        <form name="log-form" id="log_form" action="" method="post">
        	<input type="text" name="login" class="form-control" placeholder="Login" required autofocus>
       		<input type="password" name="password" class="form-control" placeholder="Password" required>
       		<button type="button" id = "log_btn" name = "log_btn" >Login</button>
        </form>
    </div> <!-- /container -->
    <br/>
    <div class="container">
        <form name = "reg-form" id="reg_form" action="" method="post">
        	<input type="text" name="login" class="form-control" placeholder="Login" required autofocus>
        	<input type="password" name="password" class="form-control" placeholder="Password" required>
        	<input type="password" name="confirm_password" class="form-control" placeholder="Confirm password" required>
        	<input type="email" name="email" class="form-control" placeholder="Email" required>
        	<input type="text" name="name" class="form-control" placeholder="Name" required>
        	<button type="button" id = "reg_btn" name = "reg_btn" >Register</button>
        </form>
    </div> <!-- /container -->
    <br/>
    <div id="result_form"></div> 
   <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js" type="text/javascript"></script>
   <script src="View/auth.js"></script>
   
   