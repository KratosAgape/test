<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = htmlspecialchars($_POST['title']);
    $content = htmlspecialchars($_POST['content']);
    $uploadOk = 1;
    $max_width = 600;

    // Check if image file is uploaded
    if ($_FILES['image']['error'] == UPLOAD_ERR_NO_FILE) {
        $uploadOk = 0;
    } else {
        $target_file = "/" . basename($_FILES["image"]["name"]);
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        // Check if image file is a actual image or fake image
        $check = getimagesize($_FILES["image"]["tmp_name"]);
        if ($check === false) {
            echo "File is not an image.";
            $uploadOk = 0;
        }

        // Check file size
        if ($_FILES["image"]["size"] > 500000) {
            echo "Sorry, your file is too large.";
            $uploadOk = 0;
        }

        // Allow certain file formats
        if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg") {
            echo "Sorry, only JPG, JPEG, & PNG files are allowed.";
            $uploadOk = 0;
        }

        // Check if $uploadOk is set to 0 by an error
        if ($uploadOk == 1) {
            if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
                // Resize image to fixed width
                list($width, $height) = getimagesize($target_file);
                $ratio = $width / $max_width;
                $new_width = $max_width;
                $new_height = $height / $ratio;

                $image_p = imagecreatetruecolor($new_width, $new_height);

                if ($imageFileType == "jpg" || $imageFileType == "jpeg") {
                    $image = imagecreatefromjpeg($target_file);
                } else if ($imageFileType == "png") {
                    $image = imagecreatefrompng($target_file);
                }

                imagecopyresampled($image_p, $image, 0, 0, 0, 0, $new_width, $new_height, $width, $height);

                if ($imageFileType == "jpg" || $imageFileType == "jpeg") {
                    imagejpeg($image_p, $target_file);
                } else if ($imageFileType == "png") {
                    imagepng($image_p, $target_file);
                }

                imagedestroy($image_p);
                imagedestroy($image);
            } else {
                echo "Sorry, there was an error uploading your file.";
                $uploadOk = 0;
            }
        }
    }

    // Display the blog post
    echo "<h2>" . $title . "</h2>";
    echo "<p>" . nl2br($content) . "</p>";
    if ($uploadOk == 1) {
        echo "<img src='" . $target_file . "' alt='Uploaded image'>";
    }
}
?>
