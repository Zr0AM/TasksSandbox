<?php

session_start();

require_once 'html/htmlwrapper.php';
$objHtml = new html\htmlwrapper();
$objHtml::writeHeader($_SERVER["PHP_SELF"]);

if (! isset($_SESSION['userid']))
{
  
  die('You must be logged in to view this page.');
}

require_once 'sql/scripts.php';

$queueId = sql\getSessVar('viewQueueItem');
$completeID = sql\getSessVar('completeQueueItem');

if ((! isset($queueId) || empty($queueId)) && (! isset($completeID) || empty($completeID)))
{
  
  die('An invalid queue item was selected.');
}

require_once 'sql/connect.php';

$conn = sql\connectDB();

if (isset($completeID) && ! empty($completeID))
{
  
  $sql = 'CALL proc_complete_queue_task("' . $_SESSION['userid'] . '","' . $completeID . '")';
  
  $result = $conn->query($sql);
  
  $_SESSION['viewQueueItem'] = $completeID;
  $queueId = $completeID;
  
  unset($completeID);
  
  header('Location: queue_info.php');
}

$query = 'SELECT * FROM view_queue_info WHERE queue_id = "' . $queueId . '"';

if ($conn->connect_error)
{
  die('A valid queue item was not selected.');
}

if ($result = $conn->query($query))
{
  
  /* fetch associative array */
  while ($row = $result->fetch_assoc())
  {
    
    echo '<table class="display">';
    
    // task info
    echo '<tr class="display">';
    echo '<td colspan = "2" class="display">';
    
    // echo '<div class="smallhead">Task Name</div>';
    echo '<div>Task Name</div>';
    echo $row['task_name'];
    
    echo '</tr>';
    
    // last modified date
    echo '<tr class="display">';
    echo '<td class="display">';
    
    // echo '<div class="smallhead">Last Modified Date</div>';
    echo '<div>Last Modified Date</div>';
    
    $date = new \DateTime($row['queue_last_modified_date']);
    
    echo $date->format('Y-m-d');
    
    echo '</td>';
    
    // last modified by
    echo '<td class="display">';
    // echo '<div class="smallhead">Last Modified By</div>';
    echo '<div>Last Modified By</div>';
    
    echo $row['queue_last_modified_by'];
    
    echo '</td>';
    echo '</tr>';
    
    // start date
    echo '<tr class="display">';
    echo '<td class="display">';
    
    // echo '<div class="smallhead">Start Date</div>';
    echo '<div>Start Date</div>';
    
    $date = new \DateTime($row['queue_start_date']);
    
    echo $date->format('Y-m-d');
    
    echo '</td>';
    
    echo '</td>';
    
    echo '<td class="display">';
    
    // echo '<div class="smallhead">End Date</div>';
    echo '<div>Completed Date</div>';
    
    if (! is_null($row['queue_end_date']))
    {
      
      $date = new \DateTime($row['queue_end_date']);
      echo $date->format('Y-m-d');
    }
    else
    {
      
      echo 'N/A';
    }
    
    echo '</td>';
    
    echo '</tr>';
    
    // queue description
    echo '<tr class="display">';
    // echo '<td colspan = "2" class="display" style="max-width: 400px;">';
    echo '<td colspan = "2" class="display">';
    
    // echo '<div class="smallhead">Description</div>';
    echo '<div>Description</div>';
    echo $row['task_description'];
    
    echo '</tr>';
    
    echo '<tr class="display">';
    echo '<td class="display">';
    
    // echo '<div class="smallhead">Comment(s)</div>';
    echo '<div>Comment(s)</div>';
    
    echo $row['queue_comment'];
    
    echo '</tr>';
    
    echo '</table>';
  }
  
  /* free result set */
  $result->free();
}

$conn->close();

$objHtml::writeFooter();

?>