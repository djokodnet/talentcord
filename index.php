<?php



session_start();



error_reporting(E_ERROR | E_WARNING | E_PARSE | E_NOTICE);



include('mjl-includes/settings.inc.php');

include('mjl-includes/db.inc.php');

include('mjl-includes/employer.inc.php');

include('mjl-includes/users.inc.php');

include('mjl-includes/functions.inc.php');

require_once 'assets/class.phpmailer.php';

if (!isset($_SESSION['USERRUN'])){

	$_SESSION['USERRUN'] = '';

}else{

	if ($_SESSION['USERRUN'] != 'BEENDONE'){

		if ($runcron <> 1){

			$result = mysql_query('UPDATE jobs SET ISENABLED = 0 WHERE (SELECT DATEDIFF(NOW(),`DATEPOSTED`) > `NUMDAYS`)');

			$_SESSION['USERRUN'] = 'BEENDONE';

		}

	}

}



if (isset($_GET['loc']) && !empty($_GET['loc'])){

	$loc = htmlspecialchars($_GET['loc'], ENT_QUOTES);

}else{

	$loc = '';

}


if (isset($_GET['acct']) && !empty($_GET['acct'])){

	$acct = htmlspecialchars($_GET['acct'], ENT_QUOTES);
	
	$_SESSION['acct'] 	= $acct;

}else{

	$acct = '';

}

$theme = 'jobsdata';



$mainpage 	= file_get_contents('mjl-themes/'.$theme.'/index.theme.html');



$loginerror	= '';

$statusmsg 	= '';

$pagedata = '';

 

if (isset($loc) && ($loc == "logout")){

if (!isset($_SESSION['acct'])) {
	$acct = $_SESSION['acct']; } else {
	$acct = '';
	}
	
//	header('Location: index.php');
	
	echo '<META HTTP-EQUIV="Refresh" Content="0; URL=index.php?acct='.$acct.'">';	

	session_destroy();

	ob_start();

	exit;
}



if (($_SERVER['REQUEST_METHOD'] == 'POST') && (@$_POST['usertype'] == 'employer')){

	if(!empty($_POST['uname']) || !empty($_POST['pword'])){

		$uname 	= htmlspecialchars($_POST['uname'], ENT_QUOTES);

		$pword 	= htmlspecialchars($_POST['pword'], ENT_QUOTES);

		$result = mysql_query('SELECT * FROM companies WHERE USERNAME = "'.$uname.'" LIMIT 1');
		
		//$result = mysql_query('SELECT * FROM companies a , cordpackage b WHERE a.USERNAME = "'.$uname.'" and a.CORDPACKAGE=b.PAKID LIMIT 1');

		$cnt 	= mysql_num_rows($result);

		if ($cnt > 0){

			$row = mysql_fetch_array($result);
			

			if (md5($pword) ==  $row['PASSWORD'] && ($row['APPROVED'] == 1)){

			//if (md5($pword) ==  $row['PASSWORD'] && !empty($row['APPROVED'])){

				$_SESSION['acctype']		=	'employer';

				$_SESSION['cid'] 			= $row['CID'];

				$_SESSION['companyname'] 	= $row['COMPANYNAME'];

				$_SESSION['mailaddress1'] 	= $row['MAILADDRESS1'];

				$_SESSION['mailaddress2'] 	= $row['MAILADDRESS2'];

				$_SESSION['city'] 			= $row['CITY'];

				$_SESSION['state'] 			= $row['STATE'];

				$_SESSION['zip'] 			= $row['ZIP'];

				$_SESSION['phone'] 			= $row['PHONE'];

				$_SESSION['fax'] 			= $row['FAX'];

				$_SESSION['contact'] 		= $row['CONTACT'];

				$_SESSION['contactemail'] 	= $row['CONTACTEMAIL'];

				$_SESSION['contacttitle'] 	= $row['CONTACTTITLE'];

				$_SESSION['website'] 		= $row['WEBSITE'];

				$_SESSION['about'] 			= $row['ABOUT'];

				$_SESSION['username'] 		= $row['USERNAME'];
				
				$_SESSION['birthdate'] 		= $row['BIRTHDATE'];
				
				$_SESSION['photosmall'] 	= $row['PHOTOSMALL'];
				
				$_SESSION['photobig'] 		= $row['PHOTOBIG'];
				
				$_SESSION['pakid']			= $row['CORDPACKAGE'];
				

			}else{

				$loginerror = 'Username and Password are not matched or not listed';
				$acct = 'emp';

			}

		} else {

			$loginerror = 'Username and Password are not matched or not listed';
			$acct = 'emp';

		}

	}

} elseif (($_SERVER['REQUEST_METHOD'] == 'POST') && (@$_POST['usertype'] == 'seeker')){

	if(!empty($_POST['uname']) || !empty($_POST['pword'])){

		$uname 	= htmlspecialchars($_POST['uname'], ENT_QUOTES);

		$pword 	= htmlspecialchars($_POST['pword'], ENT_QUOTES);

		$result = mysql_query('SELECT * FROM users WHERE USERNAME = "'.$uname.'" LIMIT 1');

		$cnt 	= mysql_num_rows($result);

        if ($cnt > 0){

			$row = mysql_fetch_array($result);

			if ((md5($pword) ==  $row['PASSWORD']) && ($row['APPROVED'] == 1)){

			//if ((md5($pword) ==  $row['PASSWORD']) && !empty($row['APPROVED'])){

				$_SESSION['acctype']	=	'seeker';

				$_SESSION['cid'] 		= $row['UID'];

				$_SESSION['fname'] 		= $row['FIRSTNAME'];

				$_SESSION['lname'] 		= $row['LASTNAME'];

				$_SESSION['addr1'] 		= $row['STREETADDRESS1'];

				$_SESSION['addr2'] 		= $row['STREETADDRESS2'];

				$_SESSION['city'] 		= $row['CITY'];

				$_SESSION['state'] 		= $row['STATE'];

				$_SESSION['zip'] 		= $row['ZIP'];

				$_SESSION['phone'] 		= $row['PHONE'];

				$_SESSION['email'] 		= $row['EMAIL'];

				$_SESSION['website'] 	= $row['WEBSITE'];

				$_SESSION['username'] 	= $row['USERNAME'];
				
				$_SESSION['birthdate'] 		= $row['BIRTHDATE'];
				
				$_SESSION['photosmall'] 	= $row['PHOTOSMALL'];
				
				$_SESSION['photobig'] 		= $row['PHOTOBIG'];	
				

			}else{

				$loginerror = 'Username and Password are not matched or not listed';

			}

		} else {

			$loginerror = 'Username and Password are not matched or not listed';

		}

	} 

}



if(!isset($_SESSION['acctype'])){

				$_SESSION['acctype']	= '';

				$_SESSION['cid'] 		= '';

				$_SESSION['fname'] 		= '';

				$_SESSION['lname'] 		= '';

				$_SESSION['addr1'] 		= '';

				$_SESSION['addr2'] 		= '';

				$_SESSION['city'] 		= '';

				$_SESSION['state'] 		= '';

				$_SESSION['zip'] 		= '';

				$_SESSION['phone'] 		= '';

				$_SESSION['email'] 		= '';

				$_SESSION['website'] 	= '';

				$_SESSION['username'] 	= '';
				
				$_SESSION['birthdate'] 	= '';

				$_SESSION['photosmall'] = '';
				
				$_SESSION['photobig'] 	= '';
					

}



if (!$_SESSION['cid']){
  
    if ($acct == 'emp'){
	$loginfrm = file_get_contents('mjl-themes/'.$theme.'/loginfrm.theme.html');
	} else {
	$loginfrm = file_get_contents('mjl-themes/'.$theme.'/seeker_loginfrm.theme.html');
    }
	$usermenu = '';
	$imagelogout = '';

} elseif ($_SESSION['cid'] && ($_SESSION['acctype'] == 'employer')){

	$loginfrm = '';
	$pagedata = file_get_contents('mjl-themes/'.$theme.'/company_home.theme.html');
	$usermenu = file_get_contents('mjl-themes/'.$theme.'/employermenu.theme.html');
	$imagelogout = 'imagelogout';
	$greetings = "Welcome ".$_SESSION['companyname'];
	$coname = $_SESSION['companyname'];
	$photo =  $_SESSION['photobig'];

} elseif ($_SESSION['cid'] && ($_SESSION['acctype'] == 'seeker')){

	$loginfrm = '';
	$pagedata = file_get_contents('mjl-themes/'.$theme.'/seeker_home.theme.html');
	$usermenu = file_get_contents('mjl-themes/'.$theme.'/seekermenu.theme.html');
	$greetings = "Welcome ".$_SESSION['fname']." ".$_SESSION['lname'];
	$usermenu = str_replace('%SESSID%',session_id(),$usermenu);
	$imagelogout = 'imagelogout';
	$coname 	= $_SESSION['fname']." ".$_SESSION['lname'];
	$photo 		= $_SESSION['photobig'];
	$firstname 	= $_SESSION['fname'];
	$lastname 	= $_SESSION['lname'];
	$add1		= $_SESSION['addr1'];
	$add2		= $_SESSION['addr2'];
	$city		= $_SESSION['city'];
	$state		= $_SESSION['state'];
	$zip		= $_SESSION['zip'];
	$phone		= $_SESSION['phone'];
	//$fax		= $_SESSION['fax'];
	$email		= $_SESSION['email'];
}

// ==========================================================	

// ==========================================================	

// ==========================================================

if(isset($_GET['page']) && is_numeric($_GET['page'])){

	$page = $_GET['page'];

}else{

	$page = 0;

}

if (isset($_GET['num_per_page']) && is_numeric($_GET['num_per_page'])){

	$num_per_page = $_GET['num_per_page'];

}

if (!($num_per_page)){

	$num_per_page = num_per_page;

}

// ==========================================================	

// ==========================================================	

// ==========================================================



