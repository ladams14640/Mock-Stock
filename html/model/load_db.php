<ul>
<?php 
/*
 * SCRIPT I wrote in order to load all of the potential stock symbols and names from the nasdaq website
 * Nasdaq offers the name of all of the traded companies from their FTP site - ftp://ftp.nasdaqtrader.com/symboldirectory/nasdaqlisted.txt
 * This database will be used in order to display to the user the potential stocks he could trade in. 
 */

//$dsn = 'mysql:host=localhost;dbname=database_stocks';
// create a new PDO
//$dbh = new PDO($dsn, 'root', '');

//$dbh->beginTransaction();

$db_link = mysql_connect('localhost','root','');
if(!$db_link){
	echo "Connecting to sql error";
}
$db = mysql_select_db('database_stocks',$db_link);
if(!$db){
	echo "db selected error";
}


$sql = null;
$textHandler = fopen('stocks.txt','r');
if($textHandler){
	while(($line = fgets($textHandler)) !== false){
		$tmp = explode("|", $line);
		$sndTmp = explode("-",$tmp[1]);
		$tmp[0] = mysql_real_escape_string($tmp[0]);
		$sndTmp[0] = mysql_real_escape_string($sndTmp[0]);
		$sql="INSERT INTO `table_stocks` (`stock_symbol`, `stock_name`) VALUES ('$tmp[0]','$sndTmp[0]')";
		//print("<li style='display:list-item; text-align:left;'>".$tmp[0]." : " . $sndTmp[0] . "</li>");
		$result = mysql_query($sql);
		if (!$result) {
			echo('Invalid query: ' . mysql_error());
		}else {
			//echo "Complete";
		}
	}
	echo "Complete";
}

?>

</ul>