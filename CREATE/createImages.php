<?php 
header('Content-Type: application/json');

// ============================
// Creates team image and uploads to server
// ============================
function createTeamImage($conn, $params) {
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

        // Optionally, you can send a response back to the client indicating success or failure
        echo json_encode(['status' => 'success', 'filename' => $filename]);
    } else {
        echo json_encode(['message' => 'No image data received.']);
    }
}