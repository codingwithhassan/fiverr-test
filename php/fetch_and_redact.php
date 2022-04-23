<?php

function hash_value($value)
{
    return hash('sha256', $value);
}

function obfuscate($str)
{
    $obfuscate_str = "";
    $words = explode(' ', $str);
    foreach ($words as $word) {
        $len = strlen($word);
        if ($len > 2) {
            $word = substr($word, 0, 2);
            $word .= str_repeat("*", $len - 2);
        }
        $obfuscate_str .= $word . " ";
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
        die("API Call Failed! Status Code: " . $curlInfo['http_code']);
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
        $redact_data = [];
        foreach ($list as $value) {

            // Removing the latitude and longitude fields
            unset($value['latitude']);
            unset($value['longitude']);

            // Encrypting the email field
            $value['email'] = hash_value($value['email']);
            // Obfuscate the address string
            $value['address'] = obfuscate($value['address']);

            $redact_data[] = $value;
        }

        $json = json_encode($redact_data);

        // Exporting json to users.json file
        $file = fopen("./users.json", "w") or die("Error! File Not Opened!");
        fwrite($file,$json);
        fclose($file);

    } catch (Exception $e) {
        die('Message: ' . $e->getMessage());
    }
}

fetch_data();
