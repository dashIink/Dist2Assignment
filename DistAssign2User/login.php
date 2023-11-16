<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login/Registration Page</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
            background-image: url('https://upload.wikimedia.org/wikipedia/commons/thumb/3/31/Modern_pentathlon_pictogram.svg/2048px-Modern_pentathlon_pictogram.svg.png');
            background-repeat: no-repeat;
            background-size: 100% 150%;
        }

        .container {
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        form {
            display: flex;
            flex-direction: column;
        }

        label {
            margin-bottom: 8px;
            font-weight: bold;
        }

        input {
            padding: 10px;
            margin-bottom: 16px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
            width: 100%;
        }

        button {
            background-color: #4caf50;
            color: #fff;
            padding: 10px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
        }

        button:hover {
            background-color: #45a049;
        }

        .switch {
            text-align: center;
            margin-top: 20px;
        }

        .switch a {
            text-decoration: none;
            color: #4caf50;
            font-weight: bold;
        }
    </style>
</head>
<body>



<?php
    require 'vendor/autoload.php';
    use GuzzleHttp\Client;
        $passed = true;
        if(isset($_POST["username"]) && isset($_POST["password"])){
            $client = new GuzzleHttp\Client();
            $res = $client->get('http://localhost/DistAssign2/index.php/auth', [
                'auth' =>  [$_POST["username"], $_POST["password"]]
            ]);
            $res->getStatusCode();           // 200
            $res->getHeader('content-type'); // 'application/json; charset=utf8'
            $body = json_decode($res->getBody());                 // {"type":"User"...'
            if($body[0] == "Success"){
                session_start();
                $_SESSION["id"] = $body[2];
                header(header("location: ".$body[1].""));
            }
            else{
                $passed = false;
            }

        }
        $_POST = array();
?>






    <div class="container" id="login">
        <h2>Login</h2>
        <form action="login.php" method="post">
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" required>

            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>
            <?php
                if (!$passed){
                    echo "<p>Login Failed!</p>";
                }
            ?>


            <button type="submit">Login</button>
        </form>

        <div class="switch">
            <p>Don't have an account? <a href="#registration">Register here</a></p>
        </div>
    </div>

    <div class="container" id="registration" style="display: none;">
        <h2>Registration</h2>
        <form action="register.php" method="post">
            <label for="reg_username">Username:</label>
            <input type="text" id="reg_username" name="reg_username" required>

            <label for="reg_password">Password:</label>
            <input type="password" id="reg_password" name="reg_password" required>

            <button type="submit">Register</button>
        </form>

        <div class="switch">
            <p>Already have an account? <a href="#login">Login here</a></p>
        </div>
    </div>

    <script>
        // Toggle between login and registration forms
        document.addEventListener("DOMContentLoaded", function () {
            const registrationForm = document.getElementById("registration");
            const loginForm = document.getElementById("login");

            document.querySelector("a[href='#registration']").addEventListener("click", function (e) {
                e.preventDefault();
                registrationForm.style.display = "block";
                loginForm.style.display = "none";
            });

            document.querySelector("a[href='#login']").addEventListener("click", function (e) {
                e.preventDefault();
                registrationForm.style.display = "none";
                loginForm.style.display = "block";
            });
        });
    </script>
</body>
</html>