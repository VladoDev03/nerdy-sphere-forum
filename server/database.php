<?php

const DB_HOST = 'localhost';
const DB_USER = 'root';
const DB_PASS = 'T0rta@s@marmaladi7';
const DB_NAME = 'nerdy_sphere_forum';

$conn = getDatabaseConnection();

if (!$conn) {
    die('Connection failed: ' . mysqli_connect_error());
}

function getDatabaseConnection() {
    static $conn = null;

    if ($conn === null) {
        $conn = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);

        if (!$conn) {
            die('Connection failed: ' . mysqli_connect_error());
        }
    }

    return $conn;
}
