<?php
include 'config.php';
require_once("php-sdk/facebook.php");

$config = array();
$config['appId'] = '347999435332261';
$config['secret'] = '25ef1ce243b567da3e6b602d9e2601ff';

$facebook = new Facebook($config);

$params = array(
  'scope' => 'read_stream, friends_likes, user_likes, email',
  'redirect_uri' => $fb_callback_url
);

$loginUrl = $facebook->getLoginUrl($params);

header("location: " . $loginUrl);
?>