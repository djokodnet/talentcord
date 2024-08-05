<?php



session_start();

header ("Cache-control: private");



include('mjl-includes/settings.inc.php');

include('mjl-includes/db.inc.php');

require_once 'assets/class.phpmailer.php';



if(($_SERVER['REQUEST_METHOD'] == 'GET') && isset($_GET['recover']) && !empty($_GET['recover'])){

		$recover = trim(htmlspecialchars($_GET['recover'], ENT_QUOTES));

		$strtest = substr($recover, 0, 4);

		if($strtest == 'empl'){

			$recover = substr($recover, 4);

			$result = mysql_query('SELECT USERNAME,CONTACTEMAIL FROM companies WHERE TEMP = "'.$recover.'" LIMIT 1');

			$cnt = mysql_num_rows($result);

			if($cnt > 0){

				$row = mysql_fetch_array($result);

				$newpass = FUNCT_QUICK_PASS();

				mysql_query('UPDATE companies SET PASSWORD = "'.md5($newpass).'", TEMP = "" WHERE TEMP = "'.$recover.'" LIMIT 1');

				if(mysql_errno()){

					$statusmsg =  '<div class="descwrapper">There was an error changing your password. Please contact support. (<span class="error">Error: '.mysql_errno().', '.mysql_error().'</span>)</div>';

				}else{

					if(MAIL_NEW_PASS($row['USERNAME'],$row['CONTACTEMAIL'], $newpass)){

						$statusmsg =  '<div class="descwrapper">Your new password has been emailed to the address provided.</div>';

					}else{

						$statusmsg =  '<div class="descwrapper">There was an error emailing your password.  Please contact support.</div>';

					}

				}

			}

		}else{

			$result = mysql_query('SELECT USERNAME, EMAIL FROM users WHERE TEMP = "'.$recover.'" LIMIT 1');

			$cnt = mysql_num_rows($result);

			if($cnt > 0){

				$row = mysql_fetch_array($result);

				$newpass = FUNCT_QUICK_PASS();

				mysql_query('UPDATE users SET PASSWORD = "'.md5($newpass).'", TEMP = "" WHERE TEMP = "'.$recover.'" LIMIT 1');

				if(mysql_errno()){

					$statusmsg =  '<div class="descwrapper">There was an error changing your password. Please contact Talentcord support. (<span class="error">Error: '.mysql_errno().', '.mysql_error().'</span>)</div>';

				}else{

					if(MAIL_NEW_PASS($row['USERNAME'],$row['EMAIL'], $newpass)){

						$statusmsg =  '<div class="descwrapper">Your new password has been emailed to the address provided.</div>';

					}else{

						$statusmsg =  '<div class="descwrapper">There was an error emailing your password.  Please contact support.</div>';

					}

				}

			}

			//$statusmsg =  '<div class="descwrapper">There was an error changing your password. Please contact support Talentcord.</div>';
			$statusmsg =  '<div class="descwrapper">Your new password has been emailed to the address provided.</div>';

		}

	$mainpage = $statusmsg;		

	$pagehdr = file_get_contents('mjl-themes/'.$theme.'/master_header.theme.html');

	$pageftr = file_get_contents('mjl-themes/'.$theme.'/master_footer.theme.html');

	$mainpage = $pagehdr.$mainpage.$pageftr;

	echo $mainpage;

	exit;

}



// Verification to see if a token is being passed or the person

//  is visiting the registration page to register

