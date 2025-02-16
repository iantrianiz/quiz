<?php
function isValidPhoneNumber($phone_number, $customer_id, $api_key, $key_id) {
    // Added this routine to ensure phone number is in E.164 format
    if (!preg_match('/^\+\d{9,15}$/', $phone_number)) {
        die("Invalid phone number format.");
    }

    $api_url = "https://rest-ww.telesign.com/v1/phoneid/standard/" . urlencode($phone_number);

    $auth_header = "Authorization: Basic " . base64_encode(trim($customer_id) . ":" . trim($api_key));
   
    //I changes the headers with this one, contain the Basic Authentication as on Telesign Documentations
    $headers = [
        $auth_header,
        "Accept: application/json",
        "x-ts-keyid: $key_id"
    ];
    
    
    
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $api_url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    //Changes from POST to GET
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");

    $response = curl_exec($ch);
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($http_code !== 200) {
        return false;
    }

    $data = json_decode($response, true);
    return isset($data['numbering']['phone_type']);
}

//Changes this with Real Data, if you have Upgraded Version of Telesign account then this ones is already auto verified, but if you used Trial account then this number status is Pre Verified
$phone_number = "+6289510162246"; 

//Customer ID on Telesign, if you want to test then please changes this with the Real Telesign Data
$customer_id = "3B0D7C64-3FC0-4D22-8FBA-0C1CADD3EEFB";  

//API Key on Telesign, if you want to test then please changes this with the Real Telesign Data
$api_key = "LTBUKhsVsGPbBKkT6ZQKBjr6fIxSC/taItsiUIOotAmAZ/BKEO1/mU30w2ehMMX1Jmi4jG95vcH1av8V4U8a1g=="; 

//Key ID on Telesign, if you want to test then please changes this with the Real Telesign Data
$key_id = "76B71F73-E898-44E2-8D42-C1437216E5E7";

$result = isValidPhoneNumber($phone_number, $customer_id, $api_key, $key_id);
var_dump($result);

?>
