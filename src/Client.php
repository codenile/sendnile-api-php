<?php
class Client {
  protected $host;
  protected $headers;
  protected $version;
  protected $path;
  protected $curl_options;
  private $methods = ['get', 'post'];

  public function __construct($host, $headers = null, $version = null, $path = null, $curl_options = null) {
    $this->host = $host;
    $this->headers = $headers ?: [];
    $this->version = $version;
    $this->path = $path ?: [];
    $this->curl_options = $curl_options ?: [];
  }

  private function create_curl_options($method, $body = null, $headers = null) {
    $options = [
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_HEADER => false,
      CURLOPT_CUSTOMREQUEST => strtoupper($method),
      CURLOPT_SSL_VERIFYPEER => false,
      CURLOPT_SSL_VERIFYHOST => false,
      CURLOPT_FAILONERROR => false,
      CURLOPT_TIMEOUT => 60
    ] + $this->curl_options;

    if (isset($headers)) {
      $headers = array_merge($this->headers, $headers);
    } else {
      $headers = $this->headers;
    }

    if(isset($body)) {
      $encodedBody = json_encode($body);
      $options[CURLOPT_POSTFIELDS] = $encodedBody;
      $headers = array_merge($headers, ['Content-Type: application/json']);
    }

    $options[CURLOPT_HTTPHEADER] = $headers;

    return $options;
  }

  public function make($method, $url, $body = null, $headers = null) {

    $body = json_encode($body);

    $url = $this->host.$this->version.'/'.$url

    $channel = curl_init($url);
    $options = $this->create_curl_options($method, $body, $headers);
    curl_setopt_array($channel, $options);
    $content = curl_exec($channel);
    if($content === false) {
      return ['status' => 'ERROR', 'msg' => curl_error($channel)];
    }
    curl_close($channel);

    return json_decode($content, true);
  }

}
