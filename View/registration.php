<div class="container">
	<form name = "reg-form" id="reg_form" action="" method="post">
		<div class="container">
			<input type="text" name="login" class="form-control" placeholder="Login" required autofocus>
			<div id="login_result"></div>
			<div id="login_repeat"></div>
		</div>
		<div class="container">
			<input type="password" name="password" class="form-control" placeholder="Password" required>
			<div id="password_result"></div>
		</div>
		<div class="container">
			<input type="password" name="confirm_password" class="form-control" placeholder="Confirm password" required>
			<div id="coincidence_result"></div>
		</div>
		<div class="container">
			<input type="email" name="email" class="form-control" placeholder="Email" required>
			<div id="email_result"></div>
		</div>
		<div class="container">
			<input type="text" name="name" class="form-control" placeholder="Name" required>
			<div id="name_result"></div>
		</div>
		<button type="button" id = "reg_btn" name = "reg_btn" >Register</button>
	</form>
</div> <!-- /container -->
<br/>
<div id="fields_result"></div>




<div id="name_result"></div>
<div id="email_repeat"></div>
</body>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js" type="text/javascript"></script>
<script src="View/auth.js"></script>
