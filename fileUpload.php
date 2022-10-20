<?php
    /** uploadFile*/
    function uploadFile($inputName,$extensions,$category,$type,$userID){
            $statusOfUpload = array();
            $errors = "";
            $newFileName = "";
            $fileSize = $_FILES[$inputName]['size'];
            $fileTmp = $_FILES[$inputName]['tmp_name'];
            $fileExt = explode('.',$_FILES[$inputName]['name']);
            $fileExt = $fileExt[1];
            
            if(count($extensions) !=0 && in_array($fileExt,$extensions)=== false){
                $errors .= "This extension isn't allowed.";
            }
            
            if($fileSize > 16777216) {
                $errors .='File size must be less than or equal to 16 MB.';
            }
            
            if(empty($errors)==true) {
                $newFileName = "uploadedFiles\\$category\\$type\\$userID.$fileExt";
                move_uploaded_file($fileTmp,$newFileName);
            }
            $statusOfUpload["errorUploadFile"] = $errors;
            $statusOfUpload["fileName"] = $newFileName;
            return $statusOfUpload;
    }

?>