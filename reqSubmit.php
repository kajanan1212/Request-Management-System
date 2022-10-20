<?php
    /**reqSubmit */
    function reqSubmit($link,$acPageName,$user){
        if(array_key_exists("reqSubmit",$_POST)){
            $errorReq = "";
            $query = "INSERT INTO `requests` (`studentId`,`typeOfReq`,`subject`,`explanation`,`isNew`) 
                        VALUE (". $user["id"] .",'".$_POST["typeOfReq"]."','".mysqli_real_escape_string($link,$_POST["subject"])."','".mysqli_real_escape_string($link,$_POST["explanation"])."',1)";
            mysqli_query($link,$query);
            $successReq = "Your request was sent successfully.";
            if($_FILES["evidence"]["name"]){
                include("fileUpload.php");
                $statusOfUpload = uploadFile("evidence",array(),"requests","evidence",mysqli_insert_id($link));
                if($statusOfUpload["fileName"] != ""){
                    $query = "UPDATE `requests` SET evidence='".mysqli_real_escape_string($link,$statusOfUpload["fileName"])."' WHERE id=".mysqli_insert_id($link);
                    mysqli_query($link,$query);
                }
                else{
                    $errorReq = $statusOfUpload["errorUploadFile"];
                    $query = "DELETE FROM `requests` WHERE id=".mysqli_insert_id($link);
                    mysqli_query($link,$query);
                    $query = "";
                }
            }
            if($query != ""){
                header("Location: $acPageName.php?successReq=$successReq&reqSubmit=");
            }
            else{
                $errorReq .= "Your request wasn't sent.Send again.";
                header("Location: $acPageName.php?errorReq=$errorReq&reqSubmit=");
            }
        }
    }

?>