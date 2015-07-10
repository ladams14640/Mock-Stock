/* this adds up all the cash assets */
global_all_stocks_added = 0;
/* Number of stocks the user owns */
num_of_stocks = 0;


/* Used in logged_home.php in order to retrieve the user's current stock holdings */
function getClientsStock(){
	console.log("get clients stock entered");
	// AJAX -- Check the DB for user's stocks
	$.ajax({
		dataType:'json',
		url: 'forms/query.php',
		data:{'validate': 'user_stocks'},
		success: function(data){	
			num_of_stocks = data.stocks.length;
			if(data && data.stocks !== "no stocks"){
					console.log("get clients stock entered - got a stock");

				$(data.stocks).each(
						function(){
							var stock=this.split(/:/)[0];
							var amount=this.split(/:/)[1];
							var stock_name=this.split(/:/)[2];
							
							/* AJAX -- get stock's current value */
							get_stocks_current_value(stock,amount,stock_name);
							
						}
					);
				
				$('#clients_home_portfolio').find('tbody#second_tbody').append(""+
					"<tr "+
						"id='clients_portfolio_value'><th>Portfolio Value</th>"+
						"<th></th><th></th><th></th><th></th><th></th><th></th><th></th><th></th><th></th><th></th><th></th><th></th><th></th><th></th><th></th><th></th><th></th><th></th><th></th><th></th><th></th><th></th>"+
					"</tr>");
				//get_clients_cash_reserves();
				
			}else {					
				console.log("get clients stock entered - did not get  a stock");
				
				build_error_column();
				get_clients_cash_reserves();
			}
		},
		error: function(jqXHR, exception){
			//$('#main_col_right_home').append('<div id="clients_home_portfolio_right"></div>');
			//$('#clients_home_portfolio_right').append("<h2 style='color:white;'> Sorry, but <br/> You don't currently hold any stocks<br/><a href='trade.php'>Click to Trade</a></h2>");
			//$('#clients_home_portfolio').find('tbody#first_tbody').append("<tr><td style='text-align:center;'>------</td><td style='text-align:center;'>------</td><td style='text-align:center;'>------</td><<td style='text-align:center;'>------</td>/<td style='text-align:center;'>------</td>tr>");
			//get_clients_cash_reserve();
			console.log("AJAX -problem getClientsStock() .");
			if (jqXHR.status === 0) {
                alert('Not connect.\n Verify Network.');
            } else if (jqXHR.status == 404) {
                alert('Requested page not found. [404]');
            } else if (jqXHR.status == 500) {
                alert('Internal Server Error [500].');
            } else if (exception === 'parsererror') {
                alert('Requested JSON parse failed.');
            } else if (exception === 'timeout') {
                alert('Time out error.');
            } else if (exception === 'abort') {
                alert('Ajax request aborted.');
            } else {
                alert('Uncaught Error.\n' + jqXHR.responseText);
            }
			//get_clients_cash_reserves();
		}
		
	});
}

function get_stocks_current_value(stock,amount,stock_name){
	console.log("Entered get_stocks_current_value");
	$.ajax({
		dataType:"json",
		url:'forms/query.php?validate=quote',
		data:{quote:stock},
		success: function(data2){
			if(data2.symbol != "Not Valid" || data2.symbol.length >= 2){ 
				console.log("Got Symbol -  get_stocks_current_value");
					global_all_stocks_added += parseFloat(amount) * parseFloat(data2.price);	
					// This will add all the information
					$('#clients_home_portfolio').
						find('tbody#first_tbody').append(""           +
						"<tr>"                                        +
							"<td>" + stock_name + "</td>"             +
							"<td>" + stock.toUpperCase() + "</td>"    +
							"<td align='right' style='padding-right:2em;'>" + "$" +Number(parseFloat(data2.price)).toLocaleString("en") + "</td>" + 
							"<td align='right' style='padding-right:2em;'>" + "$" +data2.change + "</td>"+
							"<td align='right' style='padding-right:2em;'>" + "$" +Number(parseFloat(data2.change * amount)).toLocaleString("en") + "</td>"+
							"<td align='right' style='padding-right:2em;'>" + 	   Number(parseFloat(amount)).toLocaleString("en") + "</td>"+
							"<td align='right' style='padding-right:2em;'>" + "$" +Number(parseFloat(amount * data2.price).toFixed(2)).toLocaleString("en") + "</td>"+
						"</tr>");
					num_of_stocks -= 1;
					if(num_of_stocks == 0){
						get_clients_cash_reserves();
					}	
					
				}
			else {
				console.log("Did not Get Symbol -  get_stocks_current_value");
				$('#clients_home_portfolio').find('tbody#first_tbody').append("<tr><td align='center'> nothing </td><td align='center'>nothing</td><td align='center'>nothing</td><td align='center'>nothing</td></tr>");

			}
			
			//$('#clients_home_portfolio').find('tbody').append("<tr><td>nothing</td></tr>");
		},
		error: function(jqXHR, exception){
			console.log("AJAX - problem get_stocks_current_value() .");
			if (jqXHR.status === 0) {
                alert('Not connect.\n Verify Network.');
            } else if (jqXHR.status == 404) {
                alert('Requested page not found. [404]');
            } else if (jqXHR.status == 500) {
                alert('Internal Server Error [500].');
            } else if (exception === 'parsererror') {
            	console.log("Requested JSON parse Failed.");
            	console.log(jqXHR);
                alert('Requested JSON parse failed.');
            } else if (exception === 'timeout') {
                alert('Time out error.');
            } else if (exception === 'abort') {
                alert('Ajax request aborted.');
            } else {
                alert('Uncaught Error.\n' + jqXHR.responseText);
            }
		}
		
	});	
}

