<?php

    session_start();

    if(array_key_exists("id",$_COOKIE) && $_COOKIE["id"] != ""){
        $_SESSION["id"] = $_COOKIE["id"];
        $_SESSION["acType"] = $_COOKIE["acType"];
    }

    if(array_key_exists("id",$_SESSION)){
        if($_SESSION["acType"] == "students"){
            header("Location: student.php");
        }
        $acPageName = substr($_SESSION["acType"],0,strlen($_SESSION["acType"])-1);
        $link = mysqli_connect("localhost","root","","wirelessreqdb");

        $query = "SELECT * FROM `". $_SESSION["acType"] ."` WHERE id = ".$_SESSION["id"];
        if ($result = mysqli_query($link, $query)){
            $user = mysqli_fetch_array($result);

            include("reqSubmit.php");
            reqSubmit($link,$acPageName,$user);

            include("profileUpdate.php");
            profileUpdate($link,$acPageName,$user);

            include("statusUpdate.php");
            statusUpdate($link,$acPageName);
            
            include("sendComment.php");
            sendComment($link,$acPageName,$user);
        }
        else{
            /** */
        }

    }
    else{
        header("Location: index.php");
    }

?>

<!doctype html>
<html lang="en">
    <head>
        
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-BmbxuPwQa2lc/FVzBcNJ7UAyJxM6wuqIj61tLrc4wSX0szH/Ev+nYRRuWlolflfl" crossorigin="anonymous">   
        
        <title><?php echo $acPageName;?> site</title>

        <link rel="stylesheet" href="staffACStylesheet.css">

    </head>
    <body>
        <!--Header-->
        <div>
            <nav class="navbar navbar-light myGlass">
                <div class="container-fluid">
                    <div>
                        <a class="navbar-brand" style="color: #FFFFFF;text-shadow: 2px 2px 10px rgba(51, 58, 57, 0.548);float: left;">WirelessReq</a>
                        <div class="row d-flex align-items-start" style="margin: 0px 0px;">
                            <div class="nav nav-pills me-3 sideNav-content col-12 col-sm-12" id="v-pills-tab" role="tablist" aria-orientation="vertical" style="float: left;">
                                <?php
                                    $btn_active_color = "background-color:black;";
                                    $activeStatus = array("home"=>"active","profile"=>"","request"=>"");
                                    $styleStatus = array("home"=>$btn_active_color,"profile"=>"","request"=>"");
                                    if($_GET && array_key_exists("profileUpdate",$_GET)){
                                        foreach($activeStatus as $key=>$value){
                                            if($key == "profile"){$activeStatus[$key]="active";}
                                            else{$activeStatus[$key]="";}
                                        }
                                        foreach($styleStatus as $key=>$value){
                                            if($key == "profile"){$styleStatus[$key]=$btn_active_color;}
                                            else{$styleStatus[$key]="";}
                                        }
                                    }
                                    if($_GET && (array_key_exists("sendComment",$_GET) || array_key_exists("statusUpdate",$_GET) || array_key_exists("reqId",$_GET))){
                                        foreach($activeStatus as $key=>$value){
                                            if($key == "request"){$activeStatus[$key]="active";}
                                            else{$activeStatus[$key]="";}
                                        }
                                        foreach($styleStatus as $key=>$value){
                                            if($key == "request"){$styleStatus[$key]=$btn_active_color;}
                                            else{$styleStatus[$key]="";}
                                        }
                                    }
                                
                                    echo'<button class="nav-link me-2 '.$activeStatus["home"].' nav-btn" id="v-pills-home-tab" data-bs-toggle="pill" data-bs-target="#v-pills-home" type="button" role="tab" aria-controls="v-pills-home" aria-selected="true" style="'.$styleStatus["home"].'">Home</button>
                                    <button class="nav-link me-2 '.$activeStatus["profile"].' nav-btn" id="v-pills-profile-tab" data-bs-toggle="pill" data-bs-target="#v-pills-profile" type="button" role="tab" aria-controls="v-pills-profile" aria-selected="false" style="'.$styleStatus["profile"].'">Profile</buttona>
                                    <button class="nav-link me-2 '.$activeStatus["request"].' nav-btn" id="v-pills-addNewReq-tab" data-bs-toggle="pill" data-bs-target="#v-pills-addNewReq" type="button" role="tab" aria-controls="v-pills-addNewReq" aria-selected="false" style="'.$styleStatus["request"].'">Request</button>
                                    <button class="nav-link me-2 nav-btn" id="v-pills-logOut-tab" data-bs-toggle="pill" data-bs-target="#v-pills-logOut" type="button" role="tab" aria-controls="v-pills-logOut" aria-selected="false">Log out</button>';
                                ?>
                            </div>
                        </div>
                    </div>
                    <div class="d-flex">
                        <span class="navbar-text" style="color: #FFFFFF;margin: 0px 10px;text-shadow: 2px 2px 10px rgba(51, 58, 57, 0.548);">
                            <?php
                                echo $acPageName;
                            ?>
                        </span>
                        <div class="circle">
                            <?php
                                if(mysqli_real_escape_string($link,$user["profilePic"])){
                                    echo '<img class="circle" src="'.mysqli_real_escape_string($link,$user["profilePic"]).'?'.time().'"/>';
                                }
                                else{
                                    echo '<img class="circle" src="uploadedFiles\\dp\\userDefaultdp.png?'.time().'"/>';
                                }
                            ?>
                        </div>
                        <span class="navbar-text" style="color: #FFFFFF;margin: 0px 10px;text-shadow: 2px 2px 10px rgba(51, 58, 57, 0.548);">
                            <?php
                                echo mysqli_real_escape_string($link,$user["name"]);
                            ?>
                        </span>
                    </div>
                </div>
            </nav>
        </div>
        <!--HeaderEnd-->
        
        <!--myBody-->
        <div class="container-fluid myBody">
            <div class="row">

                <div class="col-12 col-xxl-12 leftContent">
                    
                    <div class="container-fluid">
                        <div class="row d-flex align-items-start" style="margin: 0px 0px">
                            <div class="tab-content col-12 col-sm-12 card" id="v-pills-tabContent">
