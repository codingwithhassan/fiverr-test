<?php

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

try {
    $connection = new mysqli("localhost","root","password","f_test",3306);
    $connection->set_charset("utf8mb4");
} catch (\Exception $e) {
    die("Error while connecting to database! : ".$e->getMessage());
}

set_exception_handler(function($e) {
    error_log($e->getMessage());
    exit('Something Went Wrong!');
});