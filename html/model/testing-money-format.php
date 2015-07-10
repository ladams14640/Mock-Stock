<?php 

// this will give us commas in the number - great for display
echo number_format(1011.225, 2, '.',',');  
echo "<br/>";
// this will give us a number removing anything past the .100THs place
echo (float) number_format(1011.225, 2,'.','');

?>