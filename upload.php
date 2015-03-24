<?php

/*
    CONFIG
*/

$file_size_limit = "30"; // the maximum file size in MB.
$allowed_directories = ["folder_1", "folder_2"]; // the directories that can have a file uploaded to.
$administrator_PIN = "1234"; // the PIN that authenticate the upload.
$current_version = "0.6.1";


/*
    UPLOAD
*/

// non-POST interceptor. If the page is accessed directly, it will re-direct to the index.
if(!isset($_FILES["file"])){
  header('Location: ./');
}


$target_directory = "./" . $_POST["directory"] . "/"; // The target directory to put the file
$pin = $_POST["pin"]; // The admin pin to allow upload
$file = $_FILES["file"]; // The file that's being uploaded
$file_name = $_FILES["file"]["name"]; // The name of the file being uploaded (Example: file.ext)
$file_type = $_FILES["file"]["type"]; // File type.
$file_size = (intval( $_FILES["file"]["size"]) / 1024 / 1024); // Convert from Bytes to MB.
$target_file = $target_directory . $file_name; // The more compiled target file/location. (Example: /folder/file.ext)
$upload_OK = true; // The upload boolean. If there's a problem, this goes false and the file is discarded.
$error_messages = []; // The error messages, is set if $upload_OK == false;

// Check if the file exists. We don't want to delete/override a file.
if (file_exists($target_file)){
    
    $upload_OK = false;
    array_push($error_messages, $target_file . " already exists. Rename it and try again.");
    
}


// Check file size.
if ($file_size > intval($file_size_limit)){
    
    $upload_OK = false;
     array_push($error_messages, $file_name . " excedes the " . $file_size_limit . "MB limit. The file size is " . $file_size . "MB.");
    
}

//check folder.
if(!file_exists($target_directory)){
    
    // check if the directly is in the allowed list, but not created yet.
    if(in_array($_POST["directory"], $allowed_directories)){
        mkdir($target_directory);
        echo("Created " . $_POST["directory"] . "<br>");
    }else{
        
        $upload_OK = false;
       array_push($error_messages,  "Quit fucking arround. I know you're trying to upload a file to a directory that doesn't exist.");
    }
    
}

// check PIN.
if($pin != $administrator_PIN){
    
    $upload_OK = false;
     array_push($error_messages, "You entered the wrong PIN.");
    
}

if( ! (isset($_POST["directory"]) and isset($_POST["pin"]) and isset($_FILES["file"]))){
    
    $upload_OK = false;
    array_push($error_messages,"Please go to the main page.");
    
}

// Check if there was an error.
if(!$upload_OK){
    
    echo ("One or more errors have occured: <br>");
  
  foreach($error_messages as $error){
  echo("- " . $error . "<br>");
  }

} else{
    
    // everything is fine, attempt to save the file.
    if (move_uploaded_file($_FILES["file"]["tmp_name"], $target_file)) {
        echo "The file was uploaded. at <a href='" .$target_directory ."'>".$target_directory ."</a>";
    } else {
        echo "There was an error saving the file to " . $target_directory;
    }
    
}



?>