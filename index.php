<!DOCTYPE html>
<html>

<body>
    
    
    <form action="upload.php" method="post" enctype="multipart/form-data">
        Select image to upload:
        <input type="file" name="file" required>
        <br>Directory:
        <select name="directory" required>

            <?php include "config.php"; foreach($allowed_directories as $dir){ echo( "<option value='" . $dir . "'>" . $dir . "</option>"); } ?>

        </select>

        <br>PIN:
        <input type="password" name="pin" required>
        <br>
        <input type="submit" value="Upload Image" name="submit">
    </form>

</body>

    
    
</html>