if(($_SERVER['REQUEST_METHOD'] == 'GET') && isset($_GET['token']) && !empty($_GET['token'])){

		$token = trim(htmlspecialchars($_GET['token'], ENT_QUOTES));

		$strtest = substr($token, 0, 4);

		//  validate whether the users is a job seeker or employer registering

		if($strtest == 'empl'){

			//  validate if you have autoregistration turned on for employers

			if($autoreg == 1){

				//  remove the empl tag and update the status of the employer

				$token = substr($token, 4);

				$mainpage = FUNCT_UPDATE_EMPLOYER_STATUS($token);

			}

		}else{

			$mainpage = FUNCT_UPDATE_USER_STATUS($token);

		}

}else{

	// if the page is loaded we verify if it is being loaded because of a post or

	// because the page was loaded directly

	if($_SERVER['REQUEST_METHOD'] == 'POST'){

		//  if we get a post method response then we check to see if recaptcha was enabled

		//  if it was enabled then we check for the proper entry of the recaptcha statement

		if ($recaptcha == 1){

			require_once('mjl-includes/recaptchalib.php');

			$resp = recaptcha_check_answer ($recaptcha_private,$_SERVER["REMOTE_ADDR"],$_POST["recaptcha_challenge_field"],$_POST["recaptcha_response_field"]);

			if ($resp->is_valid) {

				$captcha_ok = 1;

			} else {

				$captcha_ok = 0;

	        }

		}else{

			$captcha_ok = 1;

		}

		

		$mainpage = file_get_contents('mjl-themes/'.$theme.'/register_results.theme.html');

		

		//  if the recaptcha statement was correct we send the data along its way.

		if($captcha_ok == 1){

		

			// Now lets verify that the registration type was selected and if selected we allow

			// entry into the data area.  If the select is not made it will be equal to zero 

			// if it is selected then it will equal 1.

			if (count($_POST['regtype']) == 0){

				$statusmsg =  '<div class="descwrapper">Are you a Job Seeker or Employer?  Hit back and make a selection</div>';

				$mainpage = str_replace('%RESULTS%',$statusmsg,$mainpage);

			}elseif(count($_POST['regtype']) == 1){

				//  so we learned that the user successfully selected one of the radio options

				//  and we take all the form variables and scrub them so that there wont be any

				//  concern of having some sort of sql injection that would make everyones day aweful.

				$regtype = $_POST['regtype'];

				$uname = htmlspecialchars($_POST['uname'],ENT_QUOTES);

				$fname = htmlspecialchars($_POST['fname'],ENT_QUOTES);

				$lname = htmlspecialchars($_POST['lname'],ENT_QUOTES);

				$cname = htmlspecialchars($_POST['coname'],ENT_QUOTES);

				$email = htmlspecialchars($_POST['email'],ENT_QUOTES);

				$phone = htmlspecialchars($_POST['phone'],ENT_QUOTES);

				$add1 = htmlspecialchars($_POST['add1'],ENT_QUOTES);

				$add2 = htmlspecialchars($_POST['add2'],ENT_QUOTES);

				$city = htmlspecialchars($_POST['city'],ENT_QUOTES);

				$state = htmlspecialchars($_POST['state'],ENT_QUOTES);

				$zip = htmlspecialchars($_POST['zip'],ENT_QUOTES);
				
				$npwp = htmlspecialchars($_POST['npwp'],ENT_QUOTES);

				//$bday = htmlspecialchars($_POST['birthdate'],ENT_QUOTES);
				
				$bday = htmlspecialchars($_POST['bday'],ENT_QUOTES);
				
				$bmonth = htmlspecialchars($_POST['bmonth'],ENT_QUOTES);
				
				$byear = htmlspecialchars($_POST['byear'],ENT_QUOTES);

				$birthdate =  date('Y-m-d',  strtotime($byear.'-'.$bmonth.'-'.$bday));

				//  We got out datas and scrubbed it now we want to check to see if the person submitting the

				//  form is a job seeker or an emplyer.

				if ($regtype == 'employer'){

					//  if it's an employer lets make sure that the name has not been registred before.

					//  and that it is the proper length.

					$result = mysql_query('SELECT * FROM companies WHERE USERNAME = "'.$uname.'" OR CONTACTEMAIL = "'.$email.'"');

					$cnt = mysql_num_rows($result);

					//if no employers with the same username and email are currently registered then lets continue

					if($cnt == 0 ){ 

					

						$contact = $fname.' '.$lname;

						//  using the md5 hash we make their registration key based off of 

						//  username, email and first and last name.

						$temp_key = md5($uname.$email.$contact);

						

						//  lets verify that the email is properly written out and

						//  if it is then lets add the data to the database.

						if(isValidEmail($email)){

							$result = mysql_query('INSERT INTO companies (USERNAME,BIRTHDATE,COMPANYNAME,MAILADDRESS1,MAILADDRESS2,CITY,STATE,ZIP,PHONE,CONTACT,CONTACTEMAIL,NPWP,CREATEDDATE,APPROVED) VALUES ("'.$uname.'","'.$birthdate.'","'.$cname.'","'.$add1.'","'.$add2.'","'.$city.'","'.$state.'","'.$zip.'","'.$phone.'","'.$contact.'","'.$email.'","'.$npwp.'",now(),"'.$temp_key.'")');

							//  check for errors while adding to the database

							//  if no errors then thank the user for registering.

							if(mysql_errno()){

								$statusmsg =  '<div class="descwrapper">There was an error updating the position. Please contact support. (<span class="error">Error: '.mysql_errno().', '.mysql_error().'</span>)</div>';

							}else{

								//  Employer information was successfully added to the database so

								//  it's time to verify if auto registration is turned on and if sso

								//  send the registration email and let the employer know

								//  that they need to confirm their account.

								//<--we are going to do some email stuff here -->	

								if($autoreg == 1){

									employmail($email,$temp_key);

								}						

								$statusmsg = '<DIV class="acctreg">'.file_get_contents('mjl-letters/employer_registration.letter.html').'</DIV>';

							}

						}else{

							//  Employer has entered an invalid email format.

							$statusmsg =  '<div class="descwrapper">The email you entered is not in the right format please hit the back button and reenter it.</div>';

						}						

						$mainpage = str_replace('%RESULTS%',$statusmsg,$mainpage);

					}else{

						//  The employer is trying to register a name that has already been registered.

						$statusmsg =  '<div class="descwrapper">The account has been registered, please login with your username and password.</div>';

						$mainpage = str_replace('%RESULTS%',$statusmsg,$mainpage);

					}

				}elseif($regtype=='seeker') {

					//  if it's an seeker lets make sure that the name has not been registred before.

					//  and that it is the proper length.

					$result = mysql_query('SELECT * FROM users WHERE USERNAME = "'.$uname.'" OR EMAIL = "'.$email.'"'); 

					$cnt = mysql_num_rows($result);

					// if the job seeker hasn't been registered then lets continue and add the datas

					if($cnt == 0 ){

						//  create a registration key based off username and email.

						$temp_key = md5($uname.$email);

						//  We have to validate the email and make sure it is in the right format

						//  we dont want job seekers and bots not being able to recieve our emails

						//  especially for auto registration.

						if(isValidEmail($email)){						

							$result = mysql_query('INSERT INTO users (USERNAME,BIRTHDATE,FIRSTNAME,LASTNAME,STREETADDRESS1,STREETADDRESS2,CITY,STATE,ZIP,PHONE,EMAIL,CREATEDDATE,APPROVED) VALUES ("'.$uname.'","'.$birthdate.'","'.$fname.'","'.$lname.'","'.$add1.'","'.$add2.'","'.$city.'","'.$state.'","'.$zip.'","'.$phone.'","'.$email.'",now(),"'.$temp_key.'")');

							if(mysql_errno()){

								$statusmsg =  '<div class="descwrapper">There was an error updating the position. Please contact support. (<span class="error">Error: '.mysql_errno().', '.mysql_error().'</span>)</div>';

							}else{

								//  Job seeker information was successfully added to the database so

								//  it's time to send the registration email and let the users know

								//  that they need to confirm the email address by clicking on the link provided.

								if(seekmail($email,$temp_key)){

									$statusmsg = '<DIV class="acctreg">'.file_get_contents('mjl-letters/job_seeker_registration.letter.html').'</DIV>';

								}else{

									$statusmsg =  '<div class="descwrapper">There was an error in the registration process please contact the administrator.</div>';

								}

							}

						}else{

							$statusmsg =  '<div class="descwrapper">The email you entered is not in the right format please hit the back button and reenter it.</div>';

						}

						$mainpage = str_replace('%RESULTS%',$statusmsg,$mainpage);

					}else{

						$statusmsg =  '<div class="descwrapper">The account has been registered, please login with your username and password.</div>';

						$mainpage = str_replace('%RESULTS%',$statusmsg,$mainpage);

					}

				}

			}

		}else{

			//  If the recaptcha statment was incorrect we display an error message

			//  and let the user know.

			$statusmsg =  '<div class="descwrapper">Your recaptcha response was incorrect, please click the back button and reenter your validation.</div>';

			$mainpage = str_replace('%RESULTS%',$statusmsg,$mainpage);

		}

	}else{

		//  The page is loaded from the web without a get or post response

		//  the registration form is displayed

		$mainpage = file_get_contents('mjl-themes/'.$theme.'/register_form.theme.html');

		

		//  we check to see if recaptcha is displayed and remove the 

		//  theme targets

		if ($recaptcha == 1){

			require_once('mjl-includes/recaptchalib.php');

			$mainpage = str_replace('%RECAPTCHA%',recaptcha_get_html($recaptcha_public, $error),$mainpage);

		}else{

			$mainpage = str_replace('%RECAPTCHA%','',$mainpage);

		}

	}

}



