<?php

// Function to convert image URL to Base64
function image_url_to_base64($image_url) {
    $image_data = file_get_contents($image_url);
    if ($image_data === false) {
        die("Error fetching image from URL: $image_url");
    }
    return base64_encode($image_data);
}

// API Key
$api_key = "SG_b5f8f712e9924783";

// API Endpoint
$url = "https://api.segmind.com/v1/sd2.1-faceswapper";

// Check if URL Parameters Exist
if (!isset($_GET['input']) || !isset($_GET['target'])) {
    die("Error: 'input' and 'target' parameters are required in the URL.");
}

// Get Image URLs from Query Parameters
$input_url = $_GET['input'];
$target_url = $_GET['target'];

// Convert URLs to Base64
$input_face_image = image_url_to_base64($input_url);
$target_face_image = image_url_to_base64($target_url);

// API Request Data
$data = [
    "input_face_image" => $input_face_image,
    "target_face_image" => $target_face_image,
    "file_type" => "png",
    "face_restore" => true
];

// cURL Request Setup
$ch = curl_init($url);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    "x-api-key: $api_key",
    "Content-Type: application/json"
]);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

// Execute API Request
$response = curl_exec($ch);
$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

// Response Handling
if ($http_code == 200) {
    file_put_contents("output.png", $response); // Save Output Image
    echo "Face swapped image saved as output.png";
} else {
    echo "Error: HTTP Code $http_code\n";
    echo "Response: $response";
}

?>