<div id="menu_under_menu">

	<?php 
	
		echo "<div id='menu_under_menu_quote' >";
				echo "<form id='page_option_quote' action='quote.php' method='get'>";
				echo "<input id='quote' type='text' name='quote' placeholder='Quote'/>";
				echo "<input id='quote_submit' name='quote_submit' type='submit' value='Get Quote'/>";
			echo "</form>";
		echo "</div>";
	
		echo "<ul id='menu_under_menu_list'>";
			echo "<li id='mumli'>";
				echo "<form id='my_profile_btn' method='post' action='home.php'>";
					echo "<input type='submit' name='Profile' value='My Profile'>";
				echo "</form>";
			echo "</li>";
			$username = ucfirst($_SESSION['username']);
			
			echo "<li id='mumli'>";
				echo"<form id='logout' method='GET' action='index.php'>";
					echo"<input type='hidden' name='logout' value='logout'>";
					echo"<input type='submit' value='Logout {$username}'>";
				echo"</form>";
			echo "</li>";
		echo "</ul>";
	?>
</div>