$pagehdr = file_get_contents('mjl-themes/'.$theme.'/master_header.theme.html');

$pageftr = file_get_contents('mjl-themes/'.$theme.'/master_footer.theme.html');

$mainpage = $pagehdr.$mainpage.$pageftr;



echo $mainpage;



function seekmail_1($reciever,$tmpkey){  /* Not Used replaced by phpmailer */



	global $mjlmail;

	global $org;

	global $regurl;

	

	$org = stripslashes($org);

	

	$headers = "From: ".$mjlmail."\r\n"; // From address

	$headers .= "Reply-To: ".$mjlmail."\r\n"; // Reply-to address

	$headers .= "Organization: ".$org."\r\n"; // Organisation

	$headers .= 'MIME-Version: 1.0' . "\r\n";

	$headers .= "Content-Type: text/html; charset=iso-8859-1\r\n"; // Type



     //This is the subject line that will be displayed in the message as it is recieved  in email.

     $subject = $org ." Registration Validation\n";



     $gotit = file_get_contents('mjl-letters/job_seeker_verification.letter.html');

	

	$keyloc = '<a href="http://'.$regurl.'register.php?token='.$tmpkey.'">http://'.$regurl.'register.php?token='.$tmpkey.'</a>';

	

	$gotit = $gotit."<br /><br />".$keyloc;



//     mail($reciever, $subject, $gotit, $headers);

	if (mail($reciever, $subject, $gotit, null,'-ftalentcord@myjobsdata.com')) {
	 
	 return TRUE;

	}

}



