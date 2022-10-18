<?php
session_start();

require_once 'html/htmlwrapper.php';
$objHtml = new html\htmlwrapper();
$objHtml::writeHeader($_SERVER["PHP_SELF"]);

if (! isset($_SESSION['userid'])) {

    die('You must be logged in to view this page.');
}

require_once 'sql/scripts.php';
require_once 'util/Dropdown.php';
require_once 'sql/scripts.php';

$taskId = sql\getPostVar('task');

$editForm = sql\getPostVar('edit');
$saveForm = sql\getPostVar('save');

$userId = $_SESSION['userid'];
$taskName = sql\getPostVar('taskName');
$taskDesc = sql\getPostVar('taskDescription');
$taskStart = sql\getPostVar('startDate');
$taskEnd = sql\getPostVar('endDate');
$taskRecur = sql\getPostVar('recurrOption');

$viewItem = sql\getPostVar('viewQueueItem');
$completeItem = sql\getPostVar('completeQueueItem');

if (isset($viewItem) && ! empty($viewItem)) {

    $_SESSION['viewQueueItem'] = $viewItem;
    unset($viewItem);
    header('Location: queue_info.php');
    exit();
} elseif (isset($completeItem) && ! empty($completeItem)) {

    $_SESSION['completeQueueItem'] = $completeItem;
    unset($completeItem);
    header('Location: queue_info.php');
    exit();
}

if (! isset($taskId) || empty($taskId)) {

    die('A relevant record was not found');
}

require_once 'sql/connect.php';

$query = 'SELECT * FROM view_tasks_expanded WHERE task_id = "' . $taskId . '"';

$conn = sql\connectDB();

if ($conn->connect_error) {

    die('A valid task was not selected');
}

if (! $result = $conn->query($query)) {

    die('An error ocurred');
}

echo '<form target="_self" method="post">';

if (isset($saveForm) && ! empty($saveForm)) {

    $stmt = $conn->prepare('CALL proc_update_task(?,?,?,?,?,?,?);');

    $stmt->bind_param('sssssss', $taskId, $taskName, $taskDesc, $taskStart, $taskEnd, $userId, $taskRecur);

    $stmt->execute();

    $stmt->close();

    $result = $conn->query($query);
}

if (isset($editForm) && ! empty($editForm)) {
    echo '<button class="link" type="submit" name="save" value="true"><img src="images/floppy.png" style="width:16px;height:16px;border:0;">&nbsp;Save</button>&nbsp&nbsp';
    echo '<button class="link" type="submit" name="discard" value="true"><img src="images/cancel.png" style="width:16px;height:16px;border:0;">&nbsp;Discard</button>';
} else {
    echo '<button class="link" type="submit" name="edit" value="true"><img src="images/pencil.png" style="width:16px;height:16px;border:0;">&nbsp;Edit</button>';
}

echo '<input type="hidden" id="task" name="task" value="' . $taskId . '">';

