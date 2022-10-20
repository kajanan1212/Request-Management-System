<?php

    /**sendComment */
    function sendComment($link,$acPageName,$user){
        if(array_key_exists("sendComment",$_POST)){
            $errorComment = "";
            $query = "INSERT INTO `comments` (`reqId`,`createrIndex`,`commentMsg`) 
                        VALUE (". $_POST["reqId"] .",'".$user["indexNum"]."','".$_POST["commentMsg"]."')";
            mysqli_query($link,$query);
            $successComment = "Your comment was sent successfully.";
            if($_FILES["commentFile"]["name"]){
                include("fileUpload.php");
                $statusOfUpload = uploadFile("commentFile",array(),"requests","comments",mysqli_insert_id($link));
                if($statusOfUpload["fileName"] != ""){
                    $query = "UPDATE `comments` SET commentFile='".mysqli_real_escape_string($link,$statusOfUpload["fileName"])."' WHERE id=".mysqli_insert_id($link);
                    mysqli_query($link,$query);
                }
                else{
                    $errorComment = $statusOfUpload["errorUploadFile"];
                    $query = "DELETE FROM `comments` WHERE id=".mysqli_insert_id($link);
                    mysqli_query($link,$query);
                    $query = "";
                }
                    
            }
            if($query != ""){
                header("Location: $acPageName.php?reqId=".$_POST["reqId"]."&successComment=$successComment&sendComment=");
            }
            else{
                $errorComment .= "Your comment wasn't sent.Send again.";
                header("Location: $acPageName.php?reqId=".$_POST["reqId"]."&errorComment=$errorComment&sendComment=");
            }
        }
    }
?>