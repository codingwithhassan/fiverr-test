<?php

require_once('./connection.php');

function getBranches()
{
    global $connection;
    $sql = "SELECT C.name AS Company,C.id as company_id,
                B.name AS Branch,
                DATE_FORMAT(SR.visit_datetime, '%m-%y') AS Month,
        FROM branches AS B
        JOIN survey_responses AS SR ON B.id = SR.branch_id
        JOIN companies AS C ON C.id = SR.company_id
        WHERE C.status = 1
        AND B.status = 1 AND B.brand_site = 0
        AND SR.survey_mode_id = 8 AND SR.status in (1,2,5)
        GROUP BY Branch,Month
        ORDER BY Branch,Month ASC";
    $statement = $connection->prepare($sql);
    $statement->execute();

    return $statement->get_result()->fetch_all(MYSQLI_ASSOC);
}

$branches = getBranches();

$connection->close();