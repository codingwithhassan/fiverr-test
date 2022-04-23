<?php

function query($field, $value, $exact = true)
{
    $file = file_get_contents('./users.json');

    $list = json_decode($file, true) or die("Invalid JSON Format!");

    if ($field == 'email') {
        $value = hash('sha256', $value);
    }

    $count = 0;

    foreach ($list as $item) {
        if (isset($item[$field])) {
            if ($exact ? $item[$field] == $value : strpos($item[$field], $value)) {
                echo 'Name:   ' . $item['first_name'] . ' ' . $item['last_name'];
                $count++;
                echo "\n";
            }
        } else {
            die("\nError! Field Not found!");
        }
    }

    echo $count . " Records Found.\n";
    echo "________________________\n";
}

function report()
{
    $file = file_get_contents('./users.json');

    $list = json_decode($file, true) or die("Invalid JSON Format!");

    $data = [];

    usort($list, function ($a, $b) {
        $ad = new DateTime($a['created']);
        $bd = new DateTime($b['created']);

        if ($ad == $bd) {
            return 0;
        }

        return $ad < $bd ? -1 : 1;
    });

    $sum = 0;
    $count = 0;

    foreach ($list as $item) {
        $value['name'] = $item['first_name'] . ' ' . $item['last_name'];
        $value['favorite_colour'] = $item['favorite_colour'];
        $value['about'] = substr($item['about'],0,20) . " ....";
        $data['users'][] = $value;

        $sum += $item['age'];
        $count++;
    }

    $data['average_age'] = round($sum / $count,2);

    $json = json_encode($data);

    // Exporting a users-report.json as report
    $file = fopen("./users-report.json", "w") or die("Error! File Not Opened!");
    fwrite($file, $json);
    fclose($file);

    echo "JSON Data saved successfully to file.";
}

/*
query('id', '5be5884a7ab109472363c6cd');
query('id', '5be5884a331b2c695', false);
query('id', '5be5884a331b24639s3cc695');
query('age', '22');
query('age', '20');
query('about', 'exa', false);
query('about', 'ace', false);
query('email', 'McConnellbranch@zytrek.com');
query('email', 'ryansand@xandem.com');
query('email', 'edwinachang', false);
*/
report();