function get_clients_cash_reserves(){
console.log("we got to get_clients_cash_reserve()");
	$.ajax({
		dataType:"json",	
		url:"forms/query.php?validate=user_profile",
		success: function(data){
			console.log("we got to get_clients_cash_reserve() successful ajax");

			// Build second part of the table
			$('#clients_home_portfolio').
				find('tbody#second_tbody').
					append("<tr >"+
							"<td >Cash </td>"+
							"<td ></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td>" + 
							"<td align='right' style='padding-right:6em;'>"+"$"+Number(parseFloat(data.available_funds).toFixed(2)).toLocaleString("en")+ "</td>"+							
							"</tr>");
			
			global_all_stocks_added += parseFloat(data.available_funds);
			sum_up_all_values_for_portfolio();
		},
		error: function(jqXHR, exception){
			console.log("AJAX - get_clients_cash_reserves");
			if (jqXHR.status === 0) {
                alert('Not connect.\n Verify Network.');
            } else if (jqXHR.status == 404) {
                alert('Requested page not found. [404]');
            } else if (jqXHR.status == 500) {
                alert('Internal Server Error [500].');
            } else if (exception === 'parsererror') {
                alert('Requested JSON parse failed.');
            } else if (exception === 'timeout') {
                alert('Time out error.');
            } else if (exception === 'abort') {
                alert('Ajax request aborted.');
            } else {
                alert('Uncaught Error.\n' + jqXHR.responseText);
            }
		}
	});
}
function sum_up_all_values_for_portfolio(){	
	
	$('#clients_home_portfolio').find('tbody#second_tbody').append(""+
		"<tr><td width='50%'>Purchase Power </td>" +  
			"<td></td><td></td><td><td></td><td></td><td></td><td></td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td>" +	
			"<td width='50%' align='right' style='padding-right:6em;' >"+"$"+Number(global_all_stocks_added.toFixed(2)).toLocaleString('en')+"</td>"+
		"</tr>");

}


function inform_user_he_purchased_stock(){
	$("div#index_error").html('You successfully purchased your stock!');
}
function getSignUpSetup(cash){
	
	/* SETUP THIS FUNCTION TO HANDLE IF THE USER HAS NEVER LOGGED ON BEFORE UNTIL NOW*/
	$('#clients_home_portfolio').
				find('tbody#second_tbody').
					append("<tr >"+
							"<td width='50%' style='padding-left:2em;' >Cash </td>"+
							"<td ></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td>" + 
							"<td align='right' width='50%' style='padding-right:6em;''>"+"$"+cash+ "</td>"+							
							"</tr>");

	build_error_column();
}
function build_error_column(){
	$('#main_col_right_home').append('<div id="clients_home_portfolio_right"></div>');
				$('#clients_home_portfolio_right').append("<h2 style='color:white;'> Sorry, but <br/> You don't currently hold any stocks<br/><a href='trade.php'>Click to Trade</a></h2>");
				$('#clients_home_portfolio').find('tbody#first_tbody').append("<tr><td style='text-align:center;'>------</td><td style='text-align:center;'>------</td><td style='text-align:center;'>------</td><td style='text-align:center;'>------</td><td style='text-align:center;'>------</td><td style='text-align:center;'>------</td><td style='text-align:center;'>------</td>tr>");
				
}
