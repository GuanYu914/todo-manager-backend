<?php
if (!isset($_SESSION)) {
  session_name('todo-manager');
  session_start();
}
$_SESSION = array();
session_destroy();
