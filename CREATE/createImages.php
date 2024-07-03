<?php 
header('Content-Type: application/json');

// ============================
// Creates team image and uploads to server
// ============================
function createTeam($conn, $params) {
    var_dump($params);
    if (isset($params['image'])) {
        // Assuming you're receiving the base64 string as 'image' parameter
        $base64_image = $params['image'];

        // Remove data:image/png;base64 from the base64 string
        $base64_image = str_replace('data:image/png;base64,', '', $base64_image);

        // Decode the base64 string
        $image_data = base64_decode($base64_image);

        // Generate a unique filename
        $filename = uniqid() . '.png';

        // Specify the file path where you want to save the image
        $file_path = 'uploads/' . $filename;

        // Save the decoded image data to a file
        file_put_contents($file_path, $image_data);

        // SQL to insert into groups table
        $sql = "INSERT INTO groups (image_url) VALUES ('$file_path')";

        if ($conn->query($sql) === TRUE) {
            // If insertion into database was successful
            $response = ['status' => 'success', 'filename' => $filename, 'image_url' => $file_path];
            echo json_encode($response);
        } else {
            // If there was an error in inserting into database
            $response = ['status' => 'error', 'message' => 'Error: ' . $sql . '<br>' . $conn->error];
            echo json_encode($response);
        }

        // Optionally, you can send a response back to the client indicating success or failure
        echo json_encode(['status' => 'success', 'filename' => $filename]);
    } else {
        echo json_encode(['message' => 'No image data received.']);
    }
}