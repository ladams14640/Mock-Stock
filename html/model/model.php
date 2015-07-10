<?php 
	
	/* This is used to log out, I am not sure if i will put more in here. */
	if(isset($_GET['logout'])){
		$_SESSION = array();
		$_GET['logout'] = null;
	}

	function init_dbh(){
		$dsn = 'mysql:host='.DB_HOSTNAME.';dbname='.DATABASE;
		$dbh = new PDO($dsn,DB_USERNAME,DB_PASSWORD);
		$dbh->beginTransaction();
	}
		
	$dsn = 'mysql:host='.DB_HOSTNAME.';dbname='.DATABASE;
		$dbh = new PDO($dsn,DB_USERNAME,DB_PASSWORD);
		$dbh->beginTransaction();
	
	/* Used by STOCKS.php */
	function fetchStocksListItem(){
		//init_dbh();
		global $dbh;
		foreach($dbh->query('SELECT * FROM '.TABLE_STOCK) as $row){
			print("<li style='display:list-item; text-align:left;'>{$row['stock_symbol']} : {$row['stock_name']}</li>");
		}
		$dbh->commit();
	}
		/* Used by TRADE.php */
	function fetchStocksOptionsList(){
		//init_dbh();
		global $dbh;
		print("<option>Choose a Stock</option>");
		foreach($dbh->query('SELECT * FROM '.TABLE_STOCK) as $row){
			print("<option value='{$row["stock_symbol"]}' >{$row['stock_symbol']} : {$row['stock_name']}</option>");
		}
		$dbh->commit();
	}
	
	function subtractTwoNumbers($availableFunds, $costOfStock){
	
		$avail = false;
		$cost = false;
	
		$availableFunds = explode(".",$availableFunds);
		$availableFundsToCents = $availableFunds[0] * 100;
	
		if(count($availableFunds) > 1){
			$avail = true;
			$availableFundsDec = $availableFunds[1];
		}
	
		$costOfStock = explode(".", $costOfStock);
		$costOfStockToCents = $costOfStock[0] * 100;

		if(count($costOfStock) > 1){
			$cost = true;
			$costOfStockDec = $costOfStock[1];
		}

		$resultOfSubtraction = $availableFundsToCents - $costOfStockToCents;
	
		if($cost && $avail){
			$resultOfSubtraction += ($availableFundsDec - $costOfStockDec);
		}else if ($cost){
			$resultOfSubtraction -= $costOfStockDec;
		}else if ($avail){
			$resultOfSubtraction -= $availableFundsDec;
		}
		return $resultOfSubtraction / 100;
	}
	
	function addTwoNumbers($availableFunds, $costOfStock){
	
		$avail = false;
		$cost = false;
	
		$availableFunds = explode(".",$availableFunds);
		$availableFundsToCents = $availableFunds[0] * 100;
	
			
		if(count($availableFunds) > 1){
			$avail = true;
			$availableFundsDec = $availableFunds[1];
		}
	
			
		$costOfStock = explode(".", $costOfStock);
		$costOfStockToCents = $costOfStock[0] * 100;
	
			
		if(count($costOfStock) > 1){
			$cost = true;
			$costOfStockDec = $costOfStock[1];
		}
			
		$resultOfSubtraction = $availableFundsToCents + $costOfStockToCents;
		
		if($cost && $avail){
			$resultOfSubtraction += ($availableFundsDec + $costOfStockDec);
		}else if ($cost){
			$resultOfSubtraction += $costOfStockDec;
		}else if ($avail){
			$resultOfSubtraction += $availableFundsDec;
		}
		return $resultOfSubtraction / 100;
	}
	
	/*
	 **************************************************************************************** 
	 * Use this line of functions for validate_buy_or_sell_stock.php
	 * This will handle __BUYING__ the stock if possible, while making sure
	 * The client didn't get here nefariously. 
	 **************************************************************************************** 
	 ****************************************************************************************
	 * There are some functions that I use in this mess that I use with other validate screens
	 * Probably worth mentioning, if ever I want to tamper with any of this logic, could break
	 * Other validating screens  
	 */
	
	function validate_if_client_can_purchase_stock($passed_symbol, $passed_quantity){
		// check how much the stock costs, 
		// multiply the stock_cost and the quantity
		// insert the stock, and quantity to the table_profile 
		// subtract the cash.
		
		check_how_much_the_stock_costs($passed_symbol, $passed_quantity);
	}
	
	function check_how_much_the_stock_costs($passed_symbol, $passed_quantity){
		
		$theStock = retrieve_yahoo_stock($passed_symbol);
		
		// We got a valid stock
		if($theStock['symbol'] != "Not Value"){
			// tack on 20 for the trading cost
			$costOfStock = ((float) ($theStock['price']+20) * (int) $passed_quantity);
		
			// check out if we can even purchase this
			$available_funds = (float) json_decode(get_clients_available_funds(),true)['available_funds'];			
			// Dont have enough
			if($costOfStock > $available_funds){
				echo "Sorry you don't have enough to purchase this stock!";
			}else {
				complete_the_trade($passed_quantity, $costOfStock, $theStock, $available_funds);
			}
		}
		// We did not get a valid stock
		else {
			echo "Not a valid stock that came back"; 
		}
		
	}

	function retrieve_yahoo_stock($passed_symbol){
		$handle = fopen("http://download.finance.yahoo.com/d/quotes.csv?s=$passed_symbol". "&f=sl1d1t1c1ohgv&e=.csv", "r" );
		if($handle !=null){
			$data = fgetcsv($handle);
			if($data !== FALSE && $data[2] !== "N/A")
			{
				$symbol = json_encode($data[0]);
				$symbol = str_replace('"',"",$symbol);
				$price = json_encode($data[1]);
				$price = str_replace('"',"", $price);
				$change = json_encode($data[4]);
				$change = str_replace('"',"", $change);
				$update = json_encode($data[3]);
				$update = str_replace('"',"", $update);
					
				$high= json_encode($data[6]);
				$high = str_replace('"',"", $high);
				$low= json_encode($data[7]);
				$low = str_replace('"',"", $low);
				$vol= json_encode($data[8]);
				$vol = str_replace('"',"", $vol);
				$open= json_encode($data[5]);
				$open = str_replace('"',"", $open);
				
				$yahooStock = array(
					"symbol" => $symbol,
					"price" => $price,
					"change" => $change,
					"update" => $update,
					"high"=>$high,
					"low"=>$low,
					"vol"=>$vol,
					"open"=>$open	
				);
				return $yahooStock;
			}else {
				$yahooStock = array(	
					"symbol" => "Not Valid"
				);
				return $yahooStock;
			}
		}else {
				$yahooStock = array(	
					"symbol" => "Not Valid"
				);
				return $yahooStock;
			}
	}			
	function get_clients_available_funds(){
		/* Refresh the amount of money the person has. */
		$realAvailableFunds = query_available_funds();
		$realAvailable = number_format($realAvailableFunds, 2, '.','');
		$passable_json = json_encode(array("available_funds"=>$realAvailable));
		return $passable_json;
	}	
	function query_available_funds(){
		
		global $dbh;
		//$dbh->beginTransaction();
		$sql = 'SELECT `available_funds` FROM `table_user` WHERE `user_id` = '.$_SESSION['user_id'];
		foreach($dbh->query($sql) as $row){
			$result = $row['available_funds'];
		}
		// THIS MIGHT BREAK FOR BUYING
		$dbh->commit();   
		$_SESSION['available_funds'] = $result;                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                               
		if(isset($result)){
			return $result;
		}
		else {
			return -1;	
		}
	}
	
	
	function complete_the_trade($passed_quantity, $costOfStock, $theStock, $available_funds){
		// 1. Need to get stocks name
		// 2. Need to add the stock symbol with it's quantity
		// 3. Need to subtract the $CostOfStock from available_funds
		
		/* 1. */
		$stockName = json_decode(get_stocks_name($theStock),true); 
		/* 2.  and 3. is in the method below */
		insert_stock_quantity_name_for_user($stockName['stock'], $passed_quantity, $theStock,$costOfStock,$available_funds);
	
	}

	function subtract_costOfStock_from_availableFunds($costOfStock, $available_funds){
		$subtractedResult = $available_funds - $costOfStock;
		//echo $subtractedResult;
		
		if($subtractedResult < 0 ){
			header("Location: ../error.php?error=not_enough_available_funds"); 
		}else {
			subtract_from_users_portfolio($subtractedResult, $costOfStock);
		}
		
	}
	
	function subtract_from_users_portfolio($subtractedResult, $costOfStock){
		global $dbh;
		//$subtractedResult
		// go for table_user
		// available_funds
		// user_id
		$dbh->beginTransaction();
		$user_id = $_SESSION['user_id'];
		$sql = "UPDATE ".TABLE_USER." SET `available_funds` = ".$subtractedResult." WHERE `user_id` = ". $user_id;
		$dbh->query($sql);
		$dbh->commit();
		header("Location: ../home.php?stock_purchased=true");		
	}
	
	function insert_stock_quantity_name_for_user($stockName, $passed_quantity, $theStock,$costOfStock,$available_funds){
		global $dbh;
		$dbh->beginTransaction();
		$symbol = $theStock['symbol'];
		$user_id = $_SESSION['user_id'];
				
		// 1. if we dont have the stock already, do the insert above
		// 2. if we do have the stock already, lets update with more 
		
		// .2 - Check if we have the Stock
		$sql='SELECT `stock_symbol` FROM `table_portfolio` WHERE `stock_symbol` = "'.$symbol.'" AND `user_id` = '.$user_id;
		$result = '';
		foreach($dbh->query($sql) as $row){
			$result = $row;
		}
		if($result != ''){
			/*
			 * THIS IS NOT PROPERLY CHECKING TO SEE IF WE INDEED HAVE THE STOCK
			 * 
			 * */
			
			
			
			// we definitely do have this stock
			// .2
			$amount_of_this_stock = (float) get_clients_stock_amount($symbol);
			//echo "<br>" + $amount_of_this_stock + "<br>";
			update_stock_quantity($amount_of_this_stock, $passed_quantity, $symbol);
			//echo "We definitely do have the stock";
		}
		else {
			echo ".1 We definitely don't have the stock";
			insert_stock_quantity($symbol, $stockName, $user_id,$passed_quantity);
		}
		/* 3. from complete_the_trade() - fullfilling last obligation of the method */
		subtract_costOfStock_from_availableFunds($costOfStock, $available_funds);
	}
	
	function get_clients_stock_amount($symbol){
		global $dbh;
		//$dbh->beginTransaction();
		$sql = 'SELECT `amount_of_stock` FROM `table_portfolio` WHERE `user_id` = '.$_SESSION['user_id'].' AND `stock_symbol` = "'.$symbol.'"';
		foreach($dbh->query($sql)as $row){
			$result = $row['amount_of_stock'];
		}		
		$dbh->commit();
		if(isset($result)){
			return $result;		
		}else {
			return -1;
		}
	}
	
	function insert_stock_quantity($symbol, $stockName, $user_id, $passed_quantity){
		global $dbh;
		//$dbh->beginTransaction();
		// 1.
		$sql = (string) "INSERT into `table_portfolio` (`stock_symbol`, `stock_name`, `user_id`, `amount_of_stock`) VALUES ('{$symbol}','{$stockName}', ".$user_id.", ".$passed_quantity.") ";
		$dbh->query($sql);
		$dbh->commit();
	}
	
	function update_stock_quantity($amount_of_this_stock, $passed_quantity,$symbol){
		global $dbh;
		$dbh->beginTransaction();
		
		$sum_of_stocks =  addTwoNumbers($amount_of_this_stock,$passed_quantity );
		$sql = 'UPDATE `table_portfolio` SET `amount_of_stock` = '.$sum_of_stocks.' WHERE `user_id` = '.$_SESSION['user_id'].' AND `stock_symbol` = "'.$symbol.'"';
		foreach($dbh->query($sql) as $row){
			$result = $row; // really no need for this, but I dont want to do more testing. Going to leave this foreach loop.
		}
		$dbh->commit();		
		
	}
	
	function get_stocks_name($theStock){
		// lets get the stock name
		$sql = "SELECT `stock_name` FROM `table_stocks` WHERE `stock_symbol` = '".$theStock['symbol']."'";
		global $dbh;
		$dbh->beginTransaction(); // WILL THIS CAUSE A PROBLEM WITH IF 2 BEGINTRANSACTIONS HAPPEN?
		// ^^^ it did
		// NOW SAYING IT NEEDS IT!!
		$stock_name = null;
		foreach($dbh->query($sql) as $row){
			$stock_name = $row['stock_name'];
		}
		$dbh->commit();
		
		
		//$dbh->commit();
		if($stock_name == null){
			$passable_json = json_encode(
					array
					(
							"stock"=>"no stock yet"
					)
			);
		
		}else {
			$passable_json = json_encode(
					array
					(
							"stock"=>$stock_name
					)
			);
		}
		
		return $passable_json;

	}
	
	/***************** END OF THE FUNCTIONS THAT HANDLE __BUYING__ *************************/
	
	
	/*	
	****************************************************************************************
	* Use this line of functions for validate_buy_or_sell_stock.php
	* This will handle __SELLING__ the stock if possible, while making sure
	* The client didn't get here nefariously
	****************************************************************************************
	****************************************************************************************
	****************************************************************************************
	****************************************************************************************
	*/
	function validate_if_client_can_sell_stock($passed_symbol,$passed_quantity){
		// All Validation Needs to be done: 
		// 1. Make sure user owns this stock. - first time we use PDO object for selling, transaction began in validate_buy_or_sell_stock.php
		// 2. Make sure the user owns enough of the stock.
		// 3. make sure the client has enough funds, to cover the sell of the stock - with the trade costs
		// 4. Remove the stock from user's inventory
		// 5. $multSum = Multiply current price of stock with quantity
		// 6. Add the current user's amount_of_funds with the $multSum
		// 7. Update the user's table
		
		// 1.
		if($amountOfStock = user_owns_stock_and_how_much($passed_symbol, $passed_quantity)){
			// 2.
			
			echo "Amount of stock chosen: ".$amountOfStock;
			// 3.
			// grab stock amount in user's portfolio
			$stockAmountInPortfolio = get_clients_stock_amount($passed_symbol);
			if($stockAmountInPortfolio != -1){
				// grab user's available funds
				$availableFunds = query_available_funds_for_sell();
				if($availableFunds != -1){
					// grab stock Price Multiplied by the quantity
					$stockPrice = query_stock_costs($passed_symbol, $passed_quantity);
					if($stockPrice != -1){
						// grab available funds after we charge $20 trading fee and add the $stockPrice
						$availFundsLeftAfterStockTrade = client_cover_cost_and_left_over($stockPrice, $availableFunds);
						if($availFundsLeftAfterStockTrade >= 0){
							complete_sell_trade($stockAmountInPortfolio, $availableFunds, $stockPrice, $passed_symbol,$passed_quantity,$availFundsLeftAfterStockTrade);
						}else {
							echo "Don't have enough cash to cover the costs";
						}
					}else {
						echo "problem with getting the stock's price";
					}
				}else {
					echo "no funds";
				}
			}else {
				echo "dont own stock";
			}
		}else {
			header("Location: ../error.php?error=dont_have_that_stock");
		}
		
	}

	// 1. - dont forget first time we use PDO object for selling.
	function user_owns_stock_and_how_much($passed_symbol,$passed_quantity){
		global $dbh; 
		$sql = 'SELECT * FROM `table_portfolio` WHERE `user_id` = '. $_SESSION['user_id'].' AND `stock_symbol` = "'.$passed_symbol.'" AND `amount_of_stock` >= '.$passed_quantity;	
		$weHave = null;
		foreach($dbh->query($sql) as $row){
			$weHave = $row['stock_symbol'];	
			$amountOfStock = $row['amount_of_stock'];	
		}
		
		
		if($weHave != null){
			//return true; 
			// 2. Check if user owns enough of this stock
			return $amountOfStock;
		}
		else {
			return false;
		}
	}
	/*
	 * This is good, maybe we should use this for the buy section
	 * */
	
	function query_stock_costs($passed_symbol, $passed_quantity){
	
		$theStock = retrieve_yahoo_stock($passed_symbol);
	
		// We got a valid stock
		if($theStock['symbol'] != "Not Value"){
			// tack on 20 for the trading cost, not yet
			$costOfStock = ((float) $theStock['price'] * (int) $passed_quantity);
			return $costOfStock;
		}
		else {
			return -1;
		}
	
	}
	function client_cover_cost_and_left_over($stockPrice, $available_funds){
		// need the fee to be added
		$stockSaleAddedFee = $stockPrice - 20;
		$availableFundsAfterTrade = $stockSaleAddedFee + $available_funds;
		if($availableFundsAfterTrade > -1){
			echo "This is what the amount available that came back = " . $availableFundsAfterTrade;
			return $availableFundsAfterTrade;	
		}else{
			return -1;
		} 
			
	}
	function complete_sell_trade($stockAmountInPortfolio, $availableFunds, $stockPrice, $passed_symbol,$passed_quantity, $availFundsLeftAfterStockTrade){
		// Subtract user's picked quantity of stock and their stock amount
		// remove stock price from the user's table
		subtract_stock_from_users_profile($stockAmountInPortfolio, $passed_symbol,$passed_quantity);
		update_users_amount_of_funds($availFundsLeftAfterStockTrade);
		header("Location: ../home.php?stock_purchased=true");		
	}
	function subtract_stock_from_users_profile($stockAmountInPortfolio, $passed_symbol,$passed_quantity){
		global $dbh;
		//$dbh->beginTransaction();
		// Update because client has more in portfolio than he chose to sell
		if($passed_quantity < $stockAmountInPortfolio){
			$newStockAmount = $stockAmountInPortfolio - $passed_quantity;
			echo $newStockAmount;
			
				$sql = "UPDATE `table_portfolio` SET `amount_of_stock` = {$newStockAmount} WHERE `user_id` = {$_SESSION['user_id']} AND `stock_symbol` = '{$passed_symbol}'";
				$dbh->query($sql);
				//echo "you have more than the amount";
			
		}
		// Remove all of the stocks amount because the user only has that amount
		else {
			$sql = "DELETE FROM `table_portfolio` WHERE `user_id` = {$_SESSION['user_id']} AND `stock_symbol` = '{$passed_symbol}'";
			$dbh->query($sql);
			//echo "You have exactly that amount.";				
		}
		//$dbh->commit();	
		//$sql = "";
	}
	
	function update_users_amount_of_funds($availFundsLeftAfterStockTrade){
		global $dbh;
		//$dbh->beginTransaction();
		// we have the available funds left after the stock trade, easy query here to do.
		$sql = "UPDATE `table_user` SET `available_funds` = {$availFundsLeftAfterStockTrade} WHERE `user_id` = {$_SESSION['user_id']}";
		$dbh->query($sql);
		$getNewAvailableFundsToCheck = query_available_funds();
		//$dbh->commit();
		echo "<br/>" . $getNewAvailableFundsToCheck;
		
	}
	
	
	function query_available_funds_for_sell(){
	
		global $dbh;
		$dbh->beginTransaction();
		$sql = 'SELECT `available_funds` FROM `table_user` WHERE `user_id` = '.$_SESSION['user_id'];
		foreach($dbh->query($sql) as $row){
			$result = $row['available_funds'];
		}
		//$dbh->commit();
		$_SESSION['available_funds'] = $result;
		if(isset($result)){
			return $result;
		}
		else {
			return -1;
		}
	}
	
	/***************** END OF THE FUNCTIONS THAT HANDLE __SELLING__ *************************/
	
	
	
	
	
	
	
	
	
?>
