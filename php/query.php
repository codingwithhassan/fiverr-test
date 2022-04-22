<?php

require_once('./connection.php');

function query($field, $value, $exact = TRUE)
{
    global $connection;
    $sql = "SELECT * FROM survey_responses";
    $statement = $connection->prepare($sql);
    $statement->execute();

    $result = $statement->get_result();

    $data = [];
    while ($record = $result->fetch_assoc()) {
        $data[] = $record;
    }

    print_r($data);
}

function report()
{
    print("Report Done!");
}

query('id', '5be5884a7ab109472363c6cd');
query('id', '5be5884a331b2c695', FALSE);
query('id', '5be5884a331b24639s3cc695');
query('age', '22');
query('age', '20');
query('about', 'exa', FALSE);
query('about', 'ace', FALSE);
query('email', 'mcconnellbranch@zytrek.com');
query('email', 'ryansand@xandem.com');
query('email', 'edwinachang', FALSE);

report();

$connection->close();