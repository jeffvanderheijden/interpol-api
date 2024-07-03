<?php 
header('Content-Type: application/json');

// ============================
// Creates team image and uploads to server
// ============================
function createTeamImage($conn, $params) {

    var_dump($params);
    die();

    // Set the upload directory
    $target_dir = "uploads/";

    // Ensure the uploads directory exists
    if (!file_exists($target_dir)) {
        mkdir($target_dir, 0777, true);
    }

    // Get the file from the request
    $target_file = $target_dir . basename($params["image"]);
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    // Check if image file is a actual image or fake image
    if(isset($params["submit"])) {
        $check = getimagesize($params["image"]["tmp_name"]);
        if($check !== false) {
            $uploadOk = 1;
        } else {
            echo json_encode(['message' => 'File is not an image.']);
            $uploadOk = 0;
        }
    }

    // Check if file already exists
    if (file_exists($target_file)) {
        echo json_encode(['message' => 'Sorry, file already exists.']);
        $uploadOk = 0;
    }

    // Check file size (limit to 5MB)
    if ($params["image"]["size"] > 5000000) {
        echo json_encode(['message' => 'Sorry, your file is too large.']);
        $uploadOk = 0;
    }

    // Allow certain file formats
    if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif" ) {
        echo json_encode(['message' => 'Sorry, only JPG, JPEG, PNG & GIF files are allowed.']);
        $uploadOk = 0;
    }

    // Check if $uploadOk is set to 0 by an error
    if ($uploadOk == 0) {
        echo json_encode(['message' => 'Sorry, your file was not uploaded.']);
    // if everything is ok, try to upload file
    } else {
        if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
            echo json_encode(['message' => 'The file '. htmlspecialchars(basename($_FILES["image"]["name"])). ' has been uploaded.']);
        } else {
            echo json_encode(['message' => 'Sorry, there was an error uploading your file.']);
        }
    }
}