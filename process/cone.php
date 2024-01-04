<?php
$username = 'Multitelo';
$password = 'k77359095';
$conn = mysqli_connect("localhost", $username, $password, "qwestywait");

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
?>