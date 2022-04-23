<?php

function hash_value($value)
{
    return hash('sha256', $value);
}

function obfuscate($data)
{
    $clear = strpos($data, ' ');
    $output = substr($data, 0, strpos($data, ' '));
    // for non-emails addresses: keep last two characters clear
    if ($clear === false) {
        $clear = max(2, strlen($data) - 2);
    }

    // start hiding from 3rd character onwards, or earlier in some exceptional cases:
    $hide = max(0, min($clear - 1, 2));
    $result = "";
    for ($i = 0; $i < strlen($data); $i++) {
        $result .= substr($data, $i, $hide) .
        str_repeat("*", $clear - $hide) .
        substr($data, $clear);
    }

    return $result;
}

function fetch_data()
{
    try {

        $host = "https://tst-api.feeditback.com/exam.users";

        $user_name = 'dev_test_user';
        $password = 'V8(Zp7K9Ab94uRgmmx2gyuT.';

        $outfile = 'users.json';

        //Initiate cURL request
        $curlHandle = curl_init($host);

        // Set the header by creating the basic authentication
        $headers = array(
            'Content-Type: application/json',
            'Authorization: Basic ' . base64_encode("$user_name:$password"),
        );
        //Set the headers that we want our cURL client to use.

        curl_setopt($curlHandle, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($curlHandle, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);
        // Set the RETURNTRANSFER as true so that output will come as a string
        curl_setopt($curlHandle, CURLOPT_RETURNTRANSFER, 1);

        //Execute the cURL request.
        $response = curl_exec($curlHandle);
        $curlInfo = curl_getinfo($curlHandle);


        //Check if any errors occured.
        if (curl_errno($curlHandle)) {
            throw new Exception(curl_error($curlHandle));
        }

        $list = json_decode($response, true);

        foreach ($list as $value) {
            unset($value['latitude']);
            unset($value['longitude']);
            $value['hash_email'] = hash_value($value['email']);

            $broken = explode(" ", $value['address']);
            foreach ($broken as $broken) {
                $value['address'] .= str_replace($broken, str_repeat('*', strlen($broken)), str_repeat('*', strlen($broken)));
                $value['address'] = obfuscate($broken);
            }
            // $value['address'] = obfuscate($address);

            print_r($value['address']);

        }

        // if(file_exists($outfile)){
        //   echo 'The file ' . $filename . ' already exists, data will now append the file<br/>';
        // }else{
        //   if($response) {
        //       if(file_put_contents($outfile, $response, FILE_APPEND)) {
        //         echo "Saved JSON fetched from “{$host}” as “{$outfile}”.";
        //       }
        //       else {
        //         echo "Unable to save JSON to “{$outfile}”.";
        //       }
        //   }
        // }
    }

    //catch exception
     catch (Exception $e) {
        error_log('Message: ' . $e->getMessage());
    }
}

fetch_data();
