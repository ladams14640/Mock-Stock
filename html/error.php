<?php 

switch($_GET['error']){
	case 'not_enough_available_funds':
		echo "You don't have enough for that!";
		break;
	case 'not_logged_in':
		echo "You are not logged in!";
		break;
	case 'buy_or_sell_not_checked':
		echo "You never selected if you wanted to buy or sell to get to the trade screen!";
		break;
	case 'dont_have_that_stock':
		echo "You don't have that stock to sell!";
		break;
	case 'dont_have_enough_stock':
		echo "You don't have enough of that stock to sell!";
		break;
}


?>