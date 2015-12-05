<?php
//Uncomment these to display errors in the browser
//error_reporting( E_ALL );
//ini_set( "display_errors", 1 );

require __DIR__ . '/vendor/autoload.php';

//Change only these two values
$_SWAT_EMAIL="vrbalaji16@gmail.com";
$_SWAT_NAME="BALAJI VR REDDY";

if(!(isset($_POST)) || ($_POST['FNAME']==null || $_POST['LNAME']==null || $_POST['EMAIL']==null || $_POST['MOBILE1']==null || $_POST['MMERGE5']==null|| $_POST['ADDRESS']==null|| $_POST['CITY']==null|| $_POST['STATE']==null|| $_POST['COUNTRY']==null|| $_POST['PINCODE']==null|| $_FILES['CV']['tmp_name']==null|| $_POST['EXPSALARY']==null))
  die("<h1>ERROR : Incomplete Request</h1><p>Please fill in all Fields.</p>");


$confirm_message = array(
  'html' => 'Hello '.$_POST['FNAME'].',<br><br>This mail is to inform you that we have received your application.<br>We will contact you soon.<br><br><br>Here\'s wishing you a All the Best!<br>Best regards,<br>Team Swaayatt-Robots<br><br><br><br><b>If you received this email by mistake, simply delete it.</b>',
  'subject' => 'Confirmation Mail',
  'from_email' => 'no_reply@swaayattrobots.in',
  'from_name' => 'Swaayatt Auto-Mailer',
  'to' => array(
    array(
      'email' => $_POST['EMAIL'],
      'name' => $_POST['FNAME'].' '.$_POST['LNAME'],
      'type' => 'to'
    )
  )
);


$cv_data = file_get_contents($_FILES['CV']['tmp_name']);
$cv_data = base64_encode($cv_data);
$pp_data = null;
$pp_array = null;
if($_FILES['PP']['tmp_name'] != null){
  $pp_data = file_get_contents($_FILES['PP']['tmp_name']);
  $pp_data = base64_encode($pp_data);
  $pp_array = array(
    'type' => 'application/pdf',
    'name' => $_POST['FNAME'].'_'.$_POST['LNAME'].'_pp.pdf', 
    'content' => $pp_data
  );
}

$form_data ='First Name : '.$_POST['FNAME'];
if($_POST['MNAME']!=null)
  $form_data = $form_data."\nMiddle Name : ".$_POST['MNAME'];
$form_data = $form_data."\nLast Name : ".$_POST['LNAME'];
$form_data = $form_data."\nEmail : ".$_POST['EMAIL'];
$form_data = $form_data."\nMobile : ".$_POST['MOBILE1'];
if($_POST['MOBILE2']!=null)
  $form_data = $form_data.' , '.$_POST['MOBILE2'];
$form_data = $form_data."\nSex : ".$_POST['MMERGE5'];
$form_data = $form_data."\nAddress : ".$_POST['ADDRESS'];
$form_data = $form_data."\nCity : ".$_POST['CITY'];
$form_data = $form_data."\nState : ".$_POST['STATE'];
$form_data = $form_data."\nCountry : ".$_POST['COUNTRY'];
$form_data = $form_data."\nPincode : ".$_POST['PINCODE'];
if($_POST['PORT']!=null)
  $form_data = $form_data."\nPortfolio : ".$_POST['PORT'];
if($_POST['WORKEXP']!=null)
  $form_data = $form_data."\nWork Experience : ".$_POST['WORKEXP'];
$form_data = $form_data."\nExpected Salary : ".$_POST['EXPSALARY'];
if($_POST['COMMENTS']!=null)
  $form_data = $form_data."\nComments :  ".$_POST['PURPOSE'];

$form_data = base64_encode($form_data);


$app_details_message = array(
  'html' => 'Hello,<br>I filled a form on your website and the data I just entered is magically mailed to you as attachments.<br><br>Please have a look into it !!!', 
  'subject' => 'Application and Resume',
  'from_email' => $_POST['EMAIL'],
  'from_name' => $_POST['FNAME'].' '.$_POST['LNAME'],
  'to' => array(
    array(
      'email' => $_SWAT_EMAIL,
      'name' => $_SWAT_NAME,
      'type' => 'to'
    )
  ),
  'attachments' => array(
    array(
      'type' => 'application/pdf',
      'name' => $_POST['FNAME'].'_'.$_POST['LNAME'].'_cv.pdf', 
      'content' => $cv_data
    ),
    array(
      'type' =>'text/plain',
      'name' => $_POST['FNAME'].'_'.$_POST['LNAME'].'_form_data_txt', 
      'content' => $form_data
    )
  )
);

if($pp_array!=null){
  array_push($app_details_message['attachments'],$pp_array);
}

try{
  $mandrill = new Mandrill('krVca0LJhCc72qdKzCdqsg');
  $result = $mandrill->messages->send($app_details_message);
  $result2 = $mandrill->messages->send($confirm_message);
  
  //echo $result.$result2;

  header("Location: result.html"); /* Redirect browser */
  exit();
}catch(Mandrill_Error $e){
  echo 'Error occurred: ' . get_class($e) . ' - ' . $e->getMessage(); 
  throw $e;
}


?> 
