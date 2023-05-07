<?php

$host = "localhost";
$user = "root";
$password = "";
$dbname = "medicines";


$con = mysqli_connect($host, $user, $password);


if (!$con) {
    die("Connection failed: " . mysqli_connect_error());
}


$sql = "CREATE DATABASE IF NOT EXISTS $dbname";
if (mysqli_query($con, $sql)) {
    echo "Database created successfully<br>";
} else {
    echo "Error creating database: " . mysqli_error($con) . "<br>";
}


mysqli_select_db($con, $dbname);


$sql = "CREATE TABLE IF NOT EXISTS available_medicines (
    id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    medicine_name VARCHAR(50) NOT NULL,
    brand VARCHAR(50) NOT NULL,
    manufacturer VARCHAR(50) NOT NULL,
    price FLOAT(8,2) NOT NULL,
    quantity INT(10) NOT NULL,
    composition TEXT NOT NULL
)";
if (mysqli_query($con, $sql)) {
    echo "Table 'available_medicines' created successfully<br>";
} else {
    echo "Error creating table 'available_medicines': " . mysqli_error($con) . "<br>";
}


$sql = "CREATE TABLE IF NOT EXISTS medicine_composition (
    id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    medicine_name VARCHAR(50) NOT NULL,
    brand VARCHAR(50) NOT NULL,
    composition TEXT NOT NULL
)";
if (mysqli_query($con, $sql)) {
    echo "Table 'medicine_composition' created successfully<br>";
} else {
    echo "Error creating table 'medicine_composition': " . mysqli_error($con) . "<br>";
}


mysqli_close($con);
?>