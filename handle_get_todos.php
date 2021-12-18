<?php
// response formation 
// {
//   isSuccessful: STRING, 'failed' or 'successful',
//   Data        : ARRAY,  'todo',
//   msg         : STRING, 'message',
//   detail      : STRING, 'error message for debug'
// }
require_once('conn.php');

if (!isset($_SESSION)) {
  session_name('todo-manager');
  session_start();
  if (!isset($_SESSION['account'])) {
    $response = array(
      'isSuccessful' => 'failed',
      'data'         => 'none',
      'msg'          => 'session variable is not set',
      'detail'       => 'none'
    );
    $response = json_encode($response);
    header('Content-Type: application/json;charset=utf-8');
    echo $response;
    die();
  }
}
$account = $_SESSION['account'];

// get all todos according to account
$stmt = $conn->prepare('SELECT checked, content, categories, comment, priority FROM todos WHERE account=? ORDER BY id DESC');
$stmt->bind_param('s', $account);
$res = $stmt->execute();

if (!$res) {
  $response = array(
    'isSuccessful' => 'failed',
    'data'         => 'none',
    'msg'          => 'session variable is not set',
    'detail'       => 'none'
  );
  $response = json_encode($response);
  header('Content-Type: application/json;charset=utf-8');
  echo $response;
  die();
}
// use SQL transaction to ensure data transmission
// disable auto commit
$conn->autocommit(FALSE);
$conn->begin_transaction();
try {
  $res = $stmt->get_result();
  $data = array();
  while ($row = $res->fetch_assoc()) {
    array_push($data, array(
      'checked' => $row['checked'],
      'content' => $row['content'],
      'categories' => $row['categories'],
      'comment' => $row['comment'],
      'priority' => $row['priority']
    ));
  }
  // no errors, commit this 
  $conn->commit();
} catch (mysqli_sql_exception $exception) {
  mysqli_rollback($mysqli);
  $response = array(
    'isSuccessful' => 'failed',
    'data'         => 'none',
    'msg'          => 'session variable is not set',
    'detail'       => 'none'
  );
  $response = json_encode($response);
  header('Content-Type: application/json;charset=utf-8');
  echo $response;
  die();
}

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