if (isset($_GET['id']) && is_numeric($_GET['id'])){

	$id = htmlspecialchars($_GET['id'], ENT_QUOTES);

}

if (isset($_GET['cat']) && !empty($_GET['cat'])){

	$cat = htmlspecialchars($_GET['cat'],ENT_QUOTES);

}else{ $cat = '%'; };



	switch ($loc){

		case 'view':

			$jid = htmlspecialchars($_GET['jid'],ENT_QUOTES);
			
			if (isset($_GET['act']) && !empty($_GET['act'])){

				$act = htmlspecialchars($_GET['act'],ENT_QUOTES);

				if($act == 'app'){

					if($_SESSION['cid'] && ($_SESSION['acctype'] == 'seeker')){

						$pagedata = SHOW_EMAIL_FORM($jid);

					}else{

						$pagedata = '<div class="errorbox">You must be logged in with a job seeker account to use this feature.</div>'.FUNCT_JOB_DATA($jid);

					}

				}else{

					$pagedata = FUNCT_JOB_DATA($jid);

				}

			} elseif($_SERVER['REQUEST_METHOD'] == 'POST'){

				$coid = $_POST['cid'];

				$jtitle = $_POST['jtitle'];

				$opmsg = $_POST['opmsg'];

				$covselect = $_POST['covselect'];

				$reselect = $_POST['reselect'];

				

				if(FUNCT_SEND_APP($coid,$jtitle,$opmsg,$covselect,$reselect)){
				
					mysql_query('INSERT INTO table_apply_jobs (UID,JOBID,DATEPOSTED) VALUES ('.$_SESSION['cid'].','.$jid.',NOW())') or die();

					$pagedata = '<div class="errorbox">Thank you, the message has been sent.</div>'.FUNCT_JOB_DATA($jid);

				}else{

					$pagedata = '<div class="errorbox">There was an error please contact support.</div>'.FUNCT_JOB_DATA($jid);

				}

			}else{

				$pagedata = FUNCT_JOB_DATA($jid);
				mysql_query('INSERT INTO table_view_jobs (UID,JOBID,DATEPOSTED) VALUES ('.$_SESSION['cid'].','.$jid.',NOW())') or die();	

			}

			break;

		case 'employer':

			break;

		case 'profile':

			$eid = htmlspecialchars($_GET['eid'],ENT_QUOTES);

			$pagedata = SOW_CO_PROFILE($eid);

			break;

		case 'search':

			if($_SERVER['REQUEST_METHOD'] == 'POST'){

				if(isset($_POST['searchstuff']) && !empty($_POST['searchstuff'])){

					$searching = $_POST['searchstuff'];

				}

				if(isset($_GET['search']) && !empty($_GET['search'])){

					$searching = base64_decode($_GET['search']);

				}

				$pagedata = BASIC_SEARCH($searching,$num_per_page,$page);

			}
			
			$greetings = "Find A Job Vacancy";

			break;

		case 'lpw':

			if ($_SERVER['REQUEST_METHOD'] == 'POST'){

				if (isset($_POST['email']) && !empty($_POST['email'])){

					$youremail = htmlspecialchars($_POST['email'], ENT_QUOTES);

					if ($_POST['usertype'] == 'seeker'){

						$pagedata = FUNCT_EMPLOYEE_RECOVER_PW($youremail);

					}else if ($_POST['usertype'] == 'employer'){

						$pagedata = FUNCT_EMPLOYER_RECOVER_PW($youremail);

					}else{

						$pagedata = '<div class="errorbox">You must select if you are a job seeker or an employer.</div>'.file_get_contents('mjl-themes/'.$theme.'/change_pw_form.theme.html');

					}

				}else{

					$pagedata = '<div class="errorbox">You must enter an email address.</div>'.file_get_contents('mjl-themes/'.$theme.'/change_pw_form.theme.html');

				}

			}else{

				$pagedata = file_get_contents('mjl-themes/'.$theme.'/change_pw_form.theme.html');

			}

			break;

		case 'cview':

			FUNCT_SHOW_COVER($id);

			break;

		case 'rview':

			FUNCT_SHOW_RESUME($id);

			break;

		case 'jview':

			FUNCT_SHOW_JOB($id);

			break;
			
			
		case 'cat':
		
			$pagedata = FUNCT_JOB_DATA_LIST_SRC($num_per_page,$page,$cat);
			
			break;
			
		case 'mjcat':
		
			$pagedata = FUNCT_JOB_DATA_LIST_MATCHJOB($num_per_page,$page,$cat);
			
			$greeting = "Matching Job";
			
			break;
		
		default:			
			
			//$pagedata = FUNCT_JOB_DATA_LIST($num_per_page,$page,$cat);
			
}



