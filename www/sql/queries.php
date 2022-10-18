<?php
namespace sql;

require_once 'sql/connect.php';

$conn = connectDB();

if (! $conn->connect_error) {

    $conn->close();
}
