<?php
    if(array_key_exists("reqId",$_GET)){
        $userIndex = $user["indexNum"];
        $query = "SELECT * FROM `comments` WHERE reqId=".$_GET["reqId"];
        if($result = mysqli_query($link,$query)){
            if(mysqli_num_rows($result) !=0){
                while($comment = mysqli_fetch_array($result)){
                    if($comment["createrIndex"] == $userIndex){
                        $float = "right";
                    }
                    else{
                        $float = "left";
                    }
                    if($comment["commentFile"]){
                        echo '<div class="comment alert-light alert" style="float:'.$float.'">
                                <h6>'.$comment["createrIndex"].'</h6>'
                                .'<p style="margin-bottom: 0px;"><a id="commentFileLink" href="'.$comment["commentFile"].'" download="commentOfReq'.$_GET["reqId"].'">Click here to download.</a></p>'
                                .$comment["commentMsg"].'
                            </div><div class="clearFloat"></div>';
                    }
                    else{
                        echo '<div class="comment alert-light alert" style="float:'.$float.'">
                                <h6>'.$comment["createrIndex"].'</h6>'
                                .$comment["commentMsg"].'
                            </div><div class="clearFloat"></div>';
                    }
                }
            }
            else{
                echo '<div class="alert alert-dark" role="alert" style="text-align: center;"><b>There is not any comment yet.</b></div>';
            }

        }
        else{
            echo '<div class="alert alert-danger" role="alert" style="text-align: center;"><b>Database connection issue.</b></div>';
        }
    }
?>