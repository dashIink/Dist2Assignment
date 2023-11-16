<?php
    include_once("tbl_steps.php");
    include_once("tbl_userlogin.php");
    include_once("tbl_workout.php");
    
    $conn = connect();

    $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    $uri = explode( '/', $uri );
    $uri[] = $_SERVER['REQUEST_METHOD'];
    $responseData = [];

    $headers=array();
    foreach (getallheaders() as $name => $value) {
        $headers[$name] = $value;
    }
    $tbl_userlogin = new tbl_userlogin($conn);
    $tbl_steps = new tbl_steps($conn);
    $tbl_workout = new tbl_workout($conn);

    if($_SERVER['REQUEST_METHOD'] === 'GET'){
        
        if($uri[3] == "auth"){
            $credentials = $headers["Authorization"];
            $credentials = explode( ' ', $credentials);
            $credentialsBody = base64_decode($credentials[1]);
            $credentials = explode( ':', $credentialsBody);

            $return = $tbl_userlogin->checkIfAuth($credentials[0], $credentials[1]);
            if ($return[0] == "success"){
                $responseData[] = "Success"; 
                $responseData[] = "home.php";
                $responseData[] = $return[1];

            }
            else{
                $responseData[] = "Failed";

            }
            $responseData[] = "authentication";
        }
        elseif($uri[3] == "steps"){
            
            $return = $tbl_steps->getSteps($uri[4]);
            if (count($return) > 0){
                $responseData[] = "Success";
            }
            else{
                $responseData[] = "Failed";
            }
            $responseData[] = "stepsGet";
            $responseData[] = $return;
        }
        elseif($uri[3] == "workout"){
            $return = $tbl_workout->getTable($uri[4]);
            if (count($return) > 0){
                $responseData[] = "Success";
            }
            else{
                $responseData[] = "Failed";
            }
            $responseData[] = "workoutGet";
            $responseData[] = $return;
        }
       
        $headercode = ' 200 OK';
        
        
    }
    else if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if ($uri[3] == "register"){
            
            $credentials = $headers["Authorization"];
            $credentials = explode( ' ', $credentials);
            $credentialsBody = base64_decode($credentials[1]);
            $credentials = explode( ':', $credentialsBody);
            $return = $tbl_userlogin->addToTable($credentials[0], $credentials[1]);
            if ($return == "success"){
                $responseData[] = "Success";
            }else{
                $responseData[] = "Failed";
            }
            $responseData[] = "registration";
            
        }
        if ($uri[3] == "workoutAdd"){
            
            $return = $tbl_workout->addToTable($_GET['id'], $_GET['type'], $_GET['specifics']);
            if ($return == "success"){
                $responseData[] = "Success";
            }else{
                $responseData[] = "Failed";
            }
            $responseData[] = "workoutAdd";
            
        }
        $headercode = ' 200 OK';
        
    }
    else if($_SERVER['REQUEST_METHOD'] === 'PUT') {
        if($uri[3] == "stepsadd"){

            $return = $tbl_steps->updateSteps($_GET['id'], $_GET['steps']);
            
            $responseData[] = "stepsGet";
            $responseData[] = $return;
        }
        $headercode = ' 200 OK';
        
    }
    else if($_SERVER['REQUEST_METHOD'] === 'DELETE') {
        if ($uri[3] == "workoutDelete"){
            $return = $tbl_workout->deleteTable($_GET['id']);
            if ($return == "success"){
                $responseData[] = "Success";
            }else{
                $responseData[] = "Failed";
            }
            $responseData[] = "workoutDelete";
            
        }
        $headercode = ' 200 OK';
    }
    else{
        $headercode = ' 405 Method Not Found';
    }
    $responseData[] = "Send Confirm";
    $responseData = json_encode($responseData);
    
    sendOutput( $responseData, array('Content-Type: application/json', 'HTTP/1.1'.$headercode));
    
    
    
    function sendOutput($data, $httpHeaders=array())
        {
            header_remove('Set-Cookie');
            if (is_array($httpHeaders) && count($httpHeaders)) {
                foreach ($httpHeaders as $httpHeader) {
                    header($httpHeader);
                }
            }
            echo $data;
            exit;
        }
?>