<!--------------------------------------------------------Home---------------------------------------------------------------------------------------------------------------->
                                <div class="tab-pane fade show <?php echo $activeStatus["home"];?>" id="v-pills-home" role="tabpanel" aria-labelledby="v-pills-home-tab">
                                    <div class="" id="">
                                        <nav id="nav-reqCollection" class="card-header">
                                            <?php
                                                
                                                $activeStatusAS = array("all"=>"active","search"=>"");
                                                $ariaSelected = array("all"=>"true","search"=>"false");
                                                if($_GET && array_key_exists("studentDetail",$_GET)){
                                                    foreach($activeStatusAS as $key=>$value){
                                                        if($key == "search"){$activeStatusAS[$key]="active";}
                                                        else{$activeStatusAS[$key]="";}
                                                    }
                                                    foreach($ariaSelected as $key=>$value){
                                                        if($key == "search"){$ariaSelected[$key]="true";}
                                                        else{$ariaSelected[$key]="false";}
                                                    }
                                                }
                                            ?>
                                            <div class="nav nav-tabs" id="nav-tab" role="tablist">
                                                <button class="nav-link <?php echo $activeStatusAS["all"];?> nav-req" id="nav-all-tab" data-bs-toggle="tab" data-bs-target="#nav-all" type="button" role="tab" aria-controls="nav-all" aria-selected="<?php echo $ariaSelected["all"];?>">All</button>
                                                <button class="nav-link nav-req" id="nav-accepted-tab" data-bs-toggle="tab" data-bs-target="#nav-accepted" type="button" role="tab" aria-controls="nav-accepted" aria-selected="false"><span style='color:#198754;'>Accepted</span></button>
                                                <button class="nav-link nav-req" id="nav-declined-tab" data-bs-toggle="tab" data-bs-target="#nav-declined" type="button" role="tab" aria-controls="nav-declined" aria-selected="false"><span style='color:#dc3545;'>Declined</span></button>
                                                <button class="nav-link nav-req" id="nav-pending-tab" data-bs-toggle="tab" data-bs-target="#nav-pending" type="button" role="tab" aria-controls="nav-pending" aria-selected="false"><span style='color:#fd7e14;'>Pending</span></button>
                                                <button class="nav-link nav-req" id="nav-addDrop-tab" data-bs-toggle="tab" data-bs-target="#nav-addDrop" type="button" role="tab" aria-controls="nav-addDrop" aria-selected="false">Add-drop</button>
                                                <button class="nav-link nav-req" id="nav-extendDeadline-tab" data-bs-toggle="tab" data-bs-target="#nav-extendDeadline" type="button" role="tab" aria-controls="nav-extendDeadline" aria-selected="false">Extend deadline</button>
                                                <button class="nav-link nav-req" id="nav-repeatExam-tab" data-bs-toggle="tab" data-bs-target="#nav-repeatExam" type="button" role="tab" aria-controls="nav-repeatExam" aria-selected="false">Repeat exam</button>
                                                <button class="nav-link nav-req" id="nav-askLeave-tab" data-bs-toggle="tab" data-bs-target="#nav-askLeave" type="button" role="tab" aria-controls="nav-askLeave" aria-selected="false">Ask leave</button>
                                                <button class="nav-link nav-req" id="nav-others-tab" data-bs-toggle="tab" data-bs-target="#nav-others" type="button" role="tab" aria-controls="nav-others" aria-selected="false">Others</button>
                                                <button class="nav-link nav-req" id="nav-unopened-tab" data-bs-toggle="tab" data-bs-target="#nav-unopened" type="button" role="tab" aria-controls="nav-unopened" aria-selected="false">Unopened</button>
                                                <button class="nav-link <?php echo $activeStatusAS["search"];?> nav-req" id="nav-search-tab" data-bs-toggle="tab" data-bs-target="#nav-search" type="button" role="tab" aria-controls="nav-search" aria-selected="<?php echo $ariaSelected["search"];?>">Search</button>
                                            </div>
                                        </nav>
                                        <div class="tab-content" id="nav-tabContent">
                                            <?php
                                                include("getStudentsReq.php");
                                            ?>
                                            <div class="tab-pane fade <?php if($activeStatusAS["all"] == "active"){echo "show ".$activeStatusAS["all"];}?>" id="nav-all" role="tabpanel" aria-labelledby="nav-all-tab">
                                                <div class="card col-sm-12">
                                                    <div class="card-body myPink">
                                                        
                                                        <div class="overflow-auto staffReqBox">
                                                        
                                                            <?php
                                                                getStudentsReq($link);
                                                            ?>
                                                                                                            
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="tab-pane fade" id="nav-accepted" role="tabpanel" aria-labelledby="nav-accepted-tab">
                                                <div class="card col-sm-12">
                                                    <div class="card-body myPink">
                                                        
                                                        <div class="overflow-auto staffReqBox">
                                                        
                                                            <?php
                                                                getStudentsReq($link,"accepted",false,true);
                                                            ?>
                                                                                                            
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="tab-pane fade" id="nav-declined" role="tabpanel" aria-labelledby="nav-declined-tab">
                                                <div class="card col-sm-12">
                                                    <div class="card-body myPink">
                                                        
                                                        <div class="overflow-auto staffReqBox">
                                                        
                                                            <?php
                                                                getStudentsReq($link,"declined",false,true);
                                                            ?>
                                                                                                            
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="tab-pane fade" id="nav-pending" role="tabpanel" aria-labelledby="nav-pending-tab">
                                                <div class="card col-sm-12">
                                                    <div class="card-body myPink">
                                                        
                                                        <div class="overflow-auto staffReqBox">
                                                        
                                                            <?php
                                                                getStudentsReq($link,NULL,false,true);
                                                            ?>
                                                                                                            
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="tab-pane fade" id="nav-addDrop" role="tabpanel" aria-labelledby="nav-addDrop-tab">
                                                <div class="card col-sm-12">
                                                    <div class="card-body myPink">
                                                        
                                                        <div class="overflow-auto staffReqBox">
                                                        
                                                            <?php
                                                                getStudentsReq($link,"Add-drop");
                                                            ?>
                                                                                                            
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="tab-pane fade" id="nav-extendDeadline" role="tabpanel" aria-labelledby="nav-extendDeadline-tab">
                                                <div class="card col-sm-12">
                                                    <div class="card-body myPink">
                                                        
                                                        <div class="overflow-auto staffReqBox">
                                                        
                                                            <?php
                                                                getStudentsReq($link,"Extend deadline");
                                                            ?>
                                                                                                            
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="tab-pane fade" id="nav-repeatExam" role="tabpanel" aria-labelledby="nav-repeatExam-tab">
                                                <div class="card col-sm-12">
                                                    <div class="card-body myPink">
                                                        
                                                        <div class="overflow-auto staffReqBox">
                                                        
                                                            <?php
                                                                getStudentsReq($link,"Repeat exam");
                                                            ?>
                                                                                                            
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="tab-pane fade" id="nav-askLeave" role="tabpanel" aria-labelledby="nav-askLeave-tab">
                                                <div class="card col-sm-12">
                                                    <div class="card-body myPink">
                                                        
                                                        <div class="overflow-auto staffReqBox">
                                                        
                                                            <?php
                                                                getStudentsReq($link,"Ask leave");
                                                            ?>
                                                                                                            
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="tab-pane fade" id="nav-others" role="tabpanel" aria-labelledby="nav-others-tab">
                                                <div class="card col-sm-12">
                                                    <div class="card-body myPink">
                                                        
                                                        <div class="overflow-auto staffReqBox">
                                                        
                                                            <?php
                                                                getStudentsReq($link,"Others");
                                                            ?>
                                                                                                            
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="tab-pane fade" id="nav-unopened" role="tabpanel" aria-labelledby="nav-unopened-tab">
                                                <div class="card col-sm-12">
                                                    <div class="card-body myPink">
                                                        
                                                        <div class="overflow-auto staffReqBox">
                                                        
                                                            <?php
                                                                $unopenedCount = getStudentsReq($link,$where=NULL,$isSearch=false,$isStatus=false,$unopened=true);
                                                            ?>
                                                                                                            
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="tab-pane fade <?php if($activeStatusAS["search"] == "active"){echo "show ".$activeStatusAS["search"];}?>" id="nav-search" role="tabpanel" aria-labelledby="nav-search-tab">
                                                <div id="searchBar">
                                                    <nav class="navbar navbar-light">
                                                        <div class="container-fluid">
                                                            <form class="d-flex" style="width: 100%;" method="GET">
                                                                <input class="form-control me-2" type="search" placeholder="Username or Index number" aria-label="Search" name="studentDetail" required>
                                                                <button class="btn btn-outline-success" type="submit">Search</button>
                                                            </form>
                                                        </div>
                                                    </nav>
                                                </div>
                                                <div class="card col-sm-12">
                                                    <div class="card-body myPink">
                                                        
                                                        <div class="overflow-auto staffReqBox">

                                                            <?php
                                                                if($_GET && array_key_exists("studentDetail",$_GET)){
                                                                    getStudentsReq($link,$_GET["studentDetail"],true);
                                                                }
                                                                else{
                                                                    echo '<div class="alert alert-dark" role="alert" style="text-align: center;"><b>Any of the specific student\'s requests are not searched yet.</b></div>';
                                                                }
                                                            ?>
                                                                                                            
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
<!--------------------------------------------------------profile---------------------------------------------------------------------------------------------------------------->
                                <div class="tab-pane fade show <?php echo $activeStatus["profile"];?>" id="v-pills-profile" role="tabpanel" aria-labelledby="v-pills-profile-tab">                               
                                    <h5 class="card-header">
                                        Profile
                                    </h5>
                                    <div class="card-body">
                                        <div>
                                            <?php

                                                if(array_key_exists("profileUpdate",$_GET)){
                                                    if(array_key_exists("errorProfile",$_GET)){
                                                        echo '<div class="alert alert-danger" role="alert" style="text-align: center;"><b>'.$_GET["errorProfile"].'</b></div>';
                                                    }
                                                    if(array_key_exists("successProfile",$_GET)){
                                                        echo '<div class="alert alert-success" role="alert" style="text-align: center"><b>'.$_GET["successProfile"].'</b></div>';
                                                    }
                                                }
                                            
                                            ?>                   
                                        </div>
                                        <form method="POST" enctype="multipart/form-data">
                                            <div class="mb-3 row">
                                            <div style="text-align: center;">
                                                <?php
                                                    if(mysqli_real_escape_string($link,$user["profilePic"])){
                                                        echo '<img id="circleProfile" src="'.mysqli_real_escape_string($link,$user["profilePic"]).'?'.time().'"/>';
                                                    }
                                                    else{
                                                        echo '<img id="circleProfile" src="uploadedFiles\\dp\\userDefaultdp.png?'.time().'"/>';
                                                    }
                                                ?>
                                            </div>
                                            </div>
                                            <div class="mb-3 row">
                                                <label for="name" class="col-sm-4 col-form-label"><h6>Name</h6></label>
                                                <div class="col-sm-8">
                                                    <?php
                                                        echo '<input type="text" class="form-control shadowHover" id="name" value="'.mysqli_real_escape_string($link,$user["name"]).'" name="name">';
                                                    ?>
                                                </div>
                                            </div>
                                            <div class="mb-3 row">
                                                <label for="indexNum" class="col-sm-4 col-form-label"><h6>Index number</h6></label>
                                                <div class="col-sm-8">
                                                    <?php
                                                        echo '<input type="text" class="form-control shadowHover" id="indexNum" value="'.mysqli_real_escape_string($link,$user["indexNum"]).'" disabled name="indexNum">';
                                                    ?>
                                                </div>
                                            </div>
                                            <div class="mb-3 row">
                                                <label for="evidence" class="col-sm-4 col-form-label"><h6>Profile picture</h6></label>
                                                <div class="col-sm-8">
                                                    <input class="form-control" type="file" id="newDp" name="newDp">
                                                </div>
                                            </div>
                                            <div class="mb-3 row">
                                                <div class="d-grid col-sm-12 mx-auto " style="padding-top: 3px;">
                                                    <button class="btn btn-primary" type="submit" name="profileUpdate">Save</button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
