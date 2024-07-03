<?php 
header('Content-Type: application/json');

// ============================
// Creates team image and uploads to server
// ============================
function createTeamImage($conn, $params) {

    function saveBase64Image($base64ImageString, $outputFile) {
        $data = explode(',', $base64ImageString);
        $encodedData = $data[1];
    
        $decodedImage = base64_decode($encodedData);
        file_put_contents($outputFile, $decodedImage);
    }
    
    // Set the upload directory
    $target_dir = "uploads/";
    
    // Ensure the uploads directory exists
    if (!file_exists($target_dir)) {
        mkdir($target_dir, 0777, true);
    }
    
    // Check if image data is received
    $input = $params['image'];
    $data = json_decode($input, true);
    
    if (isset($data['image'])) {
        // Generate a unique file name
        $filename = uniqid() . '.png';
        $target_file = $target_dir . $filename;
    
        // Save the base64 encoded image data to file
        saveBase64Image($data['image'], $target_file);
    
        echo json_encode(['message' => 'Image uploaded successfully.', 'file' => $filename]);
    } else {
        echo json_encode(['message' => 'No image data received.']);
    }
}