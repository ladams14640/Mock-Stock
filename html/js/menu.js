$(document).ready(menu_under_menu_quote);
/* Used by menu.php, to make sure client signs in before navigating the website */
function respond_to_tab_click(theTab)
		{
			if($('div#index_error').text().indexOf(theTab) == -1){
				$('div#index_error').html("Please sign in or signup before entering the " + theTab + " page.");				
				setTimeout(function(){$('#signin_page_signin input#username').focus()}, 1);
			}
			else {
				$('div#index_error').text('');
				alert("Please sign in or signup before entering the " + theTab + " page.");
				setTimeout(function(){$('#signin_page_signin input#username').focus()}, 1);
			}
			
			
		}
		
/* Quote button, removes whats on the index page and displays all of the quote information */
function menu_under_menu_quote()
{
	$('input#quote_submit').on("click", function(e){
		
		// for reseting the tab
		
		if($('input#quote').val() == ""){
			e.preventDefault();
			alert("Please enter a Quote");
			$('input#quote').focus();
		}
			
			
		
		
	});
	
	
}		
		


