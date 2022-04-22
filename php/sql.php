<?php

require_once('./connection.php');

function getReviews($social_type_id)
{
    global $connection;
    $sql = "SELECT 
            count(social_raw_score) AS reviews,
            sum(social_raw_score)/count(social_raw_score) as average,
            B.name AS Branch,
            DATE_FORMAT(SR.visit_datetime, '%m-%y') AS Month
        FROM branches AS B
        JOIN survey_responses AS SR ON B.id = SR.branch_id
        JOIN social_types AS ST ON ST.id = SR.social_type_id
        JOIN companies AS C ON C.id = SR.company_id
        WHERE C.status = 1
        AND B.status = 1 AND B.brand_site = 0
        AND SR.survey_mode_id = 8 AND SR.status in (1,2,5)
        AND ST.id = ?
        GROUP BY Branch,Month
        ORDER BY Branch,Month ASC";
    $statement = $connection->prepare($sql);
    $statement->bind_param('i',$social_type_id);
    $statement->execute();

    return $statement->get_result()->fetch_assoc();
}

function getBranches()
{
    global $connection;
    $sql = "SELECT C.name AS Company,C.id as company_id,
                B.name AS Branch,
                DATE_FORMAT(SR.visit_datetime, '%m-%y') AS Month
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

$statement = $connection->prepare("SELECT * FROM social_types");
$statement->execute();
$social_types = $statement->get_result()->fetch_all(MYSQLI_ASSOC);

$branches_reviews = array_map(function($item){
    global $social_types;

    foreach ($social_types as $type) {
        $reviews = getReviews($type['id']);
        if(!empty($reviews)){
            $item[$type['name'].' Reviews'] = $reviews['reviews'];
            $item[$type['name'].' Rating'] = $reviews['average'];
        }
    }

    return $item;
},$branches);

var_export($branches_reviews);

$connection->close();