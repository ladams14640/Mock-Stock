function testQuote(quoteer){
	 	$.ajax({
	dataType:'json',
	url: 'forms/query.php?validate=quote',
	data: {quote:quoteer},
	success : function(data){
		if(data.symbol !='Not Valid')
		{
			
			//$('.tab-content #Home').addClass('active').siblings().removeClass('active');
			//$('.tab-content #Home').show().siblings().hide();
			/*
				"symbol"=>$symbol,
				"price"=>$price,
				"change"=>$change,
				"update"=>$update,
				"high"=>$high,
				"low"=>$low,
				"vol"=>$vol,
				"open"=>$open,
				"stock_name"=>$stock_name
			 */			
			
			
			$('#index_container').html(''); /* Clean the container*/
			$('#index_container').append('<div id="quote_top_column"></div>');/* Create banner to fill */
			$('#index_container').append('<div id="index_error"></div>');
			$('#quote_top_column').append('<div id="t_symbol"></div><div id="t_price"></div><div id="t_percentage"></div><div id="t_time"></div>');
			
			$('#index_container').append('<div id="main_col_right_quote"></div>'); /* Create column to fill */
			$('#index_container').append('<div id="main_col_left_quote"></div>'); /* Create column to fill*/
			$('#t_symbol').append(data.symbol);
			$('#t_price').append(data.price);
			$('#t_percentage').append(data.change);
			$('#t_time').append(data.update);
			
			$('#main_col_left_quote').append('<div id="quote_text" style="padding-right:2em;"> Stock Name:   </div><div id="quote_text_content">'+data.stock_name+'</div><br>');
			$('#main_col_left_quote').append('<div id="quote_text" style="padding-right:2em;"> Stock Name:   </div><div id="quote_text_content">'+data.price+'</div><br>');
			$('#main_col_left_quote').append('<div id="quote_text" style="padding-right:2em;"> Stock Name:   </div><div id="quote_text_content">'+data.change+'</div><br>');
			$('#main_col_left_quote').append('<div id="quote_text" style="padding-right:2em;"> Stock Name:   </div><div id="quote_text_content">'+data.update+'</div><br>');
			
		
			$('#main_col_right_quote').append("<div id='quote_text'> High: </div><div id='quote_text_content'>"+data.high+"</div><br>");
			$('#main_col_right_quote').append("<div id='quote_text'> Low:  </div><div id='quote_text_content'>"+data.low+"</div><br>");
			$('#main_col_right_quote').append("<div id='quote_text' style='padding-right:1em;'> Volume:</div><div id='quote_text_content'>"+data.vol+"</div><br>");
			$('#main_col_right_quote').append("<div id='quote_text'> Open:</div><div id='quote_text_content'>"+data.open+"</div><br>");
			
			/* Limit the length of the text in the following div tag */
			$('div#quote_text_content').each(function(i){
				var len = $(this).text().length;
				if(len > 9){
					$(this).text($(this).text().substr(0,9)+'...');
				}
			});
			
		}
		else 
		{
			$('#index_error').html('');
			$('#index_error').append('Not a valid stock');
		
		}
		
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