function employmail($reciever,$tmpkey){

	global $mjlmail;

	global $org;

	global $regurl;

	

	$org = stripslashes($org);



	$headers = "From: ".$mjlmail."\r\n"; // From address

	$headers .= "Reply-To: ".$mjlmail."\r\n"; // Reply-to address

	$headers .= "Organization: ".$org."\r\n"; // Organisation

	$headers .= 'MIME-Version: 1.0' . "\r\n";

	$headers .= "Content-Type: text/html; charset=iso-8859-1\r\n"; // Type





     //This is the subject line that will be displayed in the message as it is recieved  in email.

     $subject = $org ." Registration Validation\n";



     $gotit = file_get_contents('mjl-letters/employer_verification.letter.html');

	

	$keyloc = '<a href="'.$regurl.'register.php?token=empl'.$tmpkey.'">'.$regurl.'register.php?token=empl'.$tmpkey.'</a>';

	

	$gotit = $gotit."<br /><br />".$keyloc;



     //mail($reciever, $subject, $gotit, $headers);

	 $mail = new PHPMailer(true); //defaults to using php "mail()"; the true param means it will throw exceptions on errors, which we need to catch
	
	try {
	  $mail->AddReplyTo($mjlmail, $org);
	  $mail->AddAddress($reciever);
	  $mail->SetFrom($mjlmail, $org);
	  $mail->AddReplyTo($mjlmail, $org);
	  $mail->Subject = $subject;
	  $mail->AltBody = 'To view the message, please use an HTML compatible email viewer!'; // optional - MsgHTML will create an alternate automatically
	  $mail->MsgHTML($gotit);
	 // $mail->AddAttachment('images/phpmailer.gif');      // attachment
	 // $mail->AddAttachment('images/phpmailer_mini.gif'); // attachment
	  $mail->Send();
	  //echo "Message Sent OK</p>\n";
	} catch (phpmailerException $e) {
	  echo $e->errorMessage(); //Pretty error messages from PHPMailer
	} catch (Exception $e) {
	  echo $e->getMessage(); //Boring error messages from anything else!
	}

	 return TRUE;



}

