<?php

    /**profileUpdate */
    function profileUpdate($link,$acPageName,$user){
        if(array_key_exists("profileUpdate",$_POST)){
            $errorProfile = "";
            $query = "";
            if($_FILES["newDp"]["name"]){
                include("fileUpload.php");
                $statusOfUpload = uploadFile("newDp",array("jpg","jpeg","png"),"dp",$_SESSION["acType"],$user["id"]);
                if($statusOfUpload["fileName"] != ""){
                    $query = "UPDATE `".$_SESSION["acType"]."` SET name= '".mysqli_real_escape_string($link,$_POST["name"])."', profilePic= '".mysqli_real_escape_string($link,$statusOfUpload["fileName"])."' WHERE id=".$user["id"];
                }
                else{
                    $errorProfile = $statusOfUpload["errorUploadFile"];
                } 
            }
            else{
                $query = "UPDATE `".$_SESSION["acType"]."` SET name= '".mysqli_real_escape_string($link,$_POST["name"])."' WHERE id=".$user["id"];
            }
            if($query != "" && mysqli_query($link,$query)){
                $successProfile = "Your profile was updated successfully.";
                header("Location: $acPageName.php?successProfile=$successProfile&profileUpdate=");
            }
            else{
                $errorProfile .= "Your profile wasn't updated.Try again.";
                header("Location: $acPageName.php?errorProfile=$errorProfile&profileUpdate=");
            }
        }
    }

?>