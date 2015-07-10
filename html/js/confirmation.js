

/**
 * @namespace TradePage What the namespace contains or which apps/webpages use it
 */
var TradePage = function(packagedValues) {

	// Considered global all private
	var __symbol = packagedValues.symbol, __quantity = packagedValues.quantity, __trade = packagedValues.trade;
	var __stockName;
	var __price = 0;

	/**
	 * @memberOf packagedValues
	 * function init
	 */
	// public methods
	this.init = function() {
		setPriceSpan();
		setQuantity();
	};
	/**
	 * @memberOf confirmation
	*/
	// Private methods
	var setSymbolSpan = function() {
		$('span#symbol').html(__stockName + " -- ");
		$('span#symbol').append(__symbol);

	};

	var setPriceSpan = function() {
		$.ajax({
			data : {
				quote : __symbol
			},
			dataType : "json",
			url : "/forms/query.php?validate=quote",
			success : function(data) {
				console.log("setPriceSpan: " + data['price']);
				updatePrice(data['price']);
				updateStockName(data['stock_name']);

				// console.log("setPrice function Price = "+ __price);
				$('span#price').html("$" + commaFormatted(__price));
				setTrade();
			},
			error : function() {
				// console.log("problem");
			}
		});
	};

	var updatePrice = function(mPrice) {
		__price = mPrice;
		console.log("updatePrice function, price = " + __price);
	};

	var updateStockName = function(name) {
		if (name !== "undefined") {
			__stockName = name;
			console.log("updateStockName function, name = " + __stockName);
			setSymbolSpan();
		}
	};
	

	var setQuantity = function() {
		$('span#quantity').html(__quantity);

	};

	var setTrade = function() {
		console.log("setTrade has been called. Price = " + __price);
		$("label[for='total']").html("Total in trade: ");
		var total = multiply_stock_with_quantity(__price, __quantity);
		$("span#total").html("$" + total);
		var totalWithFees = total + 20;

		// Trade is Buy
		if (__trade === "Buy") {
			// $('span#buy_sell').html("Purchasing");
			$("Label[for='trading_costs']").html(
					"Total after trading fees: $"
							+ commaFormatted(totalWithFees))
					.css("color", "red");
		} 
		// Trade is Sell
		else {
			// $('span#buy_sell').html("Selling");
			totalWithFees -= 40;
			$("Label[for='trading_costs']").html(
					"Total after trading fees: $"
							+ commaFormatted(totalWithFees)).css("color",
					"green");
		}
	};

	var multiply_stock_with_quantity = 	function (stockPrice, stockQuantity) {
		console.log("multi_two initial stockPrice = " + stockPrice);

		stockPrice = stockPrice.toString().split('.');

		console.log("yeah " + stockPrice[1]);
		if (stockPrice[1].length == 1) {
			stockPrice[1] += "0";
		}
		if (stockPrice[1].length > 2) {
			stockPrice[1] = stockPrice[1].substring(0, 2);
		}

		var price = false;
		var quant = false;
		var positionOfDecimal = 0;

		console.log("stockPrice now : " + stockPrice[0]);
		console.log("StockPrice right side of decimal: " + stockPrice[1]);
		// stockPrice = stockPrice.split(".");
		var stockToCents = Number(stockPrice[0]);
		stockToCents *= 100;
		// console.log(stockToCents);
		if (stockPrice.length > 1) {
			price = true;
			positionOfDecimal = stockPrice[1].length;
			console.log("position of decimal = " + positionOfDecimal);
			var stockToDec = Number(stockPrice[1]);
		} else {

		}

		// DO the math

		if (price) {
			stockToCents = stockToCents + stockToDec;
		}

		var result = stockToCents * stockQuantity;
		result = result / 100;
		console.log("multipled result = " + result);
		var strResult = result.toString();
		// result = strResult.insert(strResult.length - positionOfDecimal, ".");
		return Number(result);

	}

};