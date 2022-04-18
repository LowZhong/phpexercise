<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $file_upload_error_messages = "";

    // sha1_file() function is used to make a unique file name
    $image = !empty($_FILES["prod_img"]["name"])
        ? sha1_file($_FILES['prod_img']['tmp_name']) . "-" . basename($_FILES["prod_img"]["name"])
        : "";
    $image = htmlspecialchars(strip_tags($image));

    if ($image) {
        $target_directory = "uploads/";
        // make sure the 'uploads' folder exists
        // if not, create it
        if (!is_dir($target_directory)) {
            mkdir($target_directory, 0777, true);
        }
        $target_file = $target_directory . $image;

        // make sure file does not exist
        if (file_exists($target_file)) {
            $file_upload_error_messages .= "<div>Image already exists. Try to change file name.</div>";
        }

        // check the extension of the upload file
        $file_type = pathinfo($target_file, PATHINFO_EXTENSION);
        // make sure certain file types are allowed
        $allowed_file_types = array("jpg", "jpeg", "png", "gif");
        if (!in_array($file_type, $allowed_file_types)) {
            $file_upload_error_messages .= "<div>Only JPG, JPEG, PNG, GIF files are allowed.</div>";
        }
        // make sure submitted file is not too large
        if ($_FILES['prod_img']['size'] > (1024000)) {
            $file_upload_error_messages .= "<div>Image must be less than 1 MB in size.</div>";
        }
    } else {
        echo "no file selected.";
    }

    // if $file_upload_error_messages is still empty
    if (empty($file_upload_error_messages)) {
        // it means there are no errors, so try to upload the file (now only start uploading)
        if (move_uploaded_file($_FILES["prod_img"]["tmp_name"], $target_file)) {
            echo "<div class='alert'>";
            echo "<div>Uploaded successfully</div>";
            echo "</div>";
        } else {
            echo "<div class='alert alert-danger'>";
            echo "<div>Unable to upload photo.</div>";
            echo "</div>";
        }
    } else {
        // it means there are some errors, so show them to user
        echo "<div class='alert alert-danger'>";
        echo "<div>{$file_upload_error_messages}</div>";
        echo "</div>";
    }
}
?>
<!DOCTYPE html>
<html>

<head>
    <title>Upload Image</title>
</head>

<body>
    <form action="<?php echo $_SERVER['PHP_SELF'] ?>" method="POST" enctype="multipart/form-data">
        <input type="file" name="prod_img" />
        <button type="submit">Upload</button>
    </form>
</body>

</html>