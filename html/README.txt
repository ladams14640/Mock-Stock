To use the web application you need to make an account or login with an existing account, the new account can't already exit.
Once the Account is made, you will be logged in and awarded $10,000 to start the user's profile.
Once logged in, the Trade menu is available to the user. This drop down display reveals every stock company in the NASDAQ and its stock
value. It does this by making an AJAX call and parses the the CSV file from NASDAQ's website that has 
the last traded price on the stock (from the night prior - if im not mistaken). 
To check a quote when not logged in you can type the symbol in the top left hand input. This will get a real time quote., based off of
Yahoo Finance. To get a real time quote from Yahoo Finance, we make a AJAX call to their site with the appropriate symbol and we get 
back a CSV that we parse. 
Below I have down the DB Name, and it's tables that would need to be setup to run this web app.

############################### TROUBLE SHOOTING ##############################
2. How to handle the trade submit
3. Change == to === for JSCRIPTS
4. DECLARE ALL VARIABLES TOP OF FUNCTION - INCLUDING FOR(VAR i)
5. use UPPER_CASE for GLOBALS
###############################################################################


############################### Database structure: ###########################
#
# DATABASE = database_stocks

# USER TABLE = table_user:
 	user_id, username, password, email, available_funds
# PORTFOLIO TABLE = table_portfolio:
 	port_id, stock_symbol, stock_name, user_id, amount_of_stock	
 	We use crypt('password', st) as our way of hashing the password
# STOCK TABLE = table_stocks:
	stock_id, stock_symbol, stock_name

# I made a script (in Forms) that would parse the downloaded file from 
# ftp://ftp.nasdaqtrader.com/symboldirectory/nasdaqlisted.txt
# and insert it into a table_stocks in order to better handle and display Stock names and such.
###############################################################################



################################### Quote.php #################################
#
# Logic of file is contained in the menu.js file.
# 
# This file is dependent on javascript and jquery, if user doesn't have them then he cant even get a quote.
##############################################################################


################################### Trade.php #################################
#
# Logic of file is contained in the trade.js file.
#
# This file is dependent on javascript and jquery, if user doesn't have them then he cant even trade.
#
# I made a giant decision I am forcing on the trade.php, in which deciding if the user has chosen a valid stock or not.
# We query the database of stocks we constructed, from the nasdag current stock database, to decide whether the user's 
# choice in stock is valid.
# This is very limiting for 2 reasons: (1) it's the nasdaq and not every other trading floor, and (2) because we have only
# the current inventory of traded stocks. Obvious remedies can be forged, but no need to include those into this project.  
#
#
# The design of this file: We take in three initial inputs, 
# Input - 1: Buy or Sell Select - by default set to Buy
# Input - 2: Stock Symbol Input - by default empty
# Input - 3: Select Stock Select - By default set to "Choose a Stock"
# Input 2 and 3's behavior are crucialy determined by Input 1, whether it is "Sell" or "Buy"
# Depending on the Intent of the user we see that Input 3 was built first (if "Buy"), some of it's helper functions
# are also called by Input 2 and Input 1 logic.
###############################################################################


####################### JAVASCRIPT LEARNING THE HARD WAY ######################
# if you have 2 functions named the same, but in two different .js files, 
# and both are included, you will cause a problem.
##############################################################################
