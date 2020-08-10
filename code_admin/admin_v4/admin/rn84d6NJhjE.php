<?php 

$code = $PURCODE;
$personalToken = "f7UVwyKdoNIPZGrhYc7sWUJ7oneVYC4o";
$userAgent = "Purchase code verification on skyforcoding.com";

$code = trim($code);

if (!preg_match("/^(\w{8})-((\w{4})-){3}(\w{12})$/", $code)) {
    throw new Exception("Invalid code");
}

$ch = curl_init();
curl_setopt_array($ch, array(
    CURLOPT_URL => "https://api.envato.com/v3/market/author/sale?code={$code}",
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_TIMEOUT => 20,
    
    CURLOPT_HTTPHEADER => array(
        "Authorization: Bearer {$personalToken}",
        "User-Agent: {$userAgent}"
    )
));

$response = @curl_exec($ch);

if (curl_errno($ch) > 0) { 
    try{
        throw new Exception("Error connecting to API: " . curl_error($ch));
    } catch(Exception $e) {
        echo $e->getMessage();
    }
}

$responseCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

if ($responseCode === 404) {
    try {
        throw new Exception("The purchase code was invalid");
    } catch(Exception $e) {
        echo $e->getMessage();
    }
}

if ($responseCode !== 200) {
    try {
        throw new Exception("Failed to validate code due to an error: HTTP {$responseCode}");
    } catch(Exception $e) {
        echo $e->getMessage();
    }

}

$body = @json_decode($response);

if ($body === false && json_last_error() !== JSON_ERROR_NONE) {
    try {
        throw new Exception("Error parsing response");
    } catch(Exception $e) {
        echo $e->getMessage();
    }
}

if (isset($body->item->name)) {

    $id = $body->item->id;
    $name = $body->item->name;

    if($id == 23898180) {
        $user = $_SESSION['user'];
        $session_id=session_id();
    } else {
        header("location:error.php");
      exit;
    }
}
else
{
    header("location:error.php");
    exit;
}

?>