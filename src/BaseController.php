<?php

class BaseController extends Client {

  const VERSION = '2.1.7';

  public $client;
  public $version = self::VERSION;

  private $types = ['promotional', 'transactional'];

  public function __construct($auth, $host, $options) {
    $headers = [
      $auth,
      'User-Agent: sendnile:php:'.$this->version,
      'Accept: application/json',
    ];

    $host = isset($options['host']) ? $options['host'] : $host;
    $version = isset($options['version']) ? $options['version'] : 'v2';

    $this->client = new Client($host, $headers, $version);
    $this->client->set_curl_options(isset($options['curl']) ? $options['curl'] : []);
  }

  public function send_mail($email) {
    // check type
    if(!in_array($email['type'], $this->$types)) {
      return ['status' => 'ERROR', 'msg' => 'Invalid email type'];
    }

    // check email addresses
    if(!filter_var($email['from_address'], FILTER_VALIDATE_EMAIL) || !filter_var($email['to_address'], FILTER_VALIDATE_EMAIL)) {
      return ['status' => 'ERROR', 'msg' => 'Invalid email address'];
    }

    if(!empty($email['reply_to']) && !filter_var($email['reply_to'], FILTER_VALIDATE_EMAIL)) {
      return ['status' => 'ERROR', 'msg' => 'Invalid email address'];
    }

    // check required data
    if(empty($email['from_name']) || empty($email['subject'])) {
      return ['status' => 'ERROR', 'msg' => 'Required data is missing'];
    }

    // send email
    return $this->client->make('POST', 'mail/send', $email);
  }
}
