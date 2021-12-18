<?php
// response formation 
// {
//   isSuccessful: STRING, 'failed' or 'successful',
//   displayError: STRING, 'true' or 'false',
//   msg         : STRING, 'message',
//   detail      : STRING, 'error message for debug'
// }
require_once('conn.php');

// check if post data is empty
if (
  empty($_POST['nickname']) ||
  empty($_POST['account'])  ||
  empty($_POST['password'])
) {
  // send error response
  $response = array('isSuccessful' => 'failed', 
                    'displayError' => 'false', 
                    'msg' => 'empty post data', 
                    'detail' => 'none');
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
    'displayError' => 'false',
    'msg'          => 'regular expression check failed',
    'detail'       => 'none'
  );
  $response = json_encode($response);
  header('Content-Type: application/json;charset=utf-8');
  echo $response;
  die();
}

// check if post data is valid under RE
if (
  !preg_match('/^(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,20}$/', $_POST['account']) ||
  !preg_match('/^(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,20}$/', $_POST['password'])
) {
  // send error response
  $response = array('isSuccessful' => 'failed', 
                    'displayError' => 'false', 
                    'msg' => 'regular expression check failed', 
                    'detail' => 'none');
  $response = json_encode($response);
  header('Content-Type: application/json;charset=utf-8');
  echo $response;
  die();
}

// use sha256 hash function generate new password
// in order to verify the password stored on database
$hashed_password = hash('sha256', $_POST['password']); 

// start to add user in database
$stmt = $conn->prepare('INSERT INTO users(nickname, account, password) VALUES(?, ?, ?)');
$stmt->bind_param('sss', $_POST['nickname'], $_POST['account'], $hashed_password);
$res = $stmt->execute();

if (!$res) {
  // if error is duplicate entry 
  if ($conn->errno === 1062) {
    $response = array('isSuccessful' => 'failed', 
                    'displayError' => 'true', 
                    'msg' => 'detect same account', 
                    'detail' => $conn->errno . ": " . $conn->error);
    $response = json_encode($response);
    header('Content-Type: application/json;charset=utf-8');
    echo $response;
    die();
  } 
  // send error response
  $response = array('isSuccessful' => 'failed', 
                    'displayError' => 'false', 
                    'msg' => 'encounter SQL error', 
                    'detail' => $conn->errno . ": " . $conn->error);
  $response = json_encode($response);
  header('Content-Type: application/json;charset=utf-8');
  echo $response;
  die();
}

$response = array('isSuccessful' => 'successful', 
                    'displayError' => 'false', 
                    'msg' => 'none', 
                    'detail' => 'none');
$response = json_encode($response);
header('Content-Type: application/json;charset=utf-8');
echo $response;
?>