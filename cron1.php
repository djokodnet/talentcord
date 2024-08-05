<?php



include('mjl-includes/db.inc.php');

$result = mysql_query('UPDATE jobs SET `ISENABLED` = 0 WHERE (SELECT DATEDIFF(NOW(),`DATEPOSTED`) > `NUMDAYS`)');


?>

