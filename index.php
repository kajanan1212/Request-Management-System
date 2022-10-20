<?php

    session_start();

    $link = mysqli_connect("localhost","root","","wirelessreqdb");

    if(array_key_exists("logOut",$_GET)){
        unset($_SESSION["id"]);
        unset($_SESSION["acType"]);
        setcookie("id","",time()-60*60);
        setcookie("acType","",time()-60*60);
        $_COOKIE["id"]="";
        $_COOKIE["acType"]="";
    }
    else if(array_key_exists("id",$_SESSION) or array_key_exists("id",$_COOKIE)){
        if($_SESSION["acType"] == "students" or $_COOKIE["acType"] == "students"){
            header("Location: student.php");
        }
        if($_SESSION["acType"] == "staffs" or $_COOKIE["acType"] == "staffs"){
            header("Location: staff.php");
        }   
    }

    if(mysqli_connect_error()){
        die("Database connection issue.");
    }

    $error = "";
    
    $validUsername = "";
    $isValidUsername = "";

    $isValidpassword = "";

    if($_POST){
        $users = array("students","staffs");
        foreach($users as $user){
            /** Each user has unique username bt might has existed password */
            $query = "SELECT `password`,`id` FROM `$user` WHERE username = '".mysqli_real_escape_string($link, $_POST["username"])."'";
            if($result = mysqli_query($link, $query)){
                if(mysqli_num_rows($result) == 1){
                    $isValidUsername = " is-valid";
                    $error = "";
                    $validUsername = $_POST["username"];
                    $row = mysqli_fetch_array($result);     
                    if(strcmp($row["password"],md5($validUsername.$_POST["password"])) == 0){
                        
                        $_SESSION["id"] = $row["id"];
                        $_SESSION["acType"] = $user;

                        setcookie("id", $row["id"], time()+60*5);
                        setcookie("acType", $user, time()+60*5);

                        if(strcmp($user,"students") == 0){
                            header("Location: student.php");
                        }
                        if(strcmp($user,"staffs") == 0){
                            header("Location: staff.php");
                        }
                        break;
                    }
                    else{
                        $error = "Incorrect password.";
                        $isValidpassword = " is-invalid";
                        break;
                    }
                }
                else{
                    $error = "Incorrect username.";
                    $isValidUsername = " is-invalid";
                }
            }
            else{
                $error = "Database connection issue.<br>Try agin later.";
            }
        }
    }

?>
<!doctype html>
<html lang="en">

<head>

    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-BmbxuPwQa2lc/FVzBcNJ7UAyJxM6wuqIj61tLrc4wSX0szH/Ev+nYRRuWlolflfl" crossorigin="anonymous">

    <title>WirelessReq</title>

    <style type="text/css">

        html { 
            background: url(bg.jpg) no-repeat center center fixed; 
            -webkit-background-size: cover;
            -moz-background-size: cover;
            -o-background-size: cover;
            background-size: cover;
        }

        body{
            background: none;
        }

        button{
            width: 100px;
            height: 40px;
            
        }

        .inputField{
            margin: 15px 0px;
            
        }

        .inputField:hover{
            box-shadow: 0px 0px 5px rgba(12, 14, 12, 0.541);
        }

       /* .mid{
            text-align: center;
            position: absolute;
            left: 50%;
            top: 50%;
            width: 30%;
            height: 35%;
            margin-left: -15%;
            margin-top: -13%;
            padding: 0px 0px;
        }*/

        .mid{
            margin-top: 16%;
        }

        .alert{
            margin: 15px 0px;
            text-align: center;
        }

        #myBtn{
            text-align: right;
        }

        #heading{
            margin: 20px 0px;
            color: #E5E4E2;
            text-align: center;
        }
        
    
    
    </style>
</head>

<body>

    <div class="container">
        <div class="row">

            <div class="col-md-4"></div>
            <div class="col-md-4 mid">
                <form method="POST">

                    <div id="heading">
                        <h1>WirelessReq</h1>
                    </div>

                    <div class="input-group flex-nowrap inputField">
                        <span class="input-group-text" id="addon-wrapping">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-person-circle" viewBox="0 0 16 16">
                                <path d="M11 6a3 3 0 1 1-6 0 3 3 0 0 1 6 0z"/>
                                <path fill-rule="evenodd" d="M0 8a8 8 0 1 1 16 0A8 8 0 0 1 0 8zm8-7a7 7 0 0 0-5.468 11.37C3.242 11.226 4.805 10 8 10s4.757 1.225 5.468 2.37A7 7 0 0 0 8 1z"/>
                            </svg>
                        </span>
                        <?php
                            echo '<input type="text" class="form-control'.$isValidUsername.'"'.' value="'.$validUsername.'" placeholder="Username" aria-label="Username" aria-describedby="addon-wrapping" name="username" id="username" required>';
                        ?>

                        
                    </div>

                    <div class="input-group flex-nowrap inputField">
                        <span class="input-group-text" id="addon-wrapping">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-lock-fill" viewBox="0 0 16 16">
                                <path d="M8 1a2 2 0 0 1 2 2v4H6V3a2 2 0 0 1 2-2zm3 6V3a3 3 0 0 0-6 0v4a2 2 0 0 0-2 2v5a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2V9a2 2 0 0 0-2-2z"/>
                            </svg>
                        </span>
                        <?php
                            echo '<input type="Password" class="form-control'.$isValidpassword.'" placeholder="Password" aria-label="Password" aria-describedby="addon-wrapping" name="password" id="password" required>';
                        ?>
                    </div>

                    <div id="myBtn">
                        <button type="submit" class="btn btn-primary">Log in</button>
                    </div>

                    <div>
                        <?php

                            if($_POST && $error != ""){
                                echo '<div class="alert alert-danger" role="alert"><b>'.$error.'</b></div>';
                            }
                        
                        ?>                   
                    </div>

                    
                </form>
            </div>
            <div class="col-md-4"></div>
        </div>
    </div>
    
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/js/bootstrap.min.js" integrity="sha384-nsg8ua9HAw1y0W1btsyWgBklPnCUAFLuTMS2G72MMONqmOymq585AcH49TLBQObG" crossorigin="anonymous"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script type="text/javascript">
    
    </script>

</body>

</html>