<?php
require_once __DIR__ . '/../src/SendNile.php';

use SendNile;

$api_key = "123";
$api_secret = "456";

$sendnile = new SendNile($api_key, $api_secret, ['version' => 'v2']);

$email = [
  'from_name' => 'From name',
  'from_address' => 'name@example.com',
  'subject' => 'Email Subject',
  'to_name' => 'Recipient Name',
  'to_address' => 'recipient@example.com',
  'reply_to' => 'reply-to@example.com',
  'type' => 'promotional'                     // type should be `promotional` or `transactional`
];

try {
    $response = $sendnile->send_mail($email);
    echo $response['status'];     // `OK` if success or `ERROR` if any error occurs
    echo $response['msg'];
    echo $response['message_id']; // Unique message ID fror tracking
} catch (Exception $e) {
    echo 'Caught exception: ',  $e->getMessage(), "\n";
}
