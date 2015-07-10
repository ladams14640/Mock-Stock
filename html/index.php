<?php session_start();require('error_logs/check_for_errors.php');?>
<?php $title = "CS76Finance | Premier Mock Stock Trading Website";?>
<?php $description="A Complete Mock stock trading company, where someone can test how well they play the stock market, without any risk!";?>
<?php $tab="index"?>
<?php include('includes/top.php');


?>


		<div id="main_container">
			<div id="menu">
				<?php include('includes/menu.php')?>
				<div id="under_menu_bar"></div>
			<div id="menu_under_menu">
				<?php include('includes/menu_under_menu.php')?>
			</div>
			</div>
			
			<div id="index_container">
			<div id="index_error"></div>
				<div id="main_col_left">
					<h2>Sign In</h2>
					<form id='signin_page_signin' method='post' action='forms/query.php'>
							<input type='hidden' name="validate" value="signin_form">
							<input id='username' autofocus="autofocus" type='text' name='username' placeholder="Username or Email"><br><br>
							<input id='password' type='password' name='first_password' placeholder="Password"><br><br>		
							<input type="hidden" name="page" value="check_database_signin"> 
							<input  id='form_sign_in_submit' type='submit' value='Log In'>
					</form>
				</div>
				<div id="main_col_right">	
					<h2>Sign Up</h2>
					<form id="signin_page_signup" method="post" action="forms/query.php" >
						<input type='hidden' name="validate" value="signup_form">
						<input id="username" onfocus="true"  type="text" id="username" name="username" placeholder="User Name"><br/><br/>
						<input id="email" type="email" id="email" name="email" placeholder="Email Address"><br/><br/>
						<input id="first_password" type="password" name="first_password" placeholder="Password"><br/><br/>
						<input id="verified_password" type="password" name="verified_password" placeholder="Verify Password"><br/><br/>
						<a href="">Terms and Conditions</a> <input id="check_terms" type="checkbox" name="check_terms"><br><br>
						<input type="hidden" name="page" value="check_database">
						<input  id="submit_signup" type="submit" value="Submit">
					</form>
				</div><!-- END main_col_right -->
			</div><!-- END index_container -->
		</div><!-- END main_container -->
<?php 	
if(isset($_GET['error'])){
	$error = $_GET['error'];
	echo '<script type="text/javascript"> $(document).ready(function(){problemSignin("'.$error .'")})</script>';	
 } 
 ?>
 	<script type='text/javascript'>
 		$(document).ready(signin_form);
		$(document).ready(signup_form);
 	</script>
	</body>
</html>
