<?php
namespace sql;

if (session_status() != PHP_SESSION_ACTIVE)
{
  
  session_start();
}

if (! isset($_SESSION['userid']))
{
  
  die('You must be logged in to view this page.');
}

require_once 'scripts.php';
require_once 'connect.php';

$status = getPostVar('queueStatus');
$taskID = getPostVar('task');

$viewItem = getPostVar('viewQueueItem');
$completeItem = getPostVar('completeQueueItem');

if (isset($taskID) && ! empty($taskID))
{
  
  switch ($status)
  {
    case 'Outstanding':
      $query = 'SELECT * FROM `view_queue` WHERE PID = ' . $taskID . ' AND `Start Date` <= CURRENT_DATE AND `End Date` IS NULL';
      break;
    
    case 'Complete':
      $query = 'SELECT * FROM `view_queue` WHERE PID = ' . $taskID . ' AND `End Date` IS NOT NULL';
      break;
    
    case 'Upcoming':
      $query = 'SELECT * FROM `view_queue` WHERE PID = ' . $taskID . ' AND `Start Date` > CURRENT_DATE AND `End Date` IS NULL';
      break;
    
    case 'All':
      $query = 'SELECT * FROM `view_queue` WHERE PID = ' . $taskID;
      break;
    
    default:
      $query = 'SELECT * FROM `view_queue` WHERE PID = ' . $taskID . ' AND `Start Date` <= CURRENT_DATE AND `End Date` IS NULL';
      break;
  }
}
else
{
  
  switch ($status)
  {
    case 'Outstanding':
      $query = 'SELECT * FROM `view_queue` WHERE `Start Date` <= CURRENT_DATE AND `End Date` IS NULL';
      break;
    
    case 'Complete':
      $query = 'SELECT * FROM `view_queue` WHERE `End Date` IS NOT NULL';
      break;
    
    case 'Upcoming':
      $query = 'SELECT * FROM `view_queue` WHERE `Start Date` > CURRENT_DATE AND `End Date` IS NULL';
      break;
    
    case 'All':
      $query = 'SELECT * FROM `view_queue`';
      break;
    
    default:
      $query = 'SELECT * FROM `view_queue` WHERE `Start Date` <= CURRENT_DATE AND `End Date` IS NULL';
      break;
  }
}

// if view or complete is set, then redirect to queue_info.php
if (isset($viewItem) && ! empty($viewItem))
{
  
  $_SESSION['viewQueueItem'] = $viewItem;
  unset($viewItem);
  header('Location: queue_info.php');
}
elseif (isset($completeItem) && ! empty($completeItem))
{
  
  $_SESSION['completeQueueItem'] = $completeItem;
  unset($completeItem);
  header('Location: queue_info.php');
}

$conn = connectDB();

if ($conn->connect_error)
{
  die('Could not connect to server. Please try again later.');
}

if ($result = $conn->query($query))
{
  
  echo '<div id="queueOutput"><br>' . "\r\n\t\t\t";
  
  echo '<form target="_parent" method="post">' . "\r\n\t\t\t";
  
  echo '<table class="list">' . "\r\n\t\t\t\t";
  echo '<thead>' . "\r\n\t\t\t\t\t";
  echo '<tr>' . "\r\n\t\t\t\t\t\t";
  echo '<th class="list">Start Date</th>' . "\r\n\t\t\t\t\t\t";
  echo '<th class="list">Completed Date</th>' . "\r\n\t\t\t\t\t\t";
  echo '<th class="list">Name</th>' . "\r\n\t\t\t\t\t\t";
  
  echo '<th class="list">Action</th>' . "\r\n\t\t\t\t\t";
  echo '</tr>' . "\r\n\t\t\t\t";
  echo '</thead>' . "\r\n\r\n";
  
  /* fetch associative array */
  while ($row = $result->fetch_assoc())
  {
    echo "\t\t\t\t" . '<tr class = "list">' . "\r\n\t\t\t\t\t";
    
    // get start date
    $date = new \DateTime($row['Start Date']);
    
    echo '<td>' . $date->format('Y-m-d') . '</td>' . "\r\n\t\t\t\t\t";
    
    // get end date
    if (! is_null($row['End Date']))
    {
      
      $date = new \DateTime($row['End Date']);
      
      echo '<td>' . $date->format('Y-m-d');
    }
    else
    {
      
      echo '<td>';
    }
    echo '</td>' . "\r\n\t\t\t\t\t";
    
    echo '<td>' . $row['Name'] . '</td>' . "\r\n\t\t\t\t\t";
    
    // view
    echo '<td>' . "\r\n\t\t\t\t\t\t";
    echo '<span class="nowrap">' . "\r\n\t\t\t\t\t\t\t";
    echo '<button class="link" type="submit" name="viewQueueItem" value=' . $row['QID'] . '>' . "\r\n\t\t\t\t\t\t\t\t";
    echo '<img src="images/magnifying_glass_2.png" style="width:16px;height:16px;border:0;" alt="Magnifying Glass">View' . "\r\n\t\t\t\t\t\t\t";
    echo '</button>' . "\r\n\t\t\t\t\t\t\t";
    echo '&nbsp;' . "\r\n\t\t\t\t\t\t\t";
    echo '<button class="link" type="submit" name="completeQueueItem" value=' . $row['QID'] . '>' . "\r\n\t\t\t\t\t\t\t\t";
    echo '<img src="images/complete.png" style="width:16px;height:16px;border:0;" alt="Green Checkbox">Complete' . "\r\n\t\t\t\t\t\t\t";
    echo '</button>' . "\r\n\t\t\t\t\t\t";
    echo '</span>' . "\r\n\t\t\t\t\t";
    echo '</td>' . "\r\n\t\t\t\t";
    
    echo '</tr>' . "\r\n\t\t\t\r\n";
  }
  
  echo "\t\t\t" . '</table>' . "\r\n\t\t\t";
  
  echo '</form>' . "\r\n\t\t";
  
  echo '</div>' . "\r\n\t";
  
  /* free result set */
  $result->free();
}

$conn->close();

?>