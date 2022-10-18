<?php
namespace sql;

if (session_status() != PHP_SESSION_ACTIVE) {

    session_start();
}

if (! isset($_SESSION['userid'])) {

    die('You must be logged in to view this page.');
}

require_once 'scripts.php';
require_once 'connect.php';

$status = getPostVar('status');

switch ($status) {
    case 'active':
        $query = 'SELECT * FROM view_all_tasks WHERE Active IS NULL';
        break;

    case 'inactive':
        $query = 'SELECT * FROM view_all_tasks WHERE Active IS NOT NULL';
        break;

    case 'all':
        $query = 'SELECT * FROM view_all_tasks';
        break;

    default:
        // $query = 'SELECT * FROM view_all_tasks WHERE Active IS NULL';
        $query = 'SELECT * FROM view_all_tasks';
        break;
}

$conn = connectDB();

if ($conn->connect_error) {
    die('Could not connect to server. Please try again later.');
}

if ($result = $conn->query($query)) {

    echo '<div id="tableOutput"><br>';

    echo '<form action="task_info.php" method="post">';

    echo '<table class="list">';
    echo '<thead>';
    echo '<tr>';
    echo '<th>Name</th>';
    echo '<th>Recurring</th>';
    echo '<th>Active</th>';
    echo '<th>Action</th>';
    echo '</tr>';
    echo '</thead>';

    /* fetch associative array */
    while ($row = $result->fetch_assoc()) {
        echo '<tr class = "list">';

        // task name
        echo '<td>' . $row['Name'] . '</td>';

        // recurring task
        echo '<td>' . $row['Recurring'] . '</td>';

        // active task
        if (is_null($row['Active'])) {

            echo '<td>Y</td>';
        } else {

            echo '<td>N</td>';
        }

        // view
        echo '<td><span class="nowrap"><button class="link" type="submit" name="task" value=' . $row['ID'] . '><img src="images/magnifying_glass_2.png" style="width:16px;height:16px;border:0;">&nbsp;View</button></td>';

        echo '</tr>';
    }

    echo '</table>';

    echo '</div>';

    /* free result set */
    $result->free();
}

$conn->close();
