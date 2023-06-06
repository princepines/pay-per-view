<?php
// Initialize the session
session_start();

// set 0 on device_once database
require "config.php";
$code = $_SESSION["code"];
$sql = "UPDATE events SET device_once = '0' WHERE code = '$code'";
if ($mysqli->query($sql) === true)
{
    // Unset all of the session variables
    $_SESSION = array();

    // Destroy the session.
    session_destroy();

    // Redirect to login page
    header("location: login.php");
    exit;
}
else
{
    echo "Error updating record: " . $mysqli->error;
}
?>
