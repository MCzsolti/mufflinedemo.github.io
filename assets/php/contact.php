<?php
header('Content-type: application/json');

if($_POST)
{
    $to_email       = "muffline@mail.com"; //Recipient email, Replace with own email here
   
    //check if its an ajax request, exit if not
    if(!isset($_SERVER['HTTP_X_REQUESTED_WITH']) AND strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) != 'xmlhttprequest') {
       
        $output = json_encode(array( //create JSON data
            'type'=>'error',
            'text' => 'Sorry Request must be Ajax POST'
        ));
        die($output); //exit script outputting json data
    }

    //Sanitize input data using PHP filter_var().
    $user_name      = filter_var($_POST["name"],    FILTER_SANITIZE_STRING);
    $user_email     = filter_var($_POST["email"],   FILTER_SANITIZE_EMAIL);
    $message        = filter_var($_POST["message"], FILTER_SANITIZE_STRING);

    //additional php validation
    if(strlen($user_name)<4){ // If length is less than 4 it will output JSON error.
        $output = json_encode(array('type'=>'error', 'text' => 'A név mező nem lehet üres.'));
        die($output);
    }

    if(!filter_var($user_email, FILTER_VALIDATE_EMAIL)){ //email validation
        $output = json_encode(array('type'=>'error', 'text' => 'Valós e-mail címet adj meg!'));
        die($output);
    }


    if(strlen($message)<3){ //check emtpy message
        $output = json_encode(array('type'=>'error', 'text' => 'Az üzenet mező nem lehet üres.'));
        die($output);
    }

    //email subject
    $subject ='Valaki használta a kapcsolat mezőt!';

    //email body
    $message_body = $message."\r\n\r\n-".$user_name."\r\n\r\nEmail : ".$user_email. 
   
    //proceed with PHP email.
    $headers = 'From: '.$user_name.'<'.$user_email.'>'."\r\n" .
    'Reply-To: '.$user_name.'<'.$user_email.'>' . "\r\n" .
    'X-Mailer: PHP/' . phpversion();
   
    $send_mail = mail($to_email, $subject, $message_body, $headers);
   
    if(!$send_mail)
    {
        //If mail couldn't be sent output error. Check your PHP email configuration (if it ever happens)
        $output = json_encode(array('type'=>'error', 'text' => 'HIBA! Az üzeneted nem ért célba. Vedd fel velünk a kapcsolatot e-mailben.'));
        die($output);
    }else{
        $output = json_encode(array('type'=>'success', 'text' => 'Helló '.$user_name .', az üzeneted eljutott hozzánk, remélhetőleg meg is nézzük.'));
        die($output);
    }
}


?>