if($_SESSION['cid'] && ($_SESSION['acctype'] == 'employer')){

	if (($_SERVER['REQUEST_METHOD'] == 'POST') && ($loc == 'enter') || ($loc == 'ed')){

			$jobtitle		= htmlspecialchars(@$_POST['jobtitle'], ENT_QUOTES);

			$joblocation 	= htmlspecialchars(@$_POST['joblocation'], ENT_QUOTES);

			$jobdesc		= htmlspecialchars(@$_POST['jobdescription'], ENT_QUOTES);

			$experience		= htmlspecialchars(@$_POST['jobrequirements'], ENT_QUOTES);

			$edreq			= htmlspecialchars(@$_POST['edrequirements'], ENT_QUOTES);

			$benefits		= htmlspecialchars(@$_POST['benefits'], ENT_QUOTES);

			$category		= htmlspecialchars(@$_POST['categories'], ENT_QUOTES);

			$howtoapp[]		= @$_POST['howapply'];

			$jobdur			= htmlspecialchars(@$_POST['jobduration'], ENT_QUOTES);

			$jobtype		= htmlspecialchars(@$_POST['jobtype'], ENT_QUOTES);
			
			$joblevel		= htmlspecialchars(@$_POST['joblevel'], ENT_QUOTES);

			$wagetype		= htmlspecialchars(@$_POST['wagetype'], ENT_QUOTES);

			$serializedhowto = serialize(@$howtoapp);

			$serializedhowto = addslashes(@$serializedhowto);

	}

	switch ($loc){

		case 'esrch':

			$pagedata = FUNCT_EMPLOYEE_SEARCH_FORM();

			if($_SERVER['REQUEST_METHOD'] == 'POST'){

				if(isset($_POST['searchstuff']) && !empty($_POST['searchstuff'])){

					$searching = $_POST['searchstuff'];

				}
				

				if(isset($_GET['search']) && !empty($_GET['search'])){

					$searching = base64_decode($_GET['search']);

				}

				$pagedata = FUNCT_EMPLOYEE_SEARCH($searching,$num_per_page,$page);

			} 
			elseif  (isset($_GET['search']) && !empty($_GET['search'])){

					$searching = base64_decode($_GET['search']);

			

				$pagedata = FUNCT_EMPLOYEE_SEARCH($searching,$num_per_page,$page);
				
				}

			$greetings = "Resume Search";

			break;

		case 'ena':

			$jid = htmlspecialchars($_GET['jid'],ENT_QUOTES);

			

			mysql_query('UPDATE jobs SET DATEPOSTED = NOW(),ISENABLED = 1 WHERE ISENABLED = 0 AND JID = '.$jid.' AND CID = '.$_SESSION['cid'].' LIMIT 1');

				if(mysql_errno()){

					$statusmsg =  '<div id="tcord"><div class="window"><a href="#tcord" class="close-button" title="Close">X</a><p>There was an error making the position active. Please contact support. (<span class="error">Error: '.mysql_errno().', '.mysql_error().'</span>)</div></div>';

				}else{

					$statusmsg =  '<div id="tcord"><div class="window"><a href="#tcord" class="close-button" title="Close">X</a><p>Position is active.</p></div></div>';

				}	

			$pagedata = $statusmsg.FUNCT_JOB_LIST();						

			break;

		case 'list':

			$pagedata = $statusmsg.FUNCT_JOB_LIST();

			break;

		case 'paklist':

			$pagedata = $statusmsg.FUNCT_PACKAGE_LIST();
			
			$greetings = "CordPackage List";

			break;

		case 'ed':

			$jid = htmlspecialchars($_GET['jid'],ENT_QUOTES);

			if (!empty($jobtitle) && !empty($category)){

				mysql_query('UPDATE jobs SET CATID="'.$category.'",JOBTITLE="'.$jobtitle.'",JOBLOCATION="'.$joblocation.'",JOBDESCRIPTION="'.$jobdesc.'",JOBREQUIREMENTS="'.$experience.'",EDREQUIREMENTS="'.$edreq.'",BENEFITS="'.$benefits.'",DATEPOSTED=NOW(),HOWTOAPPLY="'.$serializedhowto.'",JOBDURATION="'.$jobdur.'",JOBTYPE="'.$jobtype.'",JOBLEVEL="'.$joblevel.'",WAGETYPE="'.$wagetype.'" WHERE JID = '.$jid.' AND CID = '.$_SESSION['cid'].' LIMIT 1');

				if(mysql_errno()){

					$statusmsg =  '<div class="errorbox">There was an error updating the position. Please contact support. (<span class="error">Error: '.mysql_errno().', '.mysql_error().'</span>)</div>';

				}else{

					$statusmsg =  '<div id="tcord"><div class="window"><a href="#tcord" class="close-button" title="Close">X</a><p>Position has been updated.</p></div></div>';

				}				 

			}

			$pagedata = $statusmsg.FUNCT_EDIT_JOB_FORM($jid);

			break;

		case 'del':

			$jid = htmlspecialchars($_GET['jid'], ENT_QUOTES);

			

			mysql_query('DELETE FROM jobs WHERE JID = '.$jid.' AND CID = '.$_SESSION['cid'].' LIMIT 1');

			if(mysql_errno()){

				$statusmsg =  '<div class="errorbox">There was an error deleting the position. Please contact support. (<span class="error">Error: '.mysql_errno().', '.mysql_error().'</span>)</div>';

			}else{

				$statusmsg =  '<div id="tcord"><div class="window"><a href="#tcord" class="close-button" title="Close">X</a><p>The position has been deleted.</p></div></div>';

			}

			$pagedata = $statusmsg.FUNCT_JOB_LIST();

			break;

		case 'enter':

			if (($_SERVER['REQUEST_METHOD'] == 'POST') && $_SESSION['cid'] && isset($_POST['jobtitle']) && !empty($_POST['jobtitle'])){

				$result = mysql_query('INSERT INTO jobs (CID,CATID,JOBTITLE,JOBLOCATION,JOBDESCRIPTION,JOBREQUIREMENTS,EDREQUIREMENTS,BENEFITS,DATEPOSTED,HOWTOAPPLY,JOBDURATION,JOBTYPE,JOBLEVEL,WAGETYPE,NUMDAYS) VALUES ('.$_SESSION['cid'].',"'.$category.'","'.$jobtitle.'","'.$joblocation.'","'.$jobdesc.'","'.$experience.'","'.$edreq.'","'.$benefits.'",NOW(),"'.$serializedhowto.'","'.$jobdur.'","'.$jobtype.'","'.$joblevel.'","'.$wagetype.'",60)');

				

				if(mysql_errno()){

					$statusmsg =  '<div class="errorbox">There was an error adding the position. Please contact support. (<span class="error">Error: '.mysql_errno().', '.mysql_error().'</span>)</div>';

				}else{

					$statusmsg =  '<div id="tcord"><div class="window"><a href="#tcord" class="close-button" title="Close">X</a><p>Position has been added.</p></div></div>';

				}

			

			}

			$pagedata = $statusmsg.FUNCT_POST_JOB_FORM();

			break;


		case 'report':


			$pagedata = $statusmsg.FUNCT_JOB_LIST_REPORT();
			
			$greetings = "Talentcord Statistic Report";

			break;
			
			
		case 'pw':

			if (($_SERVER['REQUEST_METHOD'] == 'POST') && !empty($_POST['pw0']) && !empty($_POST['pw1']) && !empty($_POST['pw2'])){

				$pw0 = htmlspecialchars($_POST['pw0'], ENT_QUOTES);

				$pw1 = htmlspecialchars($_POST['pw1'], ENT_QUOTES);

				$pw2 = htmlspecialchars($_POST['pw2'], ENT_QUOTES);

				if(strlen($pw1) < 6){

					$statusmsg =  '<div id="tcord"><div class="window"><a href="#tcord" class="close-button" title="Close">X</a><p>Password must be at least 6 characters</p></div></div>';

				} else {

					if($pw1 == $pw2){

						$result = mysql_query('SELECT * FROM companies WHERE CID = '.$_SESSION['cid'].' AND PASSWORD = "'.md5($pw0).'" LIMIT 1');

						$cnt = mysql_num_rows($result);

						if ($cnt == 0){

							$statusmsg =  '<div id="tcord"><div class="window"><a href="#tcord" class="close-button" title="Close">X</a><p>Entered password does not match your current password.</p></div></div>';

						}else{

							mysql_query('UPDATE companies SET PASSWORD = "'.md5($pw1).'" WHERE CID = '.$_SESSION['cid'].' AND PASSWORD = "'.md5($pw0).'"');

							if(mysql_errno()){

								$statusmsg =  '<div class="errorbox">There was an error updating the password. Please contact support. (<span class="error">Error: '.mysql_errno().', '.mysql_error().'</span>)</div>';

							}else{

								$statusmsg =  '<div id="tcord"><div class="window"><a href="#tcord" class="close-button" title="Close">X</a><p>Password has been changed.</p></div></div>';

							} 

						}

					}else{

						$statusmsg =  '<div id="tcord"><div class="window"><a href="#tcord" class="close-button" title="Close">X</a><p>The new passwords do not match.</p></div></div>';

					}

				}

			}

			$pagedata = $statusmsg.FUNCT_COMPANY_PASSWORD_FRM();

			break;

		case 'coprofile':

			if (($_SERVER['REQUEST_METHOD'] == 'POST') && isset($_POST['coname']) && !empty($_POST['coname']) && isset($_POST['cid']) && ($_POST['cid'] == $_SESSION['cid'])){

				$cname	= htmlspecialchars($_POST['coname'], ENT_QUOTES);

				$add1	= htmlspecialchars($_POST['add1'], ENT_QUOTES);

				$add2	= htmlspecialchars($_POST['add2'], ENT_QUOTES);

				$city	= htmlspecialchars($_POST['city'], ENT_QUOTES);

				$state	= htmlspecialchars($_POST['state'], ENT_QUOTES);

				$zip	= htmlspecialchars($_POST['zip'], ENT_QUOTES);

				$phone	= htmlspecialchars($_POST['phone'], ENT_QUOTES);

				$fax	= htmlspecialchars($_POST['fax'], ENT_QUOTES);

				$name	= htmlspecialchars($_POST['contact'], ENT_QUOTES);

				$email	= htmlspecialchars($_POST['email'], ENT_QUOTES);

				$title	= htmlspecialchars($_POST['title'], ENT_QUOTES);

				$webste	= htmlspecialchars($_POST['website'], ENT_QUOTES);

				$about	= htmlspecialchars($_POST['about'], ENT_QUOTES);
				
				$rnd_name	=	htmlspecialchars($_POST['rnd_name'], ENT_QUOTES);
					
				$rnd_name1	=	htmlspecialchars($_POST['rnd_name1'], ENT_QUOTES);
				
				$error = '';
				$success = '';
				 
				// get uploaded file name
				$image = $_FILES["file"]["name"];
				 
				if( empty( $image ) ) {
				$error = 'File is empty, please select image to upload.';
				} else if($_FILES["file"]["type"] == "application/msword") {
				$error = 'Invalid image type, use (e.g. png, jpg, gif).';
				} else if( $_FILES["file"]["error"] > 0 ) {
				$error = 'Oops sorry, seems there is an error uploading your image, please try again later.';
				} else {
				// strip file slashes in uploaded file, although it will not happen but just in case ;)
				$filename = stripslashes( $_FILES['file']['name'] );
				$ext = get_file_extension( $filename );
				$ext = strtolower( $ext );
				if(( $ext != "jpg" ) && ( $ext != "jpeg" ) && ( $ext != "png" ) && ( $ext != "gif" ) ) {
				$error = 'Unknown Image extension.';
				return false;
				} else {
				// get uploaded file size
				$size = filesize( $_FILES['file']['tmp_name'] );
				// get php ini settings for max uploaded file size
				$max_upload = ini_get( 'upload_max_filesize' );
				 
				// check if we're able to upload lessthan the max size
				if( $size > $max_upload )
				$error = 'You have exceeded the upload file size.';
				 
				// check uploaded file extension if it is jpg or jpeg, otherwise png and if not then it goes to gif image conversion
				$uploaded_file = $_FILES['file']['tmp_name'];
				if( $ext == "jpg" || $ext == "jpeg" )
				$source = imagecreatefromjpeg( $uploaded_file );
				else if( $ext == "png" )
				$source = imagecreatefrompng( $uploaded_file );
				else
				$source = imagecreatefromgif( $uploaded_file );
				 
				// getimagesize() function simply get the size of an image
				list( $width, $height) = getimagesize( $uploaded_file );
				$ratio = $height / $width;
				 
				// new width 50(this is in pixel format)
				$nw = 50;
				$nh = ceil( $ratio * $nw );
				$dst = imagecreatetruecolor( $nw, $nh );
				 
				// new width 144 in pixel format too
				$nw1 = 144;
				$nh1 = ceil( $ratio * $nw1 );
				$dst1 = imagecreatetruecolor( $nw1, $nh1 );
				 
				imagecopyresampled( $dst, $source, 0, 0, 0,0, $nw, $nh, $width, $height );
				imagecopyresampled( $dst1, $source, 0, 0, 0, 0, $nw1, $nh1, $width, $height );
				 
				// rename our upload image file name, this to avoid conflict in previous upload images
				// to easily get our uploaded images name we added image size to the suffix
				$rnd_name = 'photos_'.uniqid(mt_rand(10, 15)).'_'.time().'_50x50.'.$ext;
				$rnd_name1 = 'photos_'.uniqid(mt_rand(10, 15)).'_'.time().'_144x144.'.$ext;
				// move it to uploads dir with full quality
				imagejpeg( $dst, 'uploads/'.$rnd_name, 100 );
				imagejpeg( $dst1, 'uploads/'.$rnd_name1, 100 );
				
				//Set Session
				$_SESSION['photosmall'] = $rnd_name;
				$_SESSION['photobig'] 	= $rnd_name1;
				 
				// I think that's it we're good to clear our created images
				imagedestroy( $source );
				imagedestroy( $dst );
				imagedestroy( $dst1 );
				 
				// so all clear, lets save our image to database
				//$is_uploaded = mysql_query( "INSERT INTO attachment(photosmall, photobig) VALUES('$rnd_name', '$rnd_name1')" ) or die('erroror inserting data '. mysql_errno());
				// check if it uploaded successfully, if so then display success message otherwise the erroror message in the else statement
				//if( $is_uploaded )
				//$success = 'Post shared successfully.';
				//else
				//$error = 'error uploading file.';
				 
				}
				 
				}

				$result = mysql_query('UPDATE companies SET COMPANYNAME="'.$cname.'",MAILADDRESS1="'.$add1.'",MAILADDRESS2="'.$add2.'",CITY="'.$city.'",STATE="'.$state.'",ZIP="'.$zip.'",PHONE="'.$phone.'",FAX="'.$fax.'",CONTACT="'.$name.'",CONTACTEMAIL="'.$email.'",CONTACTTITLE="'.$title.'",WEBSITE="'.$webste.'",ABOUT="'.$about.'",PHOTOSMALL = "'.$rnd_name.'", PHOTOBIG = "'.$rnd_name1.'" WHERE CID = '.$_SESSION['cid']);

				if(mysql_errno()){

					$statusmsg =  '<div class="errorbox">There was an error updating your Company profile. Please contact support. (<span class="error">Error: '.mysql_errno().', '.mysql_error().'</span>)</div>';

				}else{

					$statusmsg =  '<div id="tcord"><div class="window"><a href="#tcord" class="close-button" title="Close">X</a><p>Company profile has been updated</p></div></div>';

				}

			}

			$pagedata = $statusmsg.FUNCT_COMPANY_PROFILE();
			$greetings = "Company Profile";
			break;

		case 'messages':

			$statusmsg = '';

			

			$act = htmlspecialchars(@$_GET['act'],ENT_QUOTES);

			

			if (isset($_GET['a']) && !empty($_GET['a'])){

				$ar = htmlspecialchars($_GET['a'],ENT_QUOTES);

				$mid = htmlspecialchars($_GET['mid'],ENT_QUOTES);

				if(($ar == 'del') && is_numeric($mid)){

					// set company id to nothing specific so will delete from the list and still

					// be in the users list. 

					mysql_query('UPDATE mail SET TCID = -2, FCID = -2 WHERE ID='.$mid.' AND (TCID = '.$_SESSION['cid'].' OR FCID = '.$_SESSION['cid'].') LIMIT 1') or die();

					// now verify that the msg ids are not all -1 if so then delete the

					// line of code.

					mysql_query('DELETE FROM mail WHERE ID = '.$mid.' AND TUID = -2 AND FUID = -2 AND TCID = -2 AND FCID = -2 LIMIT 1') or die();

					$statusmsg = '<div id="tcord"><div class="window"><a href="#tcord" class="close-button" title="Close">X</a><p>Item has been deleted</p></div></div>';

				}

			}  

			$pagedata = $statusmsg.FUNCT_EMPLOYER_EMAIL($num_per_page,$page,$act);
			$greetings = "Applications";

			break;

		case 'msg':

			$statusmsg = "";

			$act  = htmlspecialchars($_GET['act'],ENT_QUOTES);

			if(isset($_GET['a']) && !empty($_GET['a'])){

				$a = htmlspecialchars($_GET['a'],ENT_QUOTES);

			}else{

				$a = '';

			}

			if (($_SERVER['REQUEST_METHOD'] == 'POST') && isset($_POST['touser']) && !empty($_POST['touser'])){

				$tuid = htmlspecialchars($_POST['touser'], ENT_QUOTES);

				$tsbj = htmlspecialchars($_POST['tsbj'], ENT_QUOTES);

				$tmsg = htmlspecialchars($_POST['myreply'], ENT_QUOTES);

				

				mysql_query('INSERT INTO mail (TUID,FCID,UID,CID,SUBJECT,MESSAGE,SENTDATE) VALUES ('.$tuid.','.$_SESSION['cid'].','.$tuid.','.$_SESSION['cid'].',"'.$tsbj.'","'.$tmsg.'",NOW())') or die();

				$statusmsg = '<div id="tcord"><div class="window"><a href="#tcord" class="close-button" title="Close">X</a><p>Mail has been sent.</p></div></div>';

			}

			$pagedata = $statusmsg.FUNCT_SHOW_EMPLOYER_MSG($act,$a);

			break;



	}

}elseif($_SESSION['cid'] && ($_SESSION['acctype'] == 'seeker')){

	$statusmsg = '';

	switch ($loc){

		case 'resumes':

			if (isset($_GET['act']) && is_numeric($_GET['act'])){

				$act = htmlspecialchars($_GET['act'], ENT_QUOTES);
				
				switch ($act){

					case 1:  // add a resume

						if($_SERVER['REQUEST_METHOD'] == 'POST'){

							$restit = htmlspecialchars($_POST['resumetitle'], ENT_QUOTES);

							$resbod = htmlspecialchars($_POST['resumebody'], ENT_QUOTES);

							$searchable = 'CHECKED';

							if ( htmlspecialchars($_POST['searchable'], ENT_QUOTES) == 'on') {

								$searchable = 'CHECKED';

							}

							$result = mysql_query('SELECT * FROM resumes WHERE UID = '.$_SESSION['cid']);

							$cnt = mysql_num_rows($result);

							if ($cnt < $numresumes){

								$result = mysql_query('INSERT INTO resumes (UID,RESUMETITLE,RESUMEBODY,SEARCHABLE,DATEPOSTED) VALUES ('.$_SESSION['cid'].',"'.$restit.'","'.$resbod.'","'.$searchable.'",NOW())');

								if(mysql_errno()){

									$statusmsg =  '<div id="tcord"><div class="window"><a href="#tcord" class="close-button" title="Close">X</a><p>There was an error inserting your resume. Please contact support. (<span class="error">Error: '.mysql_errno().', '.mysql_error().'</span>)</p></div></div>';

									$pagedata = $statusmsg.FUNCT_RESUME_LIST();

								}else{

									$statusmsg = '<div id="tcord"><div class="window"><a href="#tcord" class="close-button" title="Close">X</a><p>The resume has been added successfully.</p></div></div>';

									$pagedata = $statusmsg.FUNCT_RESUME_LIST();

								}

							}else{

								$statusmsg = '<div id="tcord"><div class="window"><a href="#tcord" class="close-button" title="Close">X</a><p>You may only enter '.$numresumes.' resumes.</span></p></div></div>';

							}

						}else{

							$pagedata = $statusmsg.FUNCT_RESUME_FORM();

						}

						break;

					case 2:  // edit a resume

						if(is_numeric($_GET['id'])){

							$id = htmlspecialchars($_GET['id'], ENT_QUOTES);

							if($_SERVER['REQUEST_METHOD'] == 'POST'){

								$restit = htmlspecialchars($_POST['resumetitle'], ENT_QUOTES);

								$resbod = htmlspecialchars($_POST['resumebody'], ENT_QUOTES);

								$searchable = 'CHECKED';

								if ( htmlspecialchars(@$_POST['searchable'], ENT_QUOTES) == 'on') {

									$searchable = 'CHECKED';

								}

								

								mysql_query('UPDATE resumes SET RESUMETITLE = "'.$restit.'", RESUMEBODY = "'.$resbod.'", SEARCHABLE="'.$searchable.'", DATEPOSTED = NOW() WHERE ID = '.$id.' AND UID = '.$_SESSION['cid'].' LIMIT 1');

								if(mysql_errno()){

									$statusmsg =  '<div class="errorbox">There was an error updating the resume. Please contact support. (<span class="error">Error: '.mysql_errno().', '.mysql_error().'</span>)</div>';

									$pagedata = $statusmsg.FUNCT_RESUME_LIST();

								}else{

									$statusmsg =  '<div id="tcord"><div class="window"><a href="#tcord" class="close-button" title="Close">X</a><p>Resume updated successfully.</p></div></div>';

									$pagedata = $statusmsg.FUNCT_RESUME_LIST();

								}

								

							}else{

								$pagedata = $statusmsg.FUNCT_EDIT_RESUME_FORM($id);

							}

						}

						break;

					case 3:  //  delete a resume

						if(is_numeric($_GET['id'])){

							$id = htmlspecialchars($_GET['id'], ENT_QUOTES);

							mysql_query('DELETE FROM resumes WHERE UID = '.$_SESSION['cid'].' AND ID = '.$id.' LIMIT 1');

							if(mysql_errno()){

								$statusmsg =  '<div class="errorbox">There was an error attempting to delete the resume. Please contact support. (<span class="error">Error: '.mysql_errno().', '.mysql_error().'</span>)</div>';

							}else{

								$statusmsg =  '<div id="tcord"><div class="window"><a href="#tcord" class="close-button" title="Close">X</a><p>Resume deleted successfully.</p></div></div>';

								$pagedata = $statusmsg.FUNCT_RESUME_LIST();

							}

						}

						break;
						
					case 4:  // view a resume request

							$pagedata = $statusmsg.FUNCT_RESUME_VIEW();

						break;
						
					case 5:  // view a resume request detail
					
							$vuid = $_SESSION['cid'];

							$pagedata = $statusmsg.FUNCT_RESUME_VIEW_DETAIL($vuid);

					break;						
						
												

				}

			}else{

				$pagedata = $statusmsg.FUNCT_RESUME_LIST();

			}

			$greetings = "Resume Request";
			
			break;

		case 'covers':

			if (isset($_GET['act']) && is_numeric($_GET['act'])){

				$act = htmlspecialchars($_GET['act'], ENT_QUOTES);

				switch ($act){

					case 1:  // add a cover letter

						if($_SERVER['REQUEST_METHOD'] == 'POST'){

							$covtit = htmlspecialchars($_POST['covertitle'], ENT_QUOTES);

							$covbod = htmlspecialchars($_POST['coverbody'], ENT_QUOTES);

							$searchable = '';

							if ( htmlspecialchars(@$_POST['searchable'], ENT_QUOTES) == 'on') {

								$searchable = 'CHECKED';

							}

							$result = mysql_query('SELECT * FROM covers WHERE UID = '.$_SESSION['cid']);

							$cnt = mysql_num_rows($result);

							if ($cnt < $numcovers){

								$result = mysql_query('INSERT INTO covers (UID,COVERTITLE,COVERBODY,SEARCHABLE,DATEPOSTED) VALUES ('.$_SESSION['cid'].',"'.$covtit.'","'.$covbod.'","'.$searchable.'",NOW())');

								if(mysql_errno()){

									$statusmsg =  '<div class="errorbox">There was an error inserting your cover letter. Please contact support. (<span class="error">Error: '.mysql_errno().', '.mysql_error().'</span>)</div>';

									$pagedata = $statusmsg.FUNCT_COVER_LIST();

								}else{

									$statusmsg = '<div class="errorbox">The cover letter has been added successfully.</div>';

									$pagedata = $statusmsg.FUNCT_COVER_LIST();

								}

							}else{

								$statusmsg = '<div class="errorbox">You may only enter '.$numcovers.' cover letter.</span></div>';

							}

						}else{

							$pagedata = $statusmsg.FUNCT_COVER_FORM();

						}

						break;

					case 2:  // edit a cover letter

						if(is_numeric($_GET['id'])){

							$id = htmlspecialchars($_GET['id'], ENT_QUOTES);

							if($_SERVER['REQUEST_METHOD'] == 'POST'){

								$covtit = htmlspecialchars($_POST['covertitle'], ENT_QUOTES);

								$covbod = htmlspecialchars($_POST['coverbody'], ENT_QUOTES);

								$searchable = '';

								if ( htmlspecialchars($_POST['searchable'], ENT_QUOTES) == 'on') {

									$searchable = 'CHECKED';

								}

															

								mysql_query('UPDATE covers SET COVERTITLE = "'.$covtit.'", COVERBODY = "'.$covbod.'", SEARCHABLE= "'.$searchable.'", DATEPOSTED = NOW() WHERE ID = '.$id.' AND UID = '.$_SESSION['cid'].' LIMIT 1');

								if(mysql_errno()){

									$statusmsg =  '<div class="errorbox">There was an error updating the cover letter. Please contact support. (<span class="error">Error: '.mysql_errno().', '.mysql_error().'</span>)</div>';

									$pagedata = $statusmsg.FUNCT_COVER_LIST();

								}else{

									$statusmsg =  '<div class="errorbox">Cover letter updated successfully.</div>';

									$pagedata = $statusmsg.FUNCT_COVER_LIST();

								}

								

							}else{

								$pagedata = $statusmsg.FUNCT_EDIT_COVER_FORM($id);

							}

						}

						break;

					case 3:  //  delete a cover letter

						if(is_numeric($_GET['id'])){

							$id = htmlspecialchars($_GET['id'], ENT_QUOTES);

							mysql_query('DELETE FROM covers WHERE UID = '.$_SESSION['cid'].' AND ID = '.$id.' LIMIT 1');

							if(mysql_errno()){

								$statusmsg =  '<div class="errorbox">There was an error attempting to delete the cover letter. Please contact support. (<span class="error">Error: '.mysql_errno().', '.mysql_error().'</span>)</div>';

							}else{

								$statusmsg =  '<div class="errorbox">Cover letter deleted successfully.</div>';

								$pagedata = $statusmsg.FUNCT_COVER_LIST();

							}

						}

						break;

				}

			}else{

				$pagedata = $statusmsg.FUNCT_COVER_LIST();

			}
			
			$greetings = "Cover Letter";
			
			break;

		case 'pw':

			if (($_SERVER['REQUEST_METHOD'] == 'POST') && !empty($_POST['pw0']) && !empty($_POST['pw1']) && !empty($_POST['pw2'])){

				$pw0 = htmlspecialchars($_POST['pw0'], ENT_QUOTES);

				$pw1 = htmlspecialchars($_POST['pw1'], ENT_QUOTES);

				$pw2 = htmlspecialchars($_POST['pw2'], ENT_QUOTES);

				if(strlen($pw1) < 6){

					$statusmsg =  '<div id="tcord"><div class="window"><a href="#tcord" class="close-button" title="Close">X</a><p>Password must be at least 6 characters.</p></div></div>';

				} else {

					if($pw1 == $pw2){

						$result = mysql_query('SELECT * FROM users WHERE UID = '.$_SESSION['cid'].' AND PASSWORD = "'.md5($pw0).'" LIMIT 1');



							$cnt = mysql_num_rows($result);

							if ($cnt == 0){

								$statusmsg =  '<div id="tcord"><div class="window"><a href="#tcord" class="close-button" title="Close">X</a><p>Entered password does not match your current password.</p></div></div>';

							}else{

								$result = mysql_query('UPDATE users SET PASSWORD = "'.md5($pw1).'" WHERE UID = '.$_SESSION['cid'].' LIMIT 1');

								if(mysql_errno()){

									$statusmsg =  '<div class="errorbox">There was an error updating the password. Please contact support. (<span class="error">Error: '.mysql_errno().', '.mysql_error().'</span>)</div>';

								}else{

									$statusmsg =  '<div id="tcord"><div class="window"><a href="#tcord" class="close-button" title="Close">X</a><p>Password has been changed.</p></div></div>';

								}

							

						}

					}else{

						$statusmsg =  '<div id="tcord"><div class="window"><a href="#tcord" class="close-button" title="Close">X</a><p>The new passwords do not match.</p></div></div>';

					}

				}

			}

			$pagedata = $statusmsg.FUNCT_USER_PASSWORD_FRM();		

			break;

		case 'prof':

			if (isset($_GET['act']) && is_numeric($_GET['act'])){

				if($_GET['act'] == 1){

					$statusmsg = '<div class="errorbox">Profile updated.</div>';

					$fname 	= 	htmlspecialchars($_POST['fname'], ENT_QUOTES);

					$lname 	= 	htmlspecialchars($_POST['lname'], ENT_QUOTES);

					$add1	=	htmlspecialchars($_POST['add1'], ENT_QUOTES);

					$add2	=	htmlspecialchars($_POST['add2'], ENT_QUOTES);

					$city	=	htmlspecialchars($_POST['city'], ENT_QUOTES);

					$state	=	htmlspecialchars($_POST['state'], ENT_QUOTES);

					$zip	=	htmlspecialchars($_POST['zip'], ENT_QUOTES);

					$phone	=	htmlspecialchars($_POST['phone'], ENT_QUOTES);

					$email	=	htmlspecialchars($_POST['email'], ENT_QUOTES);

					$web	=	htmlspecialchars($_POST['website'], ENT_QUOTES);
										
					$rnd_name	=	htmlspecialchars($_POST['rnd_name'], ENT_QUOTES);
					
					$rnd_name1	=	htmlspecialchars($_POST['rnd_name1'], ENT_QUOTES);
					
					$error = '';
					$success = '';
					 
					// get uploaded file name
					$image = $_FILES["file"]["name"];
					 
					if( empty( $image ) ) {
					$error = 'File is empty, please select image to upload.';
					} else if($_FILES["file"]["type"] == "application/msword") {
					$error = 'Invalid image type, use (e.g. png, jpg, gif).';
					} else if( $_FILES["file"]["error"] > 0 ) {
					$error = 'Oops sorry, seems there is an error uploading your image, please try again later.';
					} else {
					// strip file slashes in uploaded file, although it will not happen but just in case ;)
					$filename = stripslashes( $_FILES['file']['name'] );
					$ext = get_file_extension( $filename );
					$ext = strtolower( $ext );
					if(( $ext != "jpg" ) && ( $ext != "jpeg" ) && ( $ext != "png" ) && ( $ext != "gif" ) ) {
					$error = 'Unknown Image extension.';
					return false;
					} else {
					// get uploaded file size
					$size = filesize( $_FILES['file']['tmp_name'] );
					// get php ini settings for max uploaded file size
					$max_upload = ini_get( 'upload_max_filesize' );
					 
					// check if we're able to upload lessthan the max size
					if( $size > $max_upload )
					$error = 'You have exceeded the upload file size.';
					 
					// check uploaded file extension if it is jpg or jpeg, otherwise png and if not then it goes to gif image conversion
					$uploaded_file = $_FILES['file']['tmp_name'];
					if( $ext == "jpg" || $ext == "jpeg" )
					$source = imagecreatefromjpeg( $uploaded_file );
					else if( $ext == "png" )
					$source = imagecreatefrompng( $uploaded_file );
					else
					$source = imagecreatefromgif( $uploaded_file );
					 
					// getimagesize() function simply get the size of an image
					list( $width, $height) = getimagesize( $uploaded_file );
					$ratio = $height / $width;
					 
					// new width 50(this is in pixel format)
					$nw = 50;
					$nh = ceil( $ratio * $nw );
					$dst = imagecreatetruecolor( $nw, $nh );
					 
					// new width 144 in pixel format too
					$nw1 = 130;
					$nh1 = ceil( $ratio * $nw1 );
					$dst1 = imagecreatetruecolor( $nw1, $nh1 );
					 
					imagecopyresampled( $dst, $source, 0, 0, 0,0, $nw, $nh, $width, $height );
					imagecopyresampled( $dst1, $source, 0, 0, 0, 0, $nw1, $nh1, $width, $height );
					 
					// rename our upload image file name, this to avoid conflict in previous upload images
					// to easily get our uploaded images name we added image size to the suffix
					$rnd_name = 'photos_'.uniqid(mt_rand(10, 15)).'_'.time().'_50x50.'.$ext;
					$rnd_name1 = 'photos_'.uniqid(mt_rand(10, 15)).'_'.time().'_130x130.'.$ext;
					// move it to uploads dir with full quality
					imagejpeg( $dst, 'uploads/'.$rnd_name, 100 );
					imagejpeg( $dst1, 'uploads/'.$rnd_name1, 100 );
					
					//Set Session
					$_SESSION['photosmall'] = $rnd_name;
					$_SESSION['photobig'] 	= $rnd_name1;
					 
					// I think that's it we're good to clear our created images
					imagedestroy( $source );
					imagedestroy( $dst );
					imagedestroy( $dst1 );
					 
					// so all clear, lets save our image to database
					//$is_uploaded = mysql_query( "INSERT INTO attachment(photosmall, photobig) VALUES('$rnd_name', '$rnd_name1')" ) or die('erroror inserting data '. mysql_errno());
					// check if it uploaded successfully, if so then display success message otherwise the erroror message in the else statement
					//if( $is_uploaded )
					//$success = 'Post shared successfully.';
					//else
					//$error = 'error uploading file.';
					 
					}
					 
					}
					

					mysql_query('UPDATE users SET FIRSTNAME = "'.$fname.'", LASTNAME = "'.$lname.'",PHOTOSMALL = "'.$rnd_name.'", PHOTOBIG = "'.$rnd_name1.'", STREETADDRESS1 = "'.$add1.'", STREETADDRESS2 = "'.$add2.'", CITY = "'.$city.'", STATE = "'.$state.'", ZIP = "'.$zip.'", PHONE = "'.$phone.'", EMAIL = "'.$email.'", WEBSITE = "'.$web.'" WHERE UID = '.$_SESSION['cid'].' LIMIT 1');

					if(mysql_errno()){

						$statusmsg =  '<div id="tcord"><div class="window"><a href="#tcord" class="close-button" title="Close">X</a><p>There was an error updating the profile. Please contact support. (<span class="error">Error: '.mysql_errno().', '.mysql_error().'</span>)</p></div></div>';

					}else{

						$statusmsg =  '<div id="tcord"><div class="window"><a href="#tcord" class="close-button" title="Close">X</a><p>Profile has been updated.</p></div></div>';

					}

				}

			}

			$pagedata = $statusmsg.FUNCT_USER_PROFILE();
			
			$greetings = "Profile";

			break;

		case 'profview':

			if (isset($_GET['act']) && is_numeric($_GET['act'])){

				if($_GET['act'] == 2){

					$statusmsg = '<div class="errorbox">Profile updated.</div>';

					$fname 	= 	htmlspecialchars($_POST['fname'], ENT_QUOTES);

					$lname 	= 	htmlspecialchars($_POST['lname'], ENT_QUOTES);

					$add1	=	htmlspecialchars($_POST['address1'], ENT_QUOTES);

					$add2	=	htmlspecialchars($_POST['address2'], ENT_QUOTES);

					$city	=	htmlspecialchars($_POST['kotacity'], ENT_QUOTES);

					$state	=	htmlspecialchars($_POST['state'], ENT_QUOTES);

					$zip	=	htmlspecialchars($_POST['zip'], ENT_QUOTES);

					$phone	=	htmlspecialchars($_POST['phone'], ENT_QUOTES);

					$email	=	htmlspecialchars($_POST['email'], ENT_QUOTES);

					$country =	htmlspecialchars($_POST['country'], ENT_QUOTES);
					
					//--employement
					$categories = isset($_POST['categories']) ? $_POST['categories'] : '';

					$joblevel = isset($_POST['joblevel']) ? $_POST['joblevel'] : '';
					$jobtype_1 = isset($_POST['jobtype_1']) ? $_POST['jobtype_1'] : '';
					$jobtype_2 = isset($_POST['jobtype_2']) ? $_POST['jobtype_2'] : '';
					$jobtype_3 = isset($_POST['jobtype_3']) ? $_POST['jobtype_3'] : '';
					$jobtype_4 = isset($_POST['jobtype_4']) ? $_POST['jobtype_4'] : '';
					$jobtype_5 = isset($_POST['jobtype_5']) ? $_POST['jobtype_5'] : '';
					$jobtype_6 = isset($_POST['jobtype_6']) ? $_POST['jobtype_6'] : '';
					$jobtype = $jobtype_1.",".$jobtype_2.",".$jobtype_3.",".$jobtype_4.",".$jobtype_5.",".$jobtype_6;
					$compensation = isset($_POST['compensation']) ? $_POST['compensation'] : '';
					$currencyid = isset($_POST['currencyid']) ? $_POST['currencyid'] : '';
					$salarytypeid = isset($_POST['salarytypeid']) ? $_POST['salarytypeid'] : '';
					$availabilitycomments = isset($_POST['availabilitycomments']) ? $_POST['availabilitycomments'] : '';
					/* $category =	htmlspecialchars($_POST['category'], ENT_QUOTES);
					$joblevel =	htmlspecialchars($_POST['joblevel'], ENT_QUOTES);
					$jobtype =	htmlspecialchars($_POST['jobtype'], ENT_QUOTES);
					$currencyid =	htmlspecialchars($_POST['currencyid'], ENT_QUOTES);
					$salarytypeid =	htmlspecialchars($_POST['salarytypeid'], ENT_QUOTES);
					$availabilitycomments =	htmlspecialchars($_POST['availabilitycomments'], ENT_QUOTES);*/
					
					
					/*
					$workhistory_company_name[] =	htmlspecialchars($_POST['workhistory_company_name'], ENT_QUOTES);
					$workhistory_company_industry[] =	htmlspecialchars($_POST['workhistory_company_industry'], ENT_QUOTES);
					$workhistory_job_title[] =	htmlspecialchars($_POST['workhistory_job_title'], ENT_QUOTES);
					$workhistory_company_location[] =	htmlspecialchars($_POST['workhistory_company_location'], ENT_QUOTES);
					$workhistory_start_date[] =	htmlspecialchars($_POST['workhistory_start_date'], ENT_QUOTES);
					$workhistory_end_date[] =	htmlspecialchars($_POST['workhistory_end_date'], ENT_QUOTES);
					$workhistory_end_present[] =	htmlspecialchars($_POST['workhistory_end_present'], ENT_QUOTES);
					$workhistory_job_description[] =	htmlspecialchars($_POST['workhistory_job_description'], ENT_QUOTES);
					*/
					

					/*
					$eduhistory_school_name[] =	htmlspecialchars($_POST['eduhistory_school_name'], ENT_QUOTES);
					$eduhistory_school_location[] =	htmlspecialchars($_POST['eduhistory_school_location'], ENT_QUOTES);
					$eduhistory_degree_level[] =	htmlspecialchars($_POST['eduhistory_degree_level'], ENT_QUOTES);
					$eduhistory_end_date[] =	htmlspecialchars($_POST['eduhistory_end_date'], ENT_QUOTES);
					$eduhistory_school_description[] =	htmlspecialchars($_POST['eduhistory_school_description'], ENT_QUOTES);
					*/
					
					//--Referral
					$referralsource = isset($_POST['referralsource']) ? $_POST['referralsource'] : '';
					$agree2terms = isset($_POST['agree2terms']) ? $_POST['agree2terms'] : '';
					
					/*
					$referralsource =	htmlspecialchars($_POST['referralsource'], ENT_QUOTES);
					$agree2terms =	htmlspecialchars($_POST['agree2terms'], ENT_QUOTES);
					*/
					
					$error = '';
					$success = '';
					
					
					mysql_query('UPDATE users SET FIRSTNAME = "'.$fname.'", LASTNAME = "'.$lname.'", STREETADDRESS1 = "'.$add1.'", STREETADDRESS2 = "'.$add2.'", CITY = "'.$city.'", STATE = "'.$state.'", ZIP = "'.$zip.'", PHONE = "'.$phone.'", EMAIL = "'.$email.'", COUNTRY = "'.$country.'" WHERE UID = '.$_SESSION['cid'].' LIMIT 1');

					if(mysql_errno()){

						$statusmsg =  '<div class="errorbox">There was an error updating the profile. Please contact support. (<span class="error">Error: '.mysql_errno().', '.mysql_error().'</span>)</div>';

					}else{

						$statusmsg =  '<div id="tcord"><div class="window"><a href="#tcord" class="close-button" title="Close">X</a><p>Profile has been updated</p></div></div>';
					}
					
					// Insert work history Start	
					for ($xx=1; $xx<5; $xx++) {						

					//--Workhistory
					if ($xx == 1) {					
					$workhistory_company_name = isset($_POST['workhistory_company_name']) ? $_POST['workhistory_company_name'] : '';
					$workhistory_company_industry = isset($_POST['workhistory_company_industry']) ? $_POST['workhistory_company_industry'] : '';
					$workhistory_job_title = isset($_POST['workhistory_job_title']) ? $_POST['workhistory_job_title'] : '';
					$workhistory_company_location = isset($_POST['workhistory_company_location']) ? $_POST['workhistory_company_location'] : '';
					$workhistory_start_date = isset($_POST['workhistory_start_date']) ? $_POST['workhistory_start_date'] : '';
					$workhistory_end_date = isset($_POST['workhistory_end_date']) ? $_POST['workhistory_end_date'] : '';
					$workhistory_end_present = isset($_POST['workhistory_end_present']) ? $_POST['workhistory_end_present'] : '';
					$workhistory_job_description = isset($_POST['workhistory_job_description']) ? $_POST['workhistory_job_description'] : '';
					} else {
					$workhistory_company_name = isset($_POST['workhistory_company_name'.$xx]) ? $_POST['workhistory_company_name'.$xx] : '';
					$workhistory_company_industry = isset($_POST['workhistory_company_industry'.$xx]) ? $_POST['workhistory_company_industry'.$xx] : '';
					$workhistory_job_title = isset($_POST['workhistory_job_title'.$xx]) ? $_POST['workhistory_job_title'.$xx] : '';
					$workhistory_company_location = isset($_POST['workhistory_company_location'.$xx]) ? $_POST['workhistory_company_location'.$xx] : '';
					$workhistory_start_date = isset($_POST['workhistory_start_date'.$xx]) ? $_POST['workhistory_start_date'.$xx] : '';
					$workhistory_end_date = isset($_POST['workhistory_end_date'.$xx]) ? $_POST['workhistory_end_date'.$xx] : '';
					$workhistory_end_present = isset($_POST['workhistory_end_present'.$xx]) ? $_POST['workhistory_end_present'.$xx] : '';
					$workhistory_job_description = isset($_POST['workhistory_job_description'.$xx]) ? $_POST['workhistory_job_description'.$xx] : '';
					}

					
					if ($workhistory_company_name) {
					$work_start_date = date('Y-m-d',  strtotime($workhistory_start_date));
					$work_end_date = date('Y-m-d',  strtotime($workhistory_end_date));
					mysql_query('INSERT INTO table_work_history(UID, COMPANY_NAME, INDUSTRY, JOB_TITLE, CITY, START_DATE, END_DATE, PRESENT, DUTIES) VALUES ('.$_SESSION['cid'].',"'.$workhistory_company_name.'","'.$workhistory_company_industry.'","'.$workhistory_job_title.'","'.$workhistory_company_location.'","'.$work_start_date.'","'.$work_end_date.'","'.$workhistory_end_present.'","'.$workhistory_job_description.'")');
                   	
                    if(mysql_errno()){

                        $statusmsg =  '<div class="errorbox">There was an error updating the profile. Please contact support. (<span class="error">Error: '.mysql_errno().', '.mysql_error().'</span>)</div>';

                    }else{
                        $statusmsg =  '<div id="tcord"><div class="window"><a href="#tcord" class="close-button" title="Close">X</a><p>Profile has been updated</p></div></div>';
						}

					}						
					}  // End Insert Workhistory
					

					//--educationhistory
					for ($xx=1; $xx<5; $xx++) {	
										
					if ($xx == 1) {
					$edu_school_name = isset($_POST['edu_school_name']) ? $_POST['edu_school_name'] : '';
					$eduhistory_school_location = isset($_POST['eduhistory_school_location']) ? $_POST['eduhistory_school_location'] : '';
					$eduhistory_degree_level = isset($_POST['eduhistory_degree_level']) ? $_POST['eduhistory_degree_level'] : '';
					$eduhistory_end_date = isset($_POST['eduhistory_end_date']) ? $_POST['eduhistory_end_date'] : '';
					$eduhistory_school_description = isset($_POST['eduhistory_school_description']) ? $_POST['eduhistory_school_description'] : '';
						} else {
					$edu_school_name = isset($_POST['edu_school_name'.$xx]) ? $_POST['edu_school_name'.$xx] : '';
					$eduhistory_school_location = isset($_POST['eduhistory_school_location'.$xx]) ? $_POST['eduhistory_school_location'.$xx] : '';
					$eduhistory_degree_level = isset($_POST['eduhistory_degree_level'.$xx]) ? $_POST['eduhistory_degree_level'.$xx] : '';
					$eduhistory_end_date = isset($_POST['eduhistory_end_date'.$xx]) ? $_POST['eduhistory_end_date'.$xx] : '';
					$eduhistory_school_description = isset($_POST['eduhistory_school_description'.$xx]) ? $_POST['eduhistory_school_description'.$xx] : '';				
						}
					// Start Insert Eduhistory
					if ($eduhistory_end_date) {
					$edu_end_date = date('Y-m-d',  strtotime($eduhistory_end_date));

					mysql_query('INSERT INTO table_education(UID, SCHOOL_NAME, SCHOOL_LOCATION, DEGREE, GRADUATED_DATE, DESCRIPTION) VALUES ('.$_SESSION['cid'].',"'.$edu_school_name.'","'.$eduhistory_school_location.'","'.$eduhistory_degree_level.'","'.$edu_end_date.'","'.$eduhistory_school_description.'")');
						
					if(mysql_errno()){

						$statusmsg =  '<div class="errorbox">There was an error updating the profile. Please contact support. (<span class="error">Error: '.mysql_errno().', '.mysql_error().'</span>)</div>';

					}else{
						$statusmsg =  '<div id="tcord"><div class="window"><a href="#tcord" class="close-button" title="Close">X</a><p>Profile has been updated</p></div></div>';
						} 
					}// End Eduhistory
					}
					
					// Start Insert Table Resumes
					$expertise ="";
					if ($categories){
						foreach ($categories as $category){
							$expertise .= $category.';';
							}
					}										
					$query_resume = mysql_query('SELECT * FROM resumes WHERE UID = '.$_SESSION['cid']);
					if(mysql_num_rows($query_resume) == 1) { // if return 1, Resume Exist
						mysql_query('UPDATE resumes SET SEARCHABLE="CHECKED", CAREERLEVEL = "'.$joblevel.'", EXPERTISE = "'.$expertise.'", JOBPREFER = "'.$jobtype.'", SALARY = "'.$compensation.'", CURRENCY = "'.$currencyid.'", SALARYTYPE = "'.$salarytypeid.'", AVAILABILITY = "'.$availabilitycomments.'", REFERRAL = "'.$referralsource.'", TERMOFUSE = "'.$agree2terms.'" WHERE UID = '.$_SESSION['cid'].' LIMIT 1');
					} else	{
						mysql_query('INSERT INTO resumes(UID, SEARCHABLE, CAREERLEVEL, JOBPREFER, SALARY, CURRENCY, SALARYTYPE, AVAILABILITY, REFERRAL, TERMOFUSE) VALUES ('.$_SESSION['cid'].',"CHECKED","'.$joblevel.'","'.$jobtype.'","'.$compensation.'","'.$currencyid.'","'.$salarytypeid.'","'.$availabilitycomments.'","'.$referralsource.'","'.$agree2terms.'")');
					} // End Insert Resume
						
						if(mysql_errno()){
	
						$statusmsg =  '<div class="errorbox">There was an error updating the profile. Please contact support. (<span class="error">Error: '.mysql_errno().', '.mysql_error().'</span>)</div>';
	
						}else{
					   
						$statusmsg =  '<div id="tcord"><div class="window"><a href="#tcord" class="close-button" title="Close">X</a><p>Profile has been updated</p></div></div>';
					
					} // End Table Resumes
					
											
				}

				elseif($_GET['act'] == 3){ //  delete a work history

				if(is_numeric($_GET['workid'])){

					$workid = htmlspecialchars($_GET['workid'], ENT_QUOTES);

					mysql_query('DELETE FROM table_work_history WHERE UID = '.$_SESSION['cid'].' AND WORK_ID = '.$workid.' LIMIT 1');

					if(mysql_errno()){

						$statusmsg =  '<div class="errorbox">There was an error attempting to delete the Work History. Please contact support. (<span class="error">Error: '.mysql_errno().', '.mysql_error().'</span>)</div>';

					}else{	$statusmsg =  '<div id="tcord"><div class="window"><a href="#tcord" class="close-button" title="Close">X</a><p>Profile has been updated</p></div></div>';}

						}
				}  // End delete work ID
				
				
				elseif($_GET['act'] == 4){ //  delete a education

				if(is_numeric($_GET['eduid'])){

					$eduid = htmlspecialchars($_GET['eduid'], ENT_QUOTES);

					mysql_query('DELETE FROM table_education WHERE UID = '.$_SESSION['cid'].' AND EDU_ID = '.$eduid.' LIMIT 1');

					if(mysql_errno()){

						$statusmsg =  '<div class="errorbox">There was an error attempting to delete the Work History. Please contact support. (<span class="error">Error: '.mysql_errno().', '.mysql_error().'</span>)</div>';

					}else{	$statusmsg =  '<div id="tcord"><div class="window"><a href="#tcord" class="close-button" title="Close">X</a><p>Profile has been updated</p></div></div>';}

						}
				}  // End delete EDUCATION			

			}
			

			$pagedata = $statusmsg.FUNCT_USER_PROFILE_FORM();
			
			$greetings = "My Resume";

			break;

		case 'messages':

			$statusmsg = '';

			if(isset($_GET['act'])&& !empty($_GET['act'])){

				$act = htmlspecialchars($_GET['act'],ENT_QUOTES);

			}else{ $act =''; }



			if (isset($_GET['a']) && !empty($_GET['a'])){

				$ar = htmlspecialchars($_GET['a'],ENT_QUOTES);

				$mid = htmlspecialchars($_GET['mid'],ENT_QUOTES);
				
				if(($ar == 'del') && is_numeric($mid)){

					// set company id to nothing specific so will delete from the list and still

					// be in the users list. 

					mysql_query('UPDATE mail SET TUID = -2, FUID = -2 WHERE ID='.$mid.' AND (TUID = '.$_SESSION['cid'].' OR FUID = '.$_SESSION['cid'].') LIMIT 1') or die();

					// now verify that the msg ids are not all -1 if so then delete the

					// line of code.

					mysql_query('DELETE FROM mail WHERE ID = '.$mid.' AND TUID = -2 AND FUID = -2 AND TCID = -2 AND FCID = -2 LIMIT 1') or die();

					$statusmsg = '<div id="tcord"><div class="window"><a href="#tcord" class="close-button" title="Close">X</a><p>Item has been deleted</p></div></div>';

				}

			}
			
			if ($act=="sent"){
			$greetings = "Application Submitted";
			}
			elseif ($act=="inbox"){
			$greetings = "Interview Invitation";
			}
			elseif ($act=="intvw"){
			$greetings = "Upcoming Interview";
			}  

			$pagedata = $statusmsg.FUNCT_USER_EMAIL($num_per_page,$page,$act);
						

			break;

		case 'jobsearch':

			$pagedata = FUNCT_JOB_DATA_LIST($num_per_page,$page,$cat);
			
			$greetings = "Find A Job Vacancy";

			break;		
		
		
		case 'msg':

			$statusmsg = "";

			$act  = htmlspecialchars($_GET['act'],ENT_QUOTES);

			if(isset($_GET['a']) && !empty($_GET['a'])){

				$a = htmlspecialchars($_GET['a'],ENT_QUOTES);

			}else{

				$a ='';

			}

			if (($_SERVER['REQUEST_METHOD'] == 'POST') && isset($_POST['touser']) && !empty($_POST['touser'])){

				$tuid = htmlspecialchars($_POST['touser'], ENT_QUOTES);

				$tsbj = htmlspecialchars($_POST['tsbj'], ENT_QUOTES);

				$tmsg = htmlspecialchars($_POST['myreply'], ENT_QUOTES);

				$acpt  = htmlspecialchars($_GET['acpt'],ENT_QUOTES);
				
				if ($acpt == 'accept') {
				
				mysql_query('UPDATE mail set ACCEPTED = NOW() where ID ='.$act.' LIMIT 1') or die();
				
				} else {

				mysql_query('INSERT INTO mail (TCID,FUID,UID,CID,SUBJECT,MESSAGE,SENTDATE) VALUES ('.$tuid.','.$_SESSION['cid'].','.$_SESSION['cid'].','.$tuid.',"'.$tsbj.'","'.$tmsg.'",NOW())') or die();
				
				}

				$statusmsg = '<div id="tcord"><div class="window"><a href="#tcord" class="close-button" title="Close">X</a><p>Mail has been sent.</div></div>';

			}
			
			$greetings = "Application Message";

			$pagedata = $statusmsg.FUNCT_SHOW_USER_MSG($act,$a);
			
			

			break;

	}

}





