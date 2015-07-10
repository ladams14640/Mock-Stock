
<?php 

	echo "<div class='tabs'>";
		echo "<ul class='tab-links'>";
			switch($tab){
				case "index":
					echo "<li class='active'><a href='index.php'>Home</a></li>";
					echo "<li><a  onClick='respond_to_tab_click(\"Trade\");'>Trading</a></li>";
				break;
				case "trade":
					echo "<li ><a href='home.php'>Home</a></li>";
					echo "<li class='active'><a href=''>Trading</a></li>";
				break;
				case "home":
					echo "<li class='active'><a href=''>Home</a></li>";
					echo "<li><a  href='trade.php'>Trading</a></li>";
				break;
				case "quote":
					echo "<li class='active'><a href='home.php'>Home</a></li>";
					echo "<li><a  href='trade.php'>Trading</a></li>";
				break;
			}
		echo "</ul>";
	echo "</div>";


?>


	
	<!-- End of tabs -->
