<?php
// response formation 
// {
//   isSuccessful: STRING, 'failed' or 'successful',
//   msg         : STRING, 'message',
//   detail      : STRING, 'error message for debug'
// }

require_once('conn.php');
// check if session is built
if (!isset($_SESSION)) {
  session_name('todo-manager');
  session_start();
  if (!isset($_SESSION['account'])) {
    // echo error response
    $response = array(
      'isSuccessful' => 'failed',
      'msg'          => 'session variable is not set',
      'detail'       => 'none'
    );
    $response = json_encode($response);
    header('Content-Type: application/json;charset=utf-8');
    echo $response;
    die();
  }
}

// if get empty data from post request
if (
  empty($_POST['nickname']) ||
  empty($_POST['password'])
) {
    // echo error response
    $response = array(
      'isSuccessful' => 'failed',
      'msg'          => 'empty post data',
      'detail'       => 'none'
    );
    $response = json_encode($response);
    header('Content-Type: application/json;charset=utf-8');
    echo $response;
    die();
  }

// if nickname doesn't match required length
if (mb_strlen($_POST['nickname'], 'utf-8') < 1 || mb_strlen($_POST['nickname'], 'utf-8') > 10) {
  // echo error response
  $response = array(
    'isSuccessful' => 'failed',
    'msg'          => 'regular expression check failed',
    'detail'       => 'none'
  );
  $response = json_encode($response);
  header('Content-Type: application/json;charset=utf-8');
  echo $response;
  die();
}

// check password with regex 
if (!preg_match('/^(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,20}$/', $_POST['password'])) {
      // echo error response
      $response = array(
        'isSuccessful' => 'failed',
        'msg'          => 'regular expression check failed',
        'detail'       => 'none'
      );
      $response = json_encode($response);
      header('Content-Type: application/json;charset=utf-8');
      echo $response;
      die();
}

// password hashed
$hashed_password = hash('sha256', $_POST['password']);

// update user info 
$stmt = $conn->prepare('UPDATE users SET nickname=?, password=? WHERE account=?');
$stmt->bind_param('sss', 
                  $_POST['nickname'], 
                  $hashed_password, 
                  $_SESSION['account']
);
$res = $stmt->execute();

// encounter sql query error
if (!$res) {
  $response = array(
    'isSuccessful'  => 'failed',
    'msg'           => 'encounter sql error',
    'detail'        => 'none'
  );
  $response = json_encode($response);
  header('Content-Type: application/json;charset=utf-8');
  echo $response;
  die();
}

$response = array(
  'isSuccessful'  => 'successful',
  'msg'           => 'none',
  'detail'        => 'none'
);
$response = json_encode($response);
header('Content-Type: application/json;charset=utf-8');
echo $response;
die();
