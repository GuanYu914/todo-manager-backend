<?php
// response formation 
// {
//   isSuccessful: STRING, 'failed' or 'successful',
//   displayError: STRING, 'true' or 'false',
//   msg         : STRING, 'message',
//   detail      : STRING, 'error message for debug'
// }
if (!isset($_SESSION)) {
  session_name('todo-manager');
  session_start();  
}
require_once('conn.php');

// check if post data is empty
if (
  empty($_POST['account']) ||
  empty($_POST['password'])
) {
  $response = array('isSuccessful' => 'failed', 
                    'displayError' => 'false', 
                    'msg' => 'empty post data', 
                    'detail' => 'none');
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

$stmt = $conn->prepare("SELECT * FROM users WHERE account=? AND password=?");
$stmt->bind_param('ss', $_POST['account'], $hashed_password);
$res = $stmt->execute();

if (!$res) {
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

$stmt->store_result();
// if no user match 
if (!$stmt->num_rows()) {
  // send error response
  $response = array('isSuccessful' => 'failed', 
                    'displayError' => 'true', 
                    'msg' => 'not funded in database', 
                    'detail' => 'none');
  $response = json_encode($response);
  header('Content-Type: application/json;charset=utf-8');
  echo $response;
  die();
}

// fetch nickname of user as session['nickname']'s data
$stmt = $conn->prepare("SELECT * FROM users WHERE account=? AND password=?");
$stmt->bind_param('ss', $_POST['account'], $hashed_password);
$res = $stmt->execute();

if (!$res) {
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

// be careful, for other successful queries, mysqli_stmt_get_result() will return false
$res = $stmt->get_result();
$row = $res->fetch_assoc();
$user_nickname = $row['nickname'];
// build session and store variable
$_SESSION['account'] = $_POST['account'];
$_SESSION['nickname'] = $user_nickname;

// login successfully
$response = array('isSuccessful' => 'successful', 
                    'displayError' => 'false', 
                    'msg' => 'none', 
                    'detail' => 'none');
$response = json_encode($response);
header('Content-Type: application/json;charset=utf-8');
echo $response;
