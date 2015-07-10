<?php 

	/*
	 * This query out of all queries, is a nice one, since when signing in or signing up we set the 
	 * amount of available funds in the session, all we got to do is grab it.
	 */
	// SHOULD DO A CHECK TO MAKE SURE SESSION IS SET BEFORE WE ACTUALLY JUST USE THIS
	
	
	// Calling a model.php function
	echo get_clients_available_funds();
	

?>