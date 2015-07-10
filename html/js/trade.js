/* This is set by get_clients_cash_reserve - */
AVAILABLE_FUNDS = 0;
/* Set this when we go get the amount of that stock set by populate_trade_main_col_right */
AMOUNT_OF_POTENTIALLY_SELLING_STOCK = 0;

	

function listen_for_buy_sell_select(){
	$("select#buy_or_sell").click(function(){
		if($("select#buy_or_sell").val() === "Sell"){
			show_if_user_has_the_stock();
		}else {
			depopulate_index_error();
			populate_trade_main_col_right_only_funds();
			if($("select#select_stock").val() !== "Choose a Stock"){
				$("select#select_stock").click();
			}
		}
	});
}

/* Finds out if client has the selected stock */
function show_if_user_has_the_stock(){
	if($("select#select_stock").val() === "Choose a Stock"){
		populate_trade_main_col_right_only_funds();
	}else {
		find_out_if_client_owns_this_stock();
	}
}
/* Got to figure this function outr */
function find_out_if_client_owns_this_stock(){
	$.ajax({
		dataType:"json",
		url:"forms/query.php?validate=user_stocks",
		success: function(data){
			var theEquatedStock = "";
			var theEquatedStocksAmount = 0;
			var stock_symbol_choice = "";
			if(data){
				$(data.stocks).each(function()
				{
					var selected_option = $("select#select_stock").val().split(/:/)[0].replace(/ /g,'').toUpperCase();
					stock_symbol_choice = selected_option;
					var returnedStock = this.split(/:/)[0].replace(/ /g,'').toUpperCase();
					// amount the client owns
					var amount = this.split(/:/)[1];
					// we have the stock
					if(selected_option === returnedStock){
						theEquatedStock = returnedStock;
						theEquatedStocksAmount = amount;
					}else {
						// we dont have the stock!
						// which we will skip down further, since the boolean will determine if we display something or not
					}
					
				});
				// client owns stock
				if(theEquatedStock != ""){
					populate_trade_main_col_right(theEquatedStock, theEquatedStocksAmount);
					populate_trade_form();
					change_populate_trade_form_for_Sell();
					get_stock_last_price(stock_symbol_choice);
				}// client doesnt own stock
				else {
					depopulate_trade_main_col_right();
					you_dont_have_that_stock();
					
				}
			}
		},
		error: function(jqXHR, exception){
			if (jqXHR.status === 0) {
	            //alert('Not connect.\n Verify Network.');
	        } else if (jqXHR.status == 404) {
	           // alert('Requested page not found. [404]');
	        } else if (jqXHR.status == 500) {
	            //alert('Internal Server Error [500].');
	        } else if (exception === 'parsererror') {
	           // alert('Requested JSON parse failed.');
	        } else if (exception === 'timeout') {
	           // alert('Time out error.');
	        } else if (exception === 'abort') {
	           // alert('Ajax request aborted.');
	        } else {
	           // alert('Uncaught Error.\n' + jqXHR.responseText);
	        }
		} // End of error function.
	});
}
function change_populate_trade_form_for_Sell(){
	$("span#totalBuyCost_or_totalSellCost").html("Total Sell Price with Fee");
}


