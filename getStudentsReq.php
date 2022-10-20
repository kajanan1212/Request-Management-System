<?php

    function getStudentsReq($link,$where=NULL,$isSearch=false,$isStatus=false,$unopened=false){
        if(!$isSearch && !$isStatus && $where){
            $whereQuery = "WHERE typeOfReq='$where'";
        }
        else if($isStatus){
            if($where){
                $whereQuery = "WHERE status='$where'";
            }
            else{
                $whereQuery = "WHERE status IS NULL";
            }
        }
        else if($unopened){
            $whereQuery = "WHERE isNew=1";
        }
        else{
            $whereQuery = "";
        }
        $query = "SELECT * FROM `requests` $whereQuery ORDER BY `id` DESC";
        if($result = mysqli_query($link,$query)){
            if(($count = mysqli_num_rows($result)) !=0){
                $unopenedCount=0;
                while($request = mysqli_fetch_array($result)){
                    $query = "SELECT `name`,`indexNum` FROM `students` WHERE id=".$request["studentId"];
                    $resultStudent = mysqli_query($link,$query);
                    $requestStudent = mysqli_fetch_array($resultStudent);

                    if($isSearch && !(strcasecmp($where,$requestStudent["indexNum"]) == 0 || strcasecmp($where,$requestStudent["name"]) == 0)){
                        $count--;
                        continue;
                    }
                    if($request["status"]){
                        if($request["status"] == "accepted"){
                            $status = " | <span style='color:#198754;'>Accepted</span>";
                        }
                        else{
                            $status = " | <span style='color:#dc3545;'>Declined</span>";
                        }

                    }
                    else{
                        $status = " | <span style='color:#fd7e14;'>Pending</span>";
                    }
                    $notify = "";
                    if($request["isNew"] == 1){
                        $unopenedCount++;
                        $notify = "";
                    }
                    else{
                        $notify = 'style="opacity: 0.5;"';
                    }
                    echo '<div class="myRequest shadowHover alert-light alert" id="request'.$request["id"].'" '.$notify.'>
                            <h6>'.$request["typeOfReq"].'</h6>'
                            .$requestStudent["indexNum"].' | '.$request["subject"].$status.
                            '<p style="margin-bottom:0px;float:right">'
                            .$request["dateTime"].'
                            </p>
                            <form class="request'.$request["id"].'" method="GET">
                                <input type="text" name="reqId" hidden value="'.$request["id"].'">
                            </form>
                        </div>';
                }
            }
            else{
                echo '<div class="alert alert-dark" role="alert" style="text-align: center;"><b>There is not any request yet.</b></div>';
            }

        }
        else{
            echo '<div class="alert alert-danger" role="alert" style="text-align: center;"><b>Database connection issue.</b></div>';
        }
        if($unopened){
            return $unopenedCount;
        }
    }
?> 