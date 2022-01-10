<?php
namespace SendNile;

class SendNile extends BaseController {
  public function __construct($api_key, $api_secret, $options = array()) {
    $base64 = base64_encode($api_key.':'.$api_secret);

    $auth = 'Authorization: Bearer '.$base64;
    $host = 'https://app.sendnile.com/api/';

    parent::__construct($auth, $options);
  }
}
