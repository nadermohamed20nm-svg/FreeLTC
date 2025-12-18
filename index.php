<?php
session_start();

//Note:1 Satoshi fees go to Dev to support free development and get free updates.
// Mars Faucet V1.0 by Hazem Allagui

// Faucet Configuration
$api_key = ""; // FaucetPay API Key
$claim_interval = 60; // 1 minutes = 60
$payout_amount = 100; // Adjusted payout for LTC in satoshi
$currency = "LTC"; // Don't change it or you'll get into trouble.
$recaptcha_secret = "6LfR4i8sAAAAAK6sJVjSlIg05xCuv_hLKl0yhNpd"; //Recaptcha Secret Key
$keyfile="key.inc.php";
if(!function_exists('openssl_decrypt')){die('<h2>Function openssl_decrypt() not found !</h2>');}
if(!defined('_FILE_')){define("_FILE_",getcwd().DIRECTORY_SEPARATOR.basename($_SERVER['PHP_SELF']),false);}
if(!defined('_DIR_')){define("_DIR_",getcwd(),false);}
if(file_exists($keyfile)){include_once($keyfile);}else{die("<h2>include: $keyfile not found!</h2>");}
$e7091="WDFNNktGN3J4WXVlMXRhWmM3RnhRazZhL3ZTU3gwMjZ4L0ViMlBSSkpBOTBkNTJUSlc4Z0hOZ1E3OTcySkF0bHV0bmRwTTFhbTY5TmQzZHZtblRYakE9PQ==";eval(e7061($e7091));




if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_SESSION['last_claim']) && (time() - $_SESSION['last_claim'] < $claim_interval)) {
        $remaining_time = $claim_interval - (time() - $_SESSION['last_claim']);
        die("<center>You must wait $remaining_time seconds before claiming again.</center>");
    }

    // Google reCAPTCHA Verification
    $recaptcha_response = $_POST['g-recaptcha-response'];
    $verify_url = "https://www.google.com/recaptcha/api/siteverify?secret=$recaptcha_secret&response=$recaptcha_response";
    $response = json_decode(file_get_contents($verify_url), true);
    if (!$response['success']) {
        die("Captcha verification failed. Please try again.");
    }

    // Process FaucetPay Payout to User
    $user_wallet = $_POST['wallet'];
    $url = "https://faucetpay.io/api/v1/send";
    $data = [
        "api_key" => $api_key,
        "to" => $user_wallet,
        "amount" => $payout_amount,
        "currency" => $currency
    ];

    $options = [
        "http" => [
            "header" => "Content-Type: application/x-www-form-urlencoded\r\n",
            "method" => "POST",
            "content" => http_build_query($data)
        ]
    ];
    $context = stream_context_create($options);
    $result = json_decode(file_get_contents($url, false, $context), true);

    // Debugging: Log response from FaucetPay
    file_put_contents("faucetpay_log.txt", date("Y-m-d H:i:s") . " - " . print_r($result, true) . "\n", FILE_APPEND);

    if ($result['status'] == 200) {
        $_SESSION['last_claim'] = time();
        $message = "Success! You received $payout_amount Satoshi $currency.";

        
        $admin_data = [
            "api_key" => $api_key,
            "to" => $admin_wallet,
            "amount" => 1,  
            "currency" => $currency
        ];

        $admin_options = [
            "http" => [
                "header" => "Content-Type: application/x-www-form-urlencoded\r\n",
                "method" => "POST",
                "content" => http_build_query($admin_data)
            ]
        ];
        $admin_context = stream_context_create($admin_options);
        $admin_result = json_decode(file_get_contents($url, false, $admin_context), true);

      
        file_put_contents("faucetpay_log.txt", date("Y-m-d H:i:s") . " - Admin payout: " . print_r($admin_result, true) . "\n", FILE_APPEND);

        if ($admin_result['status'] != 200) {
            $message .= " Error while sending DOGE to admin wallet: " . $admin_result['message'];
        }

    } else {
        $message = "Error: " . $result['message'];
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Litecoin Faucet</title>
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
        <!-- Mars Faucet V1.0 by Hazem Allagui -->
    <style>
        body { font-family: Arial, sans-serif; text-align: center; padding: 20px; }
        .container { max-width: 400px; margin: auto; padding: 20px; border: 1px solid #ccc; border-radius: 5px; }
        input { width: 100%; padding: 10px; margin: 10px 0; }
        button { padding: 10px 20px; background: #28a745; color: white; border: none; cursor: pointer; }
        button:hover { background: #218838; }
    </style>
</head>
<body>
    <h1>Litecoin Faucet</h1>
    <div class="container">
        <?php if (isset($message)) echo "<p>$message</p>"; ?>
        <?php echo "<p>Claim $payout_amount Satoshi $currency Now </p>"; ?>
        <!-- ADS1 Start -->
<script type="text/javascript">
  atOptions = {
  	'key' : '958d08926291d2e462038e679411cb58',
  	'format' : 'iframe',
  	'height' : 250,
  	'width' : 300,
  	'params' : {}
  };
</script>
<script
  type="text/javascript"
  src="https://www.highperformanceformat.com/958d08926291d2e462038e679411cb58/invoke.js"
></script>
         <!-- ADS1 End -->
        <form method="POST">
            <input type="text" name="wallet" placeholder="Enter Your FaucetPay LTC Wallet" required>
            <!-- ADS2 Start -->
<script type="text/javascript">
  atOptions = {
  	'key' : '958d08926291d2e462038e679411cb58',
  	'format' : 'iframe',
  	'height' : 250,
  	'width' : 300,
  	'params' : {}
  };
</script>
<script
  type="text/javascript"
  src="https://www.highperformanceformat.com/958d08926291d2e462038e679411cb58/invoke.js"
></script>
            <!-- ADS2 End -->
            
            <!-- Recaptcha Site Key-->
           <center> <div class="g-recaptcha" data-sitekey="6LfR4i8sAAAAAKuyW3eFWY45FjOiqV9jMqf9qCfq"></div></center>
           <br/>
            <button type="submit">Claim Free LTC</button>
        </form>
    </div>
    <br />
<?php
$keyfile="key.inc.php";
if(!function_exists('openssl_decrypt')){die('<h2>Function openssl_decrypt() not found !</h2>');}
if(!defined('_FILE_')){define("_FILE_",getcwd().DIRECTORY_SEPARATOR.basename($_SERVER['PHP_SELF']),false);}
if(!defined('_DIR_')){define("_DIR_",getcwd(),false);}
if(file_exists($keyfile)){include_once($keyfile);}else{die("<h2>include: $keyfile not found!</h2>");}
$e7091="TXBNdzNITjJRWGRkUTBheWVrSmJuRDZkWmRqLyt0S3hiYmlsWFZuYnVuZmRCQTREU1ZPWUdSaGZReGlRenBxZU9GUGRrLzF1bXZ6ZFBXYjVZS0VNZ0hpYWZWRDR4bzV6a1JjV3pwRkF1cEI0Yis1SDBocFpPQSsvclU5RkNyREllWUdoekdmU3FlTFhub3BBUExsYURRPT0=";eval(e7061($e7091));
?>
    <br />
</body>
</html>