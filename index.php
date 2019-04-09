<?php

ini_set("session.cookie_httponly", "True");
ini_set("session.cookie_secure", "True");

################################################################
#                                                              #
# PHP Script to Send Push Notifications From Pushbullet        #
# Author: Santhosh Veer (https://santhoshveer.com)             #
#                                                              #
################################################################

session_start ();

// Load phpdotenv - https://github.com/vlucas/phpdotenv
require_once dirname(__FILE__) . '/vendor/autoload.php';
$dotenv = new Dotenv\Dotenv(__DIR__);
$dotenv->load();

// Get APP KEY and APP ID From .env File
$APIURL =  getenv('APIURL');
$APIKEY = getenv('APIKEY');

function getUserIpAddr(){
  if(!empty($_SERVER['HTTP_CLIENT_IP'])){
      //ip from share internet
      $ip = $_SERVER['HTTP_CLIENT_IP'];
  } else if(!empty($_SERVER['HTTP_X_FORWARDED_FOR'])){
      //ip pass from proxy
      $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
  } else { 
      $ip = $_SERVER['REMOTE_ADDR'];
  }
  return $ip;
}

$userip = getUserIpAddr();

if($_SERVER["REQUEST_METHOD"] == "POST") {
if(isset($_SESSION['token']) && $_POST['token'] == $_SESSION['token']) {

  $name = $_POST["name"];
  $email = $_POST["email"];
  $body = $_POST["body"];
  
  $name = htmlspecialchars($name,ENT_COMPAT);
  $email = htmlspecialchars($email,ENT_COMPAT);
  $body = htmlspecialchars($body,ENT_COMPAT);

$data = array(
  "title"=> "Message From $name ($email - $userip)",
  "body"=> "$body",
  "type"=> "note"
  );

$data_string = json_encode($data);

$url = $APIURL;

$headers = array(
  "Access-Token: " . $APIKEY,
  "Content-Type: application/json; charset=utf-8"
);

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);                                                      
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);

$result = curl_exec($ch);
$code = curl_getinfo($ch, CURLINFO_HTTP_CODE);

curl_close ($ch);

switch ($code) {
  case "200":
      echo "<div class='alert alert-success text-center'><strong>your message was submitted successfully</strong></div>";
      break;
  case "400":
      echo "<div class='alert alert-warning text-center'><strong>Bad Request - missing a required parameter</strong></div>";
      break;
  case "401":
      echo "<div class='alert alert-warning text-center'><strong>No valid access token provided</strong></div>";
      break;
  case "403":
      echo "<div class='alert alert-warning text-center'><strong>The access token is not valid</strong></div>";
      break;
  case "404":
      echo "<div class='alert alert-warning text-center'><strong>API URL Not Found</strong></div>";
      break;
  default:
      echo "<div class='alert alert-warning text-center'><strong>Hmm Something Went Wrong or HTTP Status Code is Missing</strong></div>";
}

 }
}

//Generate Random Tokens
$token = $_SESSION['token'] = bin2hex(random_bytes(32));

?>

<!DOCTYPE html>
<html lang="en">
<head>

<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<link rel="shortcut icon" href="/favicon.png" type="image/png" />

<?php $current_page = "https://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
 echo '<link rel="canonical" href="'.$current_page.'" />'; ?>


<title>Pushbullet Contact Form</title>
<meta name="Description" content="PHP Contact Form With Pushbullet Notification.">

<!-- CSS and Fonts -->
<link href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.1/css/all.css" integrity="sha384-50oBUHEmvpQ+1lW4y57PTFmhCaXp0ML5d60M1M7uH2+nqUivzIebhndOJK28anvf" crossorigin="anonymous">
<link href="https://fonts.googleapis.com/css?family=Exo+2" rel="stylesheet">