/* fetch associative array */
while ($row = $result->fetch_assoc()) {

    echo '<table class="display">';

    // first row - Last Modified Date/By
    echo '<tr class="display">';
    echo '<td class="display">';

    // echo '<div class="smallhead">Last Modified Date</div>';
    echo '<div>Last Modified Date</div>';

    $date = new \DateTime($row['task_last_modified_date']);

    echo $date->format('Y-m-d h:ia');

    echo '</td>';

    echo '<td class="display">';
    // echo '<div class="smallhead">Last Modified By</div>';
    echo '<div>Last Modified By</div>';

    echo $row['task_last_modified_by'];

    echo '</td>';
    echo '</tr>';

    // second row - Task Name
    echo '<tr class="display">';
    echo '<td colspan = "2" class="display">';

    // echo '<div class="smallhead">Task Name</div>';
    echo '<div>Task Name</div>';

    if (isset($editForm) && ! empty($editForm)) {

        echo '<input type="text" name="taskName" class="display" value="' . $row['task_name'] . '">';
    } else {

        echo $row['task_name'];
    }

    echo '</tr>';

    // third row - Description
    echo '<tr class="display">';
    echo '<td colspan = "2" class="display" style="max-width: 400px;">';

    // echo '<div class="smallhead">Description</div>';
    echo '<div>Description</div>';

    if (isset($editForm) && ! empty($editForm)) {

        echo '<textarea class="display" name="taskDescription">' . $row['task_description'] . '</textarea>';
    } else {

        echo $row['task_description'];
    }

    echo '</tr>';

    // fourth row - start/end date
    echo '<tr class="display">';
    echo '<td class="display">';

    // echo '<div class="smallhead">Start Date</div>';
    echo '<div>Start Date</div>';

    $date = new \DateTime($row['task_start_date']);

    if (isset($editForm) && ! empty($editForm)) {

        echo '<input type="date" name="startDate" class="display" value="' . $date->format('Y-m-d') . '">';
    } else {

        echo $date->format('Y-m-d');
    }

    echo '</td>';
    echo '<td class="display">';

    // echo '<div class="smallhead">End Date</div>';
    echo '<div>End Date</div>';

    if (isset($editForm) && ! empty($editForm)) {

        if (is_null($row['task_end_date'])) {

            echo '<input type="date" class="display" name="endDate">';
        } else {

            $date = new \DateTime($row['task_end_date']);

            echo '<input type="date" name="endDate" class="display" value="' . $date->format('Y-m-d') . '">';
        }
    } else {
        if (is_Null($row['task_end_date'])) {

            echo 'N/A';
        } else {

            $date = new \DateTime($row['task_end_date']);

            echo $date->format('Y-m-d');
        }
    }

    echo '</tr>';

    // recurrence info
    echo '<tr class="display">';
    echo '<td colspan = "2" class="display">';
    // echo '<div class="smallhead">Recurrence</div>';
    echo '<div>Recurrence</div>';

    if (isset($editForm) && ! empty($editForm)) {

        echo '<form>';

        echo '<select name="recurrOption" id="recurrOption" onchange="">';

        if (is_null($row['type_name'])) {

            echo '<option>Every</option>';
            echo '<option selected="selected">None</option>';
        } else {

            echo '<option selected="selected">Every</option>';
            echo '<option>None</option>';
        }

        echo '</select>&nbsp';

        // separation
        if (is_null($row['recurring_separation_count'])) {

            echo '<input type="number" min="1" max="10" value="1">';
        } else {

            echo '<input type="number" min="1" max="10" value="' . $row['recurring_separation_count'] . '">';
        }

        // weeks, months, years
        echo '<select name="recurrType" id="recurrType">';

        echo '<option value = "Weekly">Weeks</option>';
        echo '<option value = "Montly">Months</option>';
        echo '<option value = "Yearly">Years</option>';

        echo '</select>&nbspon ';

        echo '<select name="recurrOption" id="recurrOption" onchange="">';

        echo '<option value = "1">Monday</option>';
        echo '<option value = "2">Tuesday</option>';
        echo '<option value = "3">Wednesday</option>';
        echo '<option value = "4">Thursday</option>';
        echo '<option value = "5">Friday</option>';
        echo '<option value = "6">Saturday</option>';
        echo '<option value = "7">Sunday</option>';

        echo '</select>&nbsp';

        echo '</form>';
    } else {

        if (is_null($row['type_name'])) {

            echo 'None';
        } else {

            echo 'Every ' . $row['recurring_separation_count'] . ' ';

            if ($row['type_name'] == "Weekly") {

                echo 'week(s) on ';

                switch ($row['recurring_day_of_week']) {

                    case 0:
                        echo 'Monday';
                        break;
                    case 1:
                        echo 'Tuesday';
                        break;
                    case 2:
                        echo 'Wednesday';
                        break;
                    case 3:
                        echo 'Thursday';
                        break;
                    case 4:
                        echo 'Friday';
                        break;
                    case 5:
                        echo 'Saturday';
                        break;
                    default:
                        echo 'Sunday';
                        break;
                }
            } elseif ($row['type_name'] == 'Monthly') {

                echo 'month(s) on the ' . $row['recurring_day_of_month'];

                switch ($row['recurring_day_of_month']) {

                    case '1':
                    case '21':
                    case '31':
                        echo 'st';
                        break;
                    case '2':
                    case '22':
                        echo 'nd';
                        break;
                    case '3':
                    case '23':
                        echo 'rd';
                        break;
                    default:
                        echo 'th';
                        break;
                }

                echo ' day of the month';
            } else {

                echo 'year(s) on ';

                switch ($row['recurring_month_of_year']) {

                    case '1':
                        echo 'Jan';
                        break;
                    case '2':
                        echo 'Feb';
                        break;
                    case '3':
                        echo 'Mar';
                        break;
                    case '4':
                        echo 'Apr';
                        break;
                    case '5':
                        echo 'May';
                        break;
                    case '6':
                        echo 'Jun';
                        break;
                    case '7':
                        echo 'Jul';
                        break;
                    case '8':
                        echo 'Aug';
                        break;
                    case '9':
                        echo 'Sep';
                        break;
                    case '10':
                        echo 'Oct';
                        break;
                    case '11':
                        echo 'Nov';
                        break;
                    default:
                        echo 'Dec';
                        break;
                }

                echo ' ' . $row['recurring_day_of_month'];

                switch ($row['recurring_day_of_month']) {

                    case '1':
                    case '21':
                    case '31':
                        echo 'st';
                        break;
                    case '2':
                    case '22':
                        echo 'nd';
                        break;
                    case '3':
                    case '23':
                        echo 'rd';
                        break;
                    default:
                        echo 'th';
                        break;
                }
            }
        }
    }
}

echo '</tr>';

echo '</table>';

echo '</form>';

echo '<br>';

/* free result set */
$result->free();

$conn->close();

$objDropdown = new util\Dropdown();

$objDropdown->addOptions(Array(
    "Outstanding",
    "Complete",
    "Upcoming",
    "All"
));

$objDropdown->setDisplayName("Queue Status");
$objDropdown->setId("queueSelection");
$objDropdown->setObjName("queueStatus");
$objDropdown->setOnChange("getQueueStatusWithTask(this.value," . sql\getPostVar('task') . ")");
$objDropdown->setDefaultOption(0);

echo $objDropdown->getHTML();

require_once 'sql/list_queue.php';
$objHtml::writeFooter();
?>
