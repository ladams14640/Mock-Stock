<?php

	/*
	 * We come from trade.php or trade.js from a ajax call
	 * */
	if(isset($_GET['theInput'])){
		$input = htmlspecialchars($_GET['theInput']);
		$sql = "SELECT `stock_symbol` FROM `table_stocks` WHERE `stock_symbol` = '".$input."'"; 
		$symbol = null;
		foreach($dbh->query($sql) as $row){
			$symbol = $row['stock_symbol'];
		}
		if($symbol == null){
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
				"stock"=>$symbol
			)
	);
		}
	
	}// END OF isset
	// tell ajax calling that we never got a input.
	else {
		$passable_json = json_encode(
			array 
			(
				"stock"=>"never sent a stock"
			)
	);
		
	}
	
	echo $passable_json;

?>