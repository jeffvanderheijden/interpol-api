<?php 
header('Content-Type: application/json');

// ============================
// Creates team image and uploads to server
// ============================
function createTeam($conn, $params) {
    // $response = var_dump($params);
    // echo json_encode($response);
    if (isset($params['image'])) {
        // Save the image
        $base64_image = $params['image'];
        $base64_image = str_replace('data:image/png;base64,', '', $base64_image);
        $image_data = base64_decode($base64_image);
        $filename = uniqid() . '.png';
        $file_path = 'uploads/' . $filename;
        file_put_contents($file_path, $image_data);

        // SQL to insert into groups table
        $sql = "INSERT INTO groups (name, image_url, class) VALUES ('$params[name]', '$file_path', '$params[class]')";

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