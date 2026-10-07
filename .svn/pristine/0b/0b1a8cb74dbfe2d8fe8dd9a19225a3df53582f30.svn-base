<?php
/* * * * * * * * * * * * * * SEND EMAIL FUNCTIONS * * * * * * * * * * * * * */

//Authenticate Send - 21st March 2005
//This will send an email using auth smtp and output a log array
//logArray - connection,

// Found on http://support.ihostasp.net/Customer/KBArticle.aspx?articleid=45
/// Slight modifications to interface with the dropcalc.

function authSendEmail($toaddress, $subject, $message, $smtpServer, $port, $timeout, $username, $password, $localhost, $email, $show_response)
{
    //SMTP + SERVER DETAILS
    /* * * * CONFIGURATION START * * * */
    $newLine = "\r\n";
    /* * * * CONFIGURATION END * * * * */
    //Connect to the host on the specified port
    $smtpConnect = fsockopen($smtpServer, $port, $errno, $errstr, $timeout);
    $smtpResponse = fgets($smtpConnect, 515);
    if(empty($smtpConnect))
    {
        $output = "Failed to connect: $smtpResponse";
		echo "<p>failed - $output</p>";
        return $output;
    }
    else
    {
        $logArray['connection'] = "Connected: $smtpResponse";
    }

    //Say Hello to SMTP
    fputs($smtpConnect, "HELO $localhost" . $newLine);
    $smtpResponse = fgets($smtpConnect, 515);
    $logArray['heloresponse'] = "$smtpResponse";
	if ($show_response)
	{	echo "<p>$smtpResponse</p>";	}
   
    //Request Auth Login
    fputs($smtpConnect,"AUTH LOGIN" . $newLine);
    $smtpResponse = fgets($smtpConnect, 515);
    $logArray['authrequest'] = "$smtpResponse";
	if ($show_response)
	{	echo "<p>$smtpResponse</p>";	}

    //Send username
    fputs($smtpConnect, base64_encode($username) . $newLine);
    $smtpResponse = fgets($smtpConnect, 515);
    $logArray['authusername'] = "$smtpResponse";
	if ($show_response)
	{	echo "<p>$smtpResponse</p>";	}
    
    //Send password
    fputs($smtpConnect, base64_encode($password) . $newLine);
    $smtpResponse = fgets($smtpConnect, 515);
    $logArray['authpassword'] = "$smtpResponse";
	if ($show_response)
	{	echo "<p>$smtpResponse</p>";	}
	
    //Email From
    fputs($smtpConnect, "MAIL FROM: $email" . $newLine);
    $smtpResponse = fgets($smtpConnect, 515);
    $logArray['mailfromresponse'] = "$smtpResponse";
	if ($show_response)
	{	echo "<p>$smtpResponse</p>";	}

    //Email To
    fputs($smtpConnect, "RCPT TO: $toaddress" . $newLine);
    $smtpResponse = fgets($smtpConnect, 515);
    $logArray['mailtoresponse'] = "$smtpResponse";
	if ($show_response)
	{	echo "<p>$smtpResponse</p>";	}

    //The Email
    fputs($smtpConnect, "DATA" . $newLine);
    $smtpResponse = fgets($smtpConnect, 515);
    $logArray['data1response'] = "$smtpResponse";
	if ($show_response)
	{	echo "<p>$smtpResponse</p>";	}
    
    //Construct Headers
	$date = date("D j M Y G:i:s T Y");
    $headers  = "MIME-Version: 1.0" . $newLine;
    $headers .= "Content-type: text/html; charset=iso-8859-1" . $newLine;
    $headers .= "To: <$toaddress>" . $newLine;
    $headers .= "From: $bname <$email>" . $newLine;
    $headers .= "Date: $date" . $newLine;
	
    fputs($smtpConnect, "To: $toaddress" . $newLine ."From: $email" . $newLine ."Date: $date" . $newLine ."Subject: $subject" . $newLine ."$headers" . $newLine . $newLine ."$message" . $newLine ."." . $newLine ."");
    $smtpResponse = fgets($smtpConnect, 515);
    $logArray['data2response'] = "$smtpResponse";
	if ($show_response)
	{	echo "<p>$smtpResponse</p>";	}
    
    // Say Bye to SMTP
    fputs($smtpConnect,"QUIT" . $newLine);
    $smtpResponse = fgets($smtpConnect, 515);
    $logArray['quitresponse'] = "$smtpResponse";   
	if ($show_response)
	{	echo "<p>$smtpResponse</p>";	}
}
?>