<?php session_start();require('error_logs/check_for_errors.php');?>
<?php $title = "CS76Finance | Confirmation";?>
<?php $description="A Complete Mock stock trading company, where someone can test how well they play the stock market, without any risk!";?>
<?php $tab="home"?>
<?php 


/* Make sure all is set, that we are suppose to be here. */
if(isset($_SESSION['user_id'])){
	$passed_symbol = '';
	$passed_quantity = '';
	$passed_buy_or_sell = '';

	/* Check to make sure the user is suppose to be here */
	if(isset($_POST['trade_symbol'])){
		$passed_symbol = $_POST['trade_symbol'];
	} // Trade symbol was not set
	else {
		echo "Trade Symbol not set! How did we get here?.";
	}  // END OF TRADE CHECK

	if(isset($_POST['trade_quantity'])){
		$passed_quantity = $_POST['trade_quantity'];
	} // Trade Quantity was not set
	else {
		echo "Trade Quantity not set! How did we get here?.";
	} // END OF QUANTITY CHECK

	if(isset($_POST['buy_or_sell'])){
		$passed_buy_or_sell = $_POST['buy_or_sell'];
	} // Trade Quantity was not set
	else {
		echo "Trade Buy or Sell was not set! How did we get here?.";
	} // END OF BUY OR SELL CHECK


} // END OF $_SESSION CHECK
// not even logged on
else {
		header("Location: ../error.php?error=not_logged_in");
	}

?>


<?php include('includes/top.php')?>
<div id="main_container">
	<div id='menu'>
		<?php include('includes/menu.php')?>
		<div id="under_menu_bar"></div>
		<div id="menu_under_menu">
			<?php include('includes/menu_under_menu_home.php')?>
			</div>
			</div>
			
			<div id="home_container">
			<div id="index_error"></div>
				<div id="main_col_center_confirmation">	
					<h2>Confirm Your Order.</h2>
					<form id='client_confirmed_transaction' method='post' action='forms/query.php'>
							<!-- Where to go -->
							<input type="hidden" name='validate' value='buy_or_sell_stock'>
							<!-- What to pass on -->
							<input type="hidden" name='trade_symbol' value='<?php echo $passed_symbol?>'>
							<input type="hidden" name='buy_or_sell' value='<?php echo $passed_buy_or_sell?>'>
							<input type="hidden" name='trade_quantity' value='<?php echo $passed_quantity?>'>
							
							
							<label for="symbol">Stock: </label><span style='padding-left: 4px' id='symbol' name='symbol'></span><br>
							
							<label for="quantity">Quantity: </label><span style='padding-left: 4px' id="quantity" name="quantity"></span><br>
							
							<label for='price'>Price of Stock: </label><span id='price' name='price'></span><br/>
							
							<label for='total'></label><span id='total' name='total'></span><br/>
							
							<label for='trading_costs'>Trading Cost: </label><span id='trading_costs' name='trading_costs'></span><br/>
							
							
							<input style='display: inline;' value='Submit' type='submit'/><input style='display: inline;' type='button' value='Cancel'/>					
					</form>
				</div>
				<div id="main_col_right">	
					
				</div><!-- END main_col_right -->
				<script type="text/javascript">
					var values = {
						symbol : "<?php echo $passed_symbol?>",
						quantity : "<?php echo $passed_quantity?>",
						trade : "<?php echo $passed_buy_or_sell?>"
					};
					
					var tradePage = new TradePage(values);
					tradePage.init();
				</script>
			</div><!-- END home_container -->
		</div><!-- END main_container -->
	</body>
</html>
