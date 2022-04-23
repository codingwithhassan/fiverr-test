<?php

function query($field, $value, $exact = TRUE)
{
    $file = file_get_contents('./users.json');

    $list = json_decode($file, TRUE) or die("Invalid JSON Format!");

    if($field == 'email')
        $value = hash('sha256', $value);

    $count = 0;

    foreach($list as $item){
        if(isset($item[$field])){
            if($exact ? $item[$field] == $value : strpos($item[$field],$value)){
                echo 'Name:   ' . $item['first_name'] . ' ' . $item['last_name'];
                $count++;
                echo "\n";
            }
        }else{
            die("\nError! Field Not found!");
        }
    }

    echo $count . " Records Found.\n";
    echo "________________________\n";
}

function date_compare($a, $b)
{
    $t1 = strtotime($a['datetime']);
    $t2 = strtotime($b['datetime']);
    return $t1 - $t2;
} 

function report()
{
    $file = file_get_contents('users.json');
    $list = json_decode($file, TRUE);

    // Creating an outfile variable to export json data later

    $out_file= 'users-report.json';

    // Creating the requirements in the output

    foreach($list as $value){
        echo usort($value, 'date_compare');
    }

    // Exporting a users.json file as required

    if(file_exists($out_file)){
        echo '\n<br><center>';
        echo 'The file ' . $out_file . ' already exists, data will now append the file<br/>';
      }else{
        if($list) { 
            if(file_put_contents($out_file, json_encode($list), FILE_APPEND)) {
              echo "Success ! Saved JSON !";
            }
            else {
              echo "Error ! Unable to save JSON!";
            }
        }
      }

    print("Report Done!");
}

query('id', '5be5884a7ab109472363c6cd');
echo "\n";
query('id', '5be5884a331b2c695', FALSE);
echo "\n";
query('id', '5be5884a331b24639s3cc695');
echo "\n";
query('age', '22');
echo "\n";
query('age', '20');
echo "\n";
query('about', 'exa', FALSE);
echo "\n";
query('about', 'ace', FALSE);
echo "\n";
query('email', 'McConnellbranch@zytrek.com');
echo "\n";
query('email', 'ryansand@xandem.com');
echo "\n";
query('email', 'edwinachang', FALSE);

// report();