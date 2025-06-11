<?php
$user = "root";
$server = "127.0.0.1";
$port = "3306";
$database = "pl";
$pass = "Dontdosql101!";

$conn = mysqli_connect($server, $user, $pass, $database, $port);
// $conn = mysqli_connect("127.0.0.1", "root", "Dontdosql101!", "pl", "3306");
if(!$conn){
    die("Connection failed: " . mysqli_connect_error());
}
//else print("Successfully connected to database.");

?>