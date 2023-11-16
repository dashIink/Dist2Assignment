<?php
    require 'vendor/autoload.php';
    use GuzzleHttp\Client;

        if(isset($_POST["reg_username"]) && isset($_POST["reg_password"])){
            $client = new GuzzleHttp\Client();
            $res = $client->post('http://localhost/DistAssign2/index.php/register', [
                'auth' =>  [$_POST["reg_username"], $_POST["reg_password"]]
            ]);
            $res->getStatusCode();           // 200
            $res->getHeader('content-type'); // 'application/json; charset=utf8'
            $res->getBody();                 // {"type":"User"...'
        }
        $_POST = array();
        header("location: login.php")
    
?>