<?php

$connection = new mysqli("localhost", "root", "T0rta@s@marmaladi7", "nerdy_sphere_forum");

if ($connection->connect_error) {
    die("Connection failed: " . $connection->connect_error);
}

echo "Connected successfully";
