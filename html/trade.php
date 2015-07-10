<?php 
session_start();	
require('error_logs/check_for_errors.php');
?>
<?php $title = "CS76Finance | Trade";?>
<?php $description="A Complete Mock stock trading company, where someone can test how well they play the stock market, without any risk!";?>
<?php $tab="trade"?>


<?php include('includes/top.php')?>
<?php 
if(!isset($_SESSION['logged'])){
		header("Location: index.php");
	}else {
?>
<div id="main_container">
			<div id="menu">
				<?php include('includes/menu.php')?>
				<div id="under_menu_bar"></div>
			</div>	
				<?php include('includes/menu_under_menu_home.php')?>
			
			<div id="trade_container">
			<div id="index_error"></div>
				<div id=trade_main_col_left>
					<h2>Trading</h2>
					<form style='text-aling:left;' id='trade_form' action='confirmation.php' method='POST'>
						<input type="hidden" name='validate' value='buy_or_sell_stock'>
						<label  for='buy_or_sell'>Buy or Sell</label>
						<select id='buy_or_sell' name='buy_or_sell'>
							<option>Buy</option>
							<option>Sell</option>
						</select>
						<br/>
						<br/>
						<fieldset>
						<b>Type in the Symbol:</b> <br/>
						<label  for='trade_symbol'> Symbol</label>
						<input  name='trade_symbol' id='trade_symbol' size='4'><br/>
						<b>Or pick from the selection: </b><br/>
						<label  for='select_stock'>Select</label>
						<select id='select_stock'>
							<?php 
								fetchStocksOptionsList();
							?>
						</select>
						</fieldset>
						
						<div id='trade_menu_to_come'></div>
							
					</form>
				</div><!-- END trade_main_col_left -->
				<div id='trade_main_col_right'>
					<h2>Portfolio</h2>
				</div><!-- END trade_main_col_right-->
				
			</div><!-- END trade_container -->
		</div><!-- END main_container -->
		<script type='text/javascript'>
			// listen for select#select to be touched
			$(document).ready(listen_for_select_choice);
			// listen for symbol input to be touched input#trade_symbol
			$(document).ready(listen_for_symbol_input);
			// listen for what we have chosen for select#buy_or_sell
			$(document).ready(listen_for_buy_sell_select);
			/* Gets the user's available funds */
			$(document).ready(get_clients_cash_reserve);
			
		</script>
		
		</body>
		</html>
<?php }?>


