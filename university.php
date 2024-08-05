<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Country and state choose using jquery</title>
<style type="text/css">
.main{ margin:0 auto; border:0px green dashed; min-height:200px; width:550px; margin-top:150px; padding-left:55px;  }
#country { width:250px; height:25px; font-size:14px; font-family:Verdana, Geneva, sans-serif; }
#state { width:250px; height:25px; font-size:14px; font-family:Verdana, Geneva, sans-serif; }
#size { width:230px; margin-top:50px; }

</style>




</head>

<body>

<script type="text/javascript" src="assets/universities.js"></script>
<div class="main">
        <h3 align="left">Main tutorial <a href="http://www.2my4edge.com/2013/06/choose-state-and-city-based-on-country.html"> 2my4edge </a></h3>
        <h2 align="center"> Country and state choose using jquery</h2>
		<div id="size">Select the Country :</div>
        <select onchange="print_state('state',this.selectedIndex);" id="country" name ="country"></select>
		<br />
		<div id="size">State in the above country : </div>
        <select name ="state" id ="state"></select>
		<script language="javascript">print_country("country");</script>	
</div>
</body>
</html>