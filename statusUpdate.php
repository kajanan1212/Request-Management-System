<?php
    /**statusUpdate */
    function statusUpdate($link,$acPageName){
        if(array_key_exists("statusUpdate",$_POST)){
            $query = "UPDATE `requests` SET status= '".$_POST["statusUpdate"]."' WHERE id=".$_POST["reqId"];
            if(mysqli_query($link,$query)){
                $successStatus = "Status of the request was updated successfully.";
                header("Location: $acPageName.php?reqId=".$_POST["reqId"]."&successStatus=$successStatus&statusUpdate=");
            }
            else{
                $errorStatus = "Status of the request wasn't updated.<br>Try again.";
                header("Location: $acPageName.php?reqId=".$_POST["reqId"]."&errorStatus=$errorStatus&statusUpdate=");
            }
        }
    }
?>