function seekmail($reciever,$tmpkey){



	global $mjlmail;

	global $org;

	global $regurl;

	

	$org = stripslashes($org);


     //This is the subject line that will be displayed in the message as it is recieved  in email.

     $subject = $org ." Registration Validation\n";



     $gotit = file_get_contents('mjl-letters/job_seeker_verification.letter.html');

	

	$keyloc = '<a href="http://'.$regurl.'register.php?token='.$tmpkey.'">http://'.$regurl.'register.php?token='.$tmpkey.'</a>';

	

	$gotit = $gotit."<br /><br />".$keyloc;


	$mail = new PHPMailer(true); //defaults to using php "mail()"; the true param means it will throw exceptions on errors, which we need to catch
	
	try {
	
	  $mail->AddReplyTo('-f'.$mjlmail, $org);
	  $mail->AddAddress($reciever);
	  $mail->SetFrom('-f'.$mjlmail, $org);
	  $mail->AddReplyTo('-f'.$mjlmail, $org);
	  $mail->Subject = $subject;
	  $mail->AltBody = 'To view the message, please use an HTML compatible email viewer!'; // optional - MsgHTML will create an alternate automatically
	  $mail->MsgHTML($gotit);
	 // $mail->AddAttachment('images/phpmailer.gif');      // attachment
	 // $mail->AddAttachment('images/phpmailer_mini.gif'); // attachment
	  $mail->Send();
	  //echo "Message Sent OK</p>\n";
	  return TRUE;
	} catch (phpmailerException $e) {
	  echo $e->errorMessage(); //Pretty error messages from PHPMailer
	} catch (Exception $e) {
	  echo $e->getMessage(); //Boring error messages from anything else!
	}
 

}

function MAIL_NEW_PASS($uname,$email,$pword){

	global $mjlmail;

	global $org;

	global $regurl;

	

	$org = stripslashes($org);



	$headers = "From: ".$mjlmail."\r\n"; // From address

	$headers .= "Reply-To: ".$mjlmail."\r\n"; // Reply-to address

	$headers .= "Organization: ".$org."\r\n"; // Organisation

	$headers .= 'MIME-Version: 1.0' . "\r\n";

	$headers .= "Content-Type: text/html; charset=iso-8859-1\r\n"; // Type

	

	$subject = $org ." Password Recovery\n";



	$gotit = file_get_contents('mjl-letters/password_recovery.letter.html').'<br /><br /><strong>Username: </strong>'.$uname.'<br /><strong>Password: </strong>'.$pword.'<br />';

	$mail = new PHPMailer(true); //defaults to using php "mail()"; the true param means it will throw exceptions on errors, which we need to catch
	
	try {
	  $mail->AddReplyTo($mjlmail, $org);
	  $mail->AddAddress($email);
	  $mail->SetFrom($mjlmail, $org);
	  $mail->AddReplyTo($mjlmail, $org);
	  $mail->Subject = $subject;
	  $mail->AltBody = 'To view the message, please use an HTML compatible email viewer!'; // optional - MsgHTML will create an alternate automatically
	  $mail->MsgHTML($gotit);
	 // $mail->AddAttachment('images/phpmailer.gif');      // attachment
	 // $mail->AddAttachment('images/phpmailer_mini.gif'); // attachment
	  $mail->Send();
	  //echo "Message Sent OK</p>\n";
	} catch (phpmailerException $e) {
	  echo $e->errorMessage(); //Pretty error messages from PHPMailer
	} catch (Exception $e) {
	  echo $e->getMessage(); //Boring error messages from anything else!
	}

	return TRUE;

/*	if (mail($email, $subject, $gotit, $headers)){
		return TRUE;

	}else{

		return FALSE;

	} */

}





function isValidEmail($email){

	//return eregi("^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$", $email);
	
	return preg_match("/^[_\.0-9a-zA-Z-]+@([0-9a-zA-Z][0-9a-zA-Z-]+\.)+[a-zA-Z]{2,6}$/i", $email);

}



function FUNCT_UPDATE_EMPLOYER_STATUS($ssid){

	$result = mysql_query('SELECT PASSWORD, CONTACTEMAIL, USERNAME FROM companies WHERE APPROVED = "'.$ssid.'" LIMIT 1');

	$cnt = mysql_num_rows($result);

	

	if($cnt == 1){

		$row = mysql_fetch_array($result);

		$SSID = new r_id;

		$newpw = $SSID->Getcnt();

		$newpw = substr($newpw, 2, 6);

		mysql_query('UPDATE companies SET PASSWORD=\''.md5($newpw).'\', APPROVED = \'1\' WHERE APPROVED = "'.$ssid.'" LIMIT 1') or die("Epic Fail");

		sendregemail($newpw, $row['CONTACTEMAIL'],$row['USERNAME']);

		$statusmsg =  '<div class="descwrapper">Thank you again for registration at Talentcord. Your username has been activated and an email with your password has been sent to you. Please follow futher instructions to login.</div>';

	}else{

		$statusmsg =  '<div class="descwrapper">The activation key doesn\'t exist or the account is already active.</div>';

	}
	
	return $statusmsg;
}



