<?php
include "upload.php";
?>

<!DOCTYPE html>
<html>
<head>
    <title>SSOUP v. <?php echo $current_version //get the curent version ?></title>
    </head>
<body>
    <a href="https://github.com/PixelPips/SSOUP/">SSOUP v. <?php echo $current_version //get the curent version ?></a>
        <br>
    <br>
        <form action="upload.php" method="post" enctype="multipart/form-data">
            Select a file to upload:
            <input type="file" name="file" required>
            <br>Directory:
            <select name="directory" required>

                <?php foreach($allowed_directories as $dir){ echo( "<option value='" . $dir . "'>" . $dir . "</option>"); } ?>

            </select>

            <br>PIN:
            <input type="password" name="pin" required>
            <br>
            <input type="submit" value="Upload" name="submit">
        </form>

</body>
</html>