$loginfrm = str_replace('%LOGINERROR%',$loginerror,$loginfrm);

$mainpage = str_replace('%JOBDATA%',$pagedata,$mainpage);

$mainpage = str_replace('%USERMENU%',$usermenu,$mainpage);



if (isset($_GET['loc']) && !empty($_GET['loc'])) {

$mainpage = str_replace('%LOGINFORM%','',$mainpage);

} else {

$mainpage = str_replace('%LOGINFORM%',$loginfrm,$mainpage);

}

if (strrpos($mainpage, '%SEARCHFORM%') > 0){

	$searchfrm = SEARCH_FORM();

	$mainpage = str_replace('%SEARCHFORM%',$searchfrm,$mainpage);

}

if (strrpos($mainpage, '%CATEGORIES1%') > 0){
	$subcat = "1";
	$cats = FUNCT_CATS_LIST($subcat);
	$mainpage = str_replace('%CATEGORIES1%',$cats,$mainpage);
	$cats = FUNCT_CATS_LIST_OPTION($subcat);
	$mainpage = str_replace('%CATEGOPTION1%',$cats,$mainpage);
}

if (strrpos($mainpage, '%CATEGORIES2%') > 0){
	$subcat = "2";
	$cats = FUNCT_CATS_LIST($subcat);
	$mainpage = str_replace('%CATEGORIES2%',$cats,$mainpage);
	$cats = FUNCT_CATS_LIST_OPTION($subcat);
	$mainpage = str_replace('%CATEGOPTION2%',$cats,$mainpage);
}
if (strrpos($mainpage, '%CATEGORIES3%') > 0){
	$subcat = "3";
	$cats = FUNCT_CATS_LIST($subcat);
	$mainpage = str_replace('%CATEGORIES3%',$cats,$mainpage);
	$cats = FUNCT_CATS_LIST_OPTION($subcat);
	$mainpage = str_replace('%CATEGOPTION3%',$cats,$mainpage);
}
if (strrpos($mainpage, '%CATEGORIES4%') > 0){
	$subcat = "4";
	$cats = FUNCT_CATS_LIST($subcat);
	$mainpage = str_replace('%CATEGORIES4%',$cats,$mainpage);
	$cats = FUNCT_CATS_LIST_OPTION($subcat);
	$mainpage = str_replace('%CATEGOPTION4%',$cats,$mainpage);
}
if (strrpos($mainpage, '%CATEGORIES5%') > 0){
	$subcat = "5";
	$cats = FUNCT_CATS_LIST($subcat);
	$mainpage = str_replace('%CATEGORIES5%',$cats,$mainpage);
	$cats = FUNCT_CATS_LIST_OPTION($subcat);
	$mainpage = str_replace('%CATEGOPTION5%',$cats,$mainpage);
}
if (strrpos($mainpage, '%CATEGORIES6%') > 0){
	$subcat = "6";
	$cats = FUNCT_CATS_LIST($subcat);
	$mainpage = str_replace('%CATEGORIES6%',$cats,$mainpage);
	$cats = FUNCT_CATS_LIST_OPTION($subcat);
	$mainpage = str_replace('%CATEGOPTION6%',$cats,$mainpage);
}

