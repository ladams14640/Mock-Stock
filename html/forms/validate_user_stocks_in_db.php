<?php 




if(isset($_SESSION['user_id'])){

	// Grab all of the user's stock symbol, stock name, and stock amounts
	$sql = "SELECT * FROM ".TABLE_PORTFOLIO." WHERE `user_id` = ".$_SESSION['user_id'];
	$stocks = array();
	$check = false;
	foreach($dbh->query($sql) as $row){
		$stock_name= $row['stock_name'];
		$stock_symbol = $row['stock_symbol'];
		$stock_amount = $row['amount_of_stock'];
		$tmp = $stock_symbol.":".$stock_amount.":".$stock_name;
		array_push($stocks,$tmp);
		$check = true;
	}


	$passable_json = json_encode
	(
			array
			(
					"stocks" => $stocks
			)
	);



	$dbh->commit();
	if($check){
		echo $passable_json;
	}else {
		$passable_json = json_encode
		(
				array
				(
						"stocks" => "no stocks"
				)
		);
		echo $passable_json;
	}
}else {
	$passable_json = json_encode
	(
			array
			(
					"stocks" => "not signed in"
			)
	);
	echo $passable_json;
}

	?>