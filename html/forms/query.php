<?php session_start();require('../error_logs/check_for_errors.php');?>
<?php 
# (c) 2014 Modeled after Viral Technologies Darryl Seamans
# 
# Larry Adams 
# 5 August 2014
# Important variables and functions
# that must be included across
# different modules
#	
	include('../model/globals.php'); /* For Globals */
	
	$dns = 'mysql:host='.DB_HOSTNAME.';dbname='.DATABASE;
	$dbh = new PDO($dns, DB_USERNAME, DB_PASSWORD);
	$dbh->beginTransaction();
	
	include('../model/model.php'); /* For functions */
	
	
	/* If we came from 1/2 forms - signup or signin */
	if(isset($_POST['validate'])){
		
		switch($_POST['validate']){
			case "signup_form":
				//echo "we are at the signup_switch";
				include('validate_signup_form.php');
			break;
			case "signin_form":
				include('validate_signin_form.php');
				//echo "we are at the signin_switch";
				break;	
			case "buy_or_sell_stock":
				include('validate_buy_or_sell_stock.php');
				break;
		}
	}
	
	/* If we came from menu_under_menu from quote submit */
	if(isset($_GET['validate'])){
		if($_GET['validate'] == "quote"){
			include('validate_form_quote.php');
		}
	}
	
	/* If we came from home.php */
	if(isset($_GET['validate'])){
		if($_GET['validate'] == "user_stocks"){
				include('validate_user_stocks_in_db.php');
		}
	}
	
	/* If we came from Trading.php's trading_symbol's input ajax call in the trade.js function that listens for the trading_symbol's listener */
	if(isset($_GET['validate'])){
		if($_GET['validate'] == 'trade_symbol_ajaxx'){
			include('query_stock_table.php');
		}
	}
	
	/* If we came from home.js, where we are trying to find out the user's available_funds */
	if(isset($_GET['validate'])){
		if($_GET['validate'] == 'user_profile'){
			include('query_users_profile.php');
		}
	}
?>