if ($_SESSION['acctype']=='employer') {

	$result_cord = mysql_query('SELECT * FROM cordpackage WHERE PAKID ='.$_SESSION['pakid']);
	$cnt_cord = mysql_num_rows($result_cord);
	if ($cnt_cord > 0) {
   		while($row_cord=mysql_fetch_array($result_cord)){
			$pakname = $row_cord['PAKNAME'];
			$pakdesc = $row_cord['DESCRIPTION'];
		 }
	} else { $pakname = "No Package Applied";}
	
	$lowongan = FUNCT_JOB_LIST_EMP($num_per_page,$page); 
	$mainpage = str_replace('%SEEKERNAME%',$greetings,$mainpage);
	$mainpage = str_replace('%NAME%',$coname,$mainpage); 
	$mainpage = str_replace('%FOTO%',$photo,$mainpage);
	$mainpage = str_replace('%COINFO%',$_SESSION['about'],$mainpage);
	$mainpage = str_replace('%PAKET%',$pakname,$mainpage);
	$mainpage = str_replace('%LOWONGAN%',$lowongan,$mainpage);		
	 }
	
if ($_SESSION['acctype']=='seeker'){ 

	$result_rsm = mysql_query('SELECT * FROM table_work_history WHERE UID ='.$_SESSION['cid']);
	$cnt_rsm = mysql_num_rows($result_rsm);
	if ($cnt_rsm > 0) {
   		while($row_rsm=mysql_fetch_array($result_rsm)){
			$position = $row_rsm['JOB_TITLE'];
			if ($row_rsm['END_DATE']=="") { $position = $row_rsm['JOB_TITLE']; }
			else { $position_pre = $row_rsm['JOB_TITLE']; }
		 }
	} else { $position = "No Position Applied";}
	
	$result_jobtype = mysql_query('SELECT * FROM resumes WHERE UID ='.$_SESSION['cid'].' LIMIT 1');
	$cnt_jobtype = mysql_num_rows($result_jobtype);
	if ($cnt_jobtype > 0) {
		$row_jobtype = mysql_fetch_array($result_jobtype);
		
			$a = array();
			$a =  split(";",$row_jobtype['EXPERTISE']);
			
			$exp_sql="SELECT * FROM ref_expertise ORDER by 1 ASC";
			$exp_result=mysql_query($exp_sql);
			$expertise = "";
			
			 while($exp_rows=mysql_fetch_array($exp_result)){

					if (in_array($exp_rows['REXPERTISEID'],$a)) { 
						$expertise .= " ".$exp_rows['EXPERTISE_NAME'];
					} 
			 } 
	}
	
	$result_edu = mysql_query('SELECT * FROM table_education WHERE UID ='.$_SESSION['cid']);
	$cnt_edu = mysql_num_rows($result_edu);
	$grade = "E";
	if ($cnt_edu > 0) {

  	while($row_edu=mysql_fetch_array($result_edu)){
		
		$degree_sql="SELECT * FROM ref_university WHERE UNI_NAME='".$row_edu['SCHOOL_NAME']."'";
		$degree_result=mysql_query($degree_sql);
		 while($degree_rows=mysql_fetch_array($degree_result)){
				   if ($degree_rows['UNI_GRADE'] == 1) { $grade = "A"; }
				   elseif ($degree_rows['UNI_GRADE'] == 2) { $grade = "B"; }
				   elseif ($degree_rows['UNI_GRADE'] == 3) { $grade = "C"; }
				   elseif ($degree_rows['UNI_GRADE'] == 4) { $grade = "D"; }
				   elseif ($degree_rows['UNI_GRADE'] == 5) { $grade = "E"; }				   				   				   
			}
		}
	}	
	
	$result_test = mysql_query('SELECT * FROM my70b5_onlinetest.test WHERE user_id ='.$_SESSION['cid'].' and finished = 1');
	$cnt_test = mysql_num_rows($result_test);
	$testonline = 0;
	if ($cnt_test > 0) {
	
  	while($row_test=mysql_fetch_array($result_test)){
		$score_result=mysql_query('SELECT * FROM my70b5_onlinetest.results WHERE test_id ='.$row_test['id'].' LIMIT 1');
		$cnt_test1 = mysql_num_rows($score_result);
		
		 while($score_rows=mysql_fetch_array($score_result)){
				   $testonline = $score_rows['test1_score']; 			   				   				   
			}
		}
	}	

	$mainpage = str_replace('%SEEKERNAME%',$greetings,$mainpage);
	$mainpage = str_replace('%NAME%',$coname,$mainpage);
	$mainpage = str_replace('%FIRSTNAME%',$firstname,$mainpage);
	$mainpage = str_replace('%LASTNAME%',$lastname,$mainpage);	
	$mainpage = str_replace('%FOTO%',$photo,$mainpage);
	$mainpage = str_replace('%ADDRESS1%',$add1,$mainpage);
	$mainpage = str_replace('%ADDRESS2%',$add2,$mainpage);
	$mainpage = str_replace('%CITY%',$city,$mainpage);
	$mainpage = str_replace('%PROVINCE%',$state,$mainpage);
	$mainpage = str_replace('%POSTAL%',$zip,$mainpage);
	$mainpage = str_replace('%PHONE1%',$phone,$mainpage);
	//$mainpage = str_replace('%PHONE2%',$fax,$mainpage);
	$mainpage = str_replace('%POSITION%',$position,$mainpage);
	$mainpage = str_replace('%INTEREST%',$expertise,$mainpage);
	$mainpage = str_replace('%EXPERIENCE%',$position,$mainpage);
	$mainpage = str_replace('%GRADE%',$grade,$mainpage);
	$mainpage = str_replace('%SCORE%',$testonline,$mainpage);
	$mainpage = str_replace('%EMAIL%',$email,$mainpage);
 }

$pagehdr = file_get_contents('mjl-themes/'.$theme.'/master_header.theme.html');

$pageftr = file_get_contents('mjl-themes/'.$theme.'/master_footer.theme.html');

$pagehdr = str_replace('%imagelogout%',$imagelogout,$pagehdr);

echo $pagehdr.$mainpage.$pageftr;


?>