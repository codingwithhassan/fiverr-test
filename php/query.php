<?php

// require_once('./connection.php');


function query($field, $value, $exact = TRUE)
{    
    $file = file_get_contents('users.json');
    $items_list = json_decode($file, TRUE);
    
    // global $connection;
    // $sql = "SELECT * FROM survey_responses";
    // $statement = $connection->prepare($sql);
    // $statement->execute();

    // $result = $statement->get_result();

    // $data = [];
    // while ($record = $result->fetch_assoc()) {
    //     $data[] = $record;
    // }

    // print_r($data);    

    if($exact){        
        foreach($items_list as $value2){            
            if($value2[$field]){                        
                if(in_array($value, $value2, $exact)){                
                    echo 'First Name:   ' . $value2['first_name'];
                    echo '  ';
                    echo 'Last Name:    ' . $value2['last_name'];
                    break;
                }else{
                    echo '<br>';
                    echo 'Error! Not found. Fields dont match.';
                    break;
                }
            }else{
                echo "Field dont match";
            }        
        }
    }else{
        foreach($items_list as $value2){
            if(strpos($value2[$field],$value)){
                echo 'First Name:   ' . $value2['first_name'];
                echo '  ';
                echo 'Last Name:    ' . $value2['last_name'];
                break;
            }else{
                echo "Error! Not found. Fields dont match.";
                break;
            }
        }
    }    
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
    $items_list = json_decode($file, TRUE);

    // Creating an outfile variable to export json data later

    $out_file= 'users-report.json';

    // Creating the requirements in the output

    foreach($items_list as $value){
        echo usort($value, 'date_compare');
    }

    // Exporting a users.json file as required

    if(file_exists($out_file)){
        echo '<br><br><br><center>';
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
echo "<br><br>";
query('id', '5be5884a331b2c695', FALSE);
echo "<br><br>";
query('id', '5be5884a331b24639s3cc695');
echo "<br><br>";
query('age', '22');
echo "<br><br>";
query('age', '20');
echo "<br><br>";
query('about', 'exa', FALSE);
echo "<br><br>";
query('about', 'ace', FALSE);
echo "<br><br>";
query('email', 'McConnellbranch@zytrek.com');
echo "<br><br>";
query('email', 'ryansand@xandem.com');
echo "<br><br>";
query('email', 'edwinachang', FALSE);

report();

// $connection->close();