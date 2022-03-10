<?php

require_once 'vendor/autoload.php';
require_once 'config/constants.php';

// Create the Transport
$transport = (new Swift_SmtpTransport('smtp.gmail.com', 465, 'ssl'))
  ->setUsername(EMAIL)
  ->setPassword(PASSWORD)
;

// Create the Mailer using your created Transport
$mailer = new Swift_Mailer($transport);



function sendVerificationEmail($userEmail, $token){

  global $mailer;
  
  $body = 'Thank you for signing up on our website. Please click on the link below to verify your email.
  
  http://localhost/userAuthPhp/index.php?token=' . $token;

  // Create a message
  $message = (new Swift_Message('Verify your email address'))
  ->setFrom([EMAIL])
  ->setTo([$userEmail])
  ->setBody($body)
  ;
  
  // Send the message
  $result = $mailer->send($message);
}