<style>
body {
  background: #eee !important;
  font-family: 'Exo 2', sans-serif;
}
.login-form{
  margin:3% auto 0;
  max-width:380px;
}
.login-form h1{
  font-size: 30pt;
  font-weight: 700;
    letter-spacing: -1px;
    font-family: 'Exo 2', sans-serif;
}
.form-header,.form-footer{
  background-color: rgba(255, 255, 255, .8);
    border: 1px solid rgba(0,0,0,0.1);
}
.form-signin{
  padding: 45px 35px 45px;
    background-color: #fff;
    border: 1px solid rgba(0,0,0,0.1);  
    border-bottom: 0px; 
    border-top: 0px;  
}
.form-register{
  padding: 45px 35px 45px;
    background-color: #fff;
    border: 1px solid rgba(0,0,0,0.1);  
    border-bottom: 0px; 
    border-top: 0px; 
}
.form-header{
  text-align: center;
  padding: 15px 40px;
  border-radius: 10px 10px 0 0;
}
.form-header i{font-size:60px;}
.form-footer {
  padding: 15px 40px; 
}
.form-signin-heading{
  margin-bottom: 30px;
}
.btn {
    font-weight: 700;
    letter-spacing: 1px;
    text-transform: uppercase;
    color: #fff;
}
.bt-login{
    background-color: #ff8627;
    color: #ffffff;
    padding-bottom: 10px;
    padding-top: 10px;
    transition: background-color 300ms linear 0s;
}
.form-signin .form-control, .form-register .form-control{
  position: relative;
  height: auto;
  -webkit-box-sizing: border-box;
     -moz-box-sizing: border-box;
          box-sizing: border-box;
  padding: 10px;
  font-size: 16px;
}
.form-signin .form-control:focus, .form-register .form-control:focus {
  z-index: 2;
}
.form-signin input[type="email"] {
  margin-bottom: -1px;
  border-bottom-right-radius: 0;
  border-bottom-left-radius: 0;
}
input.parsley-error,
select.parsley-error,
textarea.parsley-error {    
    border-color:#843534;
    box-shadow: none;
}
input.parsley-error:focus,
select.parsley-error:focus,
textarea.parsley-error:focus {    
    border-color:#843534;
    box-shadow:inset 0 1px 1px rgba(0,0,0,.075),0 0 6px #ce8483
}
.parsley-errors-list {
    list-style-type: none;
    opacity: 0;
    transition: all .3s ease-in;

    color: #d16e6c;
    margin-top: 5px;
    margin-bottom: 0;
  padding-left: 0;
}
.parsley-errors-list.filled {
    opacity: 1;
    color: #a94442;
}
pre {
    overflow-x:auto;
    margin:1.5em 0 3em;
    padding:20px;
    max-width:100%;
    border:1px solid #000;
    color:#e5eff5;
    font-size: 14px;
    line-height:1.5em;
    background:#0e0f11;
    border-radius:5px
}
pre code{
    padding:0;
    font-size:inherit;
    line-height:inherit;
    background:transparent
}
pre code *{
    color:inherit
}
.btn {
    margin-bottom:4px;white-space: normal;
}
.input {
    margin-bottom:4px;white-space: normal;
}
.form-control {
    border-color: rgba(126, 239, 104, 0.8);
    box-shadow: 0 1px 1px rgba(0, 0, 0, 0.075) inset, 0 0 8px rgba(126, 239, 104, 0.6);
    outline: 0 none;
    margin-bottom: -1px;
    padding-bottom: 8px;
    padding-top: 8px;
}
::placeholder {
  white-space: normal;
  text-transform: uppercase;
  font-size: 13px;
}
</style>

<!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->

<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.2.1.min.js" integrity="sha256-hwg4gsxgFZhOsEEamdOYGBf13FyQuiTwlAQgxVSNgt4=" crossorigin="anonymous"></script>

</head>
<body>
<br />

<div class="container">
<div class="login-form">
<h2 class="text-center">Contact Us</h2>
<br>
<div class="form-header">
<i class="fab fa-telegram-plane"></i>
</div>
<form class="form-signin" method="post">
<div class="form-group">
<input class="form-control" type="text" name="name" placeholder="Your Name" data-parsley-required="true" data-parsley-error-message="Enter your Name">
</div>
<div class="form-group">
<input class="form-control" type="email" name="email" placeholder="Your Email ID" data-parsley-required="true" data-parsley-type="email" data-parsley-error-message="Enter a Valid Email">
</div>
<div class="form-group">
<textarea class="form-control" name="body" rows="5" placeholder=" Enter your Message" data-parsley-required="true" data-parsley-error-message="Enter Your Message"></textarea>
</div>
<input type="hidden" name="token" value="<?php echo $token; ?>">
<button type="submit" class="btn btn-block bt-login">Send Message</button>
</form>
<br />
<br />
</div>
</div>

<br />

<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/parsley.js/2.8.2/parsley.min.js" integrity="sha256-75TL99xMMUTAZIeDi7hBQwrYdig+bWn7kfFuDSSuJsc=" crossorigin="anonymous"></script>

<script>
$(document).ready(function(){
  $('form').parsley();
});
</script>

</body>
</html>