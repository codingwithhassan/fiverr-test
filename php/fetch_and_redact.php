<?php

// Default provided function to encrypt the email address

function hash_value($value){

  return hash('sha256', $value);

}

// Method to hide the all the characters of words in address but first two

function hide_address($address) {

  // Initializing the new address variable

  $new_address = "";
  
  // Dividing the address to sub strings by spaces

  $address_part1 = explode(" ", $address);
  // Separating the first two characters of the word
  $address_part2 = substr($address_part1[0],2);
  
  // Combining the both above parts
  $new_address = substr($address_part1[0],0,2);
  // Replacing all the characters with *
  $new_address .= str_repeat("*", strlen($address_part2))." ";
  
  // Joining replaced and first two strings and returning it

  return $new_address;
}

// Method to fetch data from the give url

function fetch_data(){

  try {
  
    // Given API Link

    $host="https://tst-api.feeditback.com/exam.users";

    // Username & Password

    $user_name = 'dev_test_user';
    $password = 'V8(Zp7K9Ab94uRgmmx2gyuT.';

    // Creating an outfile variable to export json data later

    $out_file= 'users.json';

    //Initiating cURL request

    $ch = curl_init($host);

    // Set the header by creating the basic authentication

    $headers = array(
    'Content-Type: application/json',
    'Authorization: Basic '. base64_encode("$user_name:$password")
    );

    //Set the headers that we want our cURL client to use.

    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);
    
    // Set the RETURNTRANSFER as true so that output will come as a string
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    
    //Execute the cURL request.
    
    echo $response = curl_exec($ch);
    var_export($host, true);
    
    //Check if any errors occured.
    
    if(curl_errno($ch)){
    
      // throw the an Exception.
      throw new Exception(curl_error($ch));
    
    }

    // Getting the json data from the URL into an array

    $list = json_decode($response, TRUE);

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

      foreach($broken as $value2){        

        // Using method to obfuscate

        $broken = hide_address($value2);

        // Concating the multiple obfuscated values

        $value['address_obfuscated'] .= implode(" ", (array)$broken);
      }

      $list2 = $list + $value;

    }    

    // Exporting a users.json file as required

    if(file_exists($out_file)){
      echo '<br><br><br><center>';
      echo 'The file ' . $out_file . ' already exists, data will now append the file<br/>';
    }else{
      if($list2) { 
          if(file_put_contents($out_file, json_encode($list2), FILE_APPEND)) {
            echo "Success ! Saved JSON !";
          }
          else {
            echo "Error ! Unable to save JSON!";
          }
      }
    }
    echo "</pre>";
  }

  //catch exception
  catch(Exception $e) {
    echo 'Message: ' .$e->getMessage();
  }
}

fetch_data();
?>