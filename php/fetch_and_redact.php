<?php

function hash_value($value)
{
    return hash('sha256', $value);
}

function obfuscate($str)
{
    $obfuscate_str = "";
    $words = explode(' ', $str);
    foreach($words as $word){
        $len = strlen($word);
        if($len > 2){
            $word = substr($word,0,2);
            $word .= str_repeat("*", $len - 2);
        }
        $obfuscate_str .= $word." ";
    }
    return $obfuscate_str;
}

function request()
{
    $api_url = "https://tst-api.feeditback.com/exam.users";

    $user_name = 'dev_test_user';
    $password = 'V8(Zp7K9Ab94uRgmmx2gyuT.';

    $headers = [
        'Content-Type: application/json',
        'Authorization: Basic ' . base64_encode("{$user_name}:{$password}"),
    ];

    //Initiate cURL request
    $curlHandle = curl_init();

    curl_setopt($curlHandle, CURLOPT_URL, $api_url);
    curl_setopt($curlHandle, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($curlHandle, CURLOPT_SSL_VERIFYPEER, 0);
    curl_setopt($curlHandle, CURLOPT_TIMEOUT, 30);
    curl_setopt($curlHandle, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($curlHandle, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);

    // Set the RETURNTRANSFER as true so that output will come as a string
    curl_setopt($curlHandle, CURLOPT_RETURNTRANSFER, 1);

    //Execute the cURL request.
    $response = curl_exec($curlHandle);
    $curlInfo = curl_getinfo($curlHandle);

    if ($curlInfo['http_code'] != '200') {
        die("Something Went Wrong! Status Code: " . $curlInfo['http_code']);
    }

    //Check if any errors occured.
    if (curl_errno($curlHandle)) {
        die(curl_error($curlHandle));
    }

    return $response;
}

function fetch_data()
{
    try {
        $data = request();

        $list = json_decode($data, true);

        // Performing operations on that array

        foreach ($list as $value) {

            // Removing the latitude and longitude fields

            unset($list['latitude']);
            unset($list['longitude']);

            // Separating the address sentence by spaces

            $broken = explode(" ", $value['address']);

            // Adding a new obfuscated field to store obfuscated adress later

            $value = array('address_obfuscated' => '', 'email_hash' => '');

            // Encrypting the email field

            $value['email_hash'] = hash_value($value['email']);

            // Obfuscating the each word in the sentence

            foreach ($broken as $value2) {

                // Using method to obfuscate

                $broken = hide_address($value2);

                // Concating the multiple obfuscated values

                $value['address_obfuscated'] .= implode(" ", (array) $broken);
            }

            $list2 = $list + $value;

        }

        // Exporting a users.json file as required

        if (file_exists($out_file)) {
            echo '<br><br><br><center>';
            echo 'The file ' . $out_file . ' already exists, data will now append the file<br/>';
        } else {
            if ($list2) {
                if (file_put_contents($out_file, json_encode($list2), FILE_APPEND)) {
                    echo "Success ! Saved JSON !";
                } else {
                    echo "Error ! Unable to save JSON!";
                }
            }
        }
    }

    //catch exception
     catch (Exception $e) {
        error_log('Message: ' . $e->getMessage());
    }
}

fetch_data();
