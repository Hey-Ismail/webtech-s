<?php

header('Content-Type: application/json; charset=utf-8');

require_once __DIR__ . '/../controllers/BookController.php';

$action = $_POST['action'] ?? $_GET['action'] ?? '';
$payload = array_merge($_GET, $_POST);

switch ($action) {
    case 'list':
        $response = book_list_controller();
        break;
    case 'single':
        $response = book_single_controller($payload);
        break;
    case 'create':
    case 'update':
        $response = book_store_controller($payload);
        break;
    case 'delete':
        $response = book_delete_controller($payload);
        break;
    default:
        http_response_code(400);
        $response = book_response(false, 'Invalid action requested.');
        break;
}

echo json_encode($response);

//git commit -m "Lab task -12"