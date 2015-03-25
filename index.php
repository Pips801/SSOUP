<?php

/*
    CONFIG
*/


$file_size_limit = "30"; // the maximum file size in MB. Make sure that you're php.ini is set to allow this high or higher sizes.
$allowed_directories = ["images", "files", "memes"]; // the directories that can have a file uploaded to.
$storage_folder = "./files/"; // the folder where everything is stored. I STRONGLY advise against setting this to the same folder these scripts run in. 
$administrator_PIN = "1234"; // the PIN that authenticate the upload.

$current_version = "1.0"; // please do not change.

/*
    SECURITY
*/


$allow_indexing = true; // Allow indexing of the requested folder.
$allow_executable = false; // Allow PHP, HTML, JS, etc. documents to exicute. It is recomended to leave this set to false;
$allow_read_only = true; // Set all files to read-only. This works in tandom to $allow_exicu
$show_debug = false; // Show debug messages.


?>

<!doctype html>
<html lang="en">
	<head>
		<meta charset="utf-8" />
		<title>
			SSOUP v. <?php echo $current_version ?>
		</title>
		<link rel="stylesheet" href="style.css">
      	<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
	     <script type="text/javascript" src="script.js"></script>
      
	</head>
  <body>
    
<?php


/*
    UPLOAD
*/


// non-POST interceptor. 
if(isset($_FILES["file"])){

    // all upload vairiables. Do not touch, these are all set via POST/FILE headers.
    $target_directory = $storage_folder . $_POST["directory"] . "/"; // The target directory to put the file
    $pin = $_POST["pin"]; // The admin pin to allow upload
    $file = $_FILES["file"]; // The file that's being uploaded
    $file_name = $_FILES["file"]["name"]; // The name of the file being uploaded (Example: file.ext)
    $file_type = $_FILES["file"]["type"]; // File type.
    $file_size = (intval( $_FILES["file"]["size"]) / 1024 / 1024); // Convert from Bytes to MB.
    $target_file = $target_directory . $file_name; // The more compiled target file/location. (Example: /folder/file.ext)
    $upload_OK = true; // The upload boolean. If there's a problem, this goes false and the file is discarded.
    $error_messages = []; // The error messages, is set if $upload_OK == false;
    

    // check and make sure there's an upload folder.
    if(!file_exists($storage_folder)){
        
        mkdir($storage_folder);
        if($show_debug)
            echo "Created new uploads folder. <br>";
        
    }
    
    // Check if the file exists. We don't want to delete/override a file.
    if (file_exists($target_file)){

        $upload_OK = false;
        array_push($error_messages, "This file already exists, please rename it.");

    }
    
    // check if there's the custom .htaccess
    if(!file_exists("{$storage_folder}.htaccess")){
        
        $htaccess = "";
        
        if($allow_indexing){
            $htaccess .= "Options Indexes\n";
        }
        
        if(!$allow_executable){
            $htaccess .= "php_flag engine off\n";
        }
        
        if($allow_read_only){
            $htaccess .= "ForceType text/plain\n";
        }
        

        $htaccess_file = fopen("{$storage_folder}.htaccess", "w");
        fwrite($htaccess_file,$htaccess);
        fclose($htaccess_file);
        
        if($show_debug){
            echo("Created a new .htaccess.<br>");
        }
        
    }else{

    // @TODO: check if the htaccess in the folder is the same as the auto-generated one.
    
    }
    

    // Check file size.
    if ($file_size > intval($file_size_limit)){

        $upload_OK = false;
         array_push($error_messages, "This file excedes the {$file_size_limit}MB limit.");

    }

    //check folder.
    if(!file_exists($target_directory)){

        // check if the directly is in the allowed list, but not created yet.
        if(in_array($_POST["directory"], $allowed_directories)){
            mkdir($target_directory);
        }else{

            $upload_OK = false;
           array_push($error_messages,  "Nice try there, crafting your own POST request. Too bad.");
        }

    }

    // check PIN.
    if($pin != $administrator_PIN){

        $upload_OK = false;
         array_push($error_messages, "You entered the wrong PIN.");

    }

    // 
    if( ! (isset($_POST["directory"]) and isset($_POST["pin"]) and isset($_FILES["file"]))){

        $upload_OK = false;
        array_push($error_messages,"Please go to the main page.");

    }

    // Check if there was an error.
    if(!$upload_OK){

        echo ("<center><h2>");

      foreach($error_messages as $error){
      echo("" . $error . "<br>");
      }
      
      echo("</h1></center>");

    } else{

        // everything is fine, attempt to save the file.
        if (move_uploaded_file($_FILES["file"]["tmp_name"], $target_file)) {
            echo "<center><h2 class='animated slideInDown'>The file was uploaded <a href='{$target_file}'>here</a> </h1></center>";
        } else {
            echo "<center><h2 class='animated slideInDown'>There was a file saving error.</h1></center>";
        }

    }
}

?>
		<form action="./" method="post" enctype="multipart/form-data" >
			<input type="file" name="file" id="file" class="space" required="">
			<img id="preview" src="#" alt="loading preview" />
			<br>
			<select name="directory" class="space" required="">

				<?php 
					foreach($allowed_directories as $dir){ echo( "<option value='" . $dir . "'>" . $dir . "</option>"); } 
				?>
			</select>
			<br>
			<input type="password" name="pin" class="space" placeholder="PIN" required="">
			<input type="submit" value="UPLOAD" name="submit" id="submit">
		</form>

	</body>

</html>