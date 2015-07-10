<?php 
# (c) Laurence F. Adams III
# CS75 Finance 
#
# We get here from both home.php (via home.js),
# and the quote button (via menu.js).
#


// download the csv in this form http://download.finance.yahoo.com/d/[FILENAME]?s=[TICKER SYMBOL(S)]&f=[TAGS]&e=.csv
if(isset($_GET['quote'])){
	$quoteThatCameThrough = htmlspecialchars($_GET['quote']);
	
if(preg_match('/'.'^[A-Za-z0-9 ]{3,20}$'.'/',$quoteThatCameThrough))
{
			$theStock = retrieve_yahoo_stock($quoteThatCameThrough);
			if($theStock['symbol'] !== "Not Valid"){
			/* Grab the stock's name from our database */	
			$sql = "SELECT COUNT(*) FROM `table_stocks` WHERE `stock_symbol` ='".$quoteThatCameThrough."'";
			if ($res = $dbh->query($sql)) {	
				/* Check the number of rows that match the SELECT statement, if 0 then we know we never added
				 * this stock symbol and name to our database, and we probably should, especially if its valid */
				if ($res->fetchColumn() > 0) {
					$sql = "SELECT `stock_name` FROM `table_stocks` WHERE `stock_symbol` = '".$quoteThatCameThrough."'";
	  				foreach($dbh->query($sql) as $row){
						$stock_name = $row[0];
						$dbh->commit();
						
						$passable_json = json_encode
						(
								array
								(
										"symbol"=>$theStock['symbol'],
										"price"=>$theStock['price'],
										"change"=>$theStock['change'],
										"update"=>$theStock['update'],
										"high"=>$theStock['high'],
										"low"=>$theStock['low'],
										"vol"=>$theStock['vol'],
										"open"=>$theStock['open'],
										"stock_name"=>$stock_name
											
								)
						);
						
						echo $passable_json;
					}			
				}/* We did not have this stock's name in our `table_stocks`, but its still a valid stock */
				else {
					$passable_json = json_encode
					(
							array
							(
									"symbol"=>$symbol,
									"price"=>$price,
									"change"=>$change,
									"update"=>$update,
									"high"=>$high,
									"low"=>$low,
									"vol"=>$vol,
									"open"=>$open,
									"stock_name"=>"Not in DB"
			
							)
					);
					echo $passable_json;
				}
			}			// SAVE THIIIIIIIIIIIIIIIIIIIIIIIIIIIISSSSSSSSSSSSSSSSSSSSSSSSSSSSSS
		
		}	else {
		$passable_json = json_encode
		(
				array
				(
						"symbol"=>"Not Valid"
		
				)
		);
		echo $passable_json;
	}
		
		
	}/* if the inputed quote is outside the regular expression that would fit for a quote's symbol */
	else {
		$passable_json = json_encode
		(
				array
				(
						"symbol"=>"Not Valid"
		
				)
		);
		echo $passable_json;
	}
} /* If the quote was not actually inputed and we some how got to this point */
else {
	$passable_json = json_encode
		(
				array
				(
						"symbol"=>"Not Valid"
		
				)
		);
		echo $passable_json;
}

?>