function get_clients_cash_reserve(){
	$.ajax({
		dataType:"json",
		url:"forms/query.php?validate=user_profile",
		success: function(data){			
			AVAILABLE_FUNDS = parseFloat(data.available_funds);
			populate_trade_main_col_right_only_funds();
		},
		error: function(jqXHR, exception){
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

function you_dont_have_that_stock(){
	$('div#index_error').html("You dont have any shares in that stock!");
}


function populate_trade_main_col_right(returnedStock,amount){
	/* I need a better format */
	depopulate_trade_main_col_right();
	AMOUNT_OF_POTENTIALLY_SELLING_STOCK = amount;
	$('div#trade_main_col_right').append(
		"<table id='portfolio_table_for_trade'>" +
			"<tbody>"+
			"<tr><td id='left_side'>Cash </td><td></td><td id='right_side'>"  +"$"+  Number(parseFloat(AVAILABLE_FUNDS)).toLocaleString('en')+"</td></tr>" +
			"<tr><td id='left_side'>Symbol </td><td></td><td id='right_side'>"+		returnedStock+"</td></tr>"+ 
			"<tr><td id='left_side'>Shares </td><td></td><td id='right_side'>"+		amount+"</td></tr>"+
			"</tbody>"+
		"</table>");
}
function populate_trade_main_col_right_only_funds(){
	depopulate_trade_main_col_right();
	$('div#trade_main_col_right').append(
			"<table id='portfolio_table_for_trade'>" +
				"<tbody>"+
				"<tr><td id='left_side'>Cash </td><td></td><td></td><td></td><td id='right_side'>"  +"$"+  Number(parseFloat(AVAILABLE_FUNDS)).toLocaleString('en')+"</td></tr>" +
				"</tbody>"+
			"</table>");
}

function depopulate_trade_main_col_right(){
	$('div#trade_main_col_right').
		html('<h2>Portfolio</h2>').
		append('');
	
	depopulate_index_error();
}
function depopulate_index_error(){
	$('div#index_error').html("");
}

/* Listens for user to input a symbol */
function listen_for_symbol_input(){
	$('input#trade_symbol').keyup(function(){
		/* not sure if this is the best idea, but I kind of want to do ajaxx calls 
		   to the database over yahoo finance, don't want to make too many requests to yahoo. */
		verify_input_symbol($('input#trade_symbol').val());
		
	});// end of keyup listener
}// end of listen_for_symbol_input


/* Helper for listen_for_symbol_input() */
function verify_input_symbol(theInput){
	if(theInput.length >= 4){
		$.ajax
		({
			dataType: 'json',
			url: 'forms/query.php?validate=trade_symbol_ajaxx',
			data:{theInput: $('input#trade_symbol').val()},
			success: function(data){
				if(data.stock !== "no stock yet" && data.stock !== "never sent a stock"){
					// we have a valid symbol
					handle_ajaxx_response_from_input_symbol(data.stock);				
				}else {
					you_dont_have_that_stock();
					populate_trade_main_col_right_only_funds();
					resetSelection();
					
				}
				
			},
			error: function(jqXHR, exception){
				if (jqXHR.status === 0) {
		            //alert('Not connect.\n Verify Network.');
		        } else if (jqXHR.status == 404) {
		           // alert('Requested page not found. [404]');
		        } else if (jqXHR.status == 500) {
		            //alert('Internal Server Error [500].');
		        } else if (exception === 'parsererror') {
		           // alert('Requested JSON parse failed.');
		        } else if (exception === 'timeout') {
		           // alert('Time out error.');
		        } else if (exception === 'abort') {
		           // alert('Ajax request aborted.');
		        } else {
		           // alert('Uncaught Error.\n' + jqXHR.responseText);
		        }
			} // End of error function.
			
		});// end of ajax call
	} // end of conditional
	else {
		populate_trade_main_col_right_only_funds();
		resetSelection();
	}
}

/* Helper for verify_input_symbol */
function handle_ajaxx_response_from_input_symbol(theData){
	// we select the option that corresponds with the symbol input
	$('select#select_stock').find("option[value='"+theData+"']").attr("selected", true);
	// if we have chosen buy then proceed
	if($("select#buy_or_sell").val() === "Buy"){
		// now lets display the rest of the form
		populate_trade_form();
		//change_populate_trade_form_for_buy();
		
		// get the stock's last price
		get_stock_last_price(theData);
	}else {
		// we really should do more checking and see if user even has this stock, if not they cant sell it!
		find_out_if_client_owns_this_stock();
		
	}
}
function change_populate_trade_form_for_buy(){
	$('span#totalBuyCost_or_totalSellCost').html("Total Cost of purchase, including fee.");
}
	

/* Helper for verify_input_symbol() */
function depopulateRestOfTheForm(){
	$('div#trade_container form#trade_form div#trade_menu_to_come').html('');
	depopulate_trade_main_col_right();
}

function resetSelection(){
	$('option:selected', 'select#select_stock').removeAttr('selected');
}


/* Listens for an option to be selected */
function listen_for_select_choice(){
	// selection made
	$("select#select_stock").click(function(){
		//$("#index_error").text($(this).find(":selected").val()); /*trouble shooting function*/
		if($("select#select_stock").val() !== "Choose a Stock"){
			var symbol = $(this).find(":selected").val().split(':');
			var sym = $.trim(symbol[0]);
			// make sure input#trade_symbol's value changes
			change_trade_symbol_input(sym);
			// We handle this according to select#buy_or_sell
			if($('select#buy_or_sell').val() === "Buy"){
				// Handle without care of select#buy_or_sell
				populate_trade_form();	
				get_stock_last_price(sym);				
			}else {
				// Handle with concern of select#buy_or_sell because Sell is selected
				find_out_if_client_owns_this_stock();
				get_stock_last_price(sym);
			}
		}
	});
}

function change_trade_symbol_input(symbol){
	$('input#trade_symbol').val(symbol);
}

/* helper function for listen_for_select_choice() */
function get_stock_last_price(symbol1){
	$("span#last_price").text("finding stock price, please be patient...");
	$.ajax
	({
		dataType: "json",
		url: "forms/query.php?validate=quote",
		data: {quote:symbol1},
		success: function(data)
		{
			if(data.symbol != "Not Valid")
			{
				var dataPrice = Number(data.price).toFixed(2);
				console.log(dataPrice);
				$('span#last_price').html('$' + dataPrice);

			}else {
				$('span#last_price').html('0');
			}
			enable_input_quantity();
		},
		error: function(jqXHR, exception){
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
		} // End of error function.
	});
}
/* Listens for quantity input not activated until populate_trade_form() is called in get_stock_last_price() */
function listen_for_quantity_change(){
	$('input#trade_quantity').keyup(function(){
		
			var last_price = $('span#last_price').text().replace('$','');

			if($('input#trade_quantity').val() !== ""){

				var quantity = parseInt($('input#trade_quantity').val());

				var total_cost = multiply_stock_with_quantity(last_price, quantity);
				
				// If "Buy" is selected
				if($("select#buy_or_sell").val() === "Buy"){
					$("span#total_cost").text("Stocks Cost: ");
					$('span#trade_total_cost').text('$' + commaFormatted(total_cost,2));
					var total_cost_with_fee = total_cost + 20;
					$('span#trade_total_fee').text("$"+commaFormatted(total_cost_with_fee,2)).css("color", "red");
					// check if the amount of cash is higher than the cost of the total_cost
					if(total_cost_with_fee < AVAILABLE_FUNDS){
						depopulate_index_error();
						enable_button();
					}else {
						//console.log("WHAT?! - listen_for_quantity_change -  " + AVAILABLE_FUNDS);
						cost_higher_than_client_has();
						disable_button();
						
					}
				}
				// If "Sell" is selected
				else {
					if(parseInt($('input#trade_quantity').val()) <= AMOUNT_OF_POTENTIALLY_SELLING_STOCK){
						$("span#total_cost").text("Sale Prices: ");
						$('span#trade_total_cost').text('$' + commaFormatted(total_cost, 2));
						var total_cost_with_fee = total_cost - 20;
						$("span#trade_total_fee").text("$" + commaFormatted(total_cost_with_fee, 2)).css("color","green");
						enable_button();
						depopulate_index_error();
					}else {
						quantity_higher_than_client_has();
						disable_button();
					}
					
				}
			}else {
				$('input#trade_quantity').html('');
				disable_button();

			}
		
	});
}

function commaFormatted(rawNumber, decimalPlaces) {
     var number = rawNumber
     console.log("passed Number " + number);
     var decimal= decimalPlaces;
     var delimiter= ",";
     var result = formatNumber(number, decimal, delimiter);
    	return result;
  }
  function formatNumber(rawNumber, decimal, delimiter) 
  {
	  console.log("rawNumber " + rawNumber);
     var temp = parseFloat(rawNumber).toFixed(2);
     console.log("toFixed: " + temp);
     var partArray = temp.toString().split("."); 
     partArray[0] = partArray[0].toString().replace(/\B(?=(\d{3})+(?!\d))/g, delimiter); 
     return partArray.join("."); 
  } 

  String.prototype.insert = function (index, string) {
          if (index > 0)
            return this.substring(0, index) + string + this.substring(index, this.length);
          else
            return string + this;
        };
    
        function multiply_stock_with_quantity(stockPrice, stockQuantity){
            console.log("multi_two initial stockPrice = " + stockPrice);

            stockPrice = stockPrice.toString().split('.');

            
	
            console.log("yeah " + stockPrice[1]);
            if(stockPrice[1].length === 1){
            	stockPrice[1] += "0";
            }
            if(stockPrice[1].length > 2){
            	stockPrice[1] = stockPrice[1].substring(0,2);
            }
            
            
            var price = false;
            var quant = false;
            var positionOfDecimal = 0;
            
            console.log("stockPrice now : " + stockPrice[0]);
            console.log("StockPrice right side of decimal: "+ stockPrice[1]);
            //stockPrice = stockPrice.split(".");
            var stockToCents = Number(stockPrice[0]); 
            stockToCents *= 100;	
            console.log("stock to cents= " + stockToCents);
            if(stockPrice.length > 1){
                price = true;
                positionOfDecimal = stockPrice[1].length;
                console.log("position of decimal = " + positionOfDecimal);
                var stockToDec = Number(stockPrice[1]);
            }else {
            	
            }
           
            
            
          
            
            // DO the math
            
            if(price){
                stockToCents = stockToCents + stockToDec;
            }
            
            
            var result = stockToCents * stockQuantity;
            result = result / 100;
            console.log("multipled result = " + result);
            var strResult = result.toString();
            //result = strResult.insert(strResult.length - positionOfDecimal, ".");
            return Number(result);
            
        }
	
	
/* Helper function for get_stock_last_price(), which is helper of listen_for_select_choice */
function populate_trade_form(){
	$('div#trade_container form#trade_form div#trade_menu_to_come').
	html(	"<span>Last Price: </span> <span id='last_price' style='color:#99ccff;'></span>" +
			"<br/>" +
			"<label for='trade_quantity'>Quantity: </label><input type='number' name='trade_quantity' id='trade_quantity'>" +
			"<br/>" +
			"<span id='total_cost'>Total Cost: </span><span id='trade_total_cost' style='color:#99ccff;'></span>" +
			"<br/>" +
			"<span id='totalBuyCost_or_totalSellCost'>Total Cost with trading fees: </span><span id='trade_total_fee' name='trade_total_fee'></span>" +
			"<br/>" +
			"<input id='trade_button' type='button' value='submit'>" +
			"<br/>");
	disable_button();
	disable_input_quantity();
	listen_for_quantity_change();
	process_trade_request();
}

function process_trade_request(){
	$('form#trade_form input#trade_button').click(function(){
		$('form#trade_form').submit();
//		/alert("You Pressed it!");
	});
}


function enable_input_quantity(){
	$('input#trade_quantity').prop("disabled", false);
}
function disable_input_quantity(){
	$('input#trade_quantity').prop("disabled", true);
}

function enable_button(){
	$("input#trade_button").prop("disabled",false);
}
function disable_button(){
	$("input#trade_button").prop("disabled",true);
}
function quantity_higher_than_client_has(){
	$("div#index_error").html("Not enough shares for that!");
}
function cost_higher_than_client_has(){
	$("div#index_error").html("Not enough funds for that!");
}
