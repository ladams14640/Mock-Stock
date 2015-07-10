<?php 
session_start();
require('error_logs/check_for_errors.php');
?>
<?php $title = "CS76Finance | Home "; ?>
<?php $description="A Complete Mock stock trading company, where someone can test how well they play the stock market, without any risk!";?>
<?php $tab="home"?>

<?php 
if(!isset($_SESSION['logged'])){
		header("Location: index.php");
	}else {
?>


<?php include('includes/top.php')?>
<div id="main_container">
	<div id='menu'>
		<?php include('includes/menu.php')?>

		<div id="under_menu_bar"></div>
		<div id="menu_under_menu">
			<?php include('includes/menu_under_menu_home.php')?>
		</div>

		<!-- END menu_under_menu -->
	</div>
	<div id='home_container'>
		<div id="index_error"></div>
		<div id="main_col_left_home">

			<table id='clients_home_portfolio'>
				<tbody id='first_tbody'>
					<tr>
						<th>Stock Name</th>
						<th>Stock</th>
						<th align="right">Last Price</th>
						<th align="right">Day's Change</th>
						<th align="right">Day's Gain</th>
						<th align="right">Shares</th>
						<th align="right">Market Value</th>
					</tr>
				</tbody>
				<tbody id='second_tbody'>
				</tbody>
			</table>




		</div>
		<!-- END main_col_left -->
		<div id="main_col_right_home">
			<?php /*
				echo $_SESSION['user_id'];
				echo $_SESSION['username'];
				echo $_SESSION['available_funds'];
				*/
			?>
		</div>
	</div>
	<!-- END home_container -->
</div>
<!-- END main_container -->




<script type='text/javascript'> 
		getClientsStock();	
	</script>


<?php
// if we purchased a stock, we let the user know, hack-like.
if(isset($_GET['stock_purchased'])){
		if($_GET['stock_purchased'] == "true"){
			?>
<script type="text/javascript"> $(document).ready(inform_user_he_purchased_stock());</script>
<?php 
		}
	}
?>

</body>
</html>
<?php }?>
