<? 

	include('../mjl-includes/db.inc.php');

	//$result = mysql_query('SELECT jo.JID, jo.JOBTITLE, jo.JOBLOCATION, jo.DATEPOSTED, co.COMPANYNAME FROM companies co, jobs jo where co.CID = jo.CID ORDER BY jo.DATEPOSTED' );
	
	$result = mysql_query('SELECT * FROM companies co, jobs jo where co.CID = jo.CID ORDER BY jo.DATEPOSTED DESC' );
	
	$json = array();

	if(mysql_num_rows($result)){
		while ($row = mysql_fetch_assoc($result)){
			$json['jobs'][]=$row;
		}
	}
	echo json_encode($json); 
?>