<!--------------------------------------------------------Request---------------------------------------------------------------------------------------------------------------->
                                <div class="tab-pane fade show <?php echo $activeStatus["request"];?>" id="v-pills-addNewReq" role="tabpanel" aria-labelledby="v-pills-addNewReq-tab">
                                    <h5 class="card-header">
                                        A student's request
                                    </h5>
                                    <?php
                                        if(array_key_exists("reqId",$_GET)){
                                            echo '<div class="card-body" style="display: none;">';
                                        }
                                        else{
                                            echo '<div class="card-body">';
                                        }
                                    ?>
                                        <div class="card col-sm-12">
                                            <div class="card-body myPink">
                                                
                                                <div id="myReqBox" class="overflow-auto">
                                                
                                                    <?php
                                                            echo '<div class="alert alert-dark" role="alert" style="text-align: center;"><b>Select one request from \'Home\' page.</b></div>';
                                                    
                                                    ?>
                                                                                                    
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <?php
                                        $name = "";$indexNum = "";$typeOfReq = "";$dateTime = "";$subject = "";$explanation = "";$status = "";
                                        if(array_key_exists("reqId",$_GET)){
                                            echo '<div class="card-body" id="singleReqBody">';
                                            $query = "SELECT * FROM `requests` WHERE id=".$_GET["reqId"];
                                            if($result = mysqli_query($link,$query)){
                                                $oneReq = mysqli_fetch_array($result);
                                                $typeOfReq = $oneReq["typeOfReq"];
                                                $dateTime = $oneReq["dateTime"];
                                                $subject = $oneReq["subject"];
                                                $explanation = $oneReq["explanation"];
                                                $evidence = $oneReq["evidence"];
                                                $status = $oneReq["status"];
                                                $query = "SELECT `name`,`indexNum` FROM `students` WHERE id=".$oneReq["studentId"];
                                                if($result = mysqli_query($link,$query)){
                                                    $oneReqStudent = mysqli_fetch_array($result);
                                                    $name = $oneReqStudent["name"];
                                                    $indexNum = $oneReqStudent["indexNum"];
                                                }
                                                if($oneReq["isNew"] == 1){
                                                    $query = "UPDATE `requests` SET isNew=0 WHERE id=".$_GET["reqId"]; 
                                                    mysqli_query($link,$query);
                                                    $unopenedCount--;
                                                }
                                            }
                                            else{
                                                echo '<div class="alert alert-danger" role="alert" style="text-align: center;"><b>Database connection issue.</b></div>';
                                            }
                                        }
                                        else{
                                            echo '<div class="card-body" id="singleReqBody" style="display: none;">';
                                        }
                                        
                                    ?>
                                        <div>
                                                <?php

                                                    if(array_key_exists("statusUpdate",$_GET)){
                                                        if(array_key_exists("errorStatus",$_GET)){
                                                            echo '<div class="alert alert-danger" role="alert" style="text-align: center;"><b>'.$_GET["errorStatus"].'</b></div>';
                                                        }
                                                        if(array_key_exists("successStatus",$_GET)){
                                                            echo '<div class="alert alert-success" role="alert" style="text-align: center;"><b>'.$_GET["successStatus"].'</b></div>';
                                                        }
                                                    }

                                                    if(array_key_exists("sendComment",$_GET)){
                                                        if(array_key_exists("errorComment",$_GET)){
                                                            echo '<div class="alert alert-danger" role="alert" style="text-align: center;"><b>'.$_GET["errorComment"].'</b></div>';
                                                        }
                                                        if(array_key_exists("successComment",$_GET)){
                                                            echo '<div class="alert alert-success" role="alert" style="text-align: center;"><b>'.$_GET["successComment"].'</b></div>';
                                                        }
                                                    }
                                                
                                                ?>                   
                                        </div>
                                        <div class="mb-3 row">
                                            <label for="name" class="col-sm-4 col-form-label"><h6>Name</h6></label>
                                            <div class="col-sm-8">
                                                <?php
                                                    echo '<input type="text" readonly class="form-control-plaintext clearOutline" id="name" value="'.$name.'">';
                                                ?>
                                            </div>
                                        </div>
                                        <div class="mb-3 row">
                                            <label for="indexNum" class="col-sm-4 col-form-label"><h6>Index number</h6></label>
                                            <div class="col-sm-8">
                                                <?php
                                                    echo '<input type="text" readonly class="form-control-plaintext clearOutline" id="indexNum" value="'.$indexNum.'">';
                                                ?>
                                            </div>
                                        </div>
                                        <div class="mb-3 row">
                                            <label for="typeOfReq" class="col-sm-4 col-form-label"><h6>Type of request</h6></label>
                                            <div class="col-sm-8">
                                                <?php
                                                    echo '<input type="text" readonly class="form-control-plaintext clearOutline" id="typeOfReq" value="'.$typeOfReq.'">';
                                                ?>
                                            </div>
                                        </div>
                                        <div class="mb-3 row">
                                            <label for="dateTime" class="col-sm-4 col-form-label"><h6>Date and time</h6></label>
                                            <div class="col-sm-8">
                                                <?php
                                                    echo '<input type="text" readonly class="form-control-plaintext clearOutline" id="typeOfReq" value="'.$dateTime.'">';
                                                ?>
                                            </div>
                                        </div>
                                        <div class="mb-3 row">
                                            <label for="subject" class="col-sm-4 col-form-label"><h6>Subject</h6></label>
                                            <div class="col-sm-8">
                                                <?php
                                                    echo '<input type="text" readonly class="form-control-plaintext clearOutline" id="subject" value="'.$subject.'">';
                                                ?>
                                            </div>
                                        </div>
                                        <div class="mb-3 row">
                                            <label for="explanation" class="col-sm-4 col-form-label"><h6>Explanation</h6></label>
                                            <div class="col-sm-8">
                                                <?php
                                                    echo '<textarea type="text" readonly class="form-control-plaintext clearOutline" id="explanation" cols="30" rows="4" value="" style="text-align: justify;">'.$explanation.'</textarea>';
                                                ?>
                                            </div>
                                        </div>
                                        <div class="mb-3 row">
                                            <label for="evidence" class="col-sm-4 col-form-label"><h6>Evidence</h6></label>
                                            <div class="col-sm-8">
                                                <?php
                                                    if($evidence){
                                                        echo '<a id="evidence" href="'.$evidence.'" download="evidenceOfReq'.$_GET["reqId"].'">';
                                                    }
                                                    else{
                                                        echo '<a id="evidence" href="'.$evidence.'" download="evidenceOfReq'.$_GET["reqId"].'" style="pointer-events: none; color:#adb5bd;">';
                                                    }
                                                     
                                                ?>
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="#698ec4" class="bi bi-download" viewBox="0 0 16 16" style="margin-right: 2px;">
                                                        <path d="M.5 9.9a.5.5 0 0 1 .5.5v2.5a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1v-2.5a.5.5 0 0 1 1 0v2.5a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2v-2.5a.5.5 0 0 1 .5-.5z"/>
                                                        <path d="M7.646 11.854a.5.5 0 0 0 .708 0l3-3a.5.5 0 0 0-.708-.708L8.5 10.293V1.5a.5.5 0 0 0-1 0v8.793L5.354 8.146a.5.5 0 1 0-.708.708l3 3z"/>
                                                    </svg>
                                                    Click here to download.
                                                </a>
                                            </div>
                                        </div>
                                        <div class="mb-3 row">
                                            <div class="card col-sm-12">
                                                <h5 class="card-header">
                                                    Comments
                                                </h5>
                                                <div class="card-body myPink">
                                                    
                                                    <div id="commentBox" class="overflow-auto">
                                                    
                                                        <?php
                                                            include("showComments.php");
                                                        ?>
                                                                                                        
                                                    </div>

                                                    <form method="POST" id="commentForm" enctype="multipart/form-data">
                                                        <div class="container-fluid">
                                                            <div class="row">

                                                                <div class="col-sm-10" style="padding-top: 2px;">
                                                                    <textarea type="text" class="form-control overflow-hidden shadowHover" id="commentMsg" cols="30" rows="1" value="" style="text-align: justify;" placeholder="Type a message" name="commentMsg" required spellcheck="true"></textarea>
                                                                </div>

                                                                <div class="col-12 col-sm-1" style="padding-top: 2px;">
                                                                    <div>
                                                                        <span class="input-group-text sendFile shadowHover" id="basic-addon1">
                                                                            <svg xmlns="http://www.w3.org/2000/svg" width="30" height="24" fill="#698ec4" class="bi bi-link-45deg" viewBox="0 0 16 16">
                                                                                <path d="M4.715 6.542L3.343 7.914a3 3 0 1 0 4.243 4.243l1.828-1.829A3 3 0 0 0 8.586 5.5L8 6.086a1.001 1.001 0 0 0-.154.199 2 2 0 0 1 .861 3.337L6.88 11.45a2 2 0 1 1-2.83-2.83l.793-.792a4.018 4.018 0 0 1-.128-1.287z"/>
                                                                                <path d="M6.586 4.672A3 3 0 0 0 7.414 9.5l.775-.776a2 2 0 0 1-.896-3.346L9.12 3.55a2 2 0 0 1 2.83 2.83l-.793.792c.112.42.155.855.128 1.287l1.372-1.372a3 3 0 0 0-4.243-4.243L6.586 4.672z"/>
                                                                            </svg>
                                                                        </span>
                                                                        <input hidden class="form-control" type="file" id="formFile" name="commentFile">
                                                                    </div>
                                                                </div>

                                                                <div class="col-sm-1" style="padding-top: 2px;">
                                                                    <div style="text-align: center;" id="sendComment">
                                                                        <svg xmlns="http://www.w3.org/2000/svg" width="30" height="35" fill="#1d1616" class="bi bi-cursor-fill" viewBox="0 0 16 16" title="send">
                                                                            <path d="M14.082 2.182a.5.5 0 0 1 .103.557L8.528 15.467a.5.5 0 0 1-.917-.007L5.57 10.694.803 8.652a.5.5 0 0 1-.006-.916l12.728-5.657a.5.5 0 0 1 .556.103z"/>
                                                                        </svg>
                                                                    </div>
                                                                    <input hidden class="form-control" type="text" id="" name="sendComment">
                                                                </div>
                                                                <?php
                                                                    echo '<input type="text" name="reqId" value="'.$_GET["reqId"].'" hidden>';
                                                                ?>
                                                            </div>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                        <?php

                                            if(!$status){
                                                echo '<div class="mb-3 row">
                                                    <form class="d-grid col-sm-6 mx-auto" style="padding-top: 3px;" method="POST">
                                                        <input type="text" name="reqId" value="'.$_GET["reqId"].'" hidden>
                                                        <button class="btn btn-success" type="submit" name="statusUpdate" value="accepted">Accept</button>
                                                    </form>
                                                    <form class="d-grid col-sm-6 mx-auto" style="padding-top: 3px;" method="POST">
                                                        <input type="text" name="reqId" value="'.$_GET["reqId"].'" hidden>
                                                        <button class="btn btn-danger" type="submit" name="statusUpdate" value="declined">Decline</button>
                                                    </form>
                                                </div>';
                                            }
                                            else if($status == "accepted"){
                                                echo '<div class="mb-3 row">
                                                    <div class="d-grid col-sm-12 mx-auto" style="padding-top: 3px;">
                                                        <button class="btn btn-success" type="button" disabled>Accepted</button>
                                                    </div>
                                                </div>';
                                            }
                                            else if($status == "declined"){
                                                echo '<div class="mb-3 row">
                                                    <div class="d-grid col-sm-12 mx-auto" style="padding-top: 3px;">
                                                        <button class="btn btn-danger" type="button" disabled>Declined</button>
                                                    </div>
                                                </div>';
                                            }
                                        ?>
                                    </div>
                                </div>
