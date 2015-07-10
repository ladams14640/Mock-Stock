<?php
/*
 * (c) Laurence Adams III
*
* This page will handle when the client has decided to make a trade on the stock market.
* Whether Selling a stock or Buying a stock.
* We need to validate their choices, make sure the user has the ability to even make those trades
* then handle according.
*
* No way to get to this screen without being logged on, and in the trade screen
*/

/*
 * Passing variable keys with description:
* buy_or_sell - Will tell the server if the user is buying or selling
* trade_symbol - Will tell us the symbol to double check
* trade_quantity - Will tell us how much the person wants to trade
*/

echo($_POST['trade_symbol']);
echo "<br/>";
echo($_POST['trade_quantity']);
echo "<br/>";
echo($_POST['buy_or_sell']);

$passed_symbol = '';
$passed_quantity = '';
$passed_buy_or_sell = '';

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

	/*
		* All checks were made, lets proceed to handle this request,
	* depending on whether a buy or sell request was initiated.
	*/
	if($passed_buy_or_sell == "Buy"){
		validate_if_client_can_purchase_stock($passed_symbol,$passed_quantity);
	}
	else if ($passed_buy_or_sell == "Sell"){
		validate_if_client_can_sell_stock($passed_symbol,$passed_quantity);
	}
	// how did they even get here if buy or sell was never checked
	else {
		header("Location: ../error.php?error=buy_or_sell_not_checked");
	}

} // END OF $_SESSION CHECK
// not even logged on
else {
		header("Location: ../error.php?error=not_logged_in");
	}
?>