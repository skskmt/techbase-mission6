<html>
  <head>
  <title>techbase mission 6 dev</title>
  <meta charset="utf-8">
  <style type="text/css">
        body{ 
        background-color:#dddddd;
        color : #000000;
        margin-right: auto;
        margin-left : auto;
        width:1100px;
        font-size:110%;
        }
        #contents{
        width:700px;
        min-height:300px;
        color : #000000;
        margin-top:1.5%; 
        margin-bottom: 1.5%;
        background-color :#ffffff;
        font-size: 1.6em;
        line-height: 1.5em;
        border-radius: 15px;
        }
        textarea {
        width: 400px;
        height: 10em;
        }
        </style>
  </head>
  <body>

<?php
require_once("class.php");
$register_auth = new Register(); 
if (isset($_GET["urltoken"])){
    $token_get = $_GET["urltoken"];
    $register_auth -> checkUrltoken($token_get);
}

?>
<br>
<a href="login.php">こちらからログインしてください。</a>

</body>
</html>