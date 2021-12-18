<?php
// response formation 
// {
//   isSuccessful: STRING, 'failed' or 'successful',
//   Data        : Object, 'session variable'
//   msg         : STRING, 'message',
//   detail      : STRING, 'error message for debug'
// }
if (!isset($_SESSION)) {
  session_name('todo-manager');
  session_start();
}

if (
  !isset($_SESSION['account']) ||
  !isset($_SESSION['nickname'])
) {
  $response = array(
    'isSuccessful' => 'failed',
    'data' => 'none',
    'msg' => 'session variable not set',
    'detail' => 'none'
  );
  $response = json_encode($response);
  header('Content-Type: application/json;charset=utf-8');
  echo $response;
  die();
}

if (empty($_SESSION['account'] ||
  empty($_SESSION['nickname']))) {
  $response = array(
    'isSuccessful' => 'failed',
    'data' => 'none',
    'msg' => 'session variable not set',
    'detail' => 'none'
  );
  $response = json_encode($response);
  header('Content-Type: application/json;charset=utf-8');
  echo $response;
  die();
}

$data = array(
  'account'  => $_SESSION['account'],
  'nickname' => $_SESSION['nickname']
);
$response = array(
  'isSuccessful' => 'successful',
  'data' => $data,
  'msg' => 'none',
  'detail' => 'none'
);
$response = json_encode($response);
header('Content-Type: application/json;charset=utf-8');
echo $response;
die();
