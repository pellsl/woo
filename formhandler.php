<?php
//This is a very simple PHP script that outputs the name of each bit of information in the browser window, and then sends it all to an email address you add to the script.
//Many thanks to Adam Eivy for his invaluable help with modifying the PHP.

if (empty($_POST)) {
	header('Location: '.$_SERVER['HTTP_REFERER']);
	exit();
}

//Creates function that removes magic escaping, if it's been applied, from values and then removes extra newlines and returns to foil spammers.
function clear_user_input($value) {
	if (get_magic_quotes_gpc()) $value=stripslashes($value);
	$value= str_replace( "\n", '', trim($value));
	$value= str_replace( "\r", '', $value);
	return $value;
	}


if ($_POST['comments'] == 'Please share any comments you have here') $_POST['comments'] = '';	

//Create body of message by cleaning each field and then appending each name and value to it

$body ="Here is the message from your website!:\n";

foreach ($_POST as $key => $value) {
	if(is_array($value)){ 				// if this post element is a checkbox group or multiple select box
		$value = implode(', ',$value);	// show array of values selected
		
	}

	$key = clear_user_input($key); 
	$value = clear_user_input($value);
	$$key = $value;
	
	$body .= "$key: $value\n";
}

//Get the uploaded file information
$name_of_uploaded_file =
    basename($_FILES['uploaded_file']['name']);
 
//get the file extension of the file
$type_of_uploaded_file =
    substr($name_of_uploaded_file,
    strrpos($name_of_uploaded_file, '.') + 1);
 
$size_of_uploaded_file =
    $_FILES["uploaded_file"]["size"]/1024;//size in KBs
    
//Settings
$max_allowed_file_size = 100; // size in KB
$allowed_extensions = array("jpg", "jpeg", "gif", "bmp");
 
//Validations
if($size_of_uploaded_file > $max_allowed_file_size )
{
  $errors .= "\n Size of file should be less than $max_allowed_file_size";
}
 
//------ Validate the file extension -----
$allowed_ext = false;
for($i=0; $i<sizeof($allowed_extensions); $i++)
{
  if(strcasecmp($allowed_extensions[$i],$type_of_uploaded_file) == 0)
  {
    $allowed_ext = true;
  }
}
 
if(!$allowed_ext)
{
  $errors .= "\n The uploaded file is not supported file type. ".
  " Only the following file types are supported: ".implode(',',$allowed_extensions);
}
    

//Create header that puts email in From box along with name in parentheses
$from='From: '. $email . "(" . $name . ")" . "\r\n"; 
	
//for final testing, comment out the above line and uncomment the one below, replacing yourname@domain.com with your own email address: 		
$from='From: '. $email . "(" . $name . ")" . "\r\n" . 'Bcc: lonna.pells@gmail.com' . "\r\n";
// sends bcc to alternate address 

//Creates intelligible subject line that shows where it came from
$subject = 'Another message from your website'; // if your client has more than one web site, you can put the site name here.

// for troubleshooting, uncomment the two lines below. Send your form, and you'll get a browser message showing your results.
//echo "mail ('clientname@domain.com', $subject, $body, $from);";
//exit();

//Sends email, with elements created above
//Replace clientname@domain.com with your client's email address. Put your address here for initial testing, put your client's address for final testing and use.
mail ('pellsl@uw.edu', $subject, $body, $from);

header('Location: thx.html'); // replace "thx.html" with the name and path to your actual thank you page