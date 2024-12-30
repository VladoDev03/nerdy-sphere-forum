<?php
require_once 'Category.php';
const DB_HOST = 'localhost';
const DB_USER = 'root';
const DB_PASS = 'T0rta@s@marmaladi7';
const DB_NAME = 'feedback_tutorial';

$connection = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);

if ($connection->connect_error) {
    die('Connection failed: ' . $connection->connect_error);
}

echo 'Connected successfully';

//echo Category::Comics->name;
