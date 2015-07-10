<?php session_start();require('error_logs/check_for_errors.php');?>
<?php $title = "CS76Finance | Premier Mock Stock Trading Website";?>
<?php $description="A Complete Mock stock trading company, where someone can test how well they play the stock market, without any risk!";?>
<?php $tab="quote"?>


<?php include('includes/top.php')?>

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
				</div>
				<div id="main_col_right">
				</div><!-- END main_col_right -->
			</div><!-- END index_container -->
		</div><!-- END main_container -->
<?php 	
if(isset($_GET['error'])){
	$error = $_GET['error'];
		echo '<script type="text/javascript"> $(document).ready(function(){problemSignin("'.$error .'")})</script>';	
 } 
 ?>
 <?php 
 	if(isset($_GET['quote'])){
		echo '<script type="text/javascript"> $(document).ready(function(){testQuote("'.$_GET['quote'] .'")})</script>';
	}
 ?>
 	
	</body>
</html>
