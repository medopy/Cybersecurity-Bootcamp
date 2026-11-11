<?php
header("Content-Type: application/json; charset=UTF-8");

$method = $_SERVER['REQUEST_METHOD'];

$headers = function_exists('getallheaders') ? getallheaders() : [];

$input_data = file_get_contents("php://input");
$data = json_decode($input_data, true);

$response = [
    "status" => "success",
    "method" => $method,
    "received_headers" => $headers,
    "received_data" => $data,
    "message" => ""
];

switch ($method) {
    case 'GET':
        http_response_code(200);
        $response["message"] = "GET request processed successfully.";
        break;

    case 'POST':
        http_response_code(201);
        $response["message"] = "POST request processed successfully. Resource created.";
        break;

    case 'PUT':
        http_response_code(200);
        $response["message"] = "PUT request processed successfully. Resource updated.";
        break;

    case 'DELETE':
        http_response_code(200);
        $response["message"] = "DELETE request processed successfully. Resource removed.";
        break;

    default:
        
        http_response_code(405);
        $response["status"] = "error";
        $response["message"] = "Method not allowed. Use GET, POST, PUT, or DELETE.";
        break;
}

echo json_encode($response, JSON_PRETTY_PRINT);
?>