function FUNCT_UPDATE_USER_STATUS($ssid){

	$result = mysql_query('SELECT PASSWORD, EMAIL, USERNAME FROM users WHERE APPROVED = "'.$ssid.'" LIMIT 1');

	$cnt = mysql_num_rows($result);

	

	if($cnt == 1){

		$row = mysql_fetch_array($result);

		$SSID = new r_id;

		$newpw = $SSID->Getcnt();

		$newpw = substr($newpw, 2, 6);

		mysql_query('UPDATE users SET PASSWORD=\''.md5($newpw).'\', APPROVED = \'1\' WHERE APPROVED = "'.$ssid.'" LIMIT 1') or die("Epic Fail");

		sendregemail($newpw, $row['EMAIL'],$row['USERNAME']);

		$statusmsg =  '<div class="descwrapper">Thank you again for registration at Talentcord. Your username has been activated and an email with your password has been sent to you. Please follow futher instructions to login</div>';

	}else{

		$statusmsg =  '<div class="descwrapper">The activation key doesn\'t exist or the account is already active.</div>';

	}
	
	return $statusmsg;

}



function sendregemail($ssid, $rcpt, $uname){



	global $mjlmail;

	global $org;

	global $url;

	

	$org = stripslashes($org);

	

	$headers = "From: ".$mjlmail."\r\n"; // From address

	$headers .= "Reply-To: ".$mjlmail."\r\n"; // Reply-to address

	$headers .= "Organization: ".$org."\r\n"; // Organisation

	$headers .= 'MIME-Version: 1.0' . "\r\n";

	$headers .= "Content-Type: text/html; charset=iso-8859-1\r\n"; // Type

	

	$subj = $org .' Registration';

	$regmsg = 'Thank you for registering with '.$org.' at <a href="'.$url.'">'.$url.'</a>. Below is the information you need to login to the service:<br /><br />

	username: <strong>'.$uname.'</strong><br />

	password: <strong>'.$ssid.'</strong><br /><br />

	You can login at <a href="'.$url.'">'.$url.'</a><br /><br />

	Again, thank you for registering with '.$org.'.<br /><br /><br />';


	$mail = new PHPMailer(true); //defaults to using php "mail()"; the true param means it will throw exceptions on errors, which we need to catch
	
	try {
	  $mail->AddReplyTo($mjlmail, $org);
	  $mail->AddAddress($rcpt);
	  $mail->SetFrom($mjlmail, $org);
	  $mail->AddReplyTo($mjlmail, $org);
	  $mail->Subject = $subj;
	  $mail->AltBody = 'To view the message, please use an HTML compatible email viewer!'; // optional - MsgHTML will create an alternate automatically
	  $mail->MsgHTML($regmsg);
	 // $mail->AddAttachment('images/phpmailer.gif');      // attachment
	 // $mail->AddAttachment('images/phpmailer_mini.gif'); // attachment
	 // $mail->Send();
	  //echo "Message Sent OK</p>\n";
	} catch (phpmailerException $e) {
	  echo $e->errorMessage(); //Pretty error messages from PHPMailer
	} catch (Exception $e) {
	  echo $e->getMessage(); //Boring error messages from anything else!
	}

	if(ereg("^.+@.+\\..+$", $rcpt)) {

		//if(mail($rcpt, $subj, $regmsg, $headers)){
		if($mail->Send()){
			return TRUE;

		}else{

			return FALSE;

		}

	}else{

		return FALSE;

	}

}



function FUNCT_QUICK_PASS(){

	$rID = md5(mt_rand(1, 2048));

	$id = md5($rID . microtime());

	$retval = substr($id, 0, 6);

	return $retval;

}



class r_id{

     var $mid;

     var $cnt = 1;



     function Getid(){

          $rID = md5(mt_rand(1, 2048));

          $id = md5($rID . microtime());

          return $id;

     }



     function Getcnt(){

          while ($this->cnt > 0){

               $this->mid = $this->Getid();

               $result = mysql_query("SELECT PASSWORD FROM users WHERE PASSWORD = '" . $this->mid . "' LIMIT 1");

               $this->cnt = mysql_num_rows($result);

          }

          return $this->mid;

     }

}

?>