<!--------------------------------------------------------Log out---------------------------------------------------------------------------------------------------------------->
                                <div class="tab-pane fade" id="v-pills-logOut" role="tabpanel" aria-labelledby="v-pills-logOut-tab">
                                    <div class="logOut">
                                        <h5 class="card-header">
                                                Log out
                                        </h5>
                                        <div class="card-body">
                                            <h6 id="logOutTxt">Are you sure about log out?</h6>
                                            <div class="mb-3 row logOut_btn">
                                                <a class="d-grid col-sm-6 mx-auto" style="padding-top: 3px;text-decoration: none;" href="index.php?logOut=true">
                                                    <button class="btn btn-success" type="button" id="logOut_true">Yes</button>                                  
                                                </a>
                                                <div class="d-grid col-sm-6 mx-auto" style="padding-top: 3px;">
                                                    <button class="btn btn-danger" type="button" id="logOut_false">No</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--myBodyEnd-->
        <?php 
            if($unopenedCount != 0){
                echo '<div class="fixed-top start-50" style="top:5px;"><div class="toast" role="alert" aria-live="assertive" aria-atomic="true">
                    <div class="toast-header">
                        <strong class="me-auto">Notification</strong>
                        <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
                    </div>
                    <div class="toast-body">
                        You haven\'t opened '.$unopenedCount.' request(s) yet.
                    </div>
                </div></div>';
            }
        ?>

    
        <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/js/bootstrap.min.js" integrity="sha384-nsg8ua9HAw1y0W1btsyWgBklPnCUAFLuTMS2G72MMONqmOymq585AcH49TLBQObG" crossorigin="anonymous"></script>
        <script src="staffACJavaScript.js"></script>
    </body>
</html>