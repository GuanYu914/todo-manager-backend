<?php
// response formation 
// {
//   isSuccessful: STRING, 'failed' or 'successful',
//   msg         : STRING, 'message',
//   detail      : STRING, 'error message for debug'
// }
require_once('conn.php');

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
  }
}

// delete all todos according to account
$stmt = $conn->prepare("DELETE FROM todos WHERE account=?");
$stmt->bind_param('s', $_SESSION['account']);
$res = $stmt->execute();

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

$json = json_decode($_POST['content']);
// use SQL transaction to ensure data transmission
// disable auto commit
$conn->autocommit(FALSE);
$conn->begin_transaction();

try {
  for ($i = 0; $i < count($json); $i++) {
    $stmt = $conn->prepare('INSERT INTO todos (checked, content, categories, comment, priority, account) VALUES (?, ?, ?, ?, ?, ?)');
    $stmt->bind_param('isssis', 
      $json[$i]->checked, 
      $json[$i]->content, 
      $json[$i]->categories, 
      $json[$i]->comment, 
      $json[$i]->priority, 
      $_SESSION['account']);
    $res = $stmt->execute();
  }
  // if no errors, then commit this 
  $conn->commit();
} catch (mysqli_sql_exception $exception) {
  mysqli_rollback($mysqli);
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
