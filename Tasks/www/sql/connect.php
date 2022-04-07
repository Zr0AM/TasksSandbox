<?php
namespace www\sql;

function connectDB()
{
  
  $db_hostname = 'localhost';
  $db_database = 'task_tracker_prod';
  $db_username = 'tasks_guest';
  $db_password = '9QkYzuG2ToMaCXji';
  
  return mysqli_connect($db_hostname, $db_username, $db_password, $db_database